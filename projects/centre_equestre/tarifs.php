<?php
session_start();
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';
// Inclure le fichier de configuration de la base de données
require_once 'config/config.php';

// Connexion à la base de données
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Récupération des tarifs depuis la base de données
$query = $pdo->prepare("SELECT id, nom_tarif, prix, categorie, description FROM tarifs");
$query->execute();
$tarifs = $query->fetchAll(PDO::FETCH_ASSOC);

// tableau associatif pour stocker les tarifs par catégorie
$tarifsParCategorie = array();

// Regroupe les tarifs par catégorie
foreach ($tarifs as $tarif) {
    // Extraire la catégorie du nom du tarif
    $categories = explode("(", $tarif['categorie']);
    $categorie = trim($categories[0]);

    if (!array_key_exists($categorie, $tarifsParCategorie)) {
        $tarifsParCategorie[$categorie] = array(
            'description' => $tarif['description'],
            'tarifs' => array()
        );
    }
    // Stocker uniquement les informations nécessaires sur les tarifs
    $tarifsParCategorie[$categorie]['tarifs'][] = array(
        'nom' => $tarif['nom_tarif'],
        'prix' => $tarif['prix']
    );
}

// Nombre total de cartes par page
$cartesParPage = 1;

// Page actuelle (par défaut 1 si non spécifiée dans l'URL)
$pageActuelle = isset($_GET['page']) ? $_GET['page'] : 1;

// Index de départ pour les cartes sur la page actuelle
$indexDepart = ($pageActuelle - 1) * $cartesParPage;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs - Centre Équestre</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Open+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='assets/css/Tarifs.css' rel='stylesheet' />
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <main>
        <h1 style="text-align: center; color: #360C0C; margin-top: 20px;">Tarifs</h1>
        <div class="contenu-principal">
            <?php
            // Affiche les cartes en fonction de la page actuelle
            $i = 0; // Compteur pour les cartes
            foreach ($tarifsParCategorie as $categorie => $infosCategorie) {
                // Vérifie si la carte doit être affichée sur cette page
                if ($i >= $indexDepart && $i < $indexDepart + $cartesParPage) {
                    echo "<div class='categorie'>";
                    echo "<h2>$categorie</h2>";
                    echo "<p>{$infosCategorie['description']}</p>";
                    echo "<ul class='tarifs'>";
                    foreach ($infosCategorie['tarifs'] as $tarif) {
                        echo "<li>{$tarif['nom']} : {$tarif['prix']}€</li>";
                    }
                    
                    // Si la catégorie est "Pension", 
                    if ($categorie === 'Pension') {
                        echo "<li><a href='assets/pdf/contrat_pension_vierge.pdf' target='_blank'>Voir les détails (PDF)</a></li>";
                    }
                    
                    echo "</ul>";
                    echo "</div>";
                }
                $i++;
            }
            ?>
        </div>
        <div class="pagination">
            <?php
            $totalPages = ceil(count($tarifsParCategorie) / $cartesParPage);

            // Affichage des liens de pagination
            if ($pageActuelle > 1) {
                echo "<a href='?page=" . ($pageActuelle - 1) . "'>&lt;</a>"; // Flèche vers la gauche
            }
            // Affichage des liens pour chaque page
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $pageActuelle) {
                    echo "<span class='current'>$i</span>"; // Page actuelle sans lien
                } else {
                    echo "<a href='?page=$i'>$i</a>"; // Lien vers la page
                }
            }
            if ($pageActuelle < $totalPages) {
                echo "<a href='?page=" . ($pageActuelle + 1) . "'>&gt;</a>"; // Flèche vers la droite
            }
            
            ?>
        </div>

    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>

</html>

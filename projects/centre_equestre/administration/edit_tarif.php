<?php
// Démarrer la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['loggedin'])) {
    error_log("L'utilisateur n'est pas connecté."); // Message de débogage
    header('Location: ../connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérification si l'utilisateur a le rôle d'administrateur
if ($_SESSION['role'] !== 'Administrateur') {
    error_log("L'utilisateur n'a pas le rôle d'administrateur."); // Message de débogage
    header('Location: ../connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas administrateur
    exit();
}

// Définir la variable $isAdmin
$isAdmin = ($_SESSION['role'] === 'Administrateur');
// Inclure le fichier de configuration de la base de données
require_once '../config/config.php';

// Initialiser les variables
$id = $_GET['id'];
$nom_tarif = '';
$description = '';
$prix = '';

try {
    // Requête pour récupérer les détails du tarif
    $query = "SELECT * FROM tarifs WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Vérifier si le tarif existe
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nom_tarif = $row['nom_tarif'];
        $description = $row['description'];
        $prix = $row['prix'];
    } else {
        echo "Tarif non trouvé.";
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Gérer la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_tarif = $_POST['nom_tarif'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];

    try {
        // Requête de mise à jour
        $query = "UPDATE tarifs SET nom_tarif = :nom_tarif, description = :description, prix = :prix WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nom_tarif', $nom_tarif, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Tarif mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du tarif.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le tarif</title>
    <link rel="stylesheet" href="../assets/css/Edit_tarif.css">
</head>
<body>
    <header>
        <div class="hamburger-menu">
            <span class="bar1"></span>
            <span class="bar2"></span>
            <span class="bar3"></span>
        </div>
        <div class="centre <?php echo !$isAdmin ? 'centre-not-connected' : ''; ?>">
            <div class="header-title">Écuries du Val d'Arré</div>
            <div class="header-line"></div>
            <div class="header-subtitle">Saint-Rémy en l'eau</div>
        </div>
        <div class="login-container">
            <?php if ($isAdmin): ?>
                <div class="dropdown">
                    <button class="welcome-btn">Bienvenue <br><?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></button>
                    <div class="dropdown-menu">
                        <a href="admin_actualites.php" class="admin-btn">Administration</a>
                        <a href="../deconnexion.php" class="logout-btn">Déconnexion</a>
                    </div>
                </div>
            <?php else: ?>
                <img class="login-btn" src="assets/images/icone_utilisateur.png" onclick="window.location.href='connexion.php'">
            <?php endif; ?>
        </div>
    </header>
    <nav class="sidenav" id="sidenav">
        <span>Menu</span>
        <ul>
        ²   <li><a href="../index.php">Accueil</a></li>
            <li><a href="../elevage-welsh.php">L'élevage Welsh/SLF</a></li>
            <li><a href="../methode-blondeau.php">La méthode Blondeau</a></li>
            <li><a href="../tarifs.php">Tarifs</a></li>
            <li><a href="../galerie.php">Galerie</a></li>
            <li><a href="../actualites.php">Actualités</a></li>
            <li><a href="../concours.php">Concours</a></li>
            <li><a href="../contact.php">Contact</a></li>
        </ul>
        <div class="closebtn" onclick="closeNav()">&times;</div>
    </nav>
    <div class="container">
        <h1>Modifier le tarif</h1>
        <form action="edit_tarif.php?id=<?php echo $id; ?>" method="post">
            <label for="nom_tarif">Nom du tarif:</label>
            <input type="text" id="nom_tarif" name="nom_tarif" value="<?php echo htmlspecialchars($nom_tarif); ?>" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
            
            <label for="prix">Prix:</label>
            <input type="text" id="prix" name="prix" value="<?php echo htmlspecialchars($prix); ?>" required>
            
            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Centre Equestre du Val d'Arré. Tous droits réservés.</p>
            <p><a href="#">Politique de confidentialité</a> | <a href="#">Mentions légales</a> | <a href="assets/fichiers/reglement_interieur.pdf" download="Reglement_Interieur_Ecurie">Télécharger le règlement intérieur</a></p>
        </div>
    </footer>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>

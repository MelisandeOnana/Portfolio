<?php
session_start();
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page actualités concours</title>
    <link rel="stylesheet" href="assets/css/Concours.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navigation.php'; ?>
    <main>
        <section id="telechargements">
            <h2>Documents à télécharger</h2>
            <ul>
                <li>
                    <a href="assets/pdf/Reglement_concours.pdf" target="_blank" class="download-link">Règlement des Concours</a>
                    <span class="description">(Règles et directives pour les concours)</span>
                </li>
                <li>
                    <a href="assets/pdf/tarifs_concours.pdf" target="_blank" class="download-link">Tarifs des Concours</a>
                    <span class="description">(Liste des tarifs pour les différents concours)</span>
                </li>
                <li>
                    <a href="assets/pdf/Formulaire_lic_compet.pdf" target="_blank" class="download-link">Formulaire de Licence Compétition</a>
                    <span class="description">(Formulaire nécessaire pour participer à la compétition)</span>
                </li>
            </ul>
        </section>

        <section id="actualites-concours">
            <h2>Actualités Concours</h2>
            <?php
            $host = 'mysql-ecuriesduvaldarre.alwaysdata.net';
            $db = 'ecuriesduvaldarre_centreequestre';
            $user = '364674_melisande';
            $pass = 'CentreEquestre60@';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }

            // Requête SQL pour récupérer les informations des actualités de type "concours"
            $sql = "SELECT titre, contenu, photo_path, DATE_FORMAT(date_debut, '%d/%m/%Y') AS date_debut, DATE_FORMAT(date_fin, '%d/%m/%Y') AS date_fin FROM actualites WHERE type = 'concours'";
            $stmt = $pdo->query($sql);

            // Vérifier si des résultats sont trouvés
            if ($stmt->rowCount() > 0) {
                // Parcourir chaque ligne de résultat
                while ($row = $stmt->fetch()) {
                    $titre = htmlspecialchars($row["titre"]);
                    $contenu = htmlspecialchars($row["contenu"]);
                    $date_debut = htmlspecialchars($row["date_debut"]);
                    $date_fin = htmlspecialchars($row["date_fin"]);
                    $photo_path = htmlspecialchars($row["photo_path"]);

                    // Afficher chaque actualité sous forme de section
                    echo '<section class="actualite">';
                    echo '<h3>' . $titre . '</h3>';
                    echo '<p class="date">Du ' . $date_debut . ' au ' . $date_fin . '</p>';
                    echo '<p>' . $contenu . '</p>';
                    if (!empty($photo_path)) {
                        echo '<img src="' . $photo_path . '" alt="' . $titre . '" class="actualite-image">';
                    }
                    echo '</section>';
                }
            } else {
                echo "Aucune actualité de type concours trouvée.";
            }
            ?>
            <div class="inscription-info">
                <h3>Comment s'inscrire aux concours ?</h3>
                <p>Pour vous inscrire aux concours, veuillez voir les moniteurs ou visiter notre page Facebook officielle des écuries.</p>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>

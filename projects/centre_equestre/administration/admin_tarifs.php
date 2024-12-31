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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration des tarifs</title>
    <link rel="stylesheet" href="../assets/css/Admin_tarifs.css"> <!-- Lien vers votre fichier CSS -->
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
            <li><a href="../index.php">Accueil</a></li>
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
    <div class="admin-links">
        <a href="admin_actualites.php" class="admin-link">Actualités</a>
        <a href="admin_galerie.php" class="admin-link">Galerie</a>
    </div>
    <?php
        // Inclure le fichier de configuration de la base de données
        require_once '../config/config.php';

        try {
        // Requête SQL pour récupérer tous les tarifs
        $query = "SELECT * FROM tarifs";
        $stmt = $pdo->query($query);

        // Vérifier s'il y a des résultats
        if ($stmt->rowCount() > 0) {
            echo "<div class='tarifs-container'>";
            // Parcourir chaque ligne de résultats
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='tarif-card'>";
                echo "<h3>{$row['nom_tarif']}</h3>";
                echo "<p>{$row['description']}</p>";
                echo "<span class='badge'>{$row['prix']} €</span>"; // Afficher le prix dans une badge
                echo "<div class='actions'>";
                echo "<a href='edit_tarif.php?id={$row['id']}'>Modifier</a>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='no-tarifs'>Aucun tarif trouvé.</div>";
        }
        } catch (PDOException $e) {
        echo "<div class='no-tarifs'>Erreur : " . $e->getMessage() . "</div>";
        }
    ?>

     <footer>
        <div class="footer-container">
            <p>&copy; 2024 Centre Equestre du Val d'Arré. Tous droits réservés.</p>
            <p><a href="#">Politique de confidentialité</a> | <a href="#">Mentions légales</a> | <a href="assets/fichiers/reglement_interieur.pdf" download="Reglement_Interieur_Ecurie">Télécharger le règlement intérieur</a></p>
        </div>
    </footer>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>

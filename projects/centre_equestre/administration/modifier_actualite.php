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

// Inclure le fichier de configuration de la base de données (où se trouve votre connexion PDO)
require_once '../config/config.php';

// Vérifier si l'ID de l'actualité est fourni
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour récupérer l'actualité
    $sql = "SELECT * FROM actualites WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $actualite = $stmt->fetch();

    // Vérifier si l'actualité existe
    if (!$actualite) {
        $message = "Actualité non trouvée.";
        header('Location: admin_actualites.php?message=' . urlencode($message));
        exit();
    }
} else {
    $message = "ID d'actualité non valide.";
    header('Location: admin_actualites.php?message=' . urlencode($message));
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $contenu = $_POST['contenu'];

    // Requête pour mettre à jour l'actualité
    $sql = "UPDATE actualites SET titre = :titre, type = :type, date_debut = :date_debut, date_fin = :date_fin, contenu = :contenu WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':date_debut', $date_debut, PDO::PARAM_STR);
    $stmt->bindParam(':date_fin', $date_fin, PDO::PARAM_STR);
    $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Exécuter la requête
    if ($stmt->execute()) {
        $message = "Actualité modifiée avec succès.";
    } else {
        $message = "Erreur lors de la modification de l'actualité.";
    }

    // Rediriger vers la page d'administration avec un message
    header('Location: admin_actualites.php?message=' . urlencode($message));
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Actualité</title>
    <link rel="stylesheet" href="../assets/css/Admin_actualites.css">
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

    <div class="container">
    <h1>Modifier Actualité</h1>

    <div class="form-container">
        <form action="modifier_actualite.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($actualite['titre']); ?>" required><br><br>

           
            <label for="type">Type :</label><br>
                <select id="type" name="type" required>
                    <option value="concours" <?php echo ($actualite['type'] == 'concours') ? 'selected' : ''; ?>>Concours</option>
                    <option value="stage" <?php echo ($actualite['type'] == 'stage') ? 'selected' : ''; ?>>Stage</option>
                    <option value="autre" <?php echo ($actualite['type'] == 'autre') ? 'selected' : ''; ?>>Autre</option>
                    <option value="fete" <?php echo ($actualite['type'] == 'fete') ? 'selected' : ''; ?>>Fête</option>
                    <option value="camping" <?php echo ($actualite['type'] == 'camping') ? 'selected' : ''; ?>>Camping</option>
                </select><br><br>
            
            <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($actualite['type']); ?>" required><br><br>

            <label for="date_debut">Date de début :</label><br>
            <input type="date" id="date_debut" name="date_debut" value="<?php echo htmlspecialchars($actualite['date_debut']); ?>" required><br><br>

            <label for="date_fin">Date de fin :</label><br>
            <input type="date" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($actualite['date_fin']); ?>" required><br><br>

            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" rows="4" required><?php echo htmlspecialchars($actualite['contenu']); ?></textarea><br><br>

            <!-- Affichage de l'image actuelle -->
            <?php if ($actualite['photo_path']): ?>
                <div class="current-image">
                    <p>Image actuelle :</p>
                    <img src="<?php echo htmlspecialchars($actualite['photo_path']); ?>" alt="Image actuelle">
                </div>
            <?php endif; ?>

            <!-- Champ pour télécharger une nouvelle image -->
            <label for="photo">Changer l'image :</label><br>
            <input type="file" id="photo" name="photo_path" accept="image/*"><br><br>

            <input type="submit" value="Modifier">
        </form>
    </div>
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

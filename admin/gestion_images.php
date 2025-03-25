<?php
session_start();
require '../config/config.php'; // Fichier de connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Récupérer toutes les images de la base de données avec le nom des projets
$stmt = $pdo->query("
    SELECT projet_images.id_image, projet_images.image_path, projet.titre 
    FROM projet_images 
    JOIN projet ON projet_images.id_projet = projet.id_projet
");
$images = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_image = $_POST['id_image'];
    $nouvelle_image = $_FILES['nouvelle_image'];

    if ($nouvelle_image['error'] == UPLOAD_ERR_OK) {
        $chemin_temporaire = $nouvelle_image['tmp_name'];
        $nom_fichier = basename($nouvelle_image['name']);
        $chemin_destination = "../assets/" . $nom_fichier;

        if (move_uploaded_file($chemin_temporaire, $chemin_destination)) {
            $stmt = $pdo->prepare("UPDATE projet_images SET image_path = ? WHERE id_image = ?");
            $stmt->execute([$nom_fichier, $id_image]);
            header("Location: gestion_images.php");
            exit();
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Images des projets</title>
    <link rel="stylesheet" href="../assets/css/images.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
        </header>
        <nav>
            <ul>
                <li><a href="tableau_de_bord.php">Tableau de bord</a></li>
                <li><a href="gestion_projets.php">Gestion des Projets</a></li>
                <li><a href="gestion_apprentissages.php">Gestion des Apprentissages</a></li>
                <li><a href="gestion_contacts.php">Gestion des Contacts</a></li>
                <li><a href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <h2>Images des projets</h2>
        <?php 
        $currentProject = '';
        foreach ($images as $image): 
            if ($currentProject !== $image['titre']) {
                if ($currentProject !== '') {
                    echo '</ul>';
                }
                $currentProject = $image['titre'];
                echo '<h3>' . htmlspecialchars($currentProject) . '</h3>';
                echo '<button class="edit-gallery-btn" onclick="window.location.href=\'modifier_galerie.php?projet=' . urlencode($currentProject) . '\'">Modifier la galerie</button>';
                echo '<ul>';
            }
        ?>
            <li>
                <strong><?php echo htmlspecialchars($image['titre']); ?>:</strong>
                <img src="../assets/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Image du projet" width="100">
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

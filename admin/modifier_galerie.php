<?php
session_start();
require '../config/config.php'; // Fichier de connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php");
    exit();
}

// Récupérer le projet sélectionné
$projet = $_GET['projet'] ?? '';
if (empty($projet)) {
    header("Location: gestion_images.php");
    exit();
}

// Récupérer les images du projet sélectionné
$stmt = $pdo->prepare("
    SELECT projet_images.id_image, projet_images.image_path 
    FROM projet_images 
    JOIN projet ON projet_images.id_projet = projet.id_projet 
    WHERE projet.titre = ?
");
$stmt->execute([$projet]);
$images = $stmt->fetchAll();

// Gérer le téléchargement de nouvelles images
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['nouvelle_image'])) {
    $nouvelle_image = $_FILES['nouvelle_image'];

    if ($nouvelle_image['error'] == UPLOAD_ERR_OK) {
        $chemin_temporaire = $nouvelle_image['tmp_name'];
        $nom_fichier = basename($nouvelle_image['name']);
        $chemin_destination = "../assets/" . $nom_fichier;

        if (move_uploaded_file($chemin_temporaire, $chemin_destination)) {
            $stmt = $pdo->prepare("INSERT INTO projet_images (id_projet, image_path) VALUES ((SELECT id_projet FROM projet WHERE titre = ?), ?)");
            $stmt->execute([$projet, $nom_fichier]);
            header("Location: modifier_galerie.php?projet=" . urlencode($projet));
            exit();
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}

// Gérer la suppression des images
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer_image'])) {
    $id_image = $_POST['id_image'];
    $stmt = $pdo->prepare("DELETE FROM projet_images WHERE id_image = ?");
    $stmt->execute([$id_image]);
    header("Location: modifier_galerie.php?projet=" . urlencode($projet));
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la galerie du projet <?php echo htmlspecialchars($projet); ?></title>
    <link rel="stylesheet" href="../assets/css/images.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Modifier la galerie du projet <?php echo htmlspecialchars($projet); ?></h1>
        </header>
        <nav>
            <ul>
                <li><a href="gestion_images.php">Retour à la gestion des images</a></li>
                <li><a href="tableau_de_bord.php">Tableau de bord</a></li>
                <li><a href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <h2>Images actuelles</h2>
        <ul>
            <?php foreach ($images as $image): ?>
                <li>
                    <img src="../assets/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Image du projet" width="100">
                    <form action="modifier_galerie.php?projet=<?php echo urlencode($projet); ?>" method="post" style="display:inline;">
                        <input type="hidden" name="id_image" value="<?php echo $image['id_image']; ?>">
                        <button type="submit" name="supprimer_image">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <h2>Ajouter une nouvelle image</h2>
        <form action="modifier_galerie.php?projet=<?php echo urlencode($projet); ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="nouvelle_image" accept="image/*">
            <button type="submit">Ajouter l'image</button>
        </form>
    </div>
</body>
</html>
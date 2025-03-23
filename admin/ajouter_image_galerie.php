<?php
session_start();
require '../config/config.php'; // Fichier de connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Vérifiez et créez le dossier uploads si nécessaire
$target_dir = "../uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Récupérer l'ID du projet depuis l'URL
$id_projet = $_GET['id_projet'] ?? null;
if (!$id_projet) {
    echo "ID du projet manquant.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gestion du téléchargement des images
    $images = [];
    if (isset($_FILES['images'])) {
        foreach ($_FILES['images']['name'] as $key => $name) {
            if ($_FILES['images']['error'][$key] == 0) {
                $target_file = $target_dir . basename($name);
                if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
                    $images[] = $target_file;
                } else {
                    echo "Erreur lors du téléchargement du fichier : $name.";
                }
            }
        }
    }

    // Insertion des images dans la table 'projet_images'
    foreach ($images as $image_path) {
        $sql_image = "INSERT INTO projet_images (id_projet, image_path) VALUES (:id_projet, :image_path)";
        $stmt_image = $pdo->prepare($sql_image);
        $stmt_image->execute([
            ':id_projet' => $id_projet,
            ':image_path' => $image_path
        ]);
    }

    // Redirection vers la page de gestion des projets
    header("Location: gestion_projets.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter des images à la galerie</title>
    <link rel="stylesheet" href="../assets/css/projet.css">
</head>
<body>
    <h2>Ajouter des images à la galerie du projet</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="images">Images :</label>
        <input type="file" name="images[]" multiple><br>
        <button type="submit">Ajouter les images</button>
    </form>
    <a href="gestion_projets.php">Retour</a>
</body>
</html>
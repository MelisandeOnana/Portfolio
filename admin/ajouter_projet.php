<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = !empty($_POST['titre']) ? $_POST['titre'] : NULL;
    $description = !empty($_POST['description']) ? $_POST['description'] : NULL;
    $date_debut = !empty($_POST['date_debut']) ? $_POST['date_debut'] : NULL;
    $date_fin = !empty($_POST['date_fin']) ? $_POST['date_fin'] : NULL;
    $lien = !empty($_POST['lien']) ? $_POST['lien'] : NULL;
    $lien_github = !empty($_POST['lien_github']) ? $_POST['lien_github'] : NULL;
    $visible = isset($_POST['visible']) && $_POST['visible'] == 'oui' ? 1 : 0;

    // Gestion de l'image
    $imagePath = NULL;
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $imagePath = '../assets/images/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        $imagePath = 'assets/images/' . $imageName; // Chemin relatif pour la base de données
    }

    // Gestion de la documentation
    $pdfPath = NULL;
    if (!empty($_FILES['documentation']['name'])) {
        $pdfName = basename($_FILES['documentation']['name']);
        $pdfPath = '../assets/pdf/' . $pdfName;
        move_uploaded_file($_FILES['documentation']['tmp_name'], $pdfPath);
        $pdfPath = 'assets/pdf/' . $pdfName; // Chemin relatif pour la base de données
    }

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO projets (titre, description, date_debut, date_fin, image, lien, documentation, visible, lien_github) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $date_debut, $date_fin, $imagePath, $lien, $pdfPath, $visible, $lien_github]);

    echo "<script>alert('Projet ajouté avec succès !'); window.location.href='gestion_projets.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet</title>
    <link rel="stylesheet" href="../assets/css/ajouter_projet.css">
</head>
<body>
    <main>
        <header>
            <h1>Ajouter un Projet</h1>
        </header>
        <form method="POST" action="ajouter_projet.php" enctype="multipart/form-data">
            <label for="titre">Titre du Projet:</label>
            <input type="text" id="titre" name="titre" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="date_debut">Date de Début:</label>
            <input type="month" id="date_debut" name="date_debut" required>

            <label for="date_fin">Date de Fin:</label>
            <input type="month" id="date_fin" name="date_fin">

            <label for="lien">Lien du Projet:</label>
            <input type="text" id="lien" name="lien" value="projects/">

            <label for="image">Image du Projet:</label>
            <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif" onchange="previewImage(event)">

            <img id="imagePreview" src="#" alt="Aperçu de l'image" style="display:none; max-width: 50%; height: auto; margin-top: 10px;">

            <label for="documentation">Documentation du Projet (PDF):</label>
            <input type="file" id="documentation" name="documentation" accept="application/pdf">

            <label for="lien_github">Lien Github:</label>
            <input type="text" id="lien_github" name="lien_github">

            <label for="visible">Visible:</label>
            <div>
                <input type="radio" id="visible_oui" name="visible" value="oui">
                <label for="visible_oui">Oui</label>
                <input type="radio" id="visible_non" name="visible" value="non" checked>
                <label for="visible_non">Non</label>
            </div>

            <button type="submit">Ajouter</button>
            <button type="button" onclick="window.history.back();">Retour</button>
        </form>
    </main>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const reader = new FileReader();

            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
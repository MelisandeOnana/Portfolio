<?php
session_start();
require '../config/config.php'; // Fichier de connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Vérifiez et créez les dossiers images et pdf si nécessaire
$target_dir_images = "../assets/images/";
if (!is_dir($target_dir_images)) {
    mkdir($target_dir_images, 0777, true);
}

$target_dir_pdf = "../assets/pdf/";
if (!is_dir($target_dir_pdf)) {
    mkdir($target_dir_pdf, 0777, true);
}

// Récupération des technologies disponibles
$sql_technologies = "SELECT * FROM technologie";
$stmt = $pdo->query($sql_technologies);
$technologies = $stmt->fetchAll();

// Technologies à exclure
$technologies_a_exclure = ['GitHub', 'Git', 'Visual Code', 'Visual Studio Code', 'Figma'];

// Filtrer les technologies
$technologies = array_filter($technologies, function($tech) use ($technologies_a_exclure) {
    return !in_array($tech['nom'], $technologies_a_exclure);
});

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = !empty($_POST['date_fin']) ? $_POST['date_fin'] : NULL;
    $id_utilisateur = 1; // À adapter si plusieurs utilisateurs
    $visible = isset($_POST['visible']) ? 1 : 0;
    $github_links = $_POST['github_links'] ?? NULL;
    $technologies_selected = $_POST['technologies'] ?? [];
    $lien = $_POST['lien'] ?? NULL;

   // Gestion du téléchargement des documents
   $documents = [];
   if (isset($_FILES['documents'])) {
       foreach ($_FILES['documents']['name'] as $key => $name) {
           if ($_FILES['documents']['error'][$key] == 0) {
               $target_file = $target_dir_pdf . basename($name);
               if (move_uploaded_file($_FILES['documents']['tmp_name'][$key], $target_file)) {
                   $documents[] = "pdf/" . basename($name);
               } else {
                   echo "Erreur lors du téléchargement du fichier : $name.";
               }
           }
       }
   }
   $documents = !empty($documents) ? implode(',', $documents) : NULL;

    // Gestion du téléchargement de l'image
    $image = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_file = $target_dir_images . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = "images/" . basename($_FILES["image"]["name"]);
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    }

    // Insertion du projet dans la table 'projet'
    $sql_projet = "INSERT INTO projet (titre, description, date_debut, date_fin, id_utilisateur, visible, documents, github_links, image, lien)
                   VALUES (:titre, :description, :date_debut, :date_fin, :id_utilisateur, :visible, :documents, :github_links, :image, :lien)";
    $stmt_projet = $pdo->prepare($sql_projet);
    $stmt_projet->execute([
        ':titre' => $titre,
        ':description' => $description,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
        ':id_utilisateur' => $id_utilisateur,
        ':visible' => $visible,
        ':documents' => $documents,
        ':github_links' => $github_links,
        ':image' => $image,
        ':lien' => $lien
    ]);

    // Récupérer l'ID du projet inséré
    $id_projet = $pdo->lastInsertId();

    // Insertion des technologies associées dans la table 'projet_technologie'
    foreach ($technologies_selected as $id_technologie) {
        $sql_projet_technologie = "INSERT INTO projet_technologie (id_projet, id_technologie)
                        VALUES (:id_projet, :id_technologie)";
        $stmt_technologie = $pdo->prepare($sql_projet_technologie);
        $stmt_technologie->execute([
            ':id_projet' => $id_projet,
            ':id_technologie' => $id_technologie
        ]);
    }

    // Redirection en fonction de la valeur du champ 'lien'
    if (empty($lien)) {
        header("Location: ajouter_image_galerie.php?id_projet=$id_projet");
    } else {
        header("Location: gestion_projets.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un projet</title>
    <link rel="stylesheet" href="../assets/css/projet.css">
</head>
<body>
    <h2>Ajouter un projet</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" required><br>

        <label for="description">Description :</label>
        <textarea name="description" required></textarea><br>

        <label for="date_debut">Date de début :</label>
        <input type="date" name="date_debut" required><br>

        <label for="date_fin">Date de fin :</label>
        <input type="date" name="date_fin"><br>

        <label for="visible">Visible :</label>
        <input type="checkbox" name="visible" checked><br>

        <label for="documents">Documents :</label>
        <input type="file" name="documents[]" multiple onchange="previewDocuments(event)"><br>
        <div id="documentsPreview" style="display: none;">
            <h4>Documents sélectionnés :</h4>
            <ul id="documentsList"></ul>
        </div>

        <label for="image">Image :</label>
        <input type="file" name="image" onchange="previewImage(event)"><br>
        <img id="imagePreview" src="#" alt="Aperçu de l'image" style="display: none; max-width: 200px; max-height: 200px;"><br>

        <label for="github_links">Liens GitHub :</label>
        <textarea name="github_links"></textarea><br>

        <label for="lien">Lien :</label>
        <input type="text" name="lien"><br>

        <label for="technologies">Technologies :</label><br>
        <div class="technologies-container">
            <?php foreach ($technologies as $tech) : ?>
                <input type="checkbox" id="tech_<?= $tech['id_technologie'] ?>" name="technologies[]" value="<?= $tech['id_technologie'] ?>">
                <label for="tech_<?= $tech['id_technologie'] ?>" class="tech-label"><?= $tech['nom'] ?></label>
            <?php endforeach; ?>
        </div><br>

        <a class="back" href="gestion_projets.php">Retour</a>
        <button type="submit">Ajouter</button>
    </form>
    <script>
function previewDocuments(event) {
    const files = event.target.files;
    const documentsPreview = document.getElementById('documentsPreview');
    const documentsList = document.getElementById('documentsList');
    
    documentsList.innerHTML = ''; // Clear the list
    if (files.length > 0) {
        documentsPreview.style.display = 'block';
        for (let i = 0; i < files.length; i++) {
            const listItem = document.createElement('li');
            listItem.textContent = files[i].name;
            documentsList.appendChild(listItem);
        }
    } else {
        documentsPreview.style.display = 'none';
    }
}

function previewImage(event) {
    const file = event.target.files[0];
    const imagePreview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        imagePreview.style.display = 'none';
    }
}
</script>
</body>
</html>
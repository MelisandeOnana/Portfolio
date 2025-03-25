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

// Récupération des technologies disponibles
$sql_technologies = "SELECT * FROM technologie";
$stmt = $pdo->query($sql_technologies);
$technologies = $stmt->fetchAll();

$technologies_a_exclure = ['GitHub', 'Git', 'Visual Code', 'Visual Studio Code', 'Figma'];

// Filtrer les technologies
$technologies = array_filter($technologies, function($tech) use ($technologies_a_exclure) {
    return !in_array($tech['nom'], $technologies_a_exclure);
});

// Récupération des informations du projet à modifier
$id_projet = $_GET['id_projet'];
$sql_projet = "SELECT * FROM projet WHERE id_projet = :id_projet";
$stmt_projet = $pdo->prepare($sql_projet);
$stmt_projet->execute([':id_projet' => $id_projet]);
$projet = $stmt_projet->fetch();

// Récupération des technologies associées au projet
$sql_projet_technologies = "SELECT id_technologie FROM projet_technologie WHERE id_projet = :id_projet";
$stmt_projet_technologies = $pdo->prepare($sql_projet_technologies);
$stmt_projet_technologies->execute([':id_projet' => $id_projet]);
$projet_technologies = $stmt_projet_technologies->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'] ?? NULL;
    $visible = isset($_POST['visible']) ? 1 : 0;
    $github_links = $_POST['github_links'] ?? NULL;
    $technologies_selected = $_POST['technologies'] ?? [];
    $lien = $_POST['lien'] ?? NULL;

    // Gestion du téléchargement des documents
    $documents = [];
    if (isset($_FILES['documents'])) {
        foreach ($_FILES['documents']['name'] as $key => $name) {
            if ($_FILES['documents']['error'][$key] == 0) {
                $target_file = $target_dir . basename($name);
                if (move_uploaded_file($_FILES['documents']['tmp_name'][$key], $target_file)) {
                    $documents[] = $target_file;
                } else {
                    echo "Erreur lors du téléchargement du fichier : $name.";
                }
            }
        }
    }
    $documents = !empty($documents) ? json_encode($documents) : $projet['documents'];

    // Gestion du téléchargement de l'image
    $image = $projet['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    }

    // Mise à jour du projet dans la table 'projet'
    $sql_update_projet = "UPDATE projet SET titre = :titre, description = :description, date_debut = :date_debut, date_fin = :date_fin, visible = :visible, documents = :documents, github_links = :github_links, image = :image, lien = :lien WHERE id_projet = :id_projet";
    $stmt_update_projet = $pdo->prepare($sql_update_projet);
    $stmt_update_projet->execute([
        ':titre' => $titre,
        ':description' => $description,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
        ':visible' => $visible,
        ':documents' => $documents,
        ':github_links' => $github_links,
        ':image' => $image,
        ':lien' => $lien,
        ':id_projet' => $id_projet
    ]);

    // Suppression des anciennes technologies associées
    $sql_delete_technologies = "DELETE FROM projet_technologie WHERE id_projet = :id_projet";
    $stmt_delete_technologies = $pdo->prepare($sql_delete_technologies);
    $stmt_delete_technologies->execute([':id_projet' => $id_projet]);

    // Insertion des nouvelles technologies associées
    foreach ($technologies_selected as $id_technologie) {
        $sql_projet_technologie = "INSERT INTO projet_technologie (id_projet, id_technologie) VALUES (:id_projet, :id_technologie)";
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
    <title>Modifier un projet</title>
    <link rel="stylesheet" href="../assets/css/projet.css">
</head>
<body>
    <h2>Modifier un projet</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($projet['titre'] ?? '') ?>" required><br>

        <label for="description">Description :</label>
        <textarea name="description" required><?= htmlspecialchars($projet['description'] ?? '') ?></textarea><br>

        <label for="date_debut">Date de début :</label>
        <input type="date" name="date_debut" value="<?= htmlspecialchars($projet['date_debut'] ?? '') ?>" required><br>

        <label for="date_fin">Date de fin :</label>
        <input type="date" name="date_fin" value="<?= htmlspecialchars($projet['date_fin'] ?? '') ?>"><br>

        <label for="visible">Visible :</label>
        <input type="checkbox" name="visible" <?= $projet['visible'] ? 'checked' : '' ?>><br>

        <label for="documents">Documents :</label>
        <input type="file" name="documents[]" multiple><br>

        <label for="image">Image :</label>
        <input type="file" name="image" onchange="previewImage(event)"><br>
        <img id="imagePreview" src="../assets/<?= htmlspecialchars($projet['image'] ?? '') ?>" alt="Aperçu de l'image" style="display: <?= $projet['image'] ? 'block' : 'none' ?>; max-width: 200px; max-height: 200px;"><br>

        <label for="github_links">Liens GitHub :</label>
        <textarea name="github_links"><?= htmlspecialchars($projet['github_links'] ?? '') ?></textarea><br>

        <label for="lien">Lien :</label>
        <input type="text" name="lien" value="<?= htmlspecialchars($projet['lien'] ?? '') ?>"><br>

        <label for="technologies">Technologies :</label><br>
        <div class="technologies-container">
            <?php foreach ($technologies as $tech) : ?>
                <input type="checkbox" id="tech_<?= $tech['id_technologie'] ?>" name="technologies[]" value="<?= $tech['id_technologie'] ?>">
                <label for="tech_<?= $tech['id_technologie'] ?>" class="tech-label"><?= $tech['nom'] ?></label>
            <?php endforeach; ?>
        </div><br>

        <a class="back" href="gestion_projets.php">Retour</a>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
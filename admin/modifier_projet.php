<?php
session_start();
include '../config/config.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Récupérer les informations du projet à modifier
if (isset($_GET['id'])) {
    $projectId = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM projets WHERE id = :id');
    $stmt->bindValue(':id', $projectId, PDO::PARAM_INT);
    $stmt->execute();
    $projet = $stmt->fetch(PDO::FETCH_ASSOC);

    // Formater les dates pour les champs de formulaire
    $projet['date_debut'] = date('Y-m-d', strtotime($projet['date_debut']));
    $projet['date_fin'] = $projet['date_fin'] ? date('Y-m-d', strtotime($projet['date_fin'])) : '';
}

// Mettre à jour les informations du projet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $image = $_POST['image'] ?? '';
    $lien = $_POST['lien'] ?? '';
    $visible = isset($_POST['visible']) ? 1 : 0;
    $lien_github = $_POST['lien_github'] ?? '';
    $technologies = $_POST['technologies'] ?? ''; 

     // Gestion de l'image
     if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $imagePath = '../assets/images/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        $imagePath = 'assets/images/' . $imageName; // Chemin relatif pour la base de données
    } else {
        $imagePath = $projet['image'];
    }

    // Gestion de la documentation
    if (!empty($_FILES['documentation']['name'])) {
        $pdfName = basename($_FILES['documentation']['name']);
        $pdfPath = '../assets/pdf/' . $pdfName;
        move_uploaded_file($_FILES['documentation']['tmp_name'], $pdfPath);
        $pdfPath = 'assets/pdf/' . $pdfName; // Chemin relatif pour la base de données
    } else {
        $pdfPath = $projet['documentation'];
    }

    // Requête de mise à jour
    $query = "UPDATE projets SET titre = ?, description = ?, date_debut = ?, date_fin = ?, image = ?, lien = ?, visible = ?, documentation = ?, lien_github = ?, technologies = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$titre, $description, $date_debut, $date_fin, $imagePath, $lien, $visible, $pdfPath, $lien_github, $technologies, $projectId]);

    // Définir le message de succès
    $_SESSION['message'] = "Projet modifié avec succès.";

    // Redirection après mise à jour avec message de confirmation
    echo "<script>
        alert('Projet modifié avec succès.');
        window.location.href = 'gestion_projets.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Projet</title>
    <link rel="stylesheet" href="../assets/css/modifier_projet.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Modifier un Projet</h1>
        </header>
        <main>
            <section>
                <form method="POST" action="modifier_projet.php?id=<?php echo $projectId; ?>" enctype="multipart/form-data">
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($projet['titre'] ?? '') ?>" required>
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($projet['description'] ?? '') ?></textarea>
                    <label for="date_debut">Date de Début</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($projet['date_debut'] ?? '') ?>" required>
                    <label for="date_fin">Date de Fin</label>
                    <input type="date" id="date_fin" name="date_fin" value="<?= htmlspecialchars($projet['date_fin'] ?? '') ?>">
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" onchange="previewImage(event)">
                    <div>
                        <p>Image actuelle :</p>
                        <img src="../<?= htmlspecialchars($projet['image'] ?? '') ?>" alt="Aperçu de l'image actuelle" width="200">
                    </div>
                    <div>
                        <p>Aperçu de la nouvelle image :</p>
                        <img id="image-preview" src="#" alt="Aperçu de la nouvelle image" style="display: none;" width="200">
                    </div>
                    <label for="lien">Lien</label>
                    <input type="lien" id="lien" name="lien" value="<?= htmlspecialchars($projet['lien'] ?? '') ?>">
                    <label for="visible">Visible</label>
                    <input type="checkbox" id="visible" name="visible" <?= $projet['visible'] ? 'checked' : '' ?>>
                    <label for="documentation">Documentation (PDF)</label>
                    <input type="file" id="documentation" name="documentation">
                    <label for="lien_github">Lien GitHub</label>
                    <input type="url" id="lien_github" name="lien_github" value="<?= htmlspecialchars($projet['lien_github'] ?? '') ?>">
                    <label for="technologies">Technologies</label>
                    <input type="text" id="technologies" name="technologies" value="<?= htmlspecialchars($projet['technologies'] ?? '') ?>" required>
                    <button type="submit" class="btn">Mettre à jour</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
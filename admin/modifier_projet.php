<?php
require_once '../includes/db_connect.php';

// Récupérer les informations du projet à modifier
if (isset($_GET['id'])) {
    $projectId = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM projets WHERE id = :id');
    $stmt->bindValue(':id', $projectId, PDO::PARAM_INT);
    $stmt->execute();
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Mettre à jour les informations du projet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $lien = $_POST['lien'] ?? '';
    $lien_github = $_POST['lien_github'] ?? '';
    $visible = isset($_POST['visible']) && $_POST['visible'] == 'oui' ? 1 : 0;

    // Gestion de l'image
    $imagePath = $project['image'];
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $imagePath = '../assets/images/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        $imagePath = 'assets/images/' . $imageName; // Chemin relatif pour la base de données
    }

    // Gestion de la documentation
    $pdfPath = $project['documentation'];
    if (!empty($_FILES['documentation']['name'])) {
        $pdfName = basename($_FILES['documentation']['name']);
        $pdfPath = '../assets/pdf/' . $pdfName;
        move_uploaded_file($_FILES['documentation']['tmp_name'], $pdfPath);
        $pdfPath = 'assets/pdf/' . $pdfName; // Chemin relatif pour la base de données
    }

    // Requête de mise à jour
    $query = "UPDATE projets SET titre = ?, description = ?, date_debut = ?, date_fin = ?, image = ?, lien = ?, visible = ?, documentation = ?, lien_github = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$titre, $description, $date_debut, $date_fin, $imagePath, $lien, $visible, $pdfPath, $lien_github, $projectId]);

    // Redirection après mise à jour
    header("Location: modifier_projet.php?id=" . $projectId);
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
                    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($project['titre'] ?? ''); ?>" required>
                    
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
                    
                    <label for="date_debut">Date de début</label>
                    <input type="month" id="date_debut" name="date_debut" value="<?php echo htmlspecialchars($project['date_debut'] ?? ''); ?>" required>
                    
                    <label for="date_fin">Date de fin</label>
                    <input type="month" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($project['date_fin'] ?? ''); ?>">
                    
                    <label for="lien">Lien</label>
                    <input type="text" id="lien" name="lien" value="<?php echo htmlspecialchars($project['lien'] ?? ''); ?>">
                    
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                    <div class="image-preview">
                        <img id="image-preview" src="../<?php echo htmlspecialchars($project['image'] ?? ''); ?>" alt="Aperçu de l'image" style="max-width: 200px; margin-top: 10px;">
                    </div>
                    
                    <label for="documentation">Documentation (PDF)</label>
                    <input type="file" id="documentation" name="documentation" accept="application/pdf">
                    <div class="doc-preview">
                        <?php if ($project['documentation']): ?>
                            <a href="../<?php echo htmlspecialchars($project['documentation']); ?>" target="_blank">Voir le document actuel</a>
                        <?php endif; ?>
                    </div>

                    <label for="lien_github">Lien GitHub:</label>
                    <input type="text" id="lien_github" name="lien_github" value="<?= htmlspecialchars($project['lien_github']) ?>">

                    <label for="visible">Visible</label>
                    <div>
                        <input type="radio" id="visible_oui" name="visible" value="oui" <?php echo ($project['visible'] == 1) ? 'checked' : ''; ?>>
                        <label for="visible_oui">Oui</label>
                        <input type="radio" id="visible_non" name="visible" value="non" <?php echo ($project['visible'] == 0) ? 'checked' : ''; ?>>
                        <label for="visible_non">Non</label>
                    </div>
                    
                    <button type="submit">Enregistrer les modifications</button>
                    <button type="button" onclick="window.location.href='gestion_projets.php'">Retour</button>
                </form>
            </section>
        </main>
    </div>
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const reader = new FileReader();

            reader.onload = function() {
                preview.src = reader.result;
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
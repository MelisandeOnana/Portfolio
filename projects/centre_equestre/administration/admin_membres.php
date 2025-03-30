<!-- filepath: c:\wamp64\www\Portfolio-php\projects\centre_equestre\administration\admin_membres.php -->
<?php
session_start();

// Vérification si l'utilisateur est administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: ../connexion.php');
    exit;
}

// Inclure la connexion à la base de données
include('../config/config.php');

// Gestion des actions (ajouter, modifier, supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Dossier où les fichiers seront stockés
        $uploadDir = '../assets/pdf/';

        if ($action === 'add') {
            // Vérifier si des fichiers ont été uploadés
            $imagePath = null;
            $videoPath = null;
            $diplomePath = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $uploadDir . $fileName;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);
                $imagePath = 'assets/images/' . $fileName;
            }

            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['video']['name']);
                $targetFilePath = $uploadDir . $fileName;
                move_uploaded_file($_FILES['video']['tmp_name'], $targetFilePath);
                $videoPath = 'assets/videos/' . $fileName;
            }

            if (isset($_FILES['diplome_pdf']) && $_FILES['diplome_pdf']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['diplome_pdf']['name']);
                $targetFilePath = $uploadDir . $fileName;
                move_uploaded_file($_FILES['diplome_pdf']['tmp_name'], $targetFilePath);
                $diplomePath = 'assets/pdf/' . $fileName;
            }

            // Ajouter un membre
            $stmt = $pdo->prepare("INSERT INTO equipe (nom, description, image_url, video_url, role, diplome_pdf) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['nom'], $_POST['description'], $imagePath, $videoPath, $_POST['role'], $diplomePath]);
            header('Location: admin_membres.php?success=add');
            exit;
        } elseif ($action === 'edit') {
            // Modifier un membre
            $imagePath = $_POST['current_image'];
            $videoPath = $_POST['current_video'];
            $diplomePath = $_POST['current_diplome'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $uploadDir . $fileName;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);
                $imagePath = 'assets/uploads/' . $fileName;
            }

            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['video']['name']);
                $targetFilePath = $uploadDir . $fileName;
                move_uploaded_file($_FILES['video']['tmp_name'], $targetFilePath);
                $videoPath = 'assets/uploads/' . $fileName;
            }

            if (isset($_FILES['diplome_pdf']) && $_FILES['diplome_pdf']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['diplome_pdf']['name']);
                $targetFilePath = $uploadDir . $fileName;
                move_uploaded_file($_FILES['diplome_pdf']['tmp_name'], $targetFilePath);
                $diplomePath = 'assets/uploads/' . $fileName;
            }

            $stmt = $pdo->prepare("UPDATE equipe SET nom = ?, description = ?, image_url = ?, video_url = ?, role = ?, diplome_pdf = ? WHERE id = ?");
            $stmt->execute([$_POST['nom'], $_POST['description'], $imagePath, $videoPath, $_POST['role'], $diplomePath, $_POST['id']]);
            header('Location: admin_membres.php?success=edit');
            exit;
        }
    }
} elseif (isset($_GET['delete'])) {
    // Supprimer un membre
    $stmt = $pdo->prepare("DELETE FROM equipe WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: admin_membres.php?success=delete');
    exit;
}

// Récupérer tous les membres
$membres = $pdo->query("SELECT * FROM equipe")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Membres</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center">Gestion des Membres</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php if ($_GET['success'] === 'add'): ?>
                Le membre a été ajouté avec succès !
            <?php elseif ($_GET['success'] === 'edit'): ?>
                Le membre a été modifié avec succès !
            <?php elseif ($_GET['success'] === 'delete'): ?>
                Le membre a été supprimé avec succès !
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Liste des membres -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Image/Vidéo</th>
                <th>Diplôme</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($membres as $membre): ?>
                <tr>
                    <td><?= htmlspecialchars($membre['nom']) ?></td>
                    <td><?= htmlspecialchars($membre['description']) ?></td>
                    <td>
                        <?php if (!empty($membre['image_url'])): ?>
                            <img src="../<?= htmlspecialchars($membre['image_url']) ?>" alt="<?= htmlspecialchars($membre['nom']) ?>" style="max-width: 100px;">
                        <?php elseif (!empty($membre['video_url'])): ?>
                            <video width="100" controls>
                                <source src="../<?= htmlspecialchars($membre['video_url']) ?>" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($membre['diplome_pdf'])): ?>
                            <a href="../<?= htmlspecialchars($membre['diplome_pdf']) ?>" target="_blank">Voir le diplôme</a>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($membre['role']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editMember(<?= htmlspecialchars(json_encode($membre)) ?>)">Modifier</button>
                        <a href="?delete=<?= $membre['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout/modification -->
    <form method="POST" class="my-4" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" id="member-id">
        <input type="hidden" name="current_image" id="current-image">
        <input type="hidden" name="current_video" id="current-video">
        <input type="hidden" name="current_diplome" id="current-diplome">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Rôle</label>
            <input type="text" class="form-control" id="role" name="role" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <img id="image-preview" src="" alt="Aperçu de l'image" style="max-width: 200px; margin-top: 10px; display: none;">
        </div>
        <div class="mb-3">
            <label for="video" class="form-label">Vidéo</label>
            <input type="file" class="form-control" id="video" name="video" accept="video/*" onchange="previewVideo(event)">
            <div class="mt-3">
                <video id="videoPreview" controls style="max-width: 300px; display: none;">
                    <source src="#" type="video/mp4">
                    Votre navigateur ne supporte pas la lecture des vidéos.
                </video>
            </div>
        </div>
        <div class="mb-3">
            <label for="diplome_pdf" class="form-label">Diplôme PDF</label>
            <input type="file" class="form-control" id="diplome_pdf" name="diplome_pdf" accept="application/pdf">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
        </div>
    </form>
</div>

<script>
   function editMember(member) {
    document.querySelector('form [name="action"]').value = 'edit';
    document.querySelector('form #member-id').value = member.id;
    document.querySelector('form #nom').value = member.nom || '';
    document.querySelector('form #description').value = member.description || '';
    document.querySelector('form #role').value = member.role || '';
    document.querySelector('form #current-image').value = member.image_url || '';
    document.querySelector('form #current-video').value = member.video_url || '';
    document.querySelector('form #current-diplome').value = member.diplome_pdf || '';

    // Afficher l'aperçu de l'image existante
    const imagePreview = document.getElementById('imagePreview');
    if (member.image_url) {
        imagePreview.src = '../' + member.image_url;
        imagePreview.style.display = 'block';
    } else {
        imagePreview.style.display = 'none';
    }

    // Afficher l'aperçu de la vidéo existante
    const videoPreview = document.getElementById('videoPreview');
    if (member.video_url) {
        videoPreview.src = '../' + member.video_url;
        videoPreview.style.display = 'block';
    } else {
        videoPreview.style.display = 'none';
    }
}

// Fonction pour afficher l'aperçu de l'image sélectionnée
document.querySelector('#image').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.querySelector('#image-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result; // Afficher l'image sélectionnée
            preview.style.display = 'block'; // Rendre l'aperçu visible
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = ''; // Réinitialiser l'aperçu si aucun fichier n'est sélectionné
        preview.style.display = 'none';
    }
});
function previewVideo(event) {
    const videoInput = event.target;
    const videoPreview = document.getElementById('videoPreview');

    if (videoInput.files && videoInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            videoPreview.src = e.target.result;
            videoPreview.style.display = 'block';
        };
        reader.readAsDataURL(videoInput.files[0]);
    } else {
        videoPreview.style.display = 'none';
    }
}
</script>
</body>
</html>
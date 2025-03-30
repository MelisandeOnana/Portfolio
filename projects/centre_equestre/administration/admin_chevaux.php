<?php
// filepath: c:\wamp64\www\Portfolio-php\projects\centre_equestre\admin_chevaux.php
session_start();

// Vérification si l'utilisateur est administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: index.php');
    exit;
}

// Inclure la connexion à la base de données
include('../config/config.php');

// Gestion des actions (ajouter, modifier, supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Dossier où les images seront stockées
        $uploadDir = '../assets/images/';

        if ($action === 'add') {
            // Vérifier si un fichier a été uploadé
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $uploadDir . $fileName;

                // Vérifier si le fichier est une image
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($fileType), $allowedTypes)) {
                    // Déplacer le fichier uploadé vers le dossier cible
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $imagePath = 'assets/images/' . $fileName; // Chemin relatif à enregistrer dans la BD
                    } else {
                        echo "Erreur lors du téléchargement de l'image.";
                    }
                } else {
                    echo "Type de fichier non supporté. Veuillez uploader une image (jpg, jpeg, png, gif).";
                }
            }

            // Ajouter un cheval avec l'image
            $stmt = $pdo->prepare("INSERT INTO chevaux (nom, type, race, image, description, lignée, année_de_naissance, couleur, sexe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['nom'],
                $_POST['type'],
                $_POST['race'],
                $imagePath, // Chemin de l'image
                $_POST['description'],
                $_POST['lignée'],
                $_POST['année_de_naissance'],
                $_POST['couleur'],
                $_POST['sexe']
            ]);

            // Redirection avec un message de validation
            header('Location: admin_chevaux.php?success=add');
            exit;
        } elseif ($action === 'edit') {
            // Vérifier si un fichier a été uploadé
            $imagePath = $_POST['current_image']; // Utiliser l'image actuelle par défaut
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $uploadDir . $fileName;

                // Vérifier si le fichier est une image
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($fileType), $allowedTypes)) {
                    // Déplacer le fichier uploadé vers le dossier cible
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $imagePath = 'assets/images/' . $fileName; // Chemin relatif à enregistrer dans la BD
                    } else {
                        echo "Erreur lors du téléchargement de l'image.";
                    }
                } else {
                    echo "Type de fichier non supporté. Veuillez uploader une image (jpg, jpeg, png, gif).";
                }
            }

            // Modifier un cheval avec l'image
            $stmt = $pdo->prepare("UPDATE chevaux SET nom = ?, type = ?, race = ?, image = ?, description = ?, lignée = ?, année_de_naissance = ?, couleur = ?, sexe = ? WHERE id = ?");
            $stmt->execute([
                $_POST['nom'],
                $_POST['type'],
                $_POST['race'],
                $imagePath, // Chemin de l'image
                $_POST['description'],
                $_POST['lignée'],
                $_POST['année_de_naissance'],
                $_POST['couleur'],
                $_POST['sexe'],
                $_POST['id']
            ]);

            // Redirection avec un message de validation
            header('Location: admin_chevaux.php?success=edit');
            exit;
        }
    }
} elseif (isset($_GET['delete'])) {
    // Supprimer un cheval
    $stmt = $pdo->prepare("DELETE FROM chevaux WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

// Récupérer tous les chevaux
$chevaux = $pdo->query("SELECT * FROM chevaux")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Chevaux</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center">Gestion des Chevaux</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php if ($_GET['success'] === 'add'): ?>
                Le cheval a été ajouté avec succès !
            <?php elseif ($_GET['success'] === 'edit'): ?>
                Le cheval a été modifié avec succès !
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Liste des chevaux -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Race</th>
                <th>Sexe</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chevaux as $cheval): ?>
                <tr>
                    <td><?= htmlspecialchars($cheval['nom']) ?></td>
                    <td><?= htmlspecialchars($cheval['type']) ?></td>
                    <td><?= htmlspecialchars($cheval['race']) ?></td>
                    <td><?= htmlspecialchars($cheval['sexe']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editCheval(<?= htmlspecialchars(json_encode($cheval)) ?>)">Modifier</button>
                        <a href="?delete=<?= $cheval['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cheval ? Cette action est irréversible.')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout/modification -->
    <form method="POST" class="my-4" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" id="cheval-id">
        <input type="hidden" name="current_image" id="current-image">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" class="form-control" id="type" name="type">
        </div>
        <div class="mb-3">
            <label for="race" class="form-label">Race</label>
            <input type="text" class="form-control" id="race" name="race">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <img id="image-preview" src="" alt="Aperçu de l'image" style="max-width: 200px; margin-top: 10px; display: none;">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="lignée" class="form-label">Lignée</label>
            <input type="text" class="form-control" id="lignée" name="lignée">
        </div>
        <div class="mb-3">
            <label for="année_de_naissance" class="form-label">Année de Naissance</label>
            <input type="number" class="form-control" id="année_de_naissance" name="année_de_naissance">
        </div>
        <div class="mb-3">
            <label for="couleur" class="form-label">Couleur</label>
            <input type="text" class="form-control" id="couleur" name="couleur">
        </div>
        <div class="mb-3">
            <label for="sexe" class="form-label">Sexe</label>
            <select class="form-control" id="sexe" name="sexe" required>
                <option value="" disabled selected>Choisir le sexe</option>
                <option value="jument">Jument</option>
                <option value="étalon">Étalon</option>
            </select>
        </div>
       
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
        </div>
    </form>
</div>

<script>
  function editCheval(cheval) {
    document.querySelector('form [name="action"]').value = 'edit';
    document.querySelector('form #cheval-id').value = cheval.id;
    document.querySelector('form #nom').value = cheval.nom || '';
    document.querySelector('form #type').value = cheval.type || '';
    document.querySelector('form #race').value = cheval.race || '';
    document.querySelector('form #description').value = cheval.description || '';
    document.querySelector('form #lignée').value = cheval.lignée || '';
    document.querySelector('form #année_de_naissance').value = cheval.année_de_naissance || '';
    document.querySelector('form #couleur').value = cheval.couleur || '';
    document.querySelector('form #sexe').value = cheval.sexe || '';
    document.querySelector('form #current-image').value = cheval.image || '';

    // Toujours afficher l'image actuelle
    const imagePath = cheval.image ? `../${cheval.image}` : '';
    const preview = document.querySelector('#image-preview');
    preview.src = imagePath;
    preview.style.display = imagePath ? 'block' : 'none';
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
</script>
</body>
</html>
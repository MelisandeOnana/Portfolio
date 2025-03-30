<?php
session_start();

// Vérification si l'utilisateur est administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: ../index.php');
    exit;
}

// Inclure la configuration de la base de données
require '../config/config.php';

// Connexion à la base de données
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Nombre de tarifs par page
$tarifsParPage = 5;

// Page actuelle (par défaut 1)
$pageActuelle = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$pageActuelle = max($pageActuelle, 1); // S'assurer que la page est au moins 1

// Calculer l'offset
$offset = ($pageActuelle - 1) * $tarifsParPage;

// Récupérer le nombre total de tarifs
$totalTarifs = $pdo->query("SELECT COUNT(*) FROM tarifs")->fetchColumn();

// Calculer le nombre total de pages
$totalPages = ceil($totalTarifs / $tarifsParPage);

// Récupérer les tarifs pour la page actuelle
$stmt = $pdo->prepare("SELECT * FROM tarifs ORDER BY categorie, id LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $tarifsParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$tarifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tarifs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center">Gestion des Tarifs</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php if ($_GET['success'] === 'add'): ?>
                Le tarif a été ajouté avec succès !
            <?php elseif ($_GET['success'] === 'edit'): ?>
                Le tarif a été modifié avec succès !
            <?php elseif ($_GET['success'] === 'delete'): ?>
                Le tarif a été supprimé avec succès !
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Liste des tarifs -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tarifs as $tarif): ?>
                <tr>
                    <td><?= htmlspecialchars($tarif['categorie'] ?? '') ?></td>
                    <td><?= htmlspecialchars($tarif['nom_tarif'] ?? '') ?></td>
                    <td><?= number_format($tarif['prix'], 2, ',', ' ') ?>€</td>
                    <td><?= htmlspecialchars($tarif['description'] ?? '') ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editTarif(<?= htmlspecialchars(json_encode($tarif)) ?>)">Modifier</button>
                        <a href="?delete=<?= $tarif['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tarif ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($pageActuelle > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pageActuelle - 1 ?>">Précédent</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $pageActuelle ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($pageActuelle < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pageActuelle + 1 ?>">Suivant</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Formulaire d'ajout/modification -->
    <form method="POST" class="my-4">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" id="tarif-id">
        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <input type="text" class="form-control" id="categorie" name="categorie" required>
        </div>
        <div class="mb-3">
            <label for="nom_tarif" class="form-label">Nom du Tarif</label>
            <input type="text" class="form-control" id="nom_tarif" name="nom_tarif" required>
        </div>
        <div class="mb-3">
            <label for="prix" class="form-label">Prix (€)</label>
            <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Retour</a>
        </div>
    </form>
</div>

<script>
function editTarif(tarif) {
    document.querySelector('form [name="action"]').value = 'edit';
    document.querySelector('form #tarif-id').value = tarif.id;
    document.querySelector('form #categorie').value = tarif.categorie || '';
    document.querySelector('form #nom_tarif').value = tarif.nom_tarif || '';
    document.querySelector('form #prix').value = tarif.prix || '';
    document.querySelector('form #description').value = tarif.description || '';
}
</script>
</body>
</html>
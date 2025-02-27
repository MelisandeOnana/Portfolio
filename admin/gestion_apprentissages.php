<?php
session_start();
include '../includes/db_connect.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Déterminer le nombre total d'apprentissages
$totalApprentissagesStmt = $pdo->query('SELECT COUNT(*) FROM apprentissages');
$totalApprentissages = $totalApprentissagesStmt->fetchColumn();

// Définir le nombre d'apprentissages par page
$apprentissagesParPage = 5;

// Calculer le nombre total de pages
$totalPages = ceil($totalApprentissages / $apprentissagesParPage);

// Déterminer la page actuelle
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pageActuelle = max(1, min($totalPages, $pageActuelle));

// Calculer l'offset pour la requête SQL
$offset = ($pageActuelle - 1) * $apprentissagesParPage;

// Récupérer les apprentissages pour la page actuelle
$stmt = $pdo->prepare('SELECT * FROM apprentissages LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $apprentissagesParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$apprentissages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Apprentissages</title>
    <link rel="stylesheet" href="../assets/css/gestion_apprentissages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
            <nav>
                <ul>
                    <li><a href="tableau_de_bord.php">Tableau de bord</a></li>
                    <li><a href="gestion_projets.php">Gestion des projets</a></li>
                    <li><a href="gestion_contacts.php">Gestion des contacts</a></li>
                    <li><a href="../logout.php">Déconnexion</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <section>
                <h2>Liste des Apprentissages</h2>
                <button onclick="window.location.href='ajouter_apprentissage.php'">Ajouter un apprentissage</button>
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date de Début</th>
                            <th>Certification</th>
                            <th>Logo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($apprentissages as $apprentissage): ?>
                            <tr>
                                <td><?= htmlspecialchars($apprentissage['titre'] ?? '') ?></td>
                                <td><?= htmlspecialchars($apprentissage['description'] ?? '') ?></td>
                                <td><?= htmlspecialchars($apprentissage['date_debut'] ?? '') ?></td>
                                <td><?= htmlspecialchars($apprentissage['certification'] ?? '') ?></td>
                                <td><i class="<?= htmlspecialchars($apprentissage['logo'] ?? '') ?>"></i></td>
                                <td class="actions">
                                    <a href="modifier_apprentissage.php?id=<?= $apprentissage['id'] ?>" class="modify">Modifier</a>
                                    <a href="supprimer_apprentissage.php?id=<?= $apprentissage['id'] ?>" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet apprentissage ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?php if ($pageActuelle > 1): ?>
                        <a href="?page=<?= $pageActuelle - 1 ?>">&laquo; Précédent</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="<?= $i == $pageActuelle ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($pageActuelle < $totalPages): ?>
                        <a href="?page=<?= $pageActuelle + 1 ?>">Suivant &raquo;</a>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
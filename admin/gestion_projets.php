<?php
session_start();
// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}
require_once '../includes/db_connect.php';

// Définir le nombre de projets par page
$projectsPerPage = 3;

// Récupérer le terme de recherche s'il existe
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Calculer le nombre total de projets
$sql = 'SELECT COUNT(*) FROM projets WHERE visible = 1';
if ($searchTerm) {
    $sql .= ' AND (titre LIKE :search OR description LIKE :search)';
}
$stmt = $pdo->prepare($sql);
if ($searchTerm) {
    $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
}
$stmt->execute();
$totalProjects = $stmt->fetchColumn();

// Calcule le nombre total de pages
$totalPages = ceil($totalProjects / $projectsPerPage);

// Détermine la page actuelle
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Calcule l'offset pour la requête SQL
$offset = max(0, ($currentPage - 1) * $projectsPerPage);

// Récupére les projets pour la page actuelle
$sql = 'SELECT * FROM projets';
if ($searchTerm) {
    $sql .= ' AND (titre LIKE :search OR description LIKE :search)';
}
$sql .= ' ORDER BY id DESC'; 
$sql .= ' LIMIT :limit OFFSET :offset';
$stmt = $pdo->prepare($sql);
if ($searchTerm) {
    $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $projectsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$projects = $stmt->fetchAll();

// Supprimer un projet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_project_id'])) {
    $deleteProjectId = $_POST['delete_project_id'];
    $sql = 'DELETE FROM projets WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $deleteProjectId, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: gestion_projets.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets</title>
    <link rel="stylesheet" href="../assets/css/gestion_projets.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
            <nav>
                <ul>
                    <li><a href="tableau_de_bord.php">Tableau de bord</a></li>
                    <li><a href="gestion_apprentissages.php">Gestion des Apprentissages</a></li>
                    <li><a href="gestion_contacts.php">Gestion des contacts</a></li>
                    <li><a href="../logout.php">Déconnexion</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <h2>Liste des Projets</h2>
            <form method="GET" action="gestion_projets.php">
                <input type="text" name="search" placeholder="Rechercher des projets..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Rechercher</button>
            </form>
            <button onclick="window.location.href='ajouter_projet.php'">Ajouter un projet</button>
            <?php if ($projects): ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card">
                            <div class="project-image">
                                <?php if ($project['image']): ?>
                                    <img src="../<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['titre']); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="project-info">
                                <h3><?php echo htmlspecialchars($project['titre']); ?></h3>
                                <p><?php echo htmlspecialchars($project['description']); ?></p>
                                <p>Date de début : <?php echo (new DateTime($project['date_debut']))->format('m - Y'); ?></p>
                                <?php if (!empty($project['date_fin'])): ?>
                                    <p>Date de fin : <?php echo (new DateTime($project['date_fin']))->format('m - Y'); ?></p>
                                <?php endif; ?>
                                <?php if ($project['lien']): ?>
                                    <p><a href="../<?php echo htmlspecialchars($project['lien']); ?>" target="_blank">Voir le projet</a></p>
                                <?php endif; ?>
                                <?php if ($project['documentation']): ?>
                                    <button onclick="window.location.href='../<?php echo htmlspecialchars($project['documentation']); ?>'">Voir le document</button>
                                <?php endif; ?>
                                <button onclick="window.location.href='modifier_projet.php?id=<?php echo $project['id']; ?>'">Modifier</button>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_project_id" value="<?php echo $project['id']; ?>">
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($searchTerm); ?>">&laquo; Précédent</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"<?php if ($i == $currentPage) echo ' class="active"'; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Suivant &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p>Aucun projet trouvé.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
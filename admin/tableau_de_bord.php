<?php
session_start();

include '../config/config.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

function getTotalProjects($pdo) {
    $stmt = $pdo->query('SELECT COUNT(*) FROM projets');
    return $stmt->fetchColumn();
}

function getTotalContacts($pdo) {
    $stmt = $pdo->query('SELECT COUNT(*) FROM demandes_contact');
    return $stmt->fetchColumn();
}

function getTotalApprentissages($pdo) {
    $stmt = $pdo->query('SELECT COUNT(*) FROM apprentissages');
    return $stmt->fetchColumn();
}

function searchProjects($pdo, $searchTerm) {
    $stmt = $pdo->prepare('SELECT * FROM projets WHERE titre LIKE :searchTerm OR description LIKE :searchTerm');
    $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
    return $stmt->fetchAll();
}

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$projects = $searchTerm ? searchProjects($pdo, $searchTerm) : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/tableau_de_bord.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
        </header>
        <nav>
            <ul>
                <li><a href="gestion_projets.php">Gestion des Projets</a></li>
                <li><a href="gestion_apprentissages.php">Gestion des Apprentissages</a></li>
                <li><a href="gestion_contacts.php">Gestion des Contacts</a></li>
                <li><a href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <main>
            <section>
                <h2>Recherche de Projets</h2>
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Rechercher des projets..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit">Rechercher</button>
                </form>
                <?php if ($searchTerm && $projects): ?>
                    <h3>Résultats de recherche pour "<?php echo htmlspecialchars($searchTerm); ?>"</h3>
                    <ul>
                        <?php foreach ($projects as $project): ?>
                            <li><?php echo htmlspecialchars($project['titre']); ?> - <?php echo htmlspecialchars($project['description']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php elseif ($searchTerm): ?>
                    <p>Aucun projet trouvé pour "<?php echo htmlspecialchars($searchTerm); ?>"</p>
                <?php endif; ?>
            </section>
            <section>
                <h2>Statistiques</h2>
                <div class="stats">
                    <div class="stat">
                        <h3>Projets</h3>
                        <p>Nombre total de projets : <?php echo getTotalProjects($pdo); ?></p>
                    </div>
                    <div class="stat">
                        <h3>Contacts</h3>
                        <p>Nombre total de contacts : <?php echo getTotalContacts($pdo); ?></p>
                    </div>
                    <div class="stat">
                        <h3>Apprentissages</h3>
                        <p>Nombre total d'apprentissages : 10</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
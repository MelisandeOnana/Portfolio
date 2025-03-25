<?php
session_start();
include '../config/config.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Vérifiez si un contact doit être supprimé
if (isset($_POST['delete_contact_id'])) {
    $contactId = (int)$_POST['delete_contact_id'];
    $stmt = $pdo->prepare('DELETE FROM demande_contact WHERE id = :id');
    $stmt->bindValue(':id', $contactId, PDO::PARAM_INT);
    $stmt->execute();
}

// Nombre de contacts par page
$contactsPerPage = 4;

// Numéro de la page actuelle
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// Calcul du nombre total de contacts
$totalContacts = $pdo->query('SELECT COUNT(*) FROM contact')->fetchColumn();

// Calcul du nombre total de pages
$totalPages = ceil($totalContacts / $contactsPerPage);

// Calcul de l'offset pour la page actuelle
$offset = ($page - 1) * $contactsPerPage;

// Récupération les contacts pour la page actuelle
$stmt = $pdo->prepare('SELECT * FROM contact LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $contactsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$contacts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des contacts</title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/gestion_contacts.css">
</head>
<body>
    <div class="container">
            <header>
                <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
            </header>
            <nav>
                <ul>
                    <li><a href="tableau_de_bord.php">Tableau de bord</a></li>
                    <li><a href="gestion_apprentissages.php">Gestion des Apprentissages</a></li>
                    <li><a href="gestion_images.php">Gestion des Images</a></li>
                    <li><a href="gestion_projets.php">Gestion des Projets</a></li>
                    <li><a href="../logout.php">Déconnexion</a></li>
                </ul>
            </nav>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Objet</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['nom']) ?></td>
                        <td><?= htmlspecialchars($contact['prenom']) ?></td>
                        <td><?= htmlspecialchars($contact['objet']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                        <td><?= htmlspecialchars($contact['message']) ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="delete_contact_id" value="<?= $contact['id'] ?>">
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contact ?');">Supprimer</button>
                            </form>
                            <a href="modifier_demande.php?id=<?= $contact['id'] ?>">
                                <button type="button">Modifier</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        // Fonction de confirmation de suppression
        function confirmDeletion() {
            return confirm('Êtes-vous sûr de vouloir supprimer ce contact ?');
        }
    </script>
</body>
</html>
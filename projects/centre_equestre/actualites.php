<?php
session_start();
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';

include 'config/config.php';

$sql = "SELECT id, titre, type, date_debut, date_fin, contenu, photo_path FROM actualites WHERE type IN ('stage', 'fete', 'camping', 'autre ') ORDER BY date_debut DESC";
$stmt = $pdo->query($sql);
$actualites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page actualités</title>
    <link rel="stylesheet" href="assets/css/Actualites.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <div class="actualites-container">
        <h1>Actualités</h1>
        <?php if ($actualites): ?>
            <?php foreach ($actualites as $actualite): ?>
                <div class="actualite">
                    <h2><?php echo htmlspecialchars($actualite['titre']); ?></h2>
                    <?php if ($actualite['photo_path']): ?>
                        <img src="<?php echo htmlspecialchars($actualite['photo_path']); ?>" alt="Photo de l'actualité" class="actualite-photo">
                    <?php endif; ?>
                    <div class="actualite-content">
                        <p><strong>Du:</strong> <?php echo date('d/m/y', strtotime($actualite['date_debut'])); ?> <strong>au:</strong> <?php echo date('d/m/y', strtotime($actualite['date_fin'])); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($actualite['contenu'])); ?></p>

                        <?php if ($actualite['type'] == 'stage'): ?>
                            <div class="btn-container">
                                <a href="tarifs.php?page=6" class="btn">Voir Tarif</a>
                                <a href="https://equimondo.fr" class="btn" target="_blank">S'inscrire sur Equimondo</a>
                            </div>
                        <?php elseif ($actualite['type'] == 'fete'): ?>
                            <div class="btn-container">
                                <a href="contact.php" class="btn">Contactez-nous !</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune actualité pour le moment.</p>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>

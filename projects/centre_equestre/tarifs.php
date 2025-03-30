<?php
session_start(); // Démarrez la session sur chaque page où vous utilisez des sessions
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';
// Inclure le fichier de configuration de la base de données
require 'config/config.php';

// Connexion à la base de données
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les tarifs depuis la base de données
$sql = "SELECT categorie, nom_tarif, prix, description FROM tarifs ORDER BY categorie, id";
$stmt = $pdo->query($sql);
$tarifs = $stmt->fetchAll(PDO::FETCH_GROUP);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs - Centre Équestre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Open+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='assets/css/Tarifs.css' rel='stylesheet' />
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <main>
    <div class="container">
        <h2 class="text-center">Nos Tarifs</h2>
        <div class="accordion" id="tarifsAccordion">
            <?php foreach ($tarifs as $categorie => $items): ?>
                <?php $id = preg_replace('/\s+/', '', strtolower($categorie)); ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?= $id; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $id; ?>" aria-expanded="false" aria-controls="collapse<?= $id; ?>">
                            <?= htmlspecialchars($categorie); ?>
                        </button>
                    </h2>
                    <div id="collapse<?= $id; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $id; ?>" data-bs-parent="#tarifsAccordion">
                        <div class="accordion-body">
                            <ul>
                                <?php foreach ($items as $tarif): ?>
                                    <li>
                                        <?= htmlspecialchars($tarif['nom_tarif']); ?> - <?= number_format($tarif['prix'], 2, ',', ' '); ?>€
                                        <?php if (!empty($tarif['description'])): ?>
                                            <br><small><?= htmlspecialchars($tarif['description']); ?></small>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>

</html>

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
    <style>
    body {
        background-color: #f9f9f9; /* Fond gris très clair */
        color: #4a3f35; /* Texte marron foncé */
        font-family: 'Arial', sans-serif;
    }
    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff; /* Fond blanc */
        border-radius: 10px; /* Coins arrondis */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre douce */
    }
    .accordion-button {
        background-color: #360C0C; /* Fond marron */
        color: #fff; /* Texte blanc */
        border: none; /* Pas de bordure */
        border-radius: 5px; /* Coins arrondis */
        padding: 15px;
        font-size: 1.2rem;
        text-align: left;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .accordion-button:hover {
        background-color: #2a0909; /* Marron plus foncé au survol */
        transform: scale(1.02); /* Légère mise en avant */
    }
    .accordion-button:not(.collapsed) {
        background-color: #2a0909; /* Marron plus foncé lorsque ouvert */
        color: #fff; /* Texte blanc */
    }
    .accordion-button::after {
        content: '\25bc'; /* Flèche vers le bas */
        font-size: 1rem;
        color: #fff; /* Couleur blanche pour correspondre au texte */
        float: right;
        transition: transform 0.3s ease;
    }
    .accordion-button:not(.collapsed)::after {
        transform: rotate(180deg); /* Rotation de la flèche vers le haut lorsque l'accordéon est ouvert */
    }
    .accordion-item {
        margin-bottom: 15px;
        border: 1px solid #d3c4b1; /* Bordure marron clair */
        border-radius: 5px; /* Coins arrondis */
        overflow: hidden; /* Empêche le débordement */
    }
    .accordion-header {
        padding: 0;
        margin: 0;
    }
    .accordion-body {
        background-color: #fdf8f4; /* Fond blanc cassé */
        padding: 20px;
        color: #4a3f35; /* Texte marron foncé */
        border-top: 1px solid #d3c4b1; /* Bordure supérieure marron clair */
        font-size: 1rem;
    }
</style>
</head>
<body>
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
</body>
</html>

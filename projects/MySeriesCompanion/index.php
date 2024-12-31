<?php
// Connexion à la base de données
include 'config/db.php';

// Récupération de toutes les séries avec leurs catégories associées
$query = $pdo->prepare("
    SELECT serie.nom_serie, serie.resume_serie, serie.vignette_serie, categorie.nom_cat
    FROM serie
    INNER JOIN categorie ON serie.categorie_serie = categorie.id_cat
    ORDER BY serie.nom_serie
");
$query->execute();
$series = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Séries</title>
    <link rel="stylesheet" href="assets/css/accueil.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="liste_categories.php">Mes catégories</a></li>
                <li><a href="ajout_categorie.php">Ajouter une catégorie</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Séries</h1>
        
        <?php if (!empty($series)): ?>
            <div class="series-container">
                <?php foreach ($series as $serie): ?>
                    <div class="serie-card">
                        <h2><?= htmlspecialchars($serie['nom_serie']) ?></h2>
                        <?php if (!empty($serie['vignette_serie'])): ?>
                            <img src="<?= htmlspecialchars($serie['vignette_serie']) ?>" alt="Vignette série">
                        <?php endif; ?>
                        <p class="categorie">Catégorie : <?= htmlspecialchars($serie['nom_cat']) ?></p>
                        <p><?= htmlspecialchars($serie['resume_serie']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune série disponible.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Mon Site de Séries</p>
    </footer>
</body>
</html>

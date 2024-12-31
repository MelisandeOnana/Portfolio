<?php
// Connexion à la base de données 
include 'config/db.php';

// Préparation de la requête pour récupérer toutes les catégories depuis la table CATEGORIE
$query = $pdo->prepare("SELECT * FROM categorie");
// Exécution de la requête préparée
$query->execute();
// Récupération de toutes les lignes de résultats dans un tableau associatif
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Catégories</title>
    <link rel="stylesheet" href="assets/css/liste_categories.css">
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
        <!-- Titre principal de la page -->
        <h1>Liste des Catégories</h1>

        <!-- Vérification s'il y a des catégories à afficher -->
        <?php if (count($categories) > 0): ?>
            <!-- Tableau pour afficher les catégories -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Âge minimum</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Boucle pour parcourir et afficher chaque catégorie récupérée de la base de données -->
                    <?php foreach ($categories as $categorie): ?>
                        <tr>
                            <!-- Affichage du nom de la catégorie avec échappement des caractères spéciaux pour éviter les injections XSS (htmlspecialchars) -->
                            <td><?= htmlspecialchars($categorie['nom_cat']) ?></td>
                            <!-- Affichage de l'âge minimum avec protection contre les injections XSS -->
                            <td><?= htmlspecialchars($categorie['agemin_cat']) ?> ans</td>
                            <!-- Lien pour accéder aux détails de la catégorie en passant l'ID dans l'URL -->
                            <td><a href="details_categorie.php?id_cat=<?= $categorie['id_cat'] ?>">Voir détails</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <!-- Message affiché si aucune catégorie n'est disponible -->
        <?php else: ?>
            <p>Aucune catégorie ajoutée pour le moment.</p>
        <?php endif; ?>
    </main>
</body>
</html>

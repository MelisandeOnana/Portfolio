<?php
//Connexion à la bd
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom_cat = $_POST['nom_cat'] ?? '';
    $agemin_cat = $_POST['agemin_cat'] ?? '';
    $vignette_cat = '';

    // Vérifie que les champs obligatoires sont remplis
    if (!empty($nom_cat) && !empty($agemin_cat)) {
        // Si une vignette est envoyée
        if (!empty($_FILES['vignette_cat']['name'])) {
            $vignette_cat = 'assets/images/' . basename($_FILES['vignette_cat']['name']);
            // la vignette se dirige vers le dossier images
            move_uploaded_file($_FILES['vignette_cat']['tmp_name'], $vignette_cat);
        }

        // Insère la nouvelle catégorie dans la base de données
        $query = $pdo->prepare("INSERT INTO categorie (nom_cat, agemin_cat, vignette_cat) VALUES (?, ?, ?)");
        $query->execute([$nom_cat, $agemin_cat, $vignette_cat]);

        // Redirection vers la page d'accueil après l'ajout
        header('Location: index.php');
        exit();
    } else {
        $error = "Le nom et l'âge minimum sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une catégorie</title>
    <link rel="stylesheet" href="assets/css/ajout_categorie.css">
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
        <h1>Ajouter une nouvelle Catégorie</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="nom_cat">Nom de la catégorie :</label>
            <input type="text" id="nom_cat" name="nom_cat" required>

            <label for="agemin_cat">Âge minimum :</label>
            <input type="number" id="agemin_cat" name="agemin_cat" required>

            <label for="vignette_cat">Vignette :</label>
            <input type="file" id="vignette_cat" name="vignette_cat">

            <button type="submit">Ajouter la Catégorie</button>
        </form>

        <p><a href="index.php">Retour à l'accueil</a></p>
    </main>
</body>
</html>

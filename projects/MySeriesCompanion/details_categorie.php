<?php
// Connexion à la base de données
include 'config/db.php';

// Initialisation des variables
$nom_cat = '';
$agemin_cat = '';
$vignette_cat = '';
$edit_mode = false;
$error = '';

// Vérifie si un ID de catégorie est passé en paramètre
if (isset($_GET['id_cat'])) {
    $id_cat = (int)$_GET['id_cat'];

    // Récupère les détails de la catégorie
    $query = $pdo->prepare("SELECT * FROM categorie WHERE id_cat = ?");
    $query->execute([$id_cat]);
    $categorie = $query->fetch(PDO::FETCH_ASSOC);

    // Vérifie si la catégorie existe
    if ($categorie) {
        $nom_cat = $categorie['nom_cat'];
        $agemin_cat = $categorie['agemin_cat'];
        $vignette_cat = $categorie['vignette_cat'];
        $edit_mode = isset($_GET['id_cat']);
    } else {
        echo "Catégorie non trouvée.";
        exit();
    }

    // Traitement du formulaire lors de la soumission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données du formulaire
        $nom_cat = $_POST['nom_cat'] ?? '';
        $agemin_cat = $_POST['agemin_cat'] ?? '';
        $vignette_cat_upload = '';

        // Vérifie si tous les champs obligatoires sont remplis
        if (!empty($nom_cat) && !empty($agemin_cat)) {
            // Si une nouvelle vignette est envoyée
            if (!empty($_FILES['vignette_cat']['name'])) {
                $vignette_cat_upload = 'assets/images/' . basename($_FILES['vignette_cat']['name']);
                // Déplacer la vignette vers le dossier images
                move_uploaded_file($_FILES['vignette_cat']['tmp_name'], $vignette_cat_upload);
            }

            if ($edit_mode) {
                // Mise à jour de la catégorie
                $query = $pdo->prepare("UPDATE categorie SET nom_cat = ?, agemin_cat = ?, vignette_cat = ? WHERE id_cat = ?");
                $query->execute([$nom_cat, $agemin_cat, $vignette_cat_upload, $id_cat]);

                // Rediriger vers la page de détails de la catégorie après la mise à jour
                header('Location: details_categorie.php?id_cat=' . $id_cat);
                exit();
            } else {
                // Ajouter une nouvelle catégorie (à ajouter si nécessaire)
            }
        }

        // Vérifie si la demande est pour la suppression
        if (isset($_POST['delete'])) {
            // Vérifie si la catégorie est liée à une série
            $query = $pdo->prepare("SELECT COUNT(*) FROM serie WHERE categorie_serie = ?");
            $query->execute([$id_cat]);
            $serie_count = $query->fetchColumn();

            // Si la catégorie est liée à une série on empêche la suppression
            if ($serie_count > 0) {
                $error = "Impossible de supprimer cette catégorie car elle est liée à une ou plusieurs séries.";
            } else {
                // Supprime la catégorie
                $query = $pdo->prepare("DELETE FROM categorie WHERE id_cat = ?");
                $query->execute([$id_cat]);

                // Redirection vers la liste des catégories après la suppression
                header('Location: liste_categories.php');
                exit();
            }
        }
    }
} else {
    echo "Aucune catégorie trouvée.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Catégorie</title>
    <link rel="stylesheet" href="assets/css/details_categorie.css">
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
        <h1>Détails de la Catégorie : <?= htmlspecialchars($categorie['nom_cat']) ?></h1>

        <!-- Affiche un message d'erreur si la suppression est impossible -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Confirmation de la suppression -->
        <form method="post">
            <button type="submit" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">Supprimer</button>
        </form>

        <p><strong>Nom : </strong> <?= htmlspecialchars($categorie['nom_cat']) ?></p>
        <p><strong>Âge minimum : </strong> <?= htmlspecialchars($categorie['agemin_cat']) ?> ans</p>

        <!-- Formulaire de modification de catégorie -->
        <form method="post" enctype="multipart/form-data">
            <h1>Modification de la catégorie : <?= htmlspecialchars($categorie['nom_cat']) ?></h1>
            <label for="nom_cat">Nom de la catégorie :</label>
            <input type="text" id="nom_cat" name="nom_cat" value="<?= htmlspecialchars($nom_cat) ?>" required><br>

            <label for="agemin_cat">Âge minimum :</label>
            <input type="number" id="agemin_cat" name="agemin_cat" value="<?= htmlspecialchars($agemin_cat) ?>" required><br>

            <label for="vignette_cat">Vignette :</label>
            <input type="file" id="vignette_cat" name="vignette_cat"><br>
            <?php if (!empty($vignette_cat)): ?>
                <p>Vignette actuelle : <img src="<?= htmlspecialchars($vignette_cat) ?>" alt="Vignette" width="100"></p>
            <?php endif; ?>

            <button type="submit"><?= $edit_mode ? 'Mettre à jour' : 'Ajouter' ?> la Catégorie</button>
        </form>
        <p><a href="liste_categories.php">Retour à la liste des catégories</a></p>
    </main>
</body>
</html>

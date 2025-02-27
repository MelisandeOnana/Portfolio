<?php
session_start();
include '../includes/db_connect.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Récupérer les informations de l'apprentissage à modifier
if (isset($_GET['id'])) {
    $apprentissageId = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM apprentissages WHERE id = :id');
    $stmt->bindValue(':id', $apprentissageId, PDO::PARAM_INT);
    $stmt->execute();
    $apprentissage = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Mettre à jour les informations de l'apprentissage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $certification = $_POST['certification'] ?? '';
    $logo = $_POST['logo'] ?? '';

    // Requête de mise à jour
    $query = "UPDATE apprentissages SET titre = ?, description = ?, date_debut = ?, certification = ?, logo = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$titre, $description, $date_debut, $certification, $logo, $apprentissageId]);

    // Redirection après mise à jour
    $_SESSION['message'] = "Apprentissage modifié avec succès.";
    header("Location: gestion_apprentissages.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Apprentissage</title>
    <link rel="stylesheet" href="../assets/css/modifier_apprentissage.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Modifier Apprentissage</h1>
        </header>
        <main>
            <form method="POST" action="modifier_apprentissage.php?id=<?= $apprentissageId ?>">
                <div class="form-group">
                    <label for="titre">Titre:</label>
                    <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($apprentissage['titre'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($apprentissage['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="date_debut">Date de Début:</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($apprentissage['date_debut'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="certification">Certification (URL):</label>
                    <input type="text" id="certification" name="certification" value="<?= htmlspecialchars($apprentissage['certification'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="logo">Logo (classe FontAwesome):</label>
                    <input type="text" id="logo" name="logo" value="<?= htmlspecialchars($apprentissage['logo'] ?? '') ?>">
                </div>
                <button type="submit" class="btn">Mettre à jour</button>
                <button type="back" class="btn">Retour</button>
            </form>
        </main>
    </div>
</body>
</html>
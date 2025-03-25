<?php
session_start();
include '../config/config.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

if (isset($_GET['id'])) {
    $apprentissageId = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM technologie WHERE id_technologie = :id');
    $stmt->bindValue(':id', $apprentissageId, PDO::PARAM_INT);
    $stmt->execute();
    $apprentissage = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Mettre à jour les informations de l'apprentissage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $description = $_POST['description'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';

    // Requête de mise à jour
    $query = "UPDATE technologie SET nom = ?, description = ?, date_debut = ? WHERE id_technologie = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nom, $description, $date_debut, $apprentissageId]);

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
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($apprentissage['nom'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($apprentissage['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="date_debut">Date de Début:</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($apprentissage['date_debut'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn">Mettre à jour</button>
                <button type="back" class="btn">Retour</button>
            </form>
        </main>
    </div>
</body>
</html>
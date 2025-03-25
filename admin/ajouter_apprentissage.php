<?php
session_start();
include '../config/config.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST["nom"]);
    $description = htmlspecialchars($_POST["description"]);
    $date_debut = htmlspecialchars($_POST["date_debut"]);

    // Requête d'insertion
    $query = "INSERT INTO technologie (nom, description, date_debut) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nom, $description, $date_debut]);

    // Redirection après ajout
    $_SESSION['message'] = "Apprentissage ajouté avec succès.";
    header("Location: gestion_apprentissages.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Apprentissage</title>
    <link rel="stylesheet" href="../assets/css/ajouter_apprentissage.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Ajouter un Apprentissage</h1>
        </header>
        <main>
            <form method="POST" action="ajouter_apprentissage.php">
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="date_debut">Date de Début:</label>
                    <input type="date" id="date_debut" name="date_debut" required>
                </div>
                <a href="gestion_apprentissages.php" class="btn">Retour</a>
                <button type="submit" class="btn">Ajouter</button>
            </form>
        </main>
    </div>
</body>
</html>
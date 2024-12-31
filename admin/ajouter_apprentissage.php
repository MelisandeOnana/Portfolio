<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = htmlspecialchars($_POST["titre"]);
    $description = htmlspecialchars($_POST["description"]);
    $date_debut = htmlspecialchars($_POST["date_debut"]);
    $certification = htmlspecialchars($_POST["certification"]);
    $logo = htmlspecialchars($_POST["logo"]);

    // Requête d'insertion
    $query = "INSERT INTO apprentissages (titre, description, date_debut, certification, logo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$titre, $description, $date_debut, $certification, $logo]);

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
                    <label for="titre">Titre:</label>
                    <input type="text" id="titre" name="titre" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="date_debut">Date de Début:</label>
                    <input type="date" id="date_debut" name="date_debut" required>
                </div>
                <div class="form-group">
                    <label for="certification">Certification (URL):</label>
                    <input type="text" id="certification" name="certification">
                </div>
                <div class="form-group">
                    <label for="logo">Logo (classe FontAwesome):</label>
                    <input type="text" id="logo" name="logo">
                </div>
                <button type="submit" class="btn">Ajouter</button>
            </form>
        </main>
    </div>
</body>
</html>
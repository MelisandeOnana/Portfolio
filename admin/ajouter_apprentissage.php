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

    // Gestion de l'upload du PDF
    $certification_pdfs = [];
    if (isset($_FILES['certification_pdf']) && $_FILES['certification_pdf']['error'][0] == 0) {
        $dossier = '../certifications/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }
        foreach ($_FILES['certification_pdf']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['certification_pdf']['error'][$key] == 0) {
                $fichier = basename($_FILES['certification_pdf']['name'][$key]);
                $chemin = $dossier . uniqid() . '_' . $fichier;
                if (move_uploaded_file($tmp_name, $chemin)) {
                    $certification_pdfs[] = $chemin;
                }
            }
        }
    }
    // Stockez les chemins en JSON ou séparés par une virgule selon votre besoin
    $certification_pdf = !empty($certification_pdfs) ? json_encode($certification_pdfs) : null;

    // Requête d'insertion
    $query = "INSERT INTO technologie (nom, description, date_debut, certification_pdf) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nom, $description, $date_debut, $certification_pdf]);

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
            <form method="POST" action="ajouter_apprentissage.php" enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="certification_pdf">Certification PDF :</label>
                    <input type="file" id="certification_pdf" name="certification_pdf[]" accept=".pdf" multiple>
                </div>
                <a href="gestion_apprentissages.php" class="btn">Retour</a>
                <button type="submit" class="btn">Ajouter</button>
            </form>
        </main>
    </div>
</body>
</html>
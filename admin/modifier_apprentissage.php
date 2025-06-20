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

    // Gestion de l'upload du PDF
    $certification_pdfs = [];
    // Récupérer les anciens PDF
    if (!empty($apprentissage['certification_pdf'])) {
        $old_pdfs = json_decode($apprentissage['certification_pdf'], true);
        if (is_array($old_pdfs)) {
            $certification_pdfs = $old_pdfs;
        }
    }
    // Ajouter les nouveaux PDF uploadés
    if (isset($_FILES['certification_pdf']) && isset($_FILES['certification_pdf']['tmp_name'][0]) && $_FILES['certification_pdf']['error'][0] == 0) {
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
    $certification_pdf = !empty($certification_pdfs) ? json_encode($certification_pdfs) : null;

    // Requête de mise à jour
    $query = "UPDATE technologie SET nom = ?, description = ?, date_debut = ?, certification_pdf = ? WHERE id_technologie = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nom, $description, $date_debut, $certification_pdf, $apprentissageId]);

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
            <form method="POST" action="modifier_apprentissage.php?id=<?= $apprentissageId ?>" enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="certification_pdf">Certification PDF :</label>
                    <input type="file" id="certification_pdf" name="certification_pdf[]" accept=".pdf" multiple>
                    <?php
                    // Afficher les PDF déjà présents
                    if (!empty($apprentissage['certification_pdf'])) {
                        $pdfs = json_decode($apprentissage['certification_pdf'], true);
                        if (is_array($pdfs)) {
                            foreach ($pdfs as $pdf) {
                                echo '<div><a href="'.htmlspecialchars($pdf).'" target="_blank">Voir PDF existant</a></div>';
                            }
                        }
                    }
                    ?>
                </div>
                <button type="submit" class="btn">Mettre à jour</button>
                <button type="back" class="btn">Retour</button>
            </form>
        </main>
    </div>
</body>
</html>
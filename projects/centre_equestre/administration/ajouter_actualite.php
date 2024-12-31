<?php
// Démarrer la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['loggedin'])) {
    error_log("L'utilisateur n'est pas connecté."); // Message de débogage
    header('Location: ../connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérification si l'utilisateur a le rôle d'administrateur
if ($_SESSION['role'] !== 'Administrateur') {
    error_log("L'utilisateur n'a pas le rôle d'administrateur."); // Message de débogage
    header('Location: ../connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas administrateur
    exit();
}

// Définir la variable $isAdmin
$isAdmin = ($_SESSION['role'] === 'Administrateur');
// Inclure le fichier de configuration de la base de données (où se trouve votre connexion PDO)
require_once '../config/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $contenu = $_POST['contenu'];

    // Gérer l'upload de l'image
    $photo = null;
    if (isset($_FILES['photo_path']) && $_FILES['photo_path']['error'] === UPLOAD_ERR_OK) {
        $photo = $_FILES['photo_path'];
        // Vérifier et déplacer le fichier téléchargé vers un emplacement spécifique
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($photo["name"]);
        move_uploaded_file($photo["tmp_name"], $target_file);
    }

    // Préparer la requête d'insertion SQL
    $sql = "INSERT INTO actualites (titre, type, date_debut, date_fin, contenu, photo_path) 
            VALUES (:titre, :type, :date_debut, :date_fin, :contenu, :photo_path)";
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres avec les valeurs sécurisées
    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':date_debut', $date_debut, PDO::PARAM_STR);
    $stmt->bindParam(':date_fin', $date_fin, PDO::PARAM_STR);
    $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
    $stmt->bindParam(':photo_path', $target_file, PDO::PARAM_STR); // Changer ceci en :photo si vous stockez un chemin relatif ou un identifiant dans la base de données

    // Exécuter la requête
    if ($stmt->execute()) {
        // Rediriger vers la page d'administration avec un message de succès
        header('Location: admin_actualites.php?success=1');
        exit();
    } else {
        // En cas d'échec de l'insertion, afficher un message d'erreur
        echo "Erreur lors de l'ajout de l'actualité.";
    }
}
?>

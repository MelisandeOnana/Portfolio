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

// Vérifier si l'ID de l'actualité est fourni
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour supprimer l'actualité
    $sql = "DELETE FROM actualites WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Exécuter la requête
    if ($stmt->execute()) {
        $message = "Actualité supprimée avec succès.";
    } else {
        $message = "Erreur lors de la suppression de l'actualité.";
    }
} else {
    $message = "ID d'actualité non valide.";
}

// Rediriger vers la page d'administration avec un message
header('Location: admin_actualites.php?message=' . urlencode($message));
exit();
?>

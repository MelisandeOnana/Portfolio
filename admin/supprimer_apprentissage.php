<?php
session_start();
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $apprentissageId = $_GET['id'];

    // Requête de suppression
    $query = "DELETE FROM apprentissages WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$apprentissageId]);

    // Redirection après suppression
    $_SESSION['message'] = "Apprentissage supprimé avec succès.";
    header("Location: gestion_apprentissages.php");
    exit();
}
?>
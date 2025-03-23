<?php
include '../config/config.php';

$id_projet = isset($_GET['id_projet']) ? (int)$_GET['id_projet'] : 0;

if ($id_projet > 0) {
    try {
        // Supprimer les images associées au projet
        $stmt_images = $pdo->prepare("DELETE FROM projet_images WHERE id_projet = :id_projet");
        $stmt_images->bindParam(':id_projet', $id_projet);
        $stmt_images->execute();

        // Supprimer le projet
        $stmt_projet = $pdo->prepare("DELETE FROM projet WHERE id_projet = :id_projet");
        $stmt_projet->bindParam(':id_projet', $id_projet);
        $stmt_projet->execute();

        echo "Projet supprimé avec succès.";
    } catch (PDOException $e) {
        die("Erreur lors de la suppression du projet : " . $e->getMessage());
    }
} else {
    die("Projet non spécifié.");
}
?>
<?php
// Fichier de connexion à la base de données
include 'db_connection.php';

// Requête est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération de l'ID du produit à partir du formulaire
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if (!empty($id)) {
        // Préparation de la requête SQL pour supprimer le produit
        $sql = "DELETE FROM produit WHERE id = ?";
        
        // Préparation de la requête avec une déclaration sécurisée
        if ($stmt = $conn->prepare($sql)) {
            // Associer l'ID comme paramètre
            $stmt->bind_param("i", $id);

            // Exécution de la requête
            if ($stmt->execute()) {
                // Si la suppression est réussie
                echo "<p>Le produit a été supprimé avec succès.</p>";
                echo "<a href='index.php'>Retour à la liste des produits</a>";
            } else {
                // En cas d'erreur lors de l'exécution
                echo "<p>Erreur lors de la suppression du produit : " . $stmt->error . "</p>";
                echo "<a href='index.php'>Retour</a>";
            }

            // Fermeture de la déclaration
            $stmt->close();
        } else {
            // En cas d'erreur de préparation de la requête
            echo "<p>Erreur de requête : " . $conn->error . "</p>";
            echo "<a href='index.php'>Retour</a>";
        }
    } else {
        // Si l'ID est manquant
        echo "<p>Aucun produit spécifié pour suppression.</p>";
        echo "<a href='index.php'>Retour</a>";
    }
}

// Fermeture la connexion à la base de données
$conn->close();


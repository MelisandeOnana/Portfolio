<?php
// Fonction pour obtenir les détails d'un produit à partir de l'identifiant
function getProductDetails($conn, $id) {
   
    $id = $conn->real_escape_string($id);
    
    // Préparation de la requête SQL pour récupérer les détails du produit
    $sql = "SELECT p.id, p.nom, p.prix, p.note, p.pays, p.gamme, m.nom AS marque, c.nom AS categorie 
            FROM produit p
            JOIN marque m ON p.marque_id = m.id
            JOIN categorie c ON p.categorie_id = c.id
            WHERE p.id = '$id'";

    // Exécution de la requête et retour du résultat
    return $conn->query($sql);
}

// Fonction pour afficher les détails du produit
function displayProductDetails($result) {
    // Vérifier si des résultats ont été retournés
    if ($result->num_rows > 0) {
        // Récupération la première ligne de résultats sous forme de tableau associatif
        $row = $result->fetch_assoc();
        
        // Affichage des détails du produit 
        echo "<h1>" . htmlspecialchars($row["nom"]) . "</h1>";
        echo "<p>Prix: " . htmlspecialchars($row["prix"]) . " €</p>";
        
        // Vérifie si une note est présente avant de l'afficher
        if ($row["note"]) {
            echo "<p>Note: " . htmlspecialchars($row["note"]) . "/10</p>";
        }
        
        // Affichage des autres détails du produit
        echo "<p>Pays: " . htmlspecialchars($row["pays"]) . "</p>";
        echo "<p>Gamme: " . htmlspecialchars($row["gamme"]) . "</p>";
        echo "<p>Marque: " . htmlspecialchars($row["marque"]) . "</p>";
        echo "<p>Catégorie: " . htmlspecialchars($row["categorie"]) . "</p>";
    } else {
        // Message affiché si le produit n'est pas trouvé
        echo "<p>Produit non trouvé.</p>";
    }
}

// Fonction pour fermer la connexion à la base de données
function closeConnection($conn) {
    // Fermeture de la connexion
    $conn->close();
}

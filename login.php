<?php
session_start();

include 'config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prévenir les injections SQL en utilisant des requêtes préparées
    $sql = "SELECT * FROM utilisateur WHERE prenom = ? AND mot_de_passe = ?";
    $stmt = $pdo->prepare($sql);
    
    // Exécution de la requête avec les paramètres
    $stmt->execute([$username, $password]);

    // Vérification du résultat
    if ($stmt->rowCount() == 1) {
        // Authentification réussie
        $_SESSION['username'] = $username;
        header("Location: admin/tableau_de_bord.php");
        exit();
    } else {
        // Authentification échouée
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<?php
session_start(); // Démarrer la session
include 'includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="stylesheet" href="assets/css/connexion.css">
</head>
<body>
    <div class="circles"></div> <!-- Cercles animés en arrière-plan -->
    <div class="container">
        <form action="login.php" method="post">
            <h2>Connexion</h2>
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Se connecter">
            <div class="forgot-password">
                <a href="mdp-oublie.php">Mot de passe oublié ?</a>
            </div>
        </form>
    </div>
</body>
</html>

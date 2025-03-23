<?php
// Informations de connexion à la base de données
$host = "mysql-lyceestvincent.alwaysdata.net"; // ou votre hôte MySQL
$dbname = "lyceestvincent_monana"; // nom de votre base de données
$username = "116313_monana"; // votre nom d'utilisateur MySQL
$password = "Maignelay26@"; // votre mot de passe MySQL

// Création de la connexion
$conn = new mysqli($host, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
} else {
    
}
?>
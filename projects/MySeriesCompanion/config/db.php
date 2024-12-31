<?php
// db.php
// Connexion à la base de données
$host = "mysql-lyceestvincent.alwaysdata.net"; // ou votre hôte MySQL
$dbname = "lyceestvincent_monana"; // nom de votre base de données
$username = "116313_monana"; // votre nom d'utilisateur MySQL
$password = "Maignelay26@"; // votre mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

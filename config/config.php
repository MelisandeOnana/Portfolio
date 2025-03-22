<?php
// Configuration de la base de données
if (!defined('DB_HOST')) {
    define('DB_HOST', '127.0.0.1');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'portfolio_bts_sio');
}

$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASS;
$dbname = DB_NAME;

if (!isset($pdo)) {
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}

// Autres configurations
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Mon Portfolio');
}
if (!defined('SITE_URL')) {
    define('SITE_URL', 'http://localhost/portfolio');
}
if (!defined('ADMIN_EMAIL')) {
    define('ADMIN_EMAIL', 'admin@portfolio.com');
}
?>
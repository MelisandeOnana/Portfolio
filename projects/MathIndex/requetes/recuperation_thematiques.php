<?php

$username = 'root';
$password = '';
$name = 'mathindex';
try
{
    $mysqlClient = new PDO("mysql:host=127.0.0.1; dbname=$name", $username, $password);
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

$informations = [
    'email' => htmlspecialchars($_POST['email']),
    'password' => htmlspecialchars($_POST['password']),
                
    ];
// On affiche les informations de contact.
$query = "SELECT * FROM themathic";
$stmt->execute();
$result = $stmt->fetch();
var_dump($result);

?>
<?php

$servername = "mysql-lyceestvincent.alwaysdata.net";
$username = '116313_monana';
$password = 'Maignelay26@';
$name = 'lyceestvincent_monana';

try
{
  $mysqlClient = new PDO("mysql:host=mysql-lyceestvincent.alwaysdata.net; dbname=$name", $username, $password);
  $conn = new mysqli($servername, $username, $password, $name);
}
catch (Exception $e){
  die('Erreur : ' . $e->getMessage());
}
?>

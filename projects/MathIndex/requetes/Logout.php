<?php
session_start();
session_unset(); // Efface toutes les variables de session
session_destroy(); // Détruit la session
header("Location: Accueil.php"); // Redirige vers la page d'accueil ou toute autre page appropriée après la déconnexion
exit();
?>

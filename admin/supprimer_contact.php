<?php
session_start(); // Démarrer la session
include '../includes/db_connect.php';

// Récupérer l'ID de la demande de contact à supprimer
$id = $_GET['id'];

// Supprimer la demande de contact de la base de données
$stmt = $pdo->prepare('DELETE FROM demandes_contact WHERE id = ?');
$stmt->execute([$id]);

// Rediriger vers la page de gestion des contacts
header('Location: gestion_contacts.php');
exit;
?>
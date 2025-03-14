<?php
require_once 'config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $objet = $_POST['objet'];
    $message = $_POST['message'];

    try {
        $stmt = $pdo->prepare("INSERT INTO demandes_contact (nom, prenom, email, objet, message) VALUES (:nom, :prenom, :email, :objet, :message)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':objet', $objet);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        echo "Votre message a été envoyé avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Méthode de requête non valide.";
}
?>

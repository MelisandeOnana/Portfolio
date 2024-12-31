<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers de PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

// Paramètres de connexion à la base de données (à adapter avec vos paramètres)
$servername = "mysql-ecuriesduvaldarre.alwaysdata.net";
$username = "364674_melisande";
$password = "CentreEquestre60@";
$dbname = "ecuriesduvaldarre_centreequestre";
// Vérifiez si l'utilisateur est admin
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

$emailSent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if ($email) {
        // Vérifier si l'email existe dans la base de données
        $stmt = $conn->prepare("SELECT * FROM administrateurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Envoyer le mot de passe par e-mail
            $password = $user['motdepasse']; // Récupérer le mot de passe depuis la base de données

            $mail = new PHPMailer(true);
            
            try {
                // Paramètres du serveur SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.orange.fr';  // Votre serveur SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'melisande.onana@orange.fr';  // Votre adresse email
                $mail->Password = 'Melisande24';  // Votre mot de passe email
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Définir l'encodage des caractères
                $mail->CharSet = 'UTF-8';

                // Destinataire
                $mail->setFrom('melisande.onana@orange.fr', 'Écuries du Val d\'Arré');
                $mail->addAddress($email);

                // Contenu de l'email
                $mail->isHTML(true);
                $mail->Subject = 'Demande de mot de passe';
                $mail->Body    = "Voici votre mot de passe : $password";

                $mail->send();
                $emailSent = true;
                echo "<script type='text/javascript'>alert('Le mot de passe a été envoyé à votre adresse email. Vous serez redirigé vers la page de connexion.'); window.location.href = 'connexion.php';</script>";
            } catch (Exception $e) {
                echo "Échec de l'envoi de l'email. Erreur : {$mail->ErrorInfo}";
            }
        } else {
            echo "Adresse email non trouvée dans la base de données.";
        }
    } else {
        echo "Adresse email non valide.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Open+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="assets/css/Motdepasseoublie.css" rel="stylesheet"/>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <form method="post">
        <h2>Demande de mot de passe</h2>
        <label for="email">Adresse e-mail :</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Envoyer le mot de passe</button>
    </form>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>

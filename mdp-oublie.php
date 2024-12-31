<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

include 'includes/db_connect.php';

$emailSent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if ($email) {
        // Check if the email exists in the database
        $stmt = $pdo->prepare("SELECT * FROM administrateur WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Fetch the password
            $password = $user['mot_de_passe']; // Assuming 'mot_de_passe' is the column for the password

            $mail = new PHPMailer(true);
            
            try {
                // SMTP server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.orange.fr';  
                $mail->SMTPAuth = true;
                $mail->Username = 'melisande.onana@orange.fr';  
                $mail->Password = 'Melisande24';  
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email settings
                $mail->CharSet = 'UTF-8';
                $mail->setFrom('melisande.onana@orange.fr', 'Portfolio de Melisande Onana');
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Demande de mot de passe';
                $mail->Body    = "Votre mot de passe est : <strong>$password</strong>";

                $mail->send();
                $emailSent = true;
                echo "<script type='text/javascript'>alert('Votre mot de passe a été envoyé à votre adresse email.'); window.location.href = 'connexion.php';</script>";
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
    <form method="post">
        <h2>Demande de mot de passe</h2>
        <label for="email">Adresse e-mail :</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Envoyer le mot de passe</button>
    </form>
</body>
</html>

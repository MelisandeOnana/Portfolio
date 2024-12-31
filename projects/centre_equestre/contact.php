<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

session_start(); // Start the session
$currentPage = 'contact'; // Indicate that we are on the contact page
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$messageClass = ''; // Variable to store the CSS class of the message

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for the presence and validity of the data
    if (!isset($_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message'])) {
        $_SESSION['message'] = "Tous les champs du formulaire doivent être remplis.";
        $_SESSION['messageClass'] = 'error';
    } else {
        $name = htmlspecialchars($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $subject = htmlspecialchars($_POST['subject']);
        $messageBody = htmlspecialchars($_POST['message']);

        if ($email) {
            // Send the email
            $mail = new PHPMailer(true);

            try {
                // SMTP server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.neuf.fr';  // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'debaecker.laetitia@neuf.fr';  // Replace with your email
                $mail->Password = 'Laetitia2910@';  // Replace with your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Set character encoding
                $mail->CharSet = 'UTF-8';

                // Recipient
                $mail->setFrom('debaecker.laetitia@neuf.fr', 'Écuries du Val d\'Arré');
                $mail->addAddress('debaecker.laetitia@neuf.fr'); // Replace with the email address where you want to receive contact messages

                // Email content
                $mail->isHTML(true);
                $mail->Subject = $subject; // Use the subject provided by the user
                $mail->Body = "<h3>Nouveau message :</h3>
                               <p><strong>Nom :</strong> $name</p>
                               <p><strong>Email :</strong> $email</p>
                               <p><strong>Objet :</strong> $subject</p>
                               <p><strong>Message :</strong><br> $messageBody</p>";

                $mail->send();
                $_SESSION['message'] = 'Votre message a été envoyé avec succès.';
                $_SESSION['messageClass'] = 'success';
            } catch (Exception $e) {
                $_SESSION['message'] = "Échec de l'envoi de l'e-mail. Erreur : {$mail->ErrorInfo}";
                $_SESSION['messageClass'] = 'error';
            }
        } else {
            $_SESSION['message'] = "Adresse email non valide.";
            $_SESSION['messageClass'] = 'error';
        }
    }
    // Redirect to avoid form resubmission on page reload
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Retrieve the session message and delete it
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageClass = $_SESSION['messageClass'];
    unset($_SESSION['message']);
    unset($_SESSION['messageClass']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link href="assets/css/Contact.css" rel="stylesheet"/>
    <style>
        .message {
            display: none;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .message.success {
            display: block;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            display: block;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <?php
    $isAdmin = false; // Ensure this variable is defined
    include 'includes/header.php';
    include 'includes/navigation.php';
    ?>

    <form id="contactForm" method="POST" class="contact-form">
        <h2>Contactez-nous</h2>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <label for="name">Nom:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="subject">Objet:</label><br>
        <input type="text" id="subject" name="subject" required><br><br>
        
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="4" required></textarea><br><br>
        
        <input type="submit" value="Envoyer">
    </form>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>

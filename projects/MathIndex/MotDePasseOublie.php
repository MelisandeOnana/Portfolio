<?php
        include_once 'requetes/configdb.php';
    /**
     * @param array $errors
     * @param string $field
     * @return array
     */
    function addMessageIfValueIsEmpty(array $errors, string $field): array
    {
        if (empty($_POST[$field])) {
            $errors[$field][] = sprintf('Le champ "%s" doit être renseigné.', $field);
        }

        return $errors;
    }

    function displayErrors(array $errors, string $field): void
    {

        if (isset($errors[$field])) {
            foreach ($errors[$field] as $error) {
                echo '<p class="error">' . $error . '</p>';
            }
        }
    }
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) === false) {

        // Ok des données sont transmises.
        $errors = addMessageIfValueIsEmpty($errors, 'email');

        $query = "SELECT email FROM user_mathindex";
        $stmt = $mysqlClient->prepare($query);
		$stmt->execute();
		$result = $stmt->fetch();
            
        if (!in_array($_POST['email'], $result) ){
            $errors['email'][] = 'L\'email renseigné n\'est pas connue.</br>Veuillez réessayer.';
        }
        
        if (empty($errors)) {
        	$informations = [
				'email' => htmlspecialchars($_POST['email']),
        	];
            $query ="SELECT first_name ,last_name FROM user_mathindex WHERE email=:email";
            $stmt = $mysqlClient->prepare($query);
            $stmt->bindParam(":email", $informations['email']);
		    $stmt->execute();
            $result = $stmt->fetch();

            
             // Adresse e-mail de destination
             $to = 'contact@lyceestvincent.net';

             // Sujet de l'e-mail
             $subject = 'MathIndex : '. $result['last_name'] .' '. $result['first_name']. ' demande un changement de mot de passe';
             
             // Contenu de l'e-mail
             $message = 'Adresse email de la personne : '. $informations['email'] .'. Après changement, merci de notifier l\'utilisateur de son nouveau mot de passe.';
             
             $headers  = array(
                'From' => 'user@mathindex.com'
            );
             // Envoyer l'e-mail
             if (mail($to, $subject, $message, $headers)) {
                 // Redirection après l'envoi du message
                 $reponse = 'Vos informations ont bien été envoyées';
             } else {
                 // En cas d'échec de l'envoi de l'e-mail
                 $reponse = 'Une erreur s\'est produite lors de l\'envoi du message.';
             }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https : //fonts.googleapis.com/css2?family=Epilogue:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="assets/styles/MotDePasseOublie.css" rel="stylesheet">
    <script src="requetes/menu_tel.js"></script>
    <title>Mot de passe</title>
</head>
<body>
    <nav class="barre-navigation hidden">
        <div class="ensembles-logo">
            <img alt="logo" src="assets/images/Logo.svg">
            <div class="ensembles-logo-titre ">
            <span class="titre">Math Index</span>
            <span class="sous-titre">Lycée Saint-Vincent -Senlis</span>
            </div>
        </div>
        <div class="ensembles-logo-ipad">
            <img alt="logo" src="assets/images/Logo.svg">
        </div>
        <button onclick='CachecheMenu()' class='btnFerme'>fermer le menu</button>
        <div class="navigation">
            <li><a href="Accueil.php" class="accueil-liens"><img src="assets/images/icone_home_gris.svg">Accueil</a></li>
            <li><a href="Recherche.php" class="recherche-liens"><img src="assets/images/icone_search_gris.svg">Recherche</a></li>
            <li><a href="Exercices.php" class="exercices-liens"><img src="assets/images/icone_fonctions_gris.svg">Exercices</a></li>
        </div>
        <div class="nav_ipad">
                <li><a href="Accueil.php" class="accueil-liens"><img src="assets/images/icone_home_gris.svg"></a></li>
                <li><a href="Recherche.php" class="recherche-liens"><img src="assets/images/icone_search_gris.svg"></a></li>
                <li><a href="Exercices.php" class="exercices-liens"><img src="assets/images/icone_fonctions_gris.svg"></a></li>
            <?php if(isset($_SESSION["account"])): ?>
                <?php if($_SESSION["account"]["role"] == "Administrateur" || $_SESSION["account"]["role"] == "Contributeur"): ?>
                    <li><a href="MesExercices.php" class="mesexercices-liens"><img src="assets/images/icone_liste_gris.svg"></a></li>
                    <li><a href="Soumettre.php" class="soumettre-liens"><img src="assets/images/icone_soumettre_gris.svg"></a></li>
                    <div class="deconnexion">
                        <li><a href="admin/authentification/logout.php" class="deconnexion-liens"><img src="assets/images/icone_logout.svg"></a></li>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>
    <header>
        <button onclick="AfficheMenu()" class='btn_menu_tel'><img src="assets/images/Hamburger_icon.png"></button>
        <div class="header-droite">
            <?php
           
            if (session_status() == PHP_SESSION_NONE) {
            }

            if(isset($_SESSION["account"])){
                $lastname=$_SESSION['account']['last_name'];
                $firstname=$_SESSION['account']['first_name'];
                $role=$_SESSION['account']['role'];
                echo "<div class='compte'>$lastname $firstname ($role)</div>";
                }
                else{
                echo "<a href='Connexion.php' class='connexion'><img src='assets/images/icone_login.svg' alt='login'>Connexion</a>";
                }
            ?>
        </div>
    </header>
    <div class='grand_conteneur'>
        <div class='menu_arriere'></div>
        <div class="contenu">
            <h1>Mot de passe oublié</h1>
                <div class="carre-blanc">
                    <p>Veuillez renseigner votre adresse mail pour demander un nouveau mot de passe.</p>
                    <form name="mdpoublie" method="POST">
                        <label for="email">Email :</label>
                        <div>
                            <input placeholder="Saisissez votre adresse mail" type="email" id="email" name="email">
                                <?php displayErrors($errors, 'email');
                                if (isset($informations)) { 
                                echo '<p class="reponse">'. $reponse .'</p>';
                                } ?>
                        </div>
                        
                        <button class="buttonSubmit">
                            <span>Envoyer</span>
                        </button>
                    </form>
                </div>
                <div class="mentionlegales">
                    <div class="mentionlegales-text">Mentions légales</div>
                    <div class="mentionlegales-text">•</div>
                    <div class="mentionlegales-text">Contact</div>
                    <div class="mentionlegales-text">•</div>
                    <div class="mentionlegales-text">Lycée Saint-Vincent</div>
                </div>
            </div>
        </div>
    </div>     
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="assets/styles/Connexion.css" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https : //fonts.googleapis.com/css2?family=Epilogue:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="requetes/menu_tel.js"></script>
        <title>Connexion</title>
    </head>
    <?php
    include "requetes/formulaire_connexion.php";
    ?>
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
                <ul>
                    <li><a href="Accueil.php" class="accueil-liens"><img src="assets/images/icone_home_gris.svg">Accueil</a></li>
                    <li><a href="Recherche.php" class="recherche-liens"><img src="assets/images/icone_search_gris.svg">Recherche</a></li>
                    <li><a href="Exercices.php" class="exercices-liens"><img src="assets/images/icone_fonctions_gris.svg">Exercices</a></li>
                </ul>
                <?php if(isset($_SESSION["account"])): ?>
                    <?php if($_SESSION["account"]["role"] == "Administrateur" || $_SESSION["account"]["role"] == "Contributeur"): ?>
                        <ul>
                            <li><a href="MesExercices.php" class="mesexercices-liens"><img src="assets/images/icone_liste_gris.svg">Mes exercices</a></li>
                            <li><a href="Soumettre.php" class="soumettre-liens"><img src="assets/images/icone_soumettre_gris.svg">Soumettre</a></li>
                            <div class="deconnexion">
                            <li><a href="admin/authentification/logout.php" class="deconnexion-liens"><img src="assets/images/icone_logout.svg">Déconnexion</a></li>
                            </div>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="nav_ipad">
                <ul>
                    <li><a href="Accueil.php" class="accueil-liens"><img src="assets/images/icone_home_gris.svg"></a></li>
                    <li><a href="Recherche.php" class="recherche-liens"><img src="assets/images/icone_search_gris.svg"></a></li>
                    <li><a href="Exercices.php" class="exercices-liens"><img src="assets/images/icone_fonctions_gris.svg"></a></li>
                </ul>
                <?php if(isset($_SESSION["account"])): ?>
                    <?php if($_SESSION["account"]["role"] == "Administrateur" || $_SESSION["account"]["role"] == "Contributeur"): ?>
                        <ul>
                            <li><a href="MesExercices.php" class="mesexercices-liens"><img src="assets/images/icone_liste_gris.svg"></a></li>
                            <li><a href="Soumettre.php" class="soumettre-liens"><img src="assets/images/icone_soumettre_gris.svg"></a></li>
                            <div class="deconnexion">
                            <li><a href="admin/authentification/logout.php" class="deconnexion-liens"><img src="assets/images/icone_logout.svg">Déconnexion</a></li>
                            </div>
                        </ul>
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
                    }else{
                        echo "<a href='Connexion.php' class='connexion'><img src='assets/images/icone_login.svg' alt='login'>Connexion</a>";
                    }
                ?>
            </div>
        </header>
        <div class='grand_conteneur'>
            <div class='menu_arriere'></div>
                <div class="contenu">
                    <h1>Connexion</h1>
                    <div class="carre-blanc">
                        <p>Cet espace est réservé aux enseignants du lycée Saint-Vincent - Senlis. Si vous n’avez pas encore de compte, veuillez effectuer votre demande directement en envoyant un email à <span class="contact-email">contact@lyceestvincent.net</span>.</p>
                        <form class="formulaire" method="post">

                            <label for="email">Email :</label>
                            <input type="email" name="email" placeholder="Saisissez votre adresse email"></input><br>

                            <label for="password">Mot de passe :</label>
                            <input type="password" name="password" placeholder="Saisissez votre mot de passe"></input><br>
                            <?php
                                if(empty($result) && $_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) === false){
                                    echo "email ou mot de passe incorrect";
                                }
                            ?>
                            <div class='element'>
                                <input type="submit" value="Connexion">
                                <a href="MotDePasseOublie.php">Mot de passe oublié ?</a>
                            </div>
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

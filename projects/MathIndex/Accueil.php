<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='assets/styles/Accueil.css' rel="stylesheet" />
  <script src="requetes/menu_tel.js"></script>
  <title>Accueil</title>
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
      <ul>
        <li><a href="Accueil.php" class="accueil-liens"><img src="assets/images/icone_home.svg"><strong>Accueil</strong></a></li>
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
        <li><a href="Accueil.php" class="accueil-liens"><img src="assets/images/icone_home.svg"></a></li>
        <li><a href="Recherche.php" class="recherche-liens"><img src="assets/images/icone_search_gris.svg"></a></li>
        <li><a href="Exercices.php" class="exercices-liens"><img src="assets/images/icone_fonctions_gris.svg"></a></li>
      </ul>
      <?php if(isset($_SESSION["account"])): ?>
        <?php if($_SESSION["account"]["role"] == "Administrateur" || $_SESSION["account"]["role"] == "Contributeur"): ?>
          <ul>
            <li><a href="MesExercices.php" class="mesexercices-liens"><img src="assets/images/icone_liste_gris.svg"></a></li>
            <li><a href="Soumettre.php" class="soumettre-liens"><img src="assets/images/icone_soumettre_gris.svg"></a></li>
            <div class="deconnexion">
              <li><a href="admin/authentification/logout.php" class="deconnexion-liens"><img src="assets/images/icone_logout.svg"></a></li>
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
            $profile_picture = isset($_SESSION['account']['profile_photo_file']) ? $_SESSION['account']['profile_photo_file'] : 'chemin/vers/image_par_defaut.jpg';
            echo "<div class='compte' id='bouton' tabindex='0'>$firstname $lastname <img src='assets/photos_de_profil/$profile_picture' alt='photo de profil' class='profil-image'></div>";
              if($role == "Administrateur" ){
                echo "<div class='cible' id='cible'>";
                echo "<a href='admin/Admin.php'><p>Administration</p><img class='img_admin' src='assets/images/icone-admin.svg'></a>";
                echo "<a href='admin/authentification/logout.php'><p>Déconnexion</p><img class='img_logout' src='assets/images/icone_logout.svg'></a>";
                echo "</div></div>";
              }
          } else {
              echo "<a href='Connexion.php' class='connexion'><img src='assets/images/icone_login.svg' alt='login'>Connexion</a>";
          }
      ?>
    </div>
    
  </header>
  <div class='grand_conteneur'>
    <div class='menu_arriere'></div>
    <div class="contenu">
      <h1>Accueil</h1>
      <div class="carre-blanc">
          <p>Nous sommes ravis de vous accueillir sur <strong>Math Index</strong>, la plateforme mathématique exclusive du lycée Saint-Vincent Senlis. Développée avec passion pour enrichir l'expérience éducative de nos étudiants, cette ressource en ligne offre un accès simplifié à une vaste bibliothèque d'exercices mathématiques de qualité.</p>
          <br>
          <h2>Explorez les Avantages Spécifiques à Notre Lycée :</h2>
          <ol>
              <li>Exercices Personnalisés : Découvrez une collection soigneusement sélectionnée d'exercices qui complètent notre programme éducatif, adaptés aux niveaux et aux besoins spécifiques en classe.</li>
              <br>
              <li>Soutien Pédagogique : <strong>Math Index</strong> sert de complément idéal à nos cours, offrant aux enseignants et aux étudiants un outil puissant pour renforcer la compréhension des concepts mathématiques enseignés en classe.</li>
              <br>
              <li>Collaboration Communautaire : En tant que membre de notre lycée, participez à la communauté en partageant vos propres exercices, collaborez avec d'autres enseignants et favorisez un environnement d'apprentissage collaboratif.</li>
          </ol>
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
</body>
</html>

<?php
session_start();
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="s87jrDKf2cHVuoAUfzlWqE6gem6v1deechar195EJ2Q" />
    <title>Page d'Accueil</title>
    <link href="assets/css/Accueil.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>

    <main>
        <div class="main-contenu">
            <h2>Bienvenue au Centre Équestre du Val d’Arré !</h2>
            <p>Découvrez notre univers équestre où passion, complicité et aventure se rencontrent. Que vous soyez débutant ou cavalier confirmé, rejoignez-nous pour explorer ensemble le monde magique des chevaux.</p>
            <p>À bientôt parmi nous !</p>
        </div>
        <div class="video-conteneur">
            <video class="responsive-video" autoplay muted loop>
                <source src="assets/videos/video_accueil3.mp4" type="video/mp4">
            </video>
            <img src="assets/images/Accueil.JPG">
        </div>
        <section class="equipe">
            <h2>Notre Équipe</h2>
            <div class="membres-equipe">
                <div class="membre">
                    <div class="membre-img">
                        <img src="assets/images/diane.JPG" alt="Debaecker Diane" class="clickable-image">
                    </div>
                    <h3>Debaecker Diane</h3>
                    <p>Diane est notre experte en formation des cavaliers adultes, spécialisée dans la préparation aux concours. Avec plusieurs années d'expérience, elle apporte rigueur et passion à chaque cours, guidant nos cavaliers vers l'excellence.</p>
                </div>
                <div class="membre">
                    <div class="membre-img">
                        <video class="responsive-video" autoplay muted loop>
                            <source src="assets/videos/laetitia.mp4" type="video/mp4">
                        </video>
                    </div>
                    <h3>Debaecker Laëtitia</h3>
                    <p>Fondatrice du Centre Équestre du Val d’Arré, Laëtitia a consacré sa vie à sa passion pour les chevaux. Aujourd'hui, elle se concentre principalement sur l'administration, mais continue d'apporter son expertise en tant que monitrice lorsque nécessaire. Sa vision et son leadership ont façonné le centre en ce qu'il est aujourd'hui.</p>
                </div>
                <div class="membre">
                    <div class="membre-img">
                        <img src="assets/images/marion.JPG" alt="Cavallaro Marion" class="clickable-image">
                    </div>
                    <h3>Cavallaro Marion</h3>
                    <p>Marion est dédiée à l'apprentissage des enfants, rendant chaque leçon amusante et éducative. Son approche douce et patiente inspire les jeunes cavaliers, leur inculquant les bases de l'équitation avec un sourire constant.</p>
                </div>
            </div>
        </section>
    </main>
    <!-- Lightbox container -->
    <div id="lightbox" class="lightbox">
        <span class="close">&times;</span>
        <div class="lightbox-content">
            <!-- Contenu sera injecté via JavaScript -->
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>
</html>

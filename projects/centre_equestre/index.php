<?php
include('config/config.php');
session_start();
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';
// Requête pour récupérer les membres de l'équipe
$sql = "SELECT * FROM equipe";
$result = $pdo->query($sql);
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
                <?php
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="membre">';
                        echo '<div class="membre-img">';
                        if (!empty($row['image_url'])) {
                            echo '<img src="' . $row['image_url'] . '" alt="' . $row['nom'] . '">';
                        } elseif (!empty($row['video_url'])) {
                            echo '<video autoplay muted loop>';
                            echo '<source src="' . $row['video_url'] . '" type="video/mp4">';
                            echo '</video>';
                        }
                        echo '</div>';
                        echo '<div class="membre-contenu">';
                        echo '<h3>' . $row['nom'] . '</h3>';
                        echo '<p>' . $row['description'] . '</p>';
                        if (!empty($row['diplome_pdf'])) {
                            echo '<a href="' . $row['diplome_pdf'] . '" target="_blank" class="btn-details">Voir le diplôme</a>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Aucun membre de l\'équipe trouvé.</p>';
                }
                ?>
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
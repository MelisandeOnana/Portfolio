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
    <title>Galerie d'images</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Open+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='assets/css/Galerie.css' rel='stylesheet'/>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <h1>Galerie d'images</h1>
    <!-- Section pour afficher les images -->
    <div class="gallery">
        <?php
            // Chemin vers le dossier contenant les images mises en ligne par l'administrateur
            $dossier_images_admin = 'administration/uploads/';
            // Récupération de tous les fichiers dans le dossier
            $images = glob($dossier_images_admin . '*');

            // Filtrer uniquement les images
            $extensions_supportees = ['jpg', 'jpeg', 'png', 'gif'];

            foreach ($images as $image) {
                $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                if (in_array($extension, $extensions_supportees)) {
                    // Affiche le chemin de chaque image pour vérifier
                    echo '<div class="image">';
                    echo '<img src="' . $image . '" alt="Image">';
                    echo '</div>';
                }
            }
        ?>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.gallery img').forEach(img => {
        img.addEventListener('click', function() {
            console.log('Image clicked:', this.src);
            if (!document.fullscreenElement) {
                console.log('Entering fullscreen mode');
                if (this.requestFullscreen) {
                    this.requestFullscreen();
                } else if (this.mozRequestFullScreen) { // Firefox
                    this.mozRequestFullScreen();
                } else if (this.webkitRequestFullscreen) { // Chrome, Safari and Opera
                    this.webkitRequestFullscreen();
                } else if (this.msRequestFullscreen) { // IE/Edge
                    this.msRequestFullscreen();
                }
                this.classList.add('fullscreen-img');
            } else {
                console.log('Exiting fullscreen mode');
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) { // Firefox
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { // IE/Edge
                    document.msExitFullscreen();
                }
                this.classList.remove('fullscreen-img');
            }
        });
    });
});

    </script>
</body>
</html>

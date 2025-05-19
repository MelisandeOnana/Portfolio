<!-- filepath: c:\wamp64\www\portfolio\includes\header.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="assets/js/main.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tarteaucitronjs@1.9.6/tarteaucitron.min.js"></script>
    <title>Portfolio</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="connexion.php">Mélisande<br>Onana Ngono</a>
        </div>
        <button class="hamburger" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="menu">
            <ul>
                <li><a href="#home" class="active">Accueil</a></li>
                <li><a href="#projects">Mes Réalisations</a></li>
                <li><a href="#skills">Mes Apprentissages</a></li>
                <li><a href="#technology_watch">Veille technologique</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const hamburger = document.querySelector(".hamburger");
        const menu = document.querySelector(".menu");
        const menuLinks = document.querySelectorAll(".menu ul li a");

        // Ouvrir/fermer le menu avec le bouton hamburger
        hamburger.addEventListener("click", () => {
            menu.classList.toggle("active");
            hamburger.classList.toggle("open");
        });

        // Fermer le menu lorsqu'un lien est cliqué
        menuLinks.forEach(link => {
            link.addEventListener("click", () => {
                menu.classList.remove("active");
                hamburger.classList.remove("open");
            });
        });
    });
</script>
</body>
</html>
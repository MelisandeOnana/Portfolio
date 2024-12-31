<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces de vente</title>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f3f4f6;
            color: #333;
        }

        main {
            text-align: center;
            padding: 30px 20px;
        }
        
        /* Header */
        header {
            background: linear-gradient(90deg, rgba(54,12,12,1) 0%, rgba(128,21,21,1) 100%);
            color: white;
            padding: 20px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .left-header {
            display: flex;
            align-items: center;
        }

        .left-header .hamburger {
            margin-right: 10px;
        }

        .center-header {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            font-family: 'Lobster', cursive;
        }

        .center-header h2 {
            font-size: 32px;
            margin: 0;
        }

        .center-header p {
            font-size: 16px;
            margin: 5px 0 0 0;
        }

        .hamburger {
            display: inline-block;
            cursor: pointer;
        }

        .bar1, .bar2, .bar3 {
            height: 3px;
            background-color: #fff;
            margin: 6px 0;
            transition: 0.4s;
        }

        .bar1 {
            width: 45px;
        }

        .bar2 {
            width: 35px;
        }

        .bar3 {
            width: 25px;
        }

        .hamburger:hover .bar1, .hamburger:hover .bar2, .hamburger:hover .bar3 {
            background-color: #f1f1f1;
        }

        h1, h2 {
                margin: 0;
         }

        hr {
                margin: 5px 0;
        }

        .connexion-btn {
            background-color: #fff;
            color: #360C0C;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .connexion-btn:hover {
            background-color: #f1f1f1;
        }
        
        footer {
                text-align: left;
                padding: 10px;
                background-color: #360C0C;
                color: white;
        }
       /* Side Navigation Menu */
       .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 2;
            top: 0;
            left: 0;
            background-color: #360C0C;
            overflow-x: hidden;
            transition: width 0.5s;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding-top: 60px;
        }

        .sidenav h1 {
            color: white;
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
        }

        .sidenav a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
            width: 100%;
            text-align: left;
            padding-left: 90px;
        }

        .sidenav a:hover {
            color: #f1f1f1;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidenav .closebtn {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 36px;
            color: white;
            cursor: pointer;
        }

        .closebtn:hover {
            color: #f1f1f1;
        }

        .close-button {
            margin-top: auto;
            background-color: #fff;
            color: #360C0C;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .close-button:hover {
            background-color: #f1f1f1;
        }
        
        .annonce {
            width: 300px;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
        }

        .annonce img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        button {
            background-color: #5c1717;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #360c0c;
        }

        
                /* Style pour le conteneur du message de bienvenue et du bouton de déconnexion */
                .welcome-container {
            position: relative;
            margin-bottom: 20px; /* Espacement entre le message de bienvenue et le bouton de déconnexion */
            cursor: pointer; /* Changement du curseur pour indiquer que l'élément est cliquable */
        }

        /* Style pour le bouton de déconnexion */
        .logout-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            align-items: center;
            width: 200px;
        }

        .logout-container button {
            background-color: #fff;
            color: #360C0C;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
            display: block; /* Les boutons s'affichent en bloc */
            width: 100%; /* Les boutons occupent toute la largeur du conteneur */
            margin-bottom: 5px; /* Espacement entre les boutons */
            text-align: left; /* Alignement du texte à gauche */
        }
        
        /* Style pour le bouton de déconnexion */
        .sous_menu {
            background-color: #fff;
            color: #360C0C;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
            margin-bottom: 10px;
            width: 100%;
            text-align: center;
        }

        .sous_menu:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <div class="hamburger" onclick="openNav()">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>
            </div>
            <div class="center-header">
                <h2>Écurie du Val d'Arré</h2>
                <hr>
                <p>Saint-Rémy en l'eau</p>
            </div>
            <?php echo $connexionButton; ?>
        </div>
    </header>

    <div id="mySidenav" class="sidenav">
        <span class="closebtn" onclick="closeNav()">&times;</span>
        <h1>Menu</h1>
        <a href="Accueil.php">Accueil</a>
        <a href="elevage.php" onclick="openSubMenu()">L'élevage Welsh/SLF</a>
        <a href="methode_blondeau.php">La méthode Blondeau</a>
        <a href="Tarifs.php">Tarifs</a>
        <a href="vendre.php">À Vendre</a>
        <a href="galerie.php">Galeries</a>
        <a href="actualites.php">Actualités</a>
        <a href="concours.php">Concours</a>
        <a href="contact.php">Contact</a>
    </div>

    <main>
        <div class="listing">
            <?php include 'listing.php'; ?>
        </div>
    </main>

    <footer>
        <p>© 2024 Écurie du Val d'Arré</p>
    </footer>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        // Fonction pour afficher le bouton de déconnexion
        function showLogoutButton() {
            var btn = document.querySelector('.logout-container');
            if (btn) {
                btn.style.display = (btn.style.display === 'block') ? 'none' : 'block';
            }
        }

        // Fonction pour la déconnexion
        function logout() {
            window.location.href = 'requetes/logout.php';
        }

        // Fonction pour rediriger vers la page Gestion de la Galeries 
        function goToGestionGaleries() {
            window.location.href = 'requetes/gestion_galeries.php';
        }

        // Fonction pour rediriger vers la page Gestion Tarifs
        function goToGestionTarifs() {
            window.location.href = 'requetes/gestion_tarifs.php';
        }
    </script>
</body>
</html>

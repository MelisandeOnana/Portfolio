<?php 
session_start();

include_once 'requetes/configdb.php';

// Assurez-vous que la connexion à la base de données est établie
if (!isset($mysqlClient) || !$mysqlClient) {
    die("Connection failed: " . $mysqlClient->connect_error);
}

// Pagination
$exercices_par_page = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $exercices_par_page;

// Requête pour obtenir le nombre total d'exercices
$sql_total_exercices = "SELECT COUNT(*) AS total FROM exercise";
$result_total_exercices = $mysqlClient->query($sql_total_exercices);
$row_total_exercices = $result_total_exercices->fetch(PDO::FETCH_ASSOC);
$total_exercices = $row_total_exercices['total'];

// Calcule le nombre total de pages
$total_pages = ceil($total_exercices / $exercices_par_page);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exercices</title>
  <link href='assets/styles/Exercices.css' rel='stylesheet'/>
  <script src="requetes/menu_tel.js"></script>
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
                <li><a href="Accueil.php" class="accueil-liens"><img src="assets/images/icone_home_gris.svg">Accueil</a></li>
                <li><a href="Recherche.php" class="recherche-liens"><img src="assets/images/icone_search_gris.svg">Recherche</a></li>
                <li><a href="Exercices.php" class="exercices-liens"><img src="assets/images/icone_fonctions.svg"><strong>Exercices</strong></a></li>
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
                <li><a href="Exercices.php" class="exercices-liens"><img src="assets/images/icone_fonctions.svg"></a></li>
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
                <h1>Exercices</h1>
                <div class="carre-blanc">
                    <h2>Nouveautés</h2>
                    <table>
                        <tr>
                            <th style="border-left: 1px solid #DBDBDB;">Nom</th>
                            <th>Thématique</th>
                            <th>Difficulté</th>
                            <th>Durée</th>
                            <th>Mots clés</th>
                            <th style="border-right: 1px solid #DBDBDB;">Fichiers</th>
                        </tr>
            
                        <?php

                            // Requête SQL pour récupérer les projets depuis la base de données
                            $sql = "SELECT exercise.name AS exercise_name,
                                        thematic.name AS thematic_name, exercise.difficulty,
                                        exercise.duration, exercise.keywords, 
                                        file_exercice.name AS exercice_name, 
                                        file_exercice.original_name AS exercice_original_name, 
                                        file_exercice.extension,
                                        file_correction.name AS correction_name, 
                                        file_correction.original_name AS correction_original_name, 
                                        file_correction.extension AS correction_extension
                                    FROM exercise
                                    LEFT JOIN thematic ON exercise.thematic_id = thematic.id
                                    LEFT JOIN file AS file_exercice ON exercise.exercice_file_id = file_exercice.id
                                    LEFT JOIN file AS file_correction ON exercise.correction_file_id = file_correction.id
                                    ORDER BY exercise.date DESC LIMIT 3";
                            $result = $mysqlClient->query($sql);

                            // Afficher les données dans le tableau HTML
                            if ($result->rowCount() > 0) {
                                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                        echo "<td>" . $row["exercise_name"] . "</td>";
                                        echo "<td>" . $row["thematic_name"] . "</td>";
                                        echo "<td>" . $row["difficulty"] . "</td>";
                                        echo "<td>" . $row["duration"] . "h00</td>";
                                        echo '<td>';
                                        $keywords = explode(',', $row['keywords']);
                                        foreach ($keywords as $keyword) {
                                            echo '<span class="mot_cle">' . $keyword . '</span>';
                                        }
                                        echo '</td>';
                                        echo "<td>";
                                                echo "<img src='assets/images/icone_download.svg'>
                                                <a href='assets/Exercices/" . $row["exercice_name"] . "." . $row["extension"] . "' download='" . $row["exercice_original_name"] . "." . $row["extension"] . "'>Exercice</a><br>";

                                                if ($row["correction_original_name"] && $row["correction_extension"]) {
                                                    echo "<img src='assets/images/icone_download.svg'>
                                                    <a href='assets/Corriges/" . $row["correction_name"] . "." . $row["extension"]. "' download='" . $row["correction_original_name"] . "." . $row["correction_extension"] . "'>Corrigé</a>";
                                                }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            }

                        ?>
                    </table>
                    <h2>Tous les exercices</h2>
                    <table>
                        <tr>
                            <th style="border-left: 1px solid #DBDBDB;">Nom</th>
                            <th>Thématique</th>
                            <th>Difficulté</th>
                            <th>Durée</th>
                            <th>Mots clés</th>
                            <th style="border-right: 1px solid #DBDBDB;">Fichiers</th>
                        </tr>
            
                        <?php

                            // Requête SQL pour récupérer les projets depuis la base de données
                            $sql = "SELECT exercise.name AS exercise_name,
                                        thematic.name AS thematic_name, exercise.difficulty,
                                        exercise.duration, exercise.keywords, 
                                        file_exercice.name AS exercice_name, 
                                        file_exercice.original_name AS exercice_original_name, 
                                        file_exercice.extension,
                                        file_correction.name AS correction_name, 
                                        file_correction.original_name AS correction_original_name, 
                                        file_correction.extension AS correction_extension
                                    FROM exercise
                                    LEFT JOIN thematic ON exercise.thematic_id = thematic.id
                                    LEFT JOIN file AS file_exercice ON exercise.exercice_file_id = file_exercice.id
                                    LEFT JOIN file AS file_correction ON exercise.correction_file_id = file_correction.id
                                    ORDER BY exercise.date DESC
                                    LIMIT $exercices_par_page OFFSET $offset";
                            $result = $mysqlClient->query($sql);

                            // Afficher les données dans le tableau HTML
                            if ($result->rowCount() > 0) {
                                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["exercise_name"] . "</td>";
                                    echo "<td>" . $row["thematic_name"] . "</td>";
                                    echo "<td>" . $row["difficulty"] . "</td>";
                                    echo "<td>" . $row["duration"] . "h00</td>";
                                    echo '<td>';
                                    $keywords = explode(',', $row['keywords']);
                                    foreach ($keywords as $keyword) {
                                        echo '<span class="mot_cle">' . $keyword . '</span>';
                                    }
                                    echo '</td>';
                                    echo "<td>";
                                    echo "<img src='assets/images/icone_download.svg'>
                                    <a href='assets/Exercices/" . $row["exercice_name"] . "." . $row["extension"] . "' download='" . $row["exercice_original_name"] . "." . $row["extension"] . "'>Exercice</a><br>";

                                    if ($row["correction_original_name"] && $row["correction_extension"]) {
                                        echo "<img src='assets/images/icone_download.svg'>
                                        <a href='assets/Corriges/" . $row["correction_name"] . "." . $row["extension"]. "' download='" . $row["correction_original_name"] . "." . $row["correction_extension"] . "'>Corrigé</a>";
                                    }
                                echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </table>

                        <!-- Pagination -->
                    <div class="pagination">
                            <?php
                                if ($page > 1) {
                                    echo "<a href='Exercices.php?page=".($page - 1)."' class='pagination-bouton-gauche'>&lt;</a>";
                                } else {
                                    echo "<span class='pagination-bouton-gauche'>&lt;</span>";
                                }

                                for ($i=1; $i<=$total_pages; $i++) {
                                    if ($i == $page) {
                                        echo "<span class='page-actuel'>$i</span>";
                                    } else {
                                        echo "<a href='Exercices.php?page=".$i."' class='pagination-lien'>$i</a>";
                                    }
                                }

                                if ($page < $total_pages) {
                                    echo "<a href='Exercices.php?page=".($page + 1)."' class='pagination-bouton-droite'>&gt;</a>";
                                } else {
                                    echo "<span class='pagination-bouton-droite'>&gt;</span>";
                                }
                            ?>
                    </div>
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
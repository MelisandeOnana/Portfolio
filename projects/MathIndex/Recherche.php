<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='assets/styles/Recherche.css' rel='stylesheet'/>
    <script src="requetes/menu_tel.js"></script>
    <title>Recherche</title>

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
                <li><a href="Recherche.php" class="recherche-liens"><img src="assets/images/icone_search.svg"><strong>Recherche</strong></a></li>
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
                <li><a href="Recherche.php" class="recherche-liens"><img src="assets/images/icone_search.svg"></a></li>
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
                }else {
                    echo "<a href='Connexion.php' class='connexion'><img src='assets/images/icone_login.svg' alt='login'>Connexion</a>";
                }
            ?>
        </div>
        
    </header>
    <div class='grand_conteneur'>
        <div class='menu_arriere'></div>
            <div class="contenu">
                <h1>Rechercher un exercice</h1>
                <div class="carre-blanc">
                    <form method="GET">
                        <?php
                            if (isset($_GET['difficulte']))
                            {
                                $selecteur = $_GET['thematique'];
                            }else{
                                $selecteur = "";
                            }
                        ?>
                        <div class="form-group">
                            <label for="thematique">Thématique :</label>
                            <select id="thematique" name="thematique">
                                <option value="">Toutes</option>
                                <option value="Algèbre linéaire" <?php if($selecteur == "Algèbre linéaire") echo"selected" ?>>Algèbre linéaire</option>
                                <option value="Géométrie des fractales" <?php if($selecteur == "Géométrie des fractales") echo"selected" ?>>Géométrie des fractales</option>
                                <option value="Théorie des nombres" <?php if($selecteur == "Théorie des nombres") echo"selected" ?>>Théorie des nombres</option>
                                <option value="Calcul différentiel sur les variétés" <?php if($selecteur == "Calcul différentiel sur les variétés") echo"selected" ?>>Calcul différentiel sur les variétés</option>
                                <option value="Algèbre linéaire avancée" <?php if($selecteur == "Algèbre linéaire avancée") echo"selected" ?>>Algèbre linéaire avancée</option>
                                <option value="Géométrie non euclidienne" <?php if($selecteur == "Géométrie non euclidienne") echo"selected" ?>>Géométrie non euclidienne</option>
                                <option value="Analyse fonctionnelle" <?php if($selecteur == "Analyse fonctionnelle") echo"selected" ?>>Analyse fonctionnelle</option>
                                <option value="Théorie des graphes" <?php if($selecteur == "Théorie des graphes") echo"selected" ?>>Théorie des graphes</option>
                                <option value="Cryptographie" <?php if($selecteur == "Cryptographie") echo"selected" ?>>Cryptographie</option>
                                <option value="Analyse complexe avancée" <?php if($selecteur == "Analyse complexe avancée") echo"selected" ?>>Analyse complexe avancée</option>              
                                <option value="Analyse complexe avancée" <?php if($selecteur == "Analyse complexe avancée") echo"selected" ?>>Calcul stochastique</option>
                                <option value="Théorie des catégories" <?php if($selecteur == "Théorie des catégories") echo"selected" ?>>Théorie des catégories</option>
                                <option value="Logique floue" <?php if($selecteur == "Logique floue") echo"selected" ?>>Logique floue</option>
                                <option value="Probabilités élémentaires" <?php if($selecteur == "Probabilités élémentaires") echo"selected" ?>>Probabilités élémentaires</option>
                            </select>
                        </div>
                        <?php
                            if (isset($_GET['difficulte']))
                            {
                                $selecteur = $_GET['difficulte'];
                            }else{
                                $selecteur = "";
                            }
                        ?>
                        <div class="form-group">
                            <label for="difficulte">Difficulté :</label>
                            <select id="difficulte" name="difficulte">
                                <option value="">Toutes</option>
                                <option value="1" <?php if($selecteur == "1") echo"selected" ?>>Niveau 1</option>
                                <option value="2" <?php if($selecteur == "2") echo"selected" ?>>Niveau 2</option>
                                <option value="3" <?php if($selecteur == "3") echo"selected" ?>>Niveau 3</option>
                                <option value="4" <?php if($selecteur == "4") echo"selected" ?>>Niveau 4</option>
                                <option value="5" <?php if($selecteur == "5") echo"selected" ?>>Niveau 5</option>
                                <option value="6" <?php if($selecteur == "6") echo"selected" ?>>Niveau 6</option>
                                <option value="7" <?php if($selecteur == "7") echo"selected" ?>>Niveau 7</option>
                                <option value="8" <?php if($selecteur == "8") echo"selected" ?>>Niveau 9</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mot_cle">Mot clé :</label>
                            <input type="text" id="mot_cle" name="mot_cle" value="">
                        </div>

                        <input type="submit" value="Rechercher">

                    
                    </form>
                
                    <table>
                        <tr>
                            <th>Nom</th>
                            <th>Difficulté</th>
                            <th>Mots clés</th>
                            <th>Durée</th>
                            <th>Fichiers</th>
                        </tr>
                        <?php
                        
                            include_once 'requetes/configdb.php';

                            // Construction de la requête SQL de base
                            $sql_all_exercices = "SELECT 
                                exercise.name AS exercise_name, 
                                thematic.name AS thematic_name, 
                                exercise.difficulty, 
                                exercise.duration, 
                                exercise.keywords, 
                                file_exercice.name AS exercice_name, 
                                file_exercice.original_name AS exercice_original_name, 
                                file_exercice.extension,
                                file_correction.name AS correction_name, 
                                file_correction.original_name AS correction_original_name, 
                                file_correction.extension AS correction_extension
                            FROM exercise
                            LEFT JOIN thematic ON exercise.thematic_id = thematic.id
                            LEFT JOIN file AS file_exercice ON exercise.exercice_file_id = file_exercice.id
                            LEFT JOIN file AS file_correction ON exercise.correction_file_id = file_correction.id";
                        

                            if (!empty($where_conditions)) {
                                $sql_all_exercices .= " WHERE " . implode(" AND ", $where_conditions);
                            }

                            $result = null;
                            if (empty($_GET['thematique']) && empty($_GET['difficulte']) && empty($_GET['mot_cle'])) {
                                echo "<tr><td colspan='6'>Effectuez une recherche pour afficher les résultats</td></tr>";
                            }else{
                                // Récupére les valeurs des champs de recherche
                                $thematique = isset($_GET['thematique']) ? $_GET['thematique'] : '';
                                $difficulte = isset($_GET['difficulte']) ? $_GET['difficulte'] : '';
                                $mot_cle = isset($_GET['mot_cle']) ? $_GET['mot_cle'] : '';

                                // Construie la condition WHERE en fonction des champs remplis dans le formulaire
                                $where_conditions = [];
                                if (!empty($thematique)) {
                                    $where_conditions[] = "thematic.name = '$thematique'";
                                }
                                if (!empty($difficulte)) {
                                    $where_conditions[] = "exercise.difficulty = '$difficulte'";
                                }
                                if (!empty($mot_cle)) {
                                    $where_conditions[] = "exercise.keywords LIKE '%$mot_cle%'";
                                }

                                if (!empty($where_conditions)) {
                                    $sql_all_exercices .= " WHERE " . implode(" AND ", $where_conditions);
                                }
                                // Exécute la requête SQL
                                $result = $mysqlClient->query($sql_all_exercices);

                                // Affiche le nombre d'exercices trouvés
                                $num_exercices = $result->rowCount();
                                if ($num_exercices > 1) {
                                    echo "<p><strong>$num_exercices exercices trouvés</strong></p>";
                                } elseif ($num_exercices == 1) {
                                    echo "<p><strong>$num_exercices exercice trouvé</strong></p>";
                                } else {
                                    echo "<p><strong>Aucun exercice trouvé</strong></p>";
                                }    

                                if ($result !== null && $result->rowCount() > 0) {

                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                                        echo "<tr>";
                                            echo "<td>" . $row["exercise_name"] . "</td>";
                                            echo "<td>" . 'Niveau ' . $row["difficulty"] . "</td>";
                                            echo '<td>';
                                                $keywords = explode(',', $row['keywords']);
                                                foreach ($keywords as $keyword) {
                                                    echo '<span class="mot_cle">' . $keyword . '</span>';
                                                }
                                            echo '</td>';
                                            echo "<td>" . $row["duration"] . 'h00' . "</td>";
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
                                }else {
                                    echo "<tr><td colspan='5'>Aucun exercice trouvé</td></tr>";
                                }
                            }
                        ?>
                    </table>
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

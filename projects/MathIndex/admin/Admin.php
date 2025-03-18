<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https : //fonts.googleapis.com/css2?family=Epilogue:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Administration</title>
    <script src="requetes/menu_tel.js"></script>
    <link href="../assets/styles/Administration.css" rel="stylesheet">
</head>
<?php 
    session_start();
    include_once '../requetes/configdb.php';
    if(!isset($_GET['onglet'])){
        $_GET['onglet'] = 'contributeurs';
    }
?>
<body>
  <nav class="barre-navigation hidden">
    <div class="ensembles-logo">
        <img alt="logo" src="../assets/images/Logo.svg">
        <div class="ensembles-logo-titre ">
          <span class="titre">Math Index</span>
          <span class="sous-titre">Lycée Saint-Vincent -Senlis</span>
        </div>
    </div>
    <div class="ensembles-logo-ipad">
        <img alt="logo" src="../assets/images/Logo.svg">
    </div>
    <button onclick='CachecheMenu()' class='btnFerme'>fermer le menu</button>
    <div class="navigation">
      <ul>
        <li><a href="../Accueil.php" class="accueil-liens"><img src="../assets/images/icone_home_gris.svg">Accueil</a></li>
        <li><a href="../Recherche.php" class="recherche-liens"><img src="../assets/images/icone_search_gris.svg">Recherche</a></li>
        <li><a href="../Exercices.php" class="exercices-liens"><img src="../assets/images/icone_fonctions_gris.svg">Exercices</a></li>
      </ul>
      <?php if(isset($_SESSION["account"])): ?>
        <?php if($_SESSION["account"]["role"] == "Administrateur" || $_SESSION["account"]["role"] == "Contributeur"): ?>
          <ul>
            <li><a href="../MesExercices.php" class="mesexercices-liens"><img src="../assets/images/icone_liste_gris.svg">Mes exercices</a></li>
            <li><a href="../Soumettre.php" class="soumettre-liens"><img src="../assets/images/icone_soumettre_gris.svg">Soumettre</a></li>
            <div class="deconnexion">
              <li><a href="admin/authentification/logout.php" class="deconnexion-liens"><img src="../assets/images/icone_logout.svg">Déconnexion</a></li>
            </div>
          </ul>
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <div class="nav_ipad">
      <ul>
        <li><a href="../Accueil.php" class="accueil-liens"><img src="../assets/images/icone_home_gris.svg"></a></li>
        <li><a href="../Recherche.php" class="recherche-liens"><img src="../assets/images/icone_search_gris.svg"></a></li>
        <li><a href="../Exercices.php" class="exercices-liens"><img src="../assets/images/icone_fonctions_gris.svg"></a></li>
      </ul>
      <?php if(isset($_SESSION["account"])): ?>
        <?php if($_SESSION["account"]["role"] == "Administrateur" || $_SESSION["account"]["role"] == "Contributeur"): ?>
            <ul>
                <li><a href="../MesExercices.php" class="mesexercices-liens"><img src="../assets/images/icone_liste_gris.svg"></a></li>
                <li><a href="../Soumettre.php" class="soumettre-liens"><img src="../assets/images/icone_soumettre_gris.svg"></a></li>
                <div class="deconnexion">
                    <li><a href="../admin/authentification/logout.php" class="deconnexion-liens"><img src="../assets/images/icone_logout.svg"></a></li>
                </div>
            </ul>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </nav>
  <header>
  <button onclick="AfficheMenu()" class='btn_menu_tel'><img src="../assets/images/Hamburger_icon.png"></button>
    <div class="header-droite">
      <?php
          if (session_status() == PHP_SESSION_NONE) {
          }

          if(isset($_SESSION["account"])){
            $lastname=$_SESSION['account']['last_name'];
            $firstname=$_SESSION['account']['first_name'];
            $role=$_SESSION['account']['role'];
            $iduser=$_SESSION['account']['id'];
            $profile_picture = isset($_SESSION['account']['profile_photo_file']) ? $_SESSION['account']['profile_photo_file'] : 'chemin/vers/image_par_defaut.jpg';
            echo "<div class='compte'>$firstname $lastname <img src='../assets/photos_de_profil/$profile_picture' alt='photo de profil' class='profil-image'></div>";
        } else {
            echo "<a href='../Connexion.php' class='connexion'><img src='../assets/images/icone_login.svg' alt='login'>Connexion</a>";
        }

        //script d'insertion contributeurs
        if(isset($_POST['nom_contributeur']) && !empty($_POST['nom_contributeur']) && isset($_POST['prenom_contributeur']) && !empty($_POST['prenom_contributeur']) && isset($_POST['email_contributeur']) && !empty($_POST['email_contributeur']) && isset($_POST['mdp_contributeur']) && !empty($_POST['mdp_contributeur']) && isset($_POST['role_contributeur']) && !empty($_POST['role_contributeur']) &&  $_GET['add_contributeurs'] === 'true'){
            if(!empty($_FILES['photo_profil'])){
                $uploads_dir = '../assets/photos_de_profil';
                $tmp_name = $_FILES["photo_profil"]["tmp_name"];
                $name = basename($_FILES["photo_profil"]["name"]);
                move_uploaded_file($tmp_name, "$uploads_dir/$name");
                $stmt = $mysqlClient->prepare("INSERT INTO user_mathindex(last_name,first_name,email,role,password,profile_photo_file) VALUES(:last_name,:first_name,:email,:role,:password,:profile_photo_file);");
                $stmt->bindParam(":last_name", $_POST['nom_contributeur']);
                $stmt->bindParam(":first_name", $_POST['prenom_contributeur']);
                $stmt->bindParam(":email", $_POST['email_contributeur']);
                $stmt->bindParam(":role", $_POST['role_contributeur']);
                $stmt->bindParam(":password", $_POST['mdp_contributeur']);
                $stmt->bindParam(":profile_photo_file", $_FILES['photo_profil']['name']);
                $stmt->execute();
            }else{
                $stmt = $mysqlClient->prepare("INSERT INTO user_mathindex(last_name,first_name,email,role,password) VALUES(:last_name,:first_name,:email,:role,:password);");
                $stmt->bindParam(":last_name", $_POST['nom_contributeur']);
                $stmt->bindParam(":first_name", $_POST['prenom_contributeur']);
                $stmt->bindParam(":email", $_POST['email_contributeur']);
                $stmt->bindParam(":role", $_POST['role_contributeur']);
                $stmt->bindParam(":password", $_POST['mdp_contributeur']);
                $stmt->execute();
            }
            header("location: admin.php?onglet=contributeurs");
        }
        //script de modification contributeur
        if(isset($_POST['nom_contributeur']) && !empty($_POST['nom_contributeur']) && $_GET['add_contributeur'] === 'modify'){
            $stmt = $mysqlClient->prepare("UPDATE user_mathindex SET name=:name WHERE id=:id");
            $stmt->bindParam(":name", $_POST['nom_contributeur']);
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();

            header("location: admin.php?onglet=contributeurs");
        }
        //script de recuperation du nom contributeur a modifier
        if(isset($_GET['id']) && isset($_GET['add_contributeur']) && $_GET['add_contributeur'] === 'modify'){
            $stmt = $mysqlClient->prepare("SELECT * FROM user_mathindex WHERE id=:id");
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();
            $contributeur = $stmt -> fetchAll();
        }

        //script d'insertion classe
        if(isset($_POST['nom_classe']) && !empty($_POST['nom_classe']) && $_GET['add_classe'] === 'true'){
            $stmt = $mysqlClient->prepare("INSERT INTO classroom(name) VALUES(:name);");
            $stmt->bindParam(":name", $_POST['nom_classe']);
            $stmt->execute();

            header("location: admin.php?onglet=classes");
        }
        //script de modification classe
        if(isset($_POST['nom_classe']) && !empty($_POST['nom_classe']) && $_GET['add_classe'] === 'modify'){
            $stmt = $mysqlClient->prepare("UPDATE classroom SET name=:name WHERE id=:id");
            $stmt->bindParam(":name", $_POST['nom_classe']);
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();

            header("location: admin.php?onglet=classes");
        }
        //script de recuperation du nom classe a modifier
        if(isset($_GET['id']) && isset($_GET['add_classe']) && $_GET['add_classe'] === 'modify'){
            $stmt = $mysqlClient->prepare("SELECT name FROM classroom WHERE id=:id");
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();
            $classe = $stmt -> fetchAll();
            $classe = $classe[0][0];
        }

        //script d'insertion thematique
        if(isset($_POST['nom_thematique']) && !empty($_POST['nom_thematique']) && $_GET['add_thematique'] === 'true'){
            $stmt = $mysqlClient->prepare("INSERT INTO thematic(name) VALUES(:name);");
            $stmt->bindParam(":name", $_POST['nom_thematique']);
            $stmt->execute();

            header("location: admin.php?onglet=thematiques");
        }
        //script de modification thematique
        if(isset($_POST['nom_thematique']) && !empty($_POST['nom_thematique']) && $_GET['add_thematique'] === 'modify'){
            $stmt = $mysqlClient->prepare("UPDATE thematic SET name=:name WHERE id=:id");
            $stmt->bindParam(":name", $_POST['nom_thematique']);
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();

            header("location: admin.php?onglet=thematiques");
        }
        //script de recuperation du nom thematique a modifier
        if(isset($_GET['id']) && isset($_GET['add_thematique']) && $_GET['add_thematique'] === 'modify'){
            $stmt = $mysqlClient->prepare("SELECT name FROM thematic WHERE id=:id");
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();
            $thematique = $stmt -> fetchAll();
            $thematique = $thematique[0][0];
        }

        //script d'insertion origine
        if(isset($_POST['nom_origine']) && !empty($_POST['nom_origine']) && $_GET['add_origine'] === 'true'){
            $stmt = $mysqlClient->prepare("INSERT INTO origin(name) VALUES(:name);");
            $stmt->bindParam(":name", $_POST['nom_origine']);
            $stmt->execute();

            header("location: admin.php?onglet=origines");
        }
        //script de modification origine
        if(isset($_POST['nom_origine']) && !empty($_POST['nom_origine']) && $_GET['add_origine'] === 'modify'){
            $stmt = $mysqlClient->prepare("UPDATE origin SET name=:name WHERE id=:id");
            $stmt->bindParam(":name", $_POST['nom_origine']);
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();

            header("location: admin.php?onglet=origines");
        }
        //script de recuperation du nom origine a modifier
        if(isset($_GET['id']) && isset($_GET['add_origine']) && $_GET['add_origine'] === 'modify'){
            $stmt = $mysqlClient->prepare("SELECT name FROM origin WHERE id=:id");
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();
            $origine = $stmt -> fetchAll();
            $origine = $origine[0][0];
        }

         //script d'insertion classe
         if(isset($_POST['nom_classe']) && !empty($_POST['nom_classe']) && $_GET['add_classes'] === 'true'){
            $stmt = $mysqlClient->prepare("INSERT INTO classroom(name) VALUES(:name);");
            $stmt->bindParam(":name", $_POST['nom_classe']);
            $stmt->execute();

            header("location: admin.php?onglet=classes");
        }
        //script de modification classe
        if(isset($_POST['nom_classe']) && !empty($_POST['nom_classe']) && $_GET['add_classes'] === 'modify'){
            $stmt = $mysqlClient->prepare("UPDATE classroom SET name=:name WHERE id=:id");
            $stmt->bindParam(":name", $_POST['nom_classe']);
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();

            header("location: admin.php?onglet=classes");
        }
        //script de recuperation du nom classe a modifier
        if(isset($_GET['id']) && isset($_GET['add_classes']) && $_GET['add_classes'] === 'modify'){
            $stmt = $mysqlClient->prepare("SELECT name FROM classroom WHERE id=:id");
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();
            $classe = $stmt -> fetchAll();
            $classe = $classe[0][0];
        }

      ?>
    </div>
    
  </header>
<script>
    function getfile(){
        document.getElementById('hiddenfile').click();
    }
    function getvalue(){
        document.getElementById('selectedfile').value=document.getElementById('hiddenfile').value;
    }
</script>

  <div class='grand_conteneur'>
        <div class='menu_arriere'></div>
        <div class="contenu">
            <h1>Administration</h1>  
            <div class="menu-onglets">
                
                <div id="menu-tab"><!----------------tableau-01---------------------------------->
                <div id="page-wrap">
                <div class="tabs"><!----------------onglet-01-contributeurs-------------------------->
                <div class="tab" id="tab-contri"><input id="tab-1" checked="checked" name="tab-group-1" type="radio" <?php if( $_GET['onglet'] === 'contributeurs'){ echo 'checked';} ?>/> <label class='label_onglet' for="tab-1">Contributeurs</label>
                    <?php
                        $contributeurs_par_page = 4;
                        $page_contributeurs = isset($_GET['page_contributeurs']) ? $_GET['page_contributeurs'] : 1;
                        $offset = ($page_contributeurs - 1) * $contributeurs_par_page;

                        // Requête pour obtenir le nombre total d'exercices
                        $sql_total_contributeurs = "SELECT COUNT(*) AS total FROM user_mathindex";
                        $result_total_contributeurs = $conn->query($sql_total_contributeurs);
                        $row_total_contributeurs = $result_total_contributeurs->fetch_assoc();
                        $total_contributeurs = $row_total_contributeurs['total'];

                        // Calculer le nombre total de pages
                        $total_pages_contributeurs = ceil($total_contributeurs / $contributeurs_par_page);
                    ?>
                    <?php if(isset($_GET['add_contributeurs'])){ ?>
                    <div class="content">
                                <h1> Ajouter un contributeurs </h1>
                                
                                <form enctype="multipart/form-data" method='post' name='Fichiers'>
                                    <div class='ligne'>
                                        <label class='label_ajout' for='nom_contributeur'>Nom du contributeurs :
                                        <input type='text' name='nom_contributeur' id='nom_contributeur' value="<?php if(isset($contributeurs)){echo $contributeurs['last_name'];}?>" /></label>
                                        <label class='label_ajout' for='role_contributeur'>Role du contributeurs :
                                        <input type='text' name='role_contributeur' id='role_contributeur' value="<?php if(isset($contributeurs)){echo $contributeurs['role'];}?>" /></label>
                                    </div>
                                    <div class='ligne'>
                                        <label class='label_ajout' for='prenom_contributeur'>Prénom du contributeurs :
                                        <input type='text' name='prenom_contributeur' id='prenom_contributeur' value="<?php if(isset($contributeurs)){echo $contributeurs['first_name'];}?>" /></label>
                                        <label name='photo_profil' class='label_ajout'> Photo de profil (png ou jpeg) :
                                            <div class='file'>
                                                <input type="file" id="hiddenfile" class="label-upload" style="display:none" name="photo_profil" onChange="getvalue();"/>
                                                <input class="leFichier" type="text" id="selectedfile" name='NewNamePhoto' value='Sélectionner un fichier à télécharger'/>
                                                <input class='bouton-upload' type="button" onclick="getfile();"/>
                                            </div>
                                        </label>
                                    </div>
                                    <div class='ligne'>
                                        <label class='label_ajout' for='email_contributeur'>Email du contributeurs :
                                        <input type='text' name='email_contributeur' id='email_contributeur' value="<?php if(isset($contributeurs)){echo $contributeurs['email'];}?>" /></label>
                                    </div>
                                    <div class='ligne'>
                                        <label class='label_ajout' for='mdp_contributeur'>Mot de passe du contributeurs :
                                        <input type='text' name='mdp_contributeur' id='mdp_contributeur' value="<?php if(isset($contributeurs)){echo $contributeurs['password'];}?>" /></label>
                                    </div>
                                    <a href="Admin.php"><input class='btn_retour' type='button' value='◄ Retour à la liste' class="bouton_retour"></input></a>
                                    <input type='submit' class="bouton_envoyer" value='Enregistrer'></input>
                                </form>
                    </div>


                    <?php }else{?>
                        <div class="content">
                            <h2>Gestion des contributeurs</h2>
                            <p>Rechercher un contributeurs par son nom, prénom ou email :</p>
                            <div class="recherche_origines">
                            <form action="Admin.php"  method="get">
                                <input type='hidden' name='onglet' value='contributeurs'>
                                <input type="text" id="recherche_contrib" name="recherche_contrib"
                                <?php
                                if (isset($_GET["recherche_contrib"])) {
                                    echo 'value="'.$_GET["recherche_contrib"].'"';
                                }
                                ?>
                                >
                                <button type="submit">Rechercher</button>
                            </form>
                            <?php 
                                if (isset($_GET["recherche_contrib"])) {
                                    echo '<a class="annuler_recherche" href="Admin.php?onglet=contributeurs"><p>X</p></a>';
                                }
                            ?>
                                <div class="bouton_ajout">
                                    <a href="Admin.php?onglet=contributeurs&add_contributeurs=true"><p style="color: white;">Ajouter +</p></a>
                                </div> 
                            </div>
                            <table class="tab_exercice">
                                <thead>
                                    <td><p>Nom</p></td>
                                    <td><p>Prénom</p></td>
                                    <td><p>Rôle</p></td>
                                    <td><p>Email</p></td>
                                    <td><p>Actions</p></td>
                                </thead>
                                <?php

                                if (isset($_GET["recherche_contrib"])) {
                                    $sql_search_contributeurs = "SELECT * FROM user_mathindex
                                                        WHERE last_name LIKE '%" . $_GET["recherche_contrib"] . "%'
                                                        OR  first_name LIKE '%" . $_GET["recherche_contrib"] . "%'
                                                        OR email LIKE '%" . $_GET["recherche_contrib"] . "%'
                                                        LIMIT $contributeurs_par_page OFFSET $offset";
                                    $result_all_contributeurs = $conn->query($sql_search_contributeurs);
                
                                }
                                else {

                                    $sql_all_contributeurs = "SELECT * FROM user_mathindex LIMIT $contributeurs_par_page OFFSET $offset";

                                    $result_all_contributeurs = $conn->query($sql_all_contributeurs);
                                }
                    
                                while ($row_contributeurs = $result_all_contributeurs->fetch_assoc()) {
                                    $stmt = $mysqlClient->prepare("SELECT count(*) FROM exercise WHERE thematic_id=:id;");
                                    $stmt->bindParam(":id", $row_contributeurs["id"]);
                                    $stmt->execute();

                                    $nb_exercices = $stmt->fetchAll();
                                    echo "<tr>";
                                    echo "<td class='nom'><p>" . $row_contributeurs["last_name"] . "</p></td>";
                                    echo "<td class='nom'><p>" . $row_contributeurs["first_name"] . "</p></td>";
                                    echo "<td class='nom'><p>" . $nb_exercices[0][0]. "</p></td>";
                                    echo "<td class='nom'><p>" . $row_contributeurs["email"] . "</p></td>";
                                    echo "<td class='actions'>";
                                    echo "<div class='uneAction'><img src='../assets/images/icone_modifier_gris.svg'>
                                            <p><a href='Admin.php?onglet=contributeurs&add_contributeurs=modify&id=".$row_contributeurs["id"]."'>Modifier</a></p></div>";
                                    echo "<div class='uneAction'><img src='../assets/images/icone_poubelle_gris.svg'>";

                                    if (isset($_GET['page_contributeurs'])) {
                                        echo "<p><a href='?onglet=contributeurs&page_contributeurs=".$_GET['page_contributeurs']."&action_contributeurs=delete&id=".$row_contributeurs["id"]."'>Supprimer</a></p></div>";
                                    }
                                    else {
                                        echo "<p><a href='?onglet=contributeurs&action_contributeurs=delete&id=".$row_contributeurs["id"]."'>Supprimer</a></p></div>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            echo "</table>";
                            ?>
                            <div class="pagination">
                                <?php
                                    if ($page_contributeurs > 1) {
                                        echo "<a href='Admin.php?onglet=contributeurs&page_contributeurs=".($page_contributeurs - 1)."' class='pagination-bouton-gauche'>&lt;</a>";
                                    } else {
                                        echo "<span class='pagination-bouton-gauche'>&lt;</span>";
                                    }

                                    for ($i=1; $i<=$total_pages_contributeurs; $i++) {
                                        if ($i == $page_contributeurs) {
                                        echo "<span class='page-actuel'>$i</span>";
                                        } else {
                                            echo "<a href='Admin.php?onglet=contributeurs&page_contributeurs=".$i."' class='pagination-lien'>$i</a>";
                                        }
                                    }

                                    if ($page_contributeurs < $total_pages_contributeurs) {
                                        echo "<a href='Admin.php?onglet=contributeurs&page_contributeurs=".($page_contributeurs + 1)."' class='pagination-bouton-droite'>&gt;</a>";
                                    } else {
                                        echo "<span class='pagination-bouton-droite'>&gt;</span>";
                                    }
                                ?>
                            </div>
                        </div>

                    <?php } ?>
                    <?php
                if (isset($_GET['action_contributeurs']) && $_GET['action_contributeurs'] === 'delete') {
                    // verifiaction si la thematique est utilisée sur des exercice
                    $stmt = $mysqlClient->prepare("SELECT count(*) FROM exercise WHERE created_by_id=:id;");
                    $stmt->bindParam(":id", $_GET['id']);
                    $stmt->execute();
                    $nb_exercices = $stmt->fetchAll();
                    $nb_exercices = $nb_exercices[0][0];

                    if($nb_exercices === "0" && $_GET['id'] != $iduser){
                    ?>
                    
                    <div class="confirmation">
                    <div class="contenu_confirmation">
                        <div class="info_confirmation">
                        <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                        <div>
                            <h2>Confirmez la suppression</h2>
                            <p>Êtes-vous certain de vouloir supprimer ce contributeur ?</p>
                        </div>
                        </div>
                        <?php
                        if (isset($_GET['page_contributeurs'])) {
                        echo '<a href="?onglet=contributeurs&page_contributeurs='.$_GET['page_contributeurs'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                        echo '<a href="?onglet=contributeurs&page_contributeurs='.$_GET['page_contributeurs'].'&confirmed_contributeurs=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                        }
                        else {
                        echo '<a href="./Admin.php?onglet=contributeurs" class="annuler_btn" style="color: black;">Annuler</a>';
                        echo '<a href="?onglet=contributeurs&confirmed_contributeurs=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                        } 
                        ?>
                    </div>
                    </div>
                    <?php
                        }
                        else{
                    ?>
                    <div class="confirmation">
                    <div class="contenu_confirmation">
                        <div class="info_confirmation">
                        <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                        <div>
                            <h2>Suppression impossible</h2>
                            <p>Le contributeurs est utilisée.</p>
                        </div>
                        </div>
                        <?php
                        if (isset($_GET['page_contributeurs'])) {
                        echo '<a href="?onglet=contributeurs&page_contributeurs='.$_GET['page_contributeurs'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                        }
                        else {
                        echo '<a href="./Admin.php?onglet=contributeurs" class="annuler_btn" style="color: black;">Annuler</a>';
                        } 
                        ?>
                    </div>
                    </div>
                    <?php
                        }
                    }
                    if (isset($_GET['confirmed_contributeurs']) && $_GET['confirmed_contributeurs'] == 'true') {
                        $id_contributeurs = $_GET['id'];
                        $sql_supp = "DELETE FROM user_mathindex WHERE id = $id_contributeurs";
                        $stmt_supp = $conn->prepare($sql_supp);
                        $stmt_supp->execute();
                        
                    
                        header("location: Admin.php?onglet=contributeurs");
                        
                    }
                    ?> 
                </div>
                    
                <!----------------onglet-02-exercices-------------------------->
                <div class="tab" id="tab-exo"><input id="tab-2" name="tab-group-1" type="radio" <?php if( $_GET['onglet'] === 'exercices'){ echo 'checked';} ?>/> <label class='label_onglet' for="tab-2">Exercices</label>
                <?php
                    $exercices_par_page = 4;
                    $page_exercices = isset($_GET['page_exercice']) ? $_GET['page_exercice'] : 1;
                    $offset = ($page_exercices - 1) * $exercices_par_page;

                    // Requête pour obtenir le nombre total d'exercices
                    $sql_total_exercices = "SELECT COUNT(*) AS total FROM exercise";
                    $result_total_exercices = $conn->query($sql_total_exercices);
                    $row_total_exercices = $result_total_exercices->fetch_assoc();
                    $total_exercices = $row_total_exercices['total'];

                    // Calculer le nombre total de pages
                    $total_pages_exercices = ceil($total_exercices / $exercices_par_page);
                ?>
                    <div class="content">
                        <h2>Gestion des exercices</h2>
                        <p>Rechercher un exercices par son nom ou sa thématique :</p>
                        <div class="recherche_exo">
                            <form action="Admin.php"  method="get">
                                <input type='hidden' name='onglet' value='exercices'>
                                <input type="text" id="recherche_exo" name="recherche_exo"
                                <?php
                                if (isset($_GET["recherche_exo"])) {
                                    echo 'value="'.$_GET["recherche_exo"].'"';
                                }
                                ?>
                                >
                                <button type="submit">Rechercher</button>
                            </form>
                            <?php 
                                if (isset($_GET["recherche_exo"])) {
                                    echo '<a class="annuler_recherche" href="Admin.php?onglet=exercices"><p>X</p></a>';
                                }
                            ?>
                            <div class="bouton_ajout">
                                <a href="../Soumettre.php"><p style="color: white;">Ajouter +</p></a>
                            </div> 
                        </div>
                        <table class="tab_exercice">
                      <thead>
                          <td><p>Nom</p></td>
                          <td><p>Thématiques</p></td>
                          <td><p>Fichiers</p></td>
                          <td><p>Actions</p></td>
                      </thead>
                    <?php
                        

                if (isset($_GET["recherche_exo"])) {
                    $sql_search_exercices = "SELECT exercise.name AS exercise_name, thematic.name AS thematic_name, file_exercice.original_name AS exercice_original_name, file_exercice.extension, file_correction.original_name AS correction_original_name, file_correction.extension AS correction_extension, exercise.id AS exercise_id
                                        FROM exercise
                                        LEFT JOIN thematic ON exercise.thematic_id = thematic.id
                                        LEFT JOIN file AS file_exercice ON exercise.exercice_file_id = file_exercice.id
                                        LEFT JOIN file AS file_correction ON exercise.correction_file_id = file_correction.id
                                        WHERE exercise.name LIKE '%" . $_GET["recherche_exo"] . "%'
                                        OR thematic.name LIKE '%" . $_GET["recherche_exo"] . "%'
                                        LIMIT " . $exercices_par_page . " OFFSET " . $offset;
                    $result_all_exercices = $conn->query($sql_search_exercices);

                }
                else {
                    $sql_all_exercices = "SELECT exercise.name AS exercise_name, thematic.name AS thematic_name, file_exercice.original_name AS exercice_original_name, file_exercice.extension, file_correction.original_name AS correction_original_name, file_correction.extension AS correction_extension, exercise.id AS exercise_id
                                        FROM exercise
                                        LEFT JOIN thematic ON exercise.thematic_id = thematic.id
                                        LEFT JOIN file AS file_exercice ON exercise.exercice_file_id = file_exercice.id
                                        LEFT JOIN file AS file_correction ON exercise.correction_file_id = file_correction.id
                                        LIMIT $exercices_par_page OFFSET $offset";
                    $result_all_exercices = $conn->query($sql_all_exercices);
                }
                    while ($row = $result_all_exercices->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td class='nom'><p>" . $row["exercise_name"] . "</p></td>";
                      echo "<td class='thematiques'><p>" . $row["thematic_name"] . "</p></td>";
                      echo "<td class='fichiers_exercices'>";
                      echo "<img src='../assets/images/icone_download.svg'>
                            <a href='../assets/Exercices/" . $row["exercice_original_name"] . "' download>Exercice</a>";
                      if ($row["correction_original_name"] && $row["correction_extension"]) {
                        echo "<img src='../assets/images/icone_download.svg'>
                        <a href='../assets/Corriges/" . $row["correction_original_name"]."' download>Corrigé</a>";
                      }
                      echo "</td>";
                      echo "<td class='actions'>";
                      echo "<div class='uneAction'><img src='../assets/images/icone_modifier_gris.svg'>
                            <p><a href='../Soumettre.php?info=".$row["exercise_id"]."'>Modifier</a></p></div>";
                      echo "<div class='uneAction'><img src='../assets/images/icone_poubelle_gris.svg'>";
                      if (isset($_GET['page_exercice'])) {
                        echo "<p><a href='?onglet=exercices&page_exercice=".$_GET['page_exercice']."&action_exercice=delete&id=".$row["exercise_id"]."'>Supprimer</a></p></div>";
                      }
                      else {
                        echo "<p><a href='?onglet=exercices&action_exercice=delete&id=".$row["exercise_id"]."'>Supprimer</a></p></div>";
                      }
                      echo "</td>";
                     echo "</tr>";
                    }
                
                  echo "</table>";
                    ?>  
                    <div class="pagination">
                            <?php
                        if ($page_exercices > 1) {
                            echo "<a href='Admin.php?onglet=exercices&page_exercice=".($page_exercices - 1)."' class='pagination-bouton-gauche'>&lt;</a>";
                        } else {
                            echo "<span class='pagination-bouton-gauche'>&lt;</span>";
                        }

                        for ($i=1; $i<=$total_pages_exercices; $i++) {
                            if ($i == $page_exercices) {
                            echo "<span class='page-actuel'>$i</span>";
                            } else {
                                echo "<a href='Admin.php?onglet=exercices&page_exercice=".$i."' class='pagination-lien'>$i</a>";
                            }
                        }

                        if ($page_exercices < $total_pages_exercices) {
                            echo "<a href='Admin.php?onglet=exercices&page_exercice=".($page_exercices + 1)."' class='pagination-bouton-droite'>&gt;</a>";
                        } else {
                            echo "<span class='pagination-bouton-droite'>&gt;</span>";
                        }
                      
                        ?>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($_GET['action_exercice']) && $_GET['action_exercice'] === 'delete') {
                        ?>
                            <div class="confirmation">
                            <div class="contenu_confirmation">
                                <div class="info_confirmation">
                                <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                                <div>
                                    <h2>Confirmez la suppression</h2>
                                    <p>Êtes-vous certain de vouloir supprimer cet exercice ?</p>
                                </div>
                                </div>
                                <?php
                                if (isset($_GET['page_exercice'])) {
                                echo '<a href="?onglet='.$_GET['onglet'].'page_exercice='.$_GET['page_exercice'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                                echo '<a href="?onglet='.$_GET['onglet'].'?page_exercice='.$_GET['page_exercice'].'&confirmed_exercice=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                                }
                                else {
                                echo '<a href="./admin.php?onglet=exercices" class="annuler_btn" style="color: black;">Annuler</a>';
                                echo '<a href="?onglet=exercices&confirmed_exercice=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                                } 
                                ?>
                            </div>
                            </div>
                        <?php
                        }
                        if (isset($_GET['confirmed_exercice']) && $_GET['confirmed_exercice'] == 'true') {
                            $id_exercise = $_GET['id'];
                          
                            $sql_recup_info = "SELECT exercice_file_id, correction_file_id
                                                FROM exercise
                                                WHERE id = $id_exercise";
                            $stmt = $conn->prepare($sql_recup_info);
                            $stmt->execute();
                            $result_recup_info = $stmt->get_result();
                          
                            
                          
                            if ($result_recup_info->num_rows > 0) {
                                $row2 = $result_recup_info->fetch_assoc();
                                $file_ex = $row2["exercice_file_id"];
                                $file_corr = $row2["correction_file_id"];
                          
                                $sql_recup_info2 = "SELECT name, extension
                                                    FROM file
                                                    WHERE id = $file_ex
                                                    OR id = $file_corr";
                                $stmt2 = $conn->prepare($sql_recup_info2);
                                $stmt2->execute();
                                $result_recup_info2 = $stmt2->get_result();
                          
                                $row3 = $result_recup_info2->fetch_assoc();
                                $name_file = $row3["name"];
                                $ext_file = $row3["extension"];
                                while ($row3 = $result_recup_info2->fetch_assoc()) {
                                  
                                    unlink("../assets/Exercices/".$name_file);
                                    unlink("../assets/Corrige/".$name_file);
                          
                                }
                          
                          
                                $sql_supp = "DELETE FROM exercise WHERE id = $id_exercise";
                                $stmt_supp = $conn->prepare($sql_supp);
                           
                                $stmt_supp->execute();
                                
                                $sql_supp2 = "DELETE FROM file WHERE id=$file_ex OR id=$file_corr ";
                                $stmt_supp2 = $conn->prepare($sql_supp2);
                                $stmt_supp2->execute();
                          
                                if (isset($_GET['page_exercice'])) {
                                  header("location: ./Admin.php?page_exercice=" . $_GET['page_exercice']);
                              } else {
                                  header("location: ./Admin.php");
                              }
                            }
                          }
                        ?>
                <!----------------onglet-03-classes-------------------------->
                <div class="tab" id="tab-classe"><input id="tab-4" name="tab-group-1" type="radio" <?php if( $_GET['onglet'] === 'classes'){ echo 'checked';} ?>/> <label class='label_onglet' for="tab-4">Classes</label>
                    <?php
                        $classes_par_page = 4;
                        $page_classes = isset($_GET['page_classes']) ? $_GET['page_classes'] : 1;
                        $offset = ($page_classes - 1) * $classes_par_page;

                        // Requête pour obtenir le nombre total de classes
                        $sql_total_classes = "SELECT COUNT(*) AS total FROM classroom";
                        $result_total_classes = $conn->query($sql_total_classes);
                        $row_total_classes = $result_total_classes->fetch_assoc();
                        $total_classes = $row_total_classes['total'];

                        // Calculer le nombre total de pages
                        $total_pages_classes = ceil($total_classes / $classes_par_page);
                    ?>
                    <?php if(isset($_GET['add_classes'])){ ?>
                        <div class="content">
                            <h1> Ajouter une Classe </h1>
                            
                            <form action='' method='post'>
                                <div class="ligne">
                                    <label class='label_ajout' for='nom_classe'>Nom de la classe :
                                    <input type='text' name='nom_classe' id='nom_classe' value="<?php if(isset($classe)){echo $classe;}?>" /></label>
                                </div>
                                <a href="Admin.php?onglet=classes"><input class='btn_retour' type='button' value='◄ Retour à la liste' class="bouton_retour"></input></a>
                                <input type='submit' class="bouton_envoyer" value='Enregistrer'></input>
                            </form>
                        </div>


                    <?php }else{?>
                        <div class="content">
                            <h2>Gestion des classes</h2>
                            <p>Rechercher une classe par son nom :</p>
                            <div class="recherche_origines">
                            <form action="Admin.php"  method="get">
                                <input type='hidden' name='onglet' value='classes'>
                                <input type="text" id="recherche_class" name="recherche_class"
                                <?php
                                if (isset($_GET["recherche_class"])) {
                                    echo 'value="'.$_GET["recherche_class"].'"';
                                }
                                ?>
                                >
                                <button type="submit">Rechercher</button>
                            </form>
                            <?php 
                                if (isset($_GET["recherche_class"])) {
                                    echo '<a class="annuler_recherche" href="Admin.php?onglet=classes"><p>X</p></a>';
                                }
                            ?>
                                <div class="bouton_ajout">
                                    <a href="Admin.php?onglet=classes&add_classes=true"><p style="color: white;">Ajouter +</p></a>
                                </div> 
                            </div>
                            <table class="tab_classe">
                                <thead>
                                    <td><p>Nom</p></td>
                                    <td><p>Nombre d'exercices</p></td>
                                    <td><p>Actions</p></td>
                                </thead>
                                <?php

                                if (isset($_GET["recherche_class"])) {
                                    $sql_search_classes = "SELECT  id,name FROM classroom
                                                        WHERE name LIKE '%" . $_GET["recherche_class"] . "%'
                                                        LIMIT $classes_par_page OFFSET $offset";
                                    $result_all_classes = $conn->query($sql_search_classes);
                
                                }
                                else {
                                    $sql_all_classes = "SELECT id, name FROM classroom LIMIT $classes_par_page OFFSET $offset";
                                    $result_all_classes = $conn->query($sql_all_classes);

                                }
                    
                                while ($row_classes = $result_all_classes->fetch_assoc()) {
                                    $stmt = $mysqlClient->prepare("SELECT count(*) FROM exercise WHERE classroom_id=:id;");
                                    $stmt->bindParam(":id", $row_classes["id"]);
                                    $stmt->execute();
                                    $nb_exercices = $stmt->fetchColumn();
                                
                                    echo "<tr>";
                                    echo "<td class='nom'><p>" . $row_classes["name"] . "</p></td>";

                                    echo "<td class='nom'><p>" . $nb_exercices. "</p></td>";
                                    echo "<td class='actions'>";
                                    echo "<div class='uneAction'><img src='../assets/images/icone_modifier_gris.svg'>
                                            <p><a href='Admin.php?onglet=classes&add_classes=modify&id=".$row_classes["id"]."'>Modifier</a></p></div>";
                                    echo "<div class='uneAction'><img src='../assets/images/icone_poubelle_gris.svg'>";

                                    if (isset($_GET['page_classes'])) {
                                        echo "<p><a href='?onglet=classes&page_classes=".$_GET['page_classes']."&action_classes=delete&id=".$row_classes["id"]."'>Supprimer</a></p></div>";
                                    }
                                    else {
                                        echo "<p><a href='?onglet=classes&action_classes=delete&id=".$row_classes["id"]."'>Supprimer</a></p></div>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            echo "</table>";
                            ?>
                            <div class="pagination">
                                <?php
                                    if ($page_classes > 1) {
                                        echo "<a href='Admin.php?onglet=classes&page_classes=".($page_classes - 1)."' class='pagination-bouton-gauche'>&lt;</a>";
                                    } else {
                                        echo "<span class='pagination-bouton-gauche'>&lt;</span>";
                                    }

                                    for ($i=1; $i<=$total_pages_classes; $i++) {
                                        if ($i == $page_classes) {
                                        echo "<span class='page-actuel'>$i</span>";
                                        } else {
                                            echo "<a href='Admin.php?onglet=classes&page_classes=".$i."' class='pagination-lien'>$i</a>";
                                        }
                                    }

                                    if ($page_classes < $total_pages_classes) {
                                        echo "<a href='Admin.php?onglet=classes&page_classes=".($page_classes + 1)."' class='pagination-bouton-droite'>&gt;</a>";
                                    } else {
                                        echo "<span class='pagination-bouton-droite'>&gt;</span>";
                                    }
                                ?>
                            </div>
                        </div>

                    <?php } ?>
                    <?php
                if (isset($_GET['action_classes']) && $_GET['action_classes'] === 'delete') {
                    // verifiaction si la thematique est utilisée sur des exercice
                    $stmt = $mysqlClient->prepare("SELECT count(*) FROM exercise WHERE classroom_id=:id;");
                    $stmt->bindParam(":id", $_GET['id']);
                    $stmt->execute();
                    $nb_exercices = $stmt->fetchAll();
                    $nb_exercices = $nb_exercices[0][0];

                    if($nb_exercices === "0"){
                    ?>
                    
                    <div class="confirmation">
                    <div class="contenu_confirmation">
                        <div class="info_confirmation">
                        <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                        <div>
                            <h2>Confirmez la suppression</h2>
                            <p>Êtes-vous certain de vouloir supprimer cette classe ?</p>
                        </div>
                        </div>
                        <?php
                        if (isset($_GET['page_classes'])) {
                        echo '<a href="?onglet=classes&page_classes='.$_GET['page_classes'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                        echo '<a href="?onglet=classes&page_classes='.$_GET['page_classes'].'&confirmed_classe=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                        }
                        else {
                        echo '<a href="./Admin.php?onglet=classes" class="annuler_btn" style="color: black;">Annuler</a>';
                        echo '<a href="?onglet=classes&confirmed_classe=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                        } 
                        ?>
                    </div>
                    </div>
                    <?php
                        }
                        else{
                    ?>
                    <div class="confirmation">
                    <div class="contenu_confirmation">
                        <div class="info_confirmation">
                        <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                        <div>
                            <h2>Suppression impossible</h2>
                            <p>La classe est utilisée sur au moins un exerice.</p>
                        </div>
                        </div>
                        <?php
                        if (isset($_GET['page_classes'])) {
                        echo '<a href="?onglet=classes&page_classes='.$_GET['page_classes'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                        }
                        else {
                        echo '<a href="./Admin.php?onglet=classes" class="annuler_btn" style="color: black;">Annuler</a>';
                        } 
                        ?>
                    </div>
                    </div>
                    <?php
                        }
                    }
                    if (isset($_GET['confirmed_classe']) && $_GET['confirmed_classe'] == 'true') {
                        $id_classe = $_GET['id'];
                        $sql_supp = "DELETE FROM classroom WHERE id = $id_classe";
                        $stmt_supp = $conn->prepare($sql_supp);
                        $stmt_supp->execute();
                        
                    
                        header("location: Admin.php?onglet=classes");
                        
                    }
                    ?> 
                </div>
                <!----------------onglet-04-thematiques------------------------->
                <div class="tab" id="tab-thema"><input id="tab-5" name="tab-group-1" type="radio" <?php if( $_GET['onglet'] === 'thematiques'){ echo 'checked';} ?>/> <label class='label_onglet' for="tab-5">Thématiques</label>
                    <?php
                        $thematiques_par_page = 4;
                        $page_thematiques = isset($_GET['page_thematiques']) ? $_GET['page_thematiques'] : 1;
                        $offset = ($page_thematiques - 1) * $thematiques_par_page;

                        // Requête pour obtenir le nombre total d'exercices
                        $sql_total_thematiques = "SELECT COUNT(*) AS total FROM thematic";
                        $result_total_thematiques = $conn->query($sql_total_thematiques);
                        $row_total_thematiques = $result_total_thematiques->fetch_assoc();
                        $total_thematiques = $row_total_thematiques['total'];

                        // Calculer le nombre total de pages
                        $total_pages_thematiques = ceil($total_thematiques / $thematiques_par_page);
                    ?>
                    <?php if(isset($_GET['add_thematique'])){ ?>
                        <div class="content">
                            <h1> Ajouter une thematique </h1>
                            <form action='' method='post'>
                                <div class="ligne">
                                    <label class='label_ajout' for='nom_thematique'>Nom de la thematique :
                                    <input type='text' name='nom_thematique' id='nom_thematique' value="<?php if(isset($thematique)){echo $thematique;}?>" /></label>
                                </div>
                                <div class="ligne">
                                    <label class='label_ajout' for="thematique">Choisir la matière :
                                    <select name="thematique" id="thematique">
                                        <option value="Suites">Suites</option>
                                        <option value="Primitives">Primitives</option>
                                        <option value="Continuite">Continuité</option>
                                        <option value="Geométrie">Géométrie</option>
                                    </select></label>
                                </div>
                            <a href="Admin.php?onglet=thematiques"><input class='btn_retour' type='button' value='◄ Retour à la liste' class="bouton_retour"></input></a>
                            <input type='submit' class="bouton_envoyer" value='Enregistrer'></input>
                            </form>
                        </div>


                    <?php }else{?>
                        <div class="content">
                            <h2>Gestion des Thematiques</h2>
                            <p>Rechercher une thematique par son nom :</p>
                            <div class="recherche_origines">
                            <form action="Admin.php"  method="get">
                                <input type='hidden' name='onglet' value='thematiques'>
                                <input type="text" id="recherche_thema" name="recherche_thema"
                                <?php
                                if (isset($_GET["recherche_thema"])) {
                                    echo 'value="'.$_GET["recherche_thema"].'"';
                                }
                                ?>
                                >
                                <button type="submit">Rechercher</button>
                            </form>
                            <?php 
                                if (isset($_GET["recherche_thema"])) {
                                    echo '<a class="annuler_recherche" href="Admin.php?onglet=thematiques"><p>X</p></a>';
                                }
                            ?>
                                <div class="bouton_ajout">
                                    <a href="Admin.php?onglet=thematiques&add_thematique=true"><p style="color: white;">Ajouter +</p></a>
                                </div> 
                            </div>
                            <table class="tab_exercice">
                                <thead>
                                    <td><p>Nom</p></td>
                                    <td><p>Matiere</p></td>
                                    <td><p>Nombre d'exercices</p></td>
                                    <td><p>Actions</p></td>
                                </thead>
                                <?php

                                if (isset($_GET["recherche_thema"])) {
                                    $sql_search_thematiques = "SELECT id, name FROM thematic
                                                        WHERE name LIKE '%" . $_GET["recherche_thema"] . "%'
                                                        LIMIT $thematiques_par_page OFFSET $offset";
                                    $result_all_thematiques = $conn->query($sql_search_thematiques);
                
                                }
                                else {
                                    $sql_all_thematiques = "SELECT id, name FROM thematic LIMIT $thematiques_par_page OFFSET $offset";
                                    $result_all_thematiques = $conn->query($sql_all_thematiques);
                                }
                    
                                while ($row_thematiques = $result_all_thematiques->fetch_assoc()) {
                                    $stmt = $mysqlClient->prepare("SELECT count(*) FROM exercise WHERE thematic_id=:id;");
                                    $stmt->bindParam(":id", $row_thematiques["id"]);
                                    $stmt->execute();
                                    $nb_exercices = $stmt->fetchAll();

                                    echo "<tr>";
                                    echo "<td class='nom'><p>" . $row_thematiques["name"] . "</p></td>";
                                    echo "<td class='nom'><p>Mathematiques</p></td>";
                                    echo "<td class='nom'><p>" . $nb_exercices[0][0]. "</p></td>";
                                    echo "<td class='actions'>";
                                    echo "<div class='uneAction'><img src='../assets/images/icone_modifier_gris.svg'>
                                            <p><a href='Admin.php?onglet=thematiques&add_thematique=modify&id=".$row_thematiques["id"]."'>Modifier</a></p></div>";
                                    echo "<div class='uneAction'><img src='../assets/images/icone_poubelle_gris.svg'>";
                                    if (isset($_GET['page_thematiques'])) {
                                        echo "<p><a href='?onglet=thematiques&page_thematiques=".$_GET['page_thematiques']."&action_thematiques=delete&id=".$row_thematiques["id"]."'>Supprimer</a></p></div>";
                                    }
                                    else {
                                        echo "<p><a href='?onglet=thematiques&action_thematiques=delete&id=".$row_thematiques["id"]."'>Supprimer</a></p></div>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            echo "</table>";
                            ?>
                            <div class="pagination">
                                <?php
                                    if ($page_thematiques > 1) {
                                        echo "<a href='Admin.php?onglet=thematiques&page_thematiques=".($page_thematiques - 1)."' class='pagination-bouton-gauche'>&lt;</a>";
                                    } else {
                                        echo "<span class='pagination-bouton-gauche'>&lt;</span>";
                                    }

                                    for ($i=1; $i<=$total_pages_thematiques; $i++) {
                                        if ($i == $page_thematiques) {
                                        echo "<span class='page-actuel'>$i</span>";
                                        } else {
                                            echo "<a href='Admin.php?onglet=thematiques&page_thematiques=".$i."' class='pagination-lien'>$i</a>";
                                        }
                                    }

                                    if ($page_thematiques < $total_pages_thematiques) {
                                        echo "<a href='Admin.php?onglet=thematiques&page_thematiques=".($page_thematiques + 1)."' class='pagination-bouton-droite'>&gt;</a>";
                                    } else {
                                        echo "<span class='pagination-bouton-droite'>&gt;</span>";
                                    }
                                ?>
                            </div>
                        </div>

                    <?php } ?>
                    <?php
                if (isset($_GET['action_thematiques']) && $_GET['action_thematiques'] === 'delete') {
                    // verifiaction si la thematique est utilisée sur des exercice
                    $stmt = $mysqlClient->prepare("SELECT count(*) FROM exercise WHERE thematic_id=:id;");
                    $stmt->bindParam(":id", $_GET['id']);
                    $stmt->execute();
                    $nb_exercices = $stmt->fetchAll();
                    $nb_exercices = $nb_exercices[0][0];

                    if($nb_exercices === "0"){
                    ?>
                    
                    <div class="confirmation">
                    <div class="contenu_confirmation">
                        <div class="info_confirmation">
                        <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                        <div>
                            <h2>Confirmez la suppression</h2>
                            <p>Êtes-vous certain de vouloir supprimer cette thematique ?</p>
                        </div>
                        </div>
                        <?php
                        if (isset($_GET['page_thematiques'])) {
                        echo '<a href="?onglet=thematiques&page_thematiques='.$_GET['page_thematiques'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                        echo '<a href="?onglet=thematiques&page_thematiques='.$_GET['page_thematiques'].'&confirmed_thematique=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                        }
                        else {
                        echo '<a href="./Admin.php?onglet=thematiques" class="annuler_btn" style="color: black;">Annuler</a>';
                        echo '<a href="?onglet=thematiques&confirmed_thematique=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                        } 
                        ?>
                    </div>
                    </div>
                    <?php
                        }
                        else{
                    ?>
                    <div class="confirmation">
                    <div class="contenu_confirmation">
                        <div class="info_confirmation">
                        <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                        <div>
                            <h2>Suppression impossible</h2>
                            <p>La thematique est utilisée sur au moins un exerice.</p>
                        </div>
                        </div>
                        <?php
                        if (isset($_GET['page_thematiques'])) {
                        echo '<a href="?onglet=thematiques&page_thematiques='.$_GET['page_thematiques'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                        }
                        else {
                        echo '<a href="./Admin.php?onglet=thematiques" class="annuler_btn" style="color: black;">Annuler</a>';
                        } 
                        ?>
                    </div>
                    </div>
                    <?php
                        }
                    }
                    if (isset($_GET['confirmed_thematique']) && $_GET['confirmed_thematique'] == 'true') {
                        $id_thematique = $_GET['id'];
                        $sql_supp = "DELETE FROM thematic WHERE id = $id_thematique";
                        $stmt_supp = $conn->prepare($sql_supp);
                        $stmt_supp->execute();

                        header("location: Admin.php?onglet=thematiques");
                    }
                    ?> 
                </div>
                <!----------------onglet-05-origines-------------------------->
                <div class="tab" id="tab-ori"><input id="tab-6" name="tab-group-1" type="radio" <?php if( $_GET['onglet'] === 'origines'){ echo 'checked';} ?> /> <label class='label_onglet' for="tab-6">Origines</label>
                <?php
                    $origines_par_page = 4;
                    $page_origines = isset($_GET['page_origines']) ? $_GET['page_origines'] : 1;
                    $offset = ($page_origines - 1) * $origines_par_page;

                    // Requête pour obtenir le nombre total d'exercices
                    $sql_total_origines = "SELECT COUNT(*) AS total FROM origin";
                    $result_total_origines = $conn->query($sql_total_origines);
                    $row_total_origines = $result_total_origines->fetch_assoc();
                    $total_origines = $row_total_origines['total'];

                    // Calculer le nombre total de pages
                    $total_pages_origines = ceil($total_origines / $origines_par_page);
                ?>
                <?php if(isset($_GET['add_origine'])){ ?>
                        <div class="content">
                            <h1>Ajouter une origine</h1>
                            <form action='' method='post'>
                                <div class="ligne">
                                    <label class='label_ajout' for='nom_origine'>Nom de l'origine :
                                    <input type='text' name='nom_origine' id='nom_origine' value="<?php if(isset($origine)){echo $origine;}?>" /></label>
                                </div>
                                <a href="Admin.php?onglet=origines"><input class='btn_retour' type='button' value='◄ Retour à la liste' class="bouton_retour"></input></a>
                                <input type='submit' class="bouton_envoyer" value='Enregistrer'></input>
                            </form>
                        </div>


                    <?php }else{?>
                    <div class="content">
                        <h2>Gestion des origines</h2>
                        <p>Rechercher une origine par son nom :</p>
                        <div class="recherche_origines">
                        <form action="Admin.php"  method="get">
                                <input type='hidden' name='onglet' value='origines'>
                                <input type="text" id="recherche_thema" name="recherche_origines"
                                <?php
                                if (isset($_GET["recherche_origines"])) {
                                    echo 'value="'.$_GET["recherche_origines"].'"';
                                }
                                ?>
                                >
                                <button type="submit">Rechercher</button>
                            </form>
                            <?php 
                                if (isset($_GET["recherche_origines"])) {
                                    echo '<a class="annuler_recherche" href="Admin.php?onglet=origines"><p>X</p></a>';
                                }
                            ?>
                            <div class="bouton_ajout">
                                <a href="Admin.php?onglet=origines&add_origine=true"><p style="color: white;">Ajouter +</p></a>
                            </div> 
                        </div>
                        <table class="tab_origines">
                            <thead>
                                <td><p>Nom</p></td>
                                <td><p>Actions</p></td>
                            </thead>
                    <?php
                  
                        if (isset($_GET["recherche_origines"])) {
                            $sql_search_origines = "SELECT id, name FROM origin
                                                WHERE name LIKE '%" . $_GET["recherche_origines"] . "%'
                                                LIMIT $origines_par_page OFFSET $offset";
                            $result_all_origines = $conn->query($sql_search_origines);
        
                        }
                        else {
                            $sql_all_origines = "SELECT id, name
                            FROM origin
                            LIMIT $origines_par_page OFFSET $offset";
                            $result_all_origines = $conn->query($sql_all_origines);
                        }

                    while ($row_origines = $result_all_origines->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td class='nom'><p>" . $row_origines["name"] . "</p></td>";
                      echo "<td class='actions'>";
                      echo "<div class='uneAction'><img src='../assets/images/icone_modifier_gris.svg'>
                            <p><a href='Admin.php?onglet=origines&add_origine=modify&id=".$row_origines["id"]."'>Modifier</a></p></div>";
                      echo "<div class='uneAction'><img src='../assets/images/icone_poubelle_gris.svg'>";
                      if (isset($_GET['page_origines'])) {
                        echo "<p><a href='?page_origines=".$_GET['page_origines']."&action_origines=delete&id=".$row_origines["id"]."'>Supprimer</a></p></div>";
                      }
                      else {
                        echo "<p><a href='?onglet=origines&action_origines=delete&id=".$row_origines["id"]."'>Supprimer</a></p></div>";
                      }
                      echo "</td>";
                     echo "</tr>";
                    }
                  echo "</table>";
                    ?>  
                    <div class="pagination">
                            <?php
                        if ($page_origines > 1) {
                            echo "<a href='Admin.php?onglet=origines&page_origines=".($page_origines - 1)."' class='pagination-bouton-gauche'>&lt;</a>";
                        } else {
                            echo "<span class='pagination-bouton-gauche'>&lt;</span>";
                        }

                        for ($i=1; $i<=$total_pages_origines; $i++) {
                            if ($i == $page_origines) {
                            echo "<span class='page-actuel'>$i</span>";
                            } else {
                                echo "<a href='Admin.php?onglet=origines&page_origines=".$i."' class='pagination-lien'>$i</a>";
                            }
                        }

                        if ($page_origines < $total_pages_origines) {
                            echo "<a href='Admin.php?onglet=origines&page_origines=".($page_origines + 1)."' class='pagination-bouton-droite'>&gt;</a>";
                        } else {
                            echo "<span class='pagination-bouton-droite'>&gt;</span>";
                        }
                      
                        ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php
                if (isset($_GET['action_origines']) && $_GET['action_origines'] === 'delete') {
                    $stmt = $mysqlClient->prepare("SELECT count(*) FROM exercise WHERE origin_id=:id;");
                    $stmt->bindParam(":id", $_GET['id']);
                    $stmt->execute();
                    $nb_exercices = $stmt->fetchAll();
                    $nb_exercices = $nb_exercices[0][0];

                    if($nb_exercices === "0"){

                    ?>
                    <div class="confirmation">
                    <div class="contenu_confirmation">
                        <div class="info_confirmation">
                        <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                        <div>
                            <h2>Confirmez la suppression</h2>
                            <p>Êtes-vous certain de vouloir supprimer cette origine ?</p>
                        </div>
                        </div>
                        <?php
                        if (isset($_GET['page_origines'])) {
                        echo '<a href="?page_origines='.$_GET['page_origines'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                        echo '<a href="?page_origines='.$_GET['page_origines'].'&confirmed_origines=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                        }
                        else {
                        echo '<a href="./Admin.php" class="annuler_btn" style="color: black;">Annuler</a>';
                        echo '<a href="?confirmed_origines=true&id='.$_GET['id'].'"class="confirmer_btn" style="color: white;">Confirmer</a>';
                        } 
                        ?>
                        </div>
                        </div>
                        <?php
                            }
                            else{
                        ?>
                                <div class="confirmation">
                                <div class="contenu_confirmation">
                                    <div class="info_confirmation">
                                    <div class="fond_image"><img src="../assets/images/icone_valider.svg"></div>
                                    <div>
                                        <h2>Suppression impossible</h2>
                                        <p>L'origine est utilisée sur au moins un exerice.</p>
                                    </div>
                                    </div>
                                    <?php
                                    if (isset($_GET['page_origines'])) {
                                    echo '<a href="?onglet=origines&page_origines='.$_GET['page_origines'].'"class="annuler_btn" style="color: black;">Annuler</a>';
                                    }
                                    else {
                                    echo '<a href="./Admin.php?onglet=origines" class="annuler_btn" style="color: black;">Annuler</a>';
                                    } 
                                    ?>
                                </div>
                                </div>
                    <?php
                        }
                    }
                        if (isset($_GET['confirmed_origines']) && $_GET['confirmed_origines'] == 'true') {
                            $id_origines = $_GET['id'];
                            $sql_supp = "DELETE FROM origin WHERE id = $id_origines";
                            $stmt_supp = $conn->prepare($sql_supp);
                            $stmt_supp->execute();
                            
                            header("location: Admin.php?onglet=origines");
                              
                        }
                        ?>
                </div>
                </div>
                </div>
                <p><br /><br /><br /></p>   
        </div>
        <div class="mentionlegales">
            <div class="mentionlegales-text">Mentions légales</div>
            <div class="mentionlegales-text">•</div>
            <div class="mentionlegales-text">Contact</div>
            <div class="mentionlegales-text">•</div>
            <div class="mentionlegales-text">Lycée Saint-Vincent</div>
        </div>
    </div>
</body>
</html>

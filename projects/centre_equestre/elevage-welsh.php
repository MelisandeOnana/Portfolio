<?php
session_start();
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';
$currentPage = 'elevage';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page élévage Welsh</title>
    <link rel="stylesheet" href="assets/css/Elevage.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <div class="container">
        <div class="header">
            <h2>L'élevage Welsh / Selle Français</h2>
            <p>En 1986, j'ai eu mon premier poulain UT de L'Arrée, alias "Ulysse" (Jézabel A x Fangio). En 2005, avec mon mari, nous avons fait naître Rubens de l'Arré (Éclaire du Vandy x Charleston du Cap). En 2009, nous avons commencé l'élevage de Selle Français et des poneys de club sous l'affixe "De l'Arré".</p>
            <p>En 2014, nous avons acquis l'élevage de poney Welsh "Sponté" et avons arrêté le Selle Français. Nous produisons désormais sous deux affixes : "De l'Arré" (nom du rue de notre village) et "Sponté".</p>
            <p>Le poney Welsh, originaire du Pays de Galles, est reconnu pour sa polyvalence et son tempérament docile. Il est souvent utilisé aussi bien pour le saut d'obstacles que pour le dressage. Les lignées de notre élevage sont sélectionnées pour leurs qualités athlétiques et leur caractère, faisant de ces poneys des compagnons idéaux pour les cavaliers de tous âges.</p>
            <p>La décision d'arrêter l'élevage de Selle Français a été motivée par notre désir de nous concentrer sur les poneys de club et de loisir. Les Welsh, avec leur taille plus petite et leur nature amicale, sont particulièrement adaptés pour les jeunes cavaliers et les amateurs recherchant un poney fiable et performant.</p>
            <p>Chaque année, nous avons la joie de voir naître plusieurs poulains, fruits de notre travail de sélection rigoureux. Ces poulains sont élevés dans des conditions optimales, bénéficiant de grands espaces et d'une attention quotidienne. Notre objectif est de produire des poneys sains, équilibrés et bien dans leur tête, prêts à rejoindre leurs futures familles et à exceller dans les disciplines équestres.</p>
            <p>Choisissez une catégorie pour plus d'informations :</p>
            <div class="tabs">
                <button class="tablinks active" onclick="openTab(event, 'etalon')">Étalon</button>
                <button class="tablinks" onclick="openTab(event, 'jument')">Jument/Poulinière</button>
            </div>
        </div>
        <div id="etalon" class="tabcontent" style="display: block;">
            <h2>Étalon</h2>
            <div class="horse">
                <img src="assets/images/Jack_etalon.jpeg" alt="Jack Daniel Chery">
                <p>Jack Daniel Chery, notre superbe étalon Welsh Cob, né en 2019, incarne la perfection de notre élevage. Sa robe isabelle et son caractère docile en font un cheval très apprécié.</p>
                <div class="pedigree">
                    <h4>Pedigree de Jack Daniel Chery :</h4>
                    <p><strong>Nom:</strong> Jack Daniel Chery</p>
                    <p><strong>Sexe:</strong> Mâle</p>
                    <p><strong>Race:</strong> Welsh Cob</p>
                    <p><strong>Robe:</strong> Isabelle</p>
                    <p><strong>Année de naissance:</strong> 2019</p>
                    <p><strong>Parents :</strong></p>
                    <p><strong>Père:</strong> Gwenllan Sam (GBR), WD, 1991, 16 produit(s) enregistré(s)</p>
                    <p><strong>Grand-père paternel:</strong> Gwenllan Brynmor (GBR), WD, 1997, 14 produit(s) enregistré(s)</p>
                    <p><strong>Grand-mère paternelle:</strong> Gwenllan Blodwen (GBR), WD, 1992, 1 produit(s) enregistré(s)</p>
                    <p><strong>Mère:</strong> Pennal Lightening Lady (GBR), WD, 2008, UELN: 826046020151364, 2 produit(s) enregistré(s)</p>
                    <p><strong>Grand-père maternel:</strong> Solloway Hooch (GBR), WD, 2001, 3 produit(s) enregistré(s)</p>
                    <p><strong>Grand-mère maternelle:</strong> Pennal Lady Lowri (GBR), WD, 2005, 1 produit(s) enregistré(s)</p>
                    <p><strong>Ascendants notables:</strong></p>
                    <p>Gwenllan Sparc (GBR), WD, 1992, 9 produit(s) enregistré(s)</p>
                    <p>Geler Sparc (GBR), WD, 2001, 8 produit(s) enregistré(s)</p>
                    <p>Geler Miriam (GBR), WD, 1997, 1 produit(s) enregistré(s)</p>
                    <p>Corscaron Cymro Llwyd (GBR), WD, 2006, 6 produit(s) enregistré(s)</p>
                    <p>Derwen Desert Express (GBR), WD, 1982, 52 produit(s) enregistré(s)</p>
                    <p>Corscaron Desert Maid (GBR), WD, 1 produit(s) enregistré(s)</p>
                    <p>Vernal Moonray (GBR), WD, 1978, 2 produit(s) enregistré(s)</p>
                </div>
            </div>
            <div class="horse">
                <img src="assets/images/Ouro_etalon.jpeg" alt="Ouro">
                <p>Ouro, notre magnifique étalon Welsh Pony, né en 2017, est un autre exemple de l'excellence de notre élevage. Sa robe alezane et son tempérament équilibré en font un choix idéal pour la reproduction.</p>
                <div class="pedigree">
                    <h4>Pedigree de Ouro :</h4>
                    <p><strong>Nom:</strong> Chellanne Spring Enchantmt (Ouro)</p>
                    <p><strong>Sexe:</strong> Mâle</p>
                    <p><strong>Race:</strong> Welsh Pony</p>
                    <p><strong>Robe:</strong> Alezane</p>
                    <p><strong>Année de naissance:</strong> 2017</p>
                    <p><strong>Parents :</strong></p>
                    <p><strong>Père:</strong> Lacy Buzbee (GBR), WPB, 2005, 12 produit(s) enregistré(s)</p>
                    <p><strong>Grand-père paternel:</strong> Lacy Buz (GBR), WPB, 1999, 10 produit(s) enregistré(s)</p>
                    <p><strong>Grand-mère paternelle:</strong> Lacy Buzzard (GBR), WPB, 1997, 6 produit(s) enregistré(s)</p>
                    <p><strong>Mère:</strong> Rookery Ginger (GBR), WPB, 2010, UELN: 826046020212345, 3 produit(s) enregistré(s)</p>
                    <p><strong>Grand-père maternel:</strong> Rookery Gordon (GBR), WPB, 2003, 5 produit(s) enregistré(s)</p>
                    <p><strong>Grand-mère maternelle:</strong> Rookery Gina (GBR), WPB, 2007, 4 produit(s) enregistré(s)</p>
                    <p><strong>Ascendants notables:</strong></p>
                    <p>DEARNVALLEY GOLDEN BULLIO (GBR), WD, 2003, 2 produit(s) enregistré(s)</p>
                    <p>DEARNEVALLEY GOLDEN IMAGE (GBR), WD, 2008, 1 produit(s) enregistré(s)</p>
                    <p>TREVALLION CRYSTAL (GBR), WD, 1998, 1 produit(s) enregistré(s)</p>
                    <p>DEARNVALLEY MOONDANCE (GBR), WD, 2000, 1 produit(s) enregistré(s)</p>
                    <p>DEARNVALLEY STARLIGHT (GBR), WD, 2002, 1 produit(s) enregistré(s)</p>
                    <p>RIVER VALLEY STAR (GBR), WD, 1998, 1 produit(s) enregistré(s)</p>
                    <p>CRAYDALE GOLDEN GLIMMER (GBR), WD, 1996, 3 produit(s) enregistré(s)</p>
                </div>
            </div>
        </div>
        <div id="jument" class="tabcontent">
            <h2>Jument/Poulinière</h2>
            <div class="horse">
                <img src="assets/images/jument1.jpg" alt="Raphaelle Sponté">
                <h4>Raphaelle Sponté WD</h4>
                <!-- Informations sur la jument -->
                <p><strong>Race :</strong> Welsh</p>
                <p><strong>Lignée :</strong> Trevallion Harley x Kanelle Sponte par Hywi Real Magic</p>
                <p><strong>Produits :</strong> Jerico Sponte, Imperio Sponte, Haribo Sponte, Ginko des ajoux</p>
            </div>
            <div class="horse">
                <img src="assets/images/jument2.webp" alt="Raphaelle Sponté">
                <h4>Barbara Sponte WD</h4>
                <!-- Informations sur la jument -->
                <p><strong>Race :</strong> Welsh</p>
                <p><strong>Année de naissance :</strong> 2011</p>
                <p><strong>Lignée :</strong> Trevallion Harley x Marquise Sponte par Quickly Dousud</p>
                <p><strong>Produits :</strong> Juke Boxe Sponte (par Wexland Safari) - Smocky Black</p>
            </div>
            <div class="horse">
                <img src="assets/images/jument3.webp" alt="Raphaelle Sponté">
                <h4>Caraïbe Sponte</h4>
                <!-- Informations sur la jument -->
                <p><strong>Race :</strong> Welsh</p>
                <p><strong>Lignée :</strong> Trevallion Harley x Jessie Sponte par Wildham Flying Free</p>
                <p><strong>Année de naissance :</strong> 2012</p>
                <p><strong>Couleur :</strong> Palomino</p>
                <p><strong>Points PACE :</strong> 4</p>
                <p><strong>Performances :</strong></p>
                <ul>
                    <li>IPD 115 (excellent 5 ans Dressage)</li>
                    <li>IPO 109</li>
                </ul>
                <p>Qualification finale cycle classique dressage jeunes poneys à 4 ans TRES BON</p>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>
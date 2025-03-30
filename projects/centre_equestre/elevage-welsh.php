<?php
session_start();
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';
// Inclure la connexion à la base de données
include('config/config.php');

// Requête pour récupérer les chevaux
$sql = "SELECT * FROM chevaux";
$result = $pdo->query($sql); // Remplacement de $conn par $pdo

// Stocker les résultats dans un tableau
$chevaux = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Chevaux - Élevage Welsh</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Elevage.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
        <div class="intro">
            <h2>L'élevage Welsh / Selle Français</h2>
            <p>En 1986, j'ai eu mon premier poulain UT de L'Arrée, alias "Ulysse" (Jézabel A x Fangio). En 2005, avec mon mari, nous avons fait naître Rubens de l'Arré (Éclaire du Vandy x Charleston du Cap). En 2009, nous avons commencé l'élevage de Selle Français et des poneys de club sous l'affixe "De l'Arré".</p>
            <p>En 2014, nous avons acquis l'élevage de poney Welsh "Sponté" et avons arrêté le Selle Français. Nous produisons désormais sous deux affixes : "De l'Arré" (nom du rue de notre village) et "Sponté".</p>
            <p>Le poney Welsh, originaire du Pays de Galles, est reconnu pour sa polyvalence et son tempérament docile. Il est souvent utilisé aussi bien pour le saut d'obstacles que pour le dressage. Les lignées de notre élevage sont sélectionnées pour leurs qualités athlétiques et leur caractère, faisant de ces poneys des compagnons idéaux pour les cavaliers de tous âges.</p>
            <p>La décision d'arrêter l'élevage de Selle Français a été motivée par notre désir de nous concentrer sur les poneys de club et de loisir. Les Welsh, avec leur taille plus petite et leur nature amicale, sont particulièrement adaptés pour les jeunes cavaliers et les amateurs recherchant un poney fiable et performant.</p>
            <p>Chaque année, nous avons la joie de voir naître plusieurs poulains, fruits de notre travail de sélection rigoureux. Ces poulains sont élevés dans des conditions optimales, bénéficiant de grands espaces et d'une attention quotidienne. Notre objectif est de produire des poneys sains, équilibrés et bien dans leur tête, prêts à rejoindre leurs futures familles et à exceller dans les disciplines équestres.</p>
        </div>

        <!-- Section pour les onglets -->
        <section class="container my-5">
            <h2 class="text-center mb-5">Nos Chevaux</h2>

            <!-- Onglets -->
            <ul class="nav nav-tabs" id="chevauxTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="juments-tab" data-bs-toggle="tab" href="#juments" role="tab" aria-controls="juments" aria-selected="true">Nos Juments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="etalons-tab" data-bs-toggle="tab" href="#etalons" role="tab" aria-controls="etalons" aria-selected="false">Nos Étalons</a>
                </li>
            </ul>

            <div class="tab-content" id="chevauxTabContent">
                <!-- Section Nos Juments -->
                <div class="tab-pane fade show active" id="juments" role="tabpanel" aria-labelledby="juments-tab">
                    <div id="carouselJuments" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            // Compteur pour la première image active
                            $isActive = true;
                            foreach ($chevaux as $cheval) {
                                if ($cheval["sexe"] == "jument") {
                                    echo '
                                    <div class="carousel-item ' . ($isActive ? 'active' : '') . '">
                                        <img src="' . $cheval["image"] . '" class="d-block w-100" alt="' . $cheval["nom"] . '">
                                        <div class="card-body">
                                            <h5 class="card-title">' . $cheval["nom"] . '</h5>
                                            <p class="card-text">' . $cheval["description"] . '</p>
                                            <p><strong>Race :</strong> ' . $cheval["race"] . '</p>
                                            <p><strong>Lignée :</strong> ' . $cheval["lignée"] . '</p>';
                                    
                                    if (isset($cheval["année_de_naissance"])) {
                                        echo '<p><strong>Année de naissance :</strong> ' . $cheval["année_de_naissance"] . '</p>';
                                    }
                                    if (isset($cheval["couleur"])) {
                                        echo '<p><strong>Couleur :</strong> ' . $cheval["couleur"] . '</p>';
                                    }
                                    echo '</div>
                                    </div>';
                                    $isActive = false; // Désactive la première image après utilisation
                                }
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselJuments" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselJuments" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    </div>
                </div>

                <!-- Section Nos Étalons -->
                <div class="tab-pane fade" id="etalons" role="tabpanel" aria-labelledby="etalons-tab">
                    <div id="carouselEtalons" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            // Compteur pour la première image active
                            $isActive = true;
                            foreach ($chevaux as $cheval) {
                                if ($cheval["sexe"] == "étalon") {
                                    echo '
                                    <div class="carousel-item ' . ($isActive ? 'active' : '') . '">
                                        <img src="' . $cheval["image"] . '" class="d-block w-100" alt="' . $cheval["nom"] . '">
                                        <div class="card-body">
                                            <h5 class="card-title">' . $cheval["nom"] . '</h5>
                                            <p class="card-text">' . $cheval["description"] . '</p>
                                            <p><strong>Race :</strong> ' . $cheval["race"] . '</p>
                                            <p><strong>Lignée :</strong> ' . $cheval["lignée"] . '</p>';
                                    
                                    if (isset($cheval["année_de_naissance"])) {
                                        echo '<p><strong>Année de naissance :</strong> ' . $cheval["année_de_naissance"] . '</p>';
                                    }
                                    if (isset($cheval["couleur"])) {
                                        echo '<p><strong>Couleur :</strong> ' . $cheval["couleur"] . '</p>';
                                    }
                                    echo '</div>
                                    </div>';
                                    $isActive = false; // Désactive la première image après utilisation
                                }
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselEtalons" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselEtalons" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>
<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>

<?php
session_start(); // Démarrer la session
include 'includes/db_connect.php';

// Configuration de la locale en français
setlocale(LC_TIME, 'fr_FR.UTF-8');

// Récupére les apprentissages depuis la base de données
$stmt = $pdo->query('SELECT * FROM apprentissages');
$apprentissages = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des valeurs du formulaire
    $nom = htmlspecialchars($_POST["name"]);
    $prenom = htmlspecialchars($_POST["prenom"]);
    $email = htmlspecialchars($_POST["email"]);
    $objet = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    // Validation du nom et prénom (caractères valides seulement)
    if (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $nom) || !preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $prenom)) {
        $_SESSION['contact_error'] = "Veuillez entrer un nom ou prénom valide sans chiffres ou symboles.";
        header("Location: index.php#contact");
        exit();
    }

    // Récupération du domaine de l'adresse email
    $email_domain = substr(strrchr($email, "@"), 1); 

    // Liste des domaines autorisés
    $allowed_domains = ['orange.fr', 'gmail.com', 'wanadoo.fr'];

    // Vérifie si le domaine de l'email fait partie de la liste autorisée
    if (!in_array($email_domain, $allowed_domains)) {
        $_SESSION['contact_error'] = "Veuillez entrer une adresse email valide avec un domaine autorisé (orange.fr, gmail.com, wanadoo.fr).";
        header("Location: index.php#contact");
        exit();
    }   

    // Requête d'insertion
    $query = "INSERT INTO demandes_contact (nom, prenom, email, objet, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nom, $prenom, $email, $objet, $message]);

    // Définition du message de succès
    $_SESSION['contact_success'] = "Votre message a été envoyé avec succès. Merci de m'avoir contacté !";
    header("Location: index.php#contact");
    exit();
}

// Récupére les projets depuis la base de données
$stmt = $pdo->query('SELECT * FROM projets WHERE visible = 1');
$projets = $stmt->fetchAll();

// Fonction pour formater les dates en français
function formatDateFr($date) {
    $months = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    $dateTime = new DateTime($date);
    $month = $months[(int)$dateTime->format('m')];
    $year = $dateTime->format('Y');
    return "$month $year";
}

// Trie les projets par ordre décroissant de date de début
usort($projets, function($a, $b) {
    return strtotime($b['date_debut']) - strtotime($a['date_debut']);
});

// Défini le nombre de projets par page
$projetsParPage = 3;

// Calcule le nombre total de pages
$totalProjets = count($projets);
$totalPages = ceil($totalProjets / $projetsParPage);

// Détermine la page actuelle
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pageActuelle = max(1, min($totalPages, $pageActuelle));

// Calcule l'offset pour la requête SQL
$offset = ($pageActuelle - 1) * $projetsParPage;

// Récupére les projets pour la page actuelle
$projetsPage = array_slice($projets, $offset, $projetsParPage);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Mélisande Onana Ngono - Développeuse Web</title>
    <link rel="stylesheet" href="assets/css/accueil.css">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="google-site-verification" content="2PJuHrsTZRtQXLhU-tlzeKk-OpZTNjBlU0ufnKmI4aM" />
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-left">
            <div class="first_name">
                <a href="connexion.php">Mélisande<br>Onana Ngono</a>
            </div>
        </div>
        <!-- Bouton de menu -->
        <nav class="top-nav">
            <ul>
                <li><a href="#home" class="active">Accueil</a></li>
                <li><a href="#projects">Réalisations</a></li>
                <li><a href="#shills">Apprentissages</a></li>
                <li><a href="#technology_watch">Veille technologique</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Section Accueil -->
    <section id="home">
        <div class="intro">
            <p>Passionnée par le développement web, digitale et les technologies numériques. Explorez mes réalisations et découvrez mon parcours d'apprentissage.</p>
            <a href="#realisations" class="btn">En savoir plus</a>
            <br>
            <a href="assets/pdf/CV_onana_melisande.pdf" class="btn" target="_blank">Mon CV</a>
            <a href="https://github.com/MelisandeOnana" class="btn" target="_blank">GitHub</a>
            <a href="https://www.linkedin.com/in/m%C3%A9lisande-onana-ngono-7576512ba?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3B8vu%2F5X1RRkik2bIAfoefFA%3D%3D" class="btn" target="_blank">LinkedIn</a>
        </div>
        <div class="circles" aria-hidden="true">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </section>

    <!-- Section Réalisations -->
    <section id="projects">
        <h1>Mes Réalisations</h1>
        <div class="projects-grid">
            <?php foreach ($projetsPage as $projet): ?>
                <div class="project-card">
                    <img src="<?= htmlspecialchars($projet['image']); ?>" alt="<?= htmlspecialchars($projet['titre']); ?>">
                    <div class="project-content">
                        <h3><?= htmlspecialchars($projet['titre']); ?></h3>
                        <p>
                            <?= ucfirst(formatDateFr($projet['date_debut'])); ?>
                            <?php if (!is_null($projet['date_fin'])): ?>
                                - <?= ucfirst(formatDateFr($projet['date_fin'])); ?>
                            <?php endif; ?>
                        </p>
                        <p><?= htmlspecialchars($projet['description']); ?></p>
                        <a href="<?= htmlspecialchars($projet['lien']); ?>" class="btn" target="_blank">Voir le projet</a>
                        <?php if (!empty($projet['documentation'])): ?>
                            <a href="<?= htmlspecialchars($projet['documentation']); ?>" class="btn" target="_blank">Voir documentation</a>
                        <?php endif; ?>
                        <?php if (!empty($projet['lien_github'])) : ?>
                            <a href="<?= htmlspecialchars($projet['lien_github']); ?>" class="btn" target="_blank">Voir sur GitHub</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($pageActuelle > 1): ?>
                <a href="?page=<?= $pageActuelle - 1 ?>#projects">&laquo; Précédent</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>#projects" class="<?= $i == $pageActuelle ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($pageActuelle < $totalPages): ?>
                <a href="?page=<?= $pageActuelle + 1 ?>#projects">Suivant &raquo;</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Section Apprentissages -->
    <section id="skills">
        <h1>Mes Apprentissages</h1>
        <button id="toggle-cards" class="btn">Voir mes apprentissages</button>
        <div class="cards hidden">
            <?php foreach ($apprentissages as $apprentissage): ?>
                <div class="card">
                    <i class="<?= htmlspecialchars($apprentissage['logo']) ?>"></i>
                    <h3><?= htmlspecialchars($apprentissage['titre']) ?></h3>
                    <p><?= htmlspecialchars($apprentissage['description']) ?></p>
                    <p class="date">Depuis <?= ucfirst(formatDateFr($apprentissage['date_debut'])) ?></p>
                    <?php if ($apprentissage['certification']): ?>
                        <a href="<?= htmlspecialchars($apprentissage['certification']) ?>" class="btn" target="_blank">Certification en cours</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    <!-- Section Veille Technologique -->
    <section id="technology_watch">
        <h1>Veille Technologique</h1>
        <article>
            <h3>ChatGPT et Midjourney dans le Développement Web</h3>
            <p>ChatGPT et Midjourney révolutionnent le développement web. ChatGPT permet de créer des chatbots intelligents pour une interaction utilisateur fluide, tandis que Midjourney génère des designs et visuels personnalisés, accélérant ainsi la création de contenus uniques.</p>
        </article>
        <article>
            <h3>Exemples Concrets</h3>
            <p>ChatGPT est utilisé sur de nombreux sites pour répondre aux utilisateurs en temps réel. Midjourney facilite la création de visuels adaptés aux besoins du projet.</p>
        </article>
        <article>
            <h3>Citations</h3>
            <blockquote>
                "ChatGPT et Midjourney redéfinissent notre approche du web." - <em>John Smith, Développeur Web</em>
            </blockquote>
        </article>
    </section>


    <!-- Section Contact -->
    <section id="contact">
        <h1>Contact</h1>
        <p>Contactez-moi pour plus d'informations.</p>

        <?php
        if (isset($_SESSION['contact_error'])) {
            echo '<div class="error-message">';
            echo $_SESSION['contact_error'];
            echo '</div>';
            unset($_SESSION['contact_error']);
        }

        if (isset($_SESSION['contact_success'])) {
            echo '<div class="success-message">';
            echo $_SESSION['contact_success'];
            echo '</div>';
            unset($_SESSION['contact_success']);
        }
        ?>

        <div class="contact-form">
            <form id="contact-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Objet :</label>
                    <select id="subject" name="subject">
                        <option value="info_me">Informations sur moi</option>
                        <option value="portfolio">Questions sur mon portfolio</option>
                        <option value="interview">Demande d'entretien</option>
                        <option value="internship">Demande de stage</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn">Envoyer</button>
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Mélisande Onana Ngono. Tous droits réservés.</p>
    </footer>
  
    <script src="assets/js/tarteaucitron.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>

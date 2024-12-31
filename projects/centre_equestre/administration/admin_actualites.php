<?php
// Démarrer la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['loggedin'])) {
    error_log("L'utilisateur n'est pas connecté."); // Message de débogage
    header('Location: ../connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérification si l'utilisateur a le rôle d'administrateur
if ($_SESSION['role'] !== 'Administrateur') {
    error_log("L'utilisateur n'a pas le rôle d'administrateur."); // Message de débogage
    header('Location: ../connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas administrateur
    exit();
}

// Définir la variable $isAdmin
$isAdmin = ($_SESSION['role'] === 'Administrateur');

// Inclure le fichier de configuration de la base de données (où se trouve votre connexion PDO)
require_once '../config/config.php';

// Nombre maximum d'actualités par page
$actualites_par_page = 2;

// Calculer le numéro de page actuel
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Calculer le OFFSET pour la requête SQL
$offset = ($page - 1) * $actualites_par_page;

// Requête pour récupérer le nombre total d'actualités
$sql_count = "SELECT COUNT(*) AS total FROM actualites";
$stmt_count = $pdo->query($sql_count);
$total_actualites = $stmt_count->fetchColumn();

// Requête pour récupérer les actualités avec pagination
$sql = "SELECT * FROM actualites ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $actualites_par_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll();

// Requête pour récupérer la dernière actualité ajoutée
$sql_last = "SELECT * FROM actualites ORDER BY id DESC LIMIT 1";
$stmt_last = $pdo->query($sql_last);
$result_last = $stmt_last->fetch();

// Fonction pour formater la date
function formatDate($date)
{
    // Créer un objet DateTime à partir de la chaîne de date
    $dateTime = new DateTime($date);
    // Retourner la date formatée selon le format d/m/Y
    return $dateTime->format('d/m/Y');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Actualités</title>
    <link rel="stylesheet" href="../assets/css/Admin_actualites.css">
</head>
<body>
    <header>
        <div class="hamburger-menu">
            <span class="bar1"></span>
            <span class="bar2"></span>
            <span class="bar3"></span>
        </div>
        <div class="centre <?php echo !$isAdmin ? 'centre-not-connected' : ''; ?>">
            <div class="header-title">Écuries du Val d'Arré</div>
            <div class="header-line"></div>
            <div class="header-subtitle">Saint-Rémy en l'eau</div>
        </div>
        <div class="login-container">
            <?php if ($isAdmin): ?>
                <div class="dropdown">
                    <button class="welcome-btn">Bienvenue <br><?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></button>
                    <div class="dropdown-menu">
                        <a href="admin_actualites.php" class="admin-btn">Administration</a>
                        <a href="../deconnexion.php" class="logout-btn">Déconnexion</a>
                    </div>
                </div>
            <?php else: ?>
                <img class="login-btn" src="assets/images/icone_utilisateur.png" onclick="window.location.href='connexion.php'">
            <?php endif; ?>
        </div>
    </header>

    <nav class="sidenav" id="sidenav">
        <span>Menu</span>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../elevage-welsh.php">L'élevage Welsh/SLF</a></li>
            <li><a href="../methode-blondeau.php">La méthode Blondeau</a></li>
            <li><a href="../tarifs.php">Tarifs</a></li>
            <li><a href="../galerie.php">Galerie</a></li>
            <li><a href="../actualites.php">Actualités</a></li>
            <li><a href="../concours.php">Concours</a></li>
            <li><a href="../contact.php">Contact</a></li>
        </ul>
        <div class="closebtn" onclick="closeNav()">&times;</div>
    </nav>

    <div class="admin-links">
        <a href="admin_galerie.php" class="admin-link">Galerie</a>
        <a href="admin_tarifs.php" class="admin-link">Tarifs</a>
    </div>

    <div class="container">
        <h1>Administration des Actualités</h1>
        <!-- Formulaire d'ajout d'actualité -->
        <div class="form-container">
            <h2>Ajouter une actualité</h2>
            <form action="ajouter_actualite.php" method="POST" enctype="multipart/form-data">
                <label for="titre">Titre :</label><br>
                <input type="text" id="titre" name="titre" required><br><br>

                <label for="type">Type :</label><br>
                <select id="type" name="type" required>
                    <option value="concours">Concours</option>
                    <option value="fete">Fête</option>
                    <option value="stage">Stage</option>
                    <option value="camping">Camping</option>
                    <option value="autre">Autre</option>
                </select><br><br>

                <label for="date_debut">Date de début :</label><br>
                <input type="date" id="date_debut" name="date_debut" required><br><br>

                <label for="date_fin">Date de fin :</label><br>
                <input type="date" id="date_fin" name="date_fin" required><br><br>

                <label for="contenu">Contenu :</label><br>
                <textarea id="contenu" name="contenu" rows="4" required></textarea><br><br>

                <label for="photo">Sélectionner une photo :</label><br>
                <input type="file" id="photo" name="photo" accept="image/*"><br><br>

                <input type="submit" value="Ajouter">
            </form>
        </div>


        <!-- Dernière actualité ajoutée -->
        <div class="last-news">
            <h2>Dernière actualité ajoutée</h2>
            <?php if ($result_last): ?>
                <div class='card'>
                    <h3><?php echo htmlspecialchars($result_last["titre"]); ?></h3>
                    <p><?php echo htmlspecialchars($result_last["contenu"]); ?></p>
                    <?php if ($result_last["photo_path"]): ?>
                        <img src="<?php echo htmlspecialchars($result_last["photo_path"]); ?>" alt="Image de l'actualité" style="max-width: 100%;">
                    <?php endif; ?>
                    <p><strong>Date de début:</strong> <?php echo formatDate($result_last["date_debut"]); ?></p>
                    <p><strong>Date de fin:</strong> <?php echo formatDate($result_last["date_fin"]); ?></p>
                </div>
            <?php else: ?>
                <p>Aucune actualité ajoutée pour le moment.</p>
            <?php endif; ?>
        </div>

        <!-- Liste paginée des actualités -->
        <div class="news-list">
            <h2>Liste des actualités</h2>
            <?php if ($result): ?>
                <?php foreach ($result as $row): ?>
                    <div class='card'>
                        <h3><?php echo htmlspecialchars($row["titre"]); ?></h3>
                        <?php if ($row["photo_path"]): ?>
                            <img src="<?php echo htmlspecialchars($row["photo_path"]); ?>" alt="Image de l'actualité" style="max-width: 100%;">
                        <?php endif; ?>
                        <p><?php echo htmlspecialchars($row["contenu"]); ?></p>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($row["type"]); ?></p>
                        <p><strong>Date de début:</strong> <?php echo formatDate($row["date_debut"]); ?></p>
                        <p><strong>Date de fin:</strong> <?php echo formatDate($row["date_fin"]); ?></p>
                        <p><a href="modifier_actualite.php?id=<?php echo $row["id"]; ?>">Modifier</a> | <a href="supprimer_actualite.php?id=<?php echo $row["id"]; ?>">Supprimer</a></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
            <p>Aucune actualité trouvée.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_actualites > $actualites_par_page): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">Page précédente</a>
                <?php else: ?>
                    <span class="disabled">Page précédente</span>
                <?php endif; ?>

                <?php if ($page * $actualites_par_page < $total_actualites): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Page suivante</a>
                <?php else: ?>
                    <span class="disabled">Page suivante</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Centre Equestre du Val d'Arré. Tous droits réservés.</p>
            <p><a href="assets/fichiers/reglement_interieur.pdf" download="Reglement_Interieur_Ecurie">Télécharger le règlement intérieur</a></p>
        </div>
    </footer>
    <script src="../assets/js/script.js"></script>
</body>
</html>
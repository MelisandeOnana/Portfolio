<?php
include 'config/config.php'; // Inclure le fichier de configuration

// Définir la locale en français
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Récupérer les compétences depuis la base de données
$sql = "SELECT * FROM technologie";
$result = $conn->query($sql);
?>
<section id="skills">
    <div class="section-header">
        <h2>Mes apprentissages</h2>
        <p>Découvrez mes compétences et certifications.</p>
    </div>
    <div class="skills-carousel">
    <div class="skills-grid">
        <?php
     if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="skill-card">';
                echo '<div class="skill-header">';
                    // Ajouter l'icône pour chaque langage
                    switch ($row["nom"]) {
                        case 'HTML':
                            echo '<i class="fab fa-html5"></i>';
                            break;
                        case 'CSS':
                            echo '<i class="fab fa-css3-alt"></i>';
                            break;
                        case 'PHP':
                            echo '<i class="fab fa-php"></i>';
                            break;
                        case 'JavaScript':
                            echo '<i class="fab fa-js"></i>';
                            break;
                        case 'Laravel':
                            echo '<i class="fab fa-laravel"></i>';
                            break;
                        case 'Symfony':
                            echo '<i class="fab fa-symfony"></i>';
                            break;
                        case 'React':
                            echo '<i class="fab fa-react"></i>';
                            break;
                        case 'Git':
                            echo '<i class="fab fa-git"></i>';
                            break;
                        case 'GitHub':
                            echo '<i class="fab fa-github"></i>';
                            break;
                        case 'SQL':
                            echo '<i class="fas fa-database"></i>';
                            break;
                        
                        default:
                            echo '<i class="fas fa-code"></i>'; // Icône par défaut
                            break;
                    }
                    echo '<h3>' . $row["nom"] . '</h3>';
                    $dateDebut = new DateTime($row['date_debut']);
                    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM yyyy');
                    echo '<div class="date">' . htmlspecialchars(ucfirst($formatter->format($dateDebut))) . '</div>';
                    echo '<div class="skill-description">' . $row["description"] . '</div>';
                echo '</div>';
                if (in_array($row["nom"], ['HTML', 'CSS', 'PHP'])) {
                    echo '<a href="#" class="btn">Certification en cours</a>';
                } 
            echo '</div>';
        }
    } else {
        echo "<p>Aucune compétence enregistrée.</p>";
    }
        ?>
    </div>
    <!-- Conteneur pour les dots -->
    <div class="dots-container">
        <?php
        for ($i = 0; $i < ceil($result->num_rows / 3); $i++) {
            echo '<span class="dot" data-index="' . $i . '"></span>';
        }
        ?>
    </div>
</div>
</section>
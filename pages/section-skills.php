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
$sql = "SELECT titre, date_debut, logo, description, pdf_certification FROM apprentissages";
$result = $conn->query($sql);

// Créer un formateur de date pour afficher les mois en français
$dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Paris', IntlDateFormatter::GREGORIAN, 'MMMM yyyy');
?>
<section id="skills">
    <div class="section-header">
        <h2>Apprentissages</h2>
        <p>Voici quelques-unes de mes compétences techniques.</p>
    </div>
    <div class="skills-carousel">
        <?php
        if ($result->num_rows > 0) {
            // Afficher chaque compétence
            while($row = $result->fetch_assoc()) {
                $dateDebut = strtotime($row["date_debut"]);
                echo '<div class="skill-card">';
                echo '<div class="skill-logo"><i class="' . $row["logo"] . '"></i></div>';
                echo '<h3 class="skill-title">' . $row["titre"] . '</h3>';
                echo '<p>' . $row["description"] . '</p>';
                echo '<p class="date">Depuis : ' . $dateFormatter->format($dateDebut) . '</p>';
                if ($row["pdf_certification"] !== NULL) {
                    echo '<a href="' . $row["pdf_certification"] . '" target="_blank" class="btn">Voir la certification</a>';
                } else if (in_array($row["titre"], ['HTML', 'CSS', 'PHP'])) {
                    echo '<button class="btn btn-secondary" disabled>Certification en cours</button>';
                }
                echo '</div>';
            }
        } else {
            echo "0 résultats";
        }
        $conn->close();
        ?>
    </div>
</section>
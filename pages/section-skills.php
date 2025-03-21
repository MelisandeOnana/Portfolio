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
$sql = "SELECT * FROM technologies ORDER BY date_debut DESC";
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
                        echo '<i class="' . $row["logo"] . '"></i>';
                        echo '<h3>' . $row["titre"] . '</h3>';
                    echo '</div>';
                    $mois = [
                        'January' => 'Janvier',
                        'February' => 'Février',
                        'March' => 'Mars',
                        'April' => 'Avril',
                        'May' => 'Mai',
                        'June' => 'Juin',
                        'July' => 'Juillet',
                        'August' => 'Août',
                        'September' => 'Septembre',
                        'October' => 'Octobre',
                        'November' => 'Novembre',
                        'December' => 'Décembre'
                    ];
                    
                    $date = new DateTime($row["date_debut"]);
                    $moisFrancais = $mois[$date->format('F')];
                    echo '<p class="date">Depuis : ' . $moisFrancais . ' ' . $date->format('Y') . '</p>';
                    echo '<p>' . $row["description"] . '</p>';
                    if (in_array($row["titre"], ['HTML', 'CSS', 'PHP'])) {
                        echo '<a href="#" class="btn">Certification en cours</a>';
                    } elseif ($row["pdf_certification"] !== NULL) {
                        echo '<a href="' . $row["pdf_certification"] . '" target="_blank" class="btn">Voir la certification</a>';
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
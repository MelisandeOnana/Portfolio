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
        <h2>Mes Apprentissages</h2>
        <p>Découvrez mes compétences et certifications.</p>
    </div>
    <div class="skills-carousel">
        <button class="prev">&#10094;</button>
        <div class="skills-grid">
        <?php 
            $moisFrancais = [
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
            if ($result->num_rows > 0) {
                $index = 0;
                while($row = $result->fetch_assoc()) {
                    echo '<div class="skill-card" data-index="' . floor($index / 3) . '">';
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
                        $moisDebut = $moisFrancais[$dateDebut->format('F')];
                        echo '<div class="date">' . htmlspecialchars(ucfirst($moisDebut . ' ' . $dateDebut->format('Y'))) . '</div>';
                        echo '<div class="skill-description">' . $row["description"] . '</div>';
                    echo '</div>'; 
                    if (isset($row['certification_pdf']) && !empty($row['certification_pdf'])) {
                        $certifications = explode(', ', $row['certification_pdf']);
                        foreach ($certifications as $certification) {
                            if (!empty($certification)) {
                                if ($row['statut'] === 'en_cours') {
                                    echo '<p>Certification en cours</p>';
                                } else {
                                    echo '<p><a href="assets/' . htmlspecialchars($certification) . '" target="_blank">Voir la certification</a></p>';
                                }
                            }
                        }
                    }
                    echo '</div>';
                    $index++;
                }
            } else {
                echo "<p>Aucune compétence enregistrée.</p>";
            }
            ?>
        </div>
        <button class="next">&#10095;</button>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    let currentIndex = 0;
    const skills = document.querySelectorAll('.skill-card');
    const totalSkills = skills.length;
    const skillsPerPage = 4; // Nombre de compétences par page

    function showSkills(index) {
        skills.forEach((skill, i) => {
            skill.style.display = (i >= index * skillsPerPage && i < (index + 1) * skillsPerPage) ? 'block' : 'none';
        });
    }

    document.querySelector('.prev').addEventListener('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            showSkills(currentIndex);
        }
    });

    document.querySelector('.next').addEventListener('click', function() {
        if ((currentIndex + 1) * skillsPerPage < totalSkills) {
            currentIndex++;
            showSkills(currentIndex);
        }
    });

    // Afficher les premières compétences au chargement
    showSkills(currentIndex);
});
</script>
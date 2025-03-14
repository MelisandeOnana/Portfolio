<?php
session_start(); // Démarrer la session
include 'config/config.php'; // Inclure le fichier de configuration

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

<section id="projects">
    <div class="section-header">
        <h2>Réalisations</h2>
        <p>Voici quelques-uns de mes projets récents.</p>
    </div>
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
                </div>
                <div class="btn-container">
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
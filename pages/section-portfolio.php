<?php
include 'config/config.php';

// Utiliser la connexion PDO pour exécuter les requêtes
try {
    // Récupérer uniquement les langages de programmation utilisés dans les projets (sans CSS)
    $techQuery = "SELECT DISTINCT t.nom FROM technologie t 
                  JOIN projet_technologie pt ON t.id_technologie = pt.id_technologie 
                  WHERE t.nom NOT IN ('CSS') ORDER BY t.nom";
    $techResult = $pdo->query($techQuery);

    // Filtrage par technologie
    $filter = isset($_GET['technologie']) ? $_GET['technologie'] : '';

    // Requête pour récupérer les projets
    $sql = "SELECT p.id_projet, p.titre, p.description, p.date_debut, p.date_fin, p.lien, p.image,
                   GROUP_CONCAT(t.nom SEPARATOR ', ') AS technologies
            FROM projet p
            LEFT JOIN projet_technologie pt ON p.id_projet = pt.id_projet
            LEFT JOIN technologie t ON pt.id_technologie = t.id_technologie";

    if (!empty($filter)) {
        $sql .= " WHERE t.nom = :filter OR t.nom = 'CSS'";
    }

    $sql .= " GROUP BY p.id_projet";
    $stmt = $pdo->prepare($sql);
    if (!empty($filter)) {
        $stmt->bindParam(':filter', $filter);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<section id="projects">
    <div class="section-header">
        <h2>Mes Réalisations</h2>
        <p>Découvrez mes projets.</p>
    </div>
    
    <div class="filter-container">
        <form method="GET" class="filter-form">
            <button type="submit" name="technologie" value="" class="filter-button <?php echo $filter == '' ? 'selected' : ''; ?>">Toutes</button>
            <?php foreach ($techResult as $tech) { ?>
                <button type="submit" name="technologie" value="<?php echo htmlspecialchars($tech['nom']); ?>" class="filter-button <?php echo $filter == $tech['nom'] ? 'selected' : ''; ?>">
                    <?php echo htmlspecialchars($tech['nom']); ?>
                </button>
            <?php } ?>
        </form>
    </div>

    <div class="projects-grid">
        <?php foreach ($result as $row) { ?>
            <div class="project">
                <?php if (!empty($row['image'])) { ?>
                    <img src="assets/<?php echo htmlspecialchars($row['image']); ?>" alt="Image du projet">
                <?php } ?>
                <h2><?php echo htmlspecialchars($row['titre']); ?></h2>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p><strong>Technologies :</strong> <?php echo htmlspecialchars($row['technologies']); ?></p>
                <?php if (!empty($row['lien'])) { ?>
                    <p><a href="<?php echo htmlspecialchars($row['lien']); ?>" target="_blank">Voir le projet</a></p>
                <?php } else { ?>
                    <p><a href="galerie.php" target="_blank">Voir la galerie</a></p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>
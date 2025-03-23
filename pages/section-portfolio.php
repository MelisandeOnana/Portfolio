<?php
include 'config/config.php';

try {
    $techQuery = "SELECT DISTINCT t.nom FROM technologie t 
                  JOIN projet_technologie pt ON t.id_technologie = pt.id_technologie 
                  WHERE t.nom NOT IN ('CSS') ORDER BY t.nom";
    $techResult = $pdo->query($techQuery);

    $filter = isset($_GET['technologie']) ? $_GET['technologie'] : '';

    $sql = "SELECT p.id_projet, p.titre, p.description, p.date_debut, p.date_fin, p.lien, p.image,
               GROUP_CONCAT(DISTINCT t.nom ORDER BY t.nom SEPARATOR ', ') AS technologies
        FROM projet p
        LEFT JOIN projet_technologie pt ON p.id_projet = pt.id_projet
        LEFT JOIN technologie t ON pt.id_technologie = t.id_technologie";

    if (!empty($filter)) {
        $sql .= " WHERE p.id_projet IN (
                    SELECT pt.id_projet
                    FROM projet_technologie pt
                    LEFT JOIN technologie t ON pt.id_technologie = t.id_technologie
                    WHERE t.nom = :filter
                  )";
    }

    $sql .= " GROUP BY p.id_projet ORDER BY p.date_debut DESC";
    $stmt = $pdo->prepare($sql);
    if (!empty($filter)) {
        $stmt->bindParam(':filter', $filter);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les images des projets
    $imageStmt = $pdo->prepare("SELECT image_path FROM projet_images WHERE id_projet = :id_projet");

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
        <form method="GET" class="filter-form" action="#projects">
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
                <p><strong>Dates :</strong> 
                    <?php 
                    $dateDebut = new DateTime($row['date_debut']);
                    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM yyyy');
                    echo htmlspecialchars(ucfirst($formatter->format($dateDebut))); 
                    ?>  
                    <?php 
                    if (!empty($row['date_fin'])) {
                        $dateFin = new DateTime($row['date_fin']);
                        echo ' - ' . htmlspecialchars(ucfirst($formatter->format($dateFin)));
                    }
                    ?>
                </p>
                <?php if (!empty($row['lien'])) { ?>
                    <p><a href="<?php echo htmlspecialchars($row['lien']); ?>" target="_blank">Voir le projet</a></p>
                <?php } else { ?>
                    <p><a href="galerie.php?id_projet=<?php echo $row['id_projet']; ?>" target="_blank">Voir la galerie</a></p>
                <?php } ?>
                
            </div>
        <?php } ?>
    </div>
    
</section>
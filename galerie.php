<?php

include 'config/config.php';

$id_projet = isset($_GET['id_projet']) ? (int)$_GET['id_projet'] : 0;

if ($id_projet > 0) {
    try {
        $stmt = $pdo->prepare("SELECT image_path FROM projet_images WHERE id_projet = :id_projet");
        $stmt->bindParam(':id_projet', $id_projet);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des images : " . $e->getMessage());
    }
} else {
    die("Projet non spécifié.");
}
?>
<div class="container">
    <section id="gallery">
        <div class="section-header">
            <h2>Galerie du Projet</h2>
        </div>
        <?php if (!empty($images)) { ?>
            <div id="projectCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $image) { ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="assets/<?php echo htmlspecialchars($image['image_path']); ?>" class="d-block w-100" alt="Image du projet">
                        </div>
                    <?php } ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#projectCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        <?php } else { ?>
            <p>Aucune image disponible pour ce projet.</p>
        <?php } ?>
    </section>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/main.js"></script>
<?php include 'includes/footer.php'; ?>
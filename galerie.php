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
        <div class="gallery-grid">
            <?php foreach ($images as $image) { ?>
                <div class="gallery-item">
                    <img src="assets/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Image du projet">
                </div>
            <?php } ?>
        </div>
    </section>
    
</div>
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/main.js"></script>
<?php include 'includes/footer.php'; ?>
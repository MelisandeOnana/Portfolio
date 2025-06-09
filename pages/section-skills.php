<?php
// Inclusion de la configuration de la base de données
include "config/config.php";
$sql = "SELECT * FROM technologie";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$technos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regroupement des technos par catégorie
$categories = [
    'Langages' => ['PHP', 'HTML', 'CSS'],
    'Frameworks' => ['Laravel', 'Symfony'],
    'Bibliothèques' => ['React'],
    'Outils' => ['Git', 'GitHub', 'Figma', 'Visual Studio Code', 'Visual Code', 'Pix']
];

$grouped = [];
foreach ($categories as $cat => $noms) {
    $grouped[$cat] = array_filter($technos, fn($t) => in_array($t['nom'], $noms));
}
?>

<section id="skills" class="py-5" style="background-color: #fdf6ff;">
    <div class="container">
        <h2 class="text-center mb-4">📁 Mes Apprentissages par Catégorie</h2>

        <ul class="nav nav-tabs justify-content-center mb-4" id="techTabs" role="tablist">
            <?php $first = true; foreach ($grouped as $cat => $list): ?>
                <li class="nav-item" role="presentation">
                    <button style="color:#FD4E5D;" class="nav-link <?= $first ? 'active' : '' ?>" id="<?= $cat ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $cat ?>" type="button" role="tab">
                        <?= $cat ?>
                    </button>
                </li>
            <?php $first = false; endforeach; ?>
        </ul>

        <div class="tab-content">
            <?php $first = true; foreach ($grouped as $cat => $list): ?>
                <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="<?= $cat ?>" role="tabpanel">
                    <div class="row g-4">
                        <?php foreach ($list as $tech): ?>
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0" style="border-radius: 1rem; background-color: #fff0f9;">
                                    <div class="card-body">
                                        <h5 class="card-title" style="color: #FD4E5D;"><?= htmlspecialchars($tech['nom']) ?></h5>
                                        <p class="card-text text-muted"><?= htmlspecialchars($tech['description']) ?></p>
                                        <p class="card-text small text-secondary"><?= date('m/Y', strtotime($tech['date_debut'])) ?></p>
                                        <?php
                                        if (!empty($tech['certification_pdf'])) {
                                            $pdfs = json_decode($tech['certification_pdf'], true);
                                            if (is_array($pdfs)) {
                                                foreach ($pdfs as $i => $pdf) {
                                                    echo '<a href="'.htmlspecialchars($pdf).'" target="_blank" class="btn btn-sm btn-outline-danger me-1">📄 Certification '.($i+1).'</a>';
                                                }
                                            } else {
                                                // Cas d'un seul PDF (ancienne donnée)
                                                echo '<a href="'.htmlspecialchars($tech['certification_pdf']).'" target="_blank" class="btn btn-sm btn-outline-danger">📄 Certification</a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php $first = false; endforeach; ?>
        </div>
    </div>
</section>

<?php
// Inclusion de la configuration de la base de données
include "config/config.php";
$sql = "SELECT * FROM technologie";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$technos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section id="skills">
    <div class="container">
        <h2>Mes compétences techniques</h2>
        <div class="skills-grid">
            <?php foreach ($technos as $tech): ?>
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <div class="skill-title"><?= htmlspecialchars($tech['nom']) ?></div>
                            <div class="skill-desc"><?= htmlspecialchars($tech['description']) ?></div>
                        </div>
                        <div class="flip-card-back" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <div style="font-size:1.2rem;font-weight:bold;margin-bottom:0.5rem;">Début :</div>
                            <div class="skill-date" style="position:static;top:auto;right:auto;margin-bottom:1rem;">
                                <?= date('m/Y', strtotime($tech['date_debut'])) ?>
                            </div>
                            <?php if (!empty($tech['certification_pdf'])): ?>
                                <div class="skill-cert" style="margin-top:0;">
                                    <?php
                                    $pdfs = json_decode($tech['certification_pdf'], true);
                                    if (is_array($pdfs)) {
                                        foreach ($pdfs as $i => $pdf) {
                                            echo '<a href="'.htmlspecialchars($pdf).'" target="_blank">📄 Certif '.($i+1).'</a>';
                                        }
                                    } else {
                                        echo '<a href="'.htmlspecialchars($tech['certification_pdf']).'" target="_blank">📄 Certif</a>';
                                    }
                                    ?>
                                </div>
                            <?php else: ?>
                                <div style="color:#fff;opacity:0.7;">Aucune certification</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
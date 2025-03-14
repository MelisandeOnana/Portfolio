<!-- filepath: c:\wamp64\www\portfolio\index.php -->
<?php include 'includes/header.php'; ?>
<div class="container">
    <section id="home">
        <div class="circle"></div> <!-- Ajout du cercle animé -->
        <p>Passionnée par le développement web, digitale et les technologies numériques. Explorez mes réalisations et découvrez mon parcours d'apprentissage.</p>
        <a href="#projects" class="btn">En savoir plus</a>
        <div class="btn-container">
            <a href="assets/pdf/CV_onana_melisande.pdf" class="btn" target="_blank">Mon CV</a>
            <a href="https://github.com/MelisandeOnana" class="btn" target="_blank">GitHub</a>
            <a href="https://www.linkedin.com/in/m%C3%A9lisande-onana-ngono-7576512ba?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3B8vu%2F5X1RRkik2bIAfoefFA%3D%3D" class="btn" target="_blank">LinkedIn</a>
        </div>
    </section>
    <?php include 'pages/section-portfolio.php'; ?>
    <?php include 'pages/section-skills.php'; ?>
    <?php include 'pages/section-technology_watch.php'; ?>
    <?php include 'pages/section-contact.php'; ?>
</div>
<script src="assets/js/main.js"></script>
<?php include 'includes/footer.php'; ?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-blur">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex flex-column align-items-center" href="<?php echo isset($_SESSION['username']) ? 'admin/tableau_de_bord.php' : 'connexion.php'; ?>">
        <span style="line-height:1;">Mélisande</span>
        <span style="line-height:1;">Onana Ngono</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link nav-anim active" aria-current="page" href="#home">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-anim" href="#projects">Mes Réalisations</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-anim" href="#skills">Mes Apprentissages</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-anim" href="#technology_watch">Veille technologique</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-anim" href="#contact">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Bootstrap JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
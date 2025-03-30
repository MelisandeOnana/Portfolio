<?php
// filepath: c:\wamp64\www\Portfolio-php\projects\centre_equestre\administration\admin_dashboard.php
session_start();

// Vérification si l'utilisateur est administrateur
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: ../connexion.php');
    exit;
}

// Inclure la connexion à la base de données
include('../config/config.php');

// Récupérer les statistiques
$stats = [
    'chevaux' => $pdo->query("SELECT COUNT(*) FROM chevaux")->fetchColumn(),
    'actualites' => $pdo->query("SELECT COUNT(*) FROM actualites")->fetchColumn(),
    'images' => count(glob('../administration/uploads/*.{jpg,jpeg,png,gif}', GLOB_BRACE)),
    'tarifs' => $pdo->query("SELECT COUNT(*) FROM tarifs")->fetchColumn(),
    'equipe' => $pdo->query("SELECT COUNT(*) FROM equipe")->fetchColumn(),
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center">Tableau de Bord - Administration</h1>
    <div class="text-end mb-3">
        <a href="../deconnexion.php" class="btn btn-danger">Déconnexion</a>
    </div>
    <div class="row my-4">
        <!-- Statistiques -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Chevaux</h5>
                    <p class="card-text"><?= $stats['chevaux'] ?> enregistrés</p>
                    <a href="admin_chevaux.php" class="btn btn-primary">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Actualités</h5>
                    <p class="card-text"><?= $stats['actualites'] ?> publiées</p>
                    <a href="admin_actualites.php" class="btn btn-primary">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Galerie</h5>
                    <p class="card-text"><?= $stats['images'] ?> images</p>
                    <a href="admin_galerie.php" class="btn btn-primary">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Tarifs</h5>
                    <p class="card-text"><?= $stats['tarifs'] ?> tarifs</p>
                    <a href="admin_tarifs.php" class="btn btn-primary">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Membres</h5>
                    <p class="card-text"><?= $stats['equipe'] ?> membres</p>
                    <a href="admin_membres.php" class="btn btn-primary">Gérer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
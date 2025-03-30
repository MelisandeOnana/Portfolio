<?php
session_start();
// Vérification si l'utilisateur est connecté en tant qu'administrateur
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'Administrateur';
// Inclure le fichier de configuration de la base de données
require_once 'config/config.php';

// Connexion à la base de données
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Requête pour récupérer les tarifs pertinents
$sql = "SELECT nom_tarif, categorie, prix, description FROM tarifs WHERE categorie IN ('Remise en confiance', 'Débourrage', 'Pension')";
$result = $pdo->query($sql);

$tarifs = [];
if ($result->rowCount() > 0) {
    while ($row = $result->fetch()) {
        $tarifs[] = $row;
    }
} else {
    echo "Aucun tarif trouvé.";
}
$pdo = null; // Fermer la connexion à la base de données
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Méthode Blondeau</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Open+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='assets/css/Methode_Blondeau.css' rel='stylesheet'/>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <main>
        <h1 style="text-align: center; color: #360C0C; margin-top: 20px;">La Méthode Blondeau</h1>
        <blockquote>
            "Simplifier la vie des hommes, la rendre agréable aux chevaux"
        </blockquote>

        <h2>Découverte de la Méthode Blondeau</h2>
        <p>J'ai découvert la Méthode Blondeau en formation monitorat et tout de suite j'ai apprécié cette façon de travailler les chevaux à pied, près d'eux, en les rassurant de la voix.</p>
        <p>Une fois installée, en 2010, je suis partie à l'école Blondeau de Saumur où j'ai effectué 10 semaines de formation.</p>
        <p>Diplômée BFEE1, je peux vous faire passer vos savoirs éthologiques, débourrer vos chevaux et remettre en confiance ceux qui ont eu un passé compliqué.</p>

        <h2>La Méthode en Pratique</h2>
        <p>Contrairement à la méthode traditionnelle il y a une mise en confiance au boxe, le montoir se fait au boxe et dès le 1er jour le cheval est monté, le 4ème jour il part en balade. On ne développe pas l'instinct de fuite mais on demande la mise en avant avec la baguette (initiée au boxe), principe inspiré de F. Baucher (1796-1873). La méthode s'inspire également de F. Baucher sur le fait que le dresseur va chercher les résistances du cheval. L'important est que le dresseur soit cohérent dans ses demandes pour que le cheval se réfère à lui.</p>
        <p>D'Étienne Beudant (1863-1949) nous retiendrons pour le travail du cheval :</p>
        <blockquote>
            Il conseille d'éviter la lutte, qui amène toujours des séquelles dans l'esprit du cheval et des tares sur son corps et dans son organisme. Il faut, selon lui, éviter d'ennuyer l'animal, ne pas agir inutilement. Face à un cheval qui n'obéit pas, Beudant assure que le cavalier doit toujours s'en prendre à lui-même et que la majorité des fautes proviennent du cavalier, qui s'attaque aux effets plutôt que de chercher la cause des réticences de son cheval.
        </blockquote>
        <blockquote>
            "Demander souvent, se contenter de peu, récompenser beaucoup" - Faverot de Kerbrech.
        </blockquote>

        <h2>Principes de Nicolas Blondeau</h2>
        <p>La méthode est la même que ce soit pour un débourrage ou une remise en confiance que ce soit à pied, à cheval ou pour monter dans un van.</p>
        <p>Nicolas Blondeau s'est inspiré de F. Baucher mais également du Général l'Hotte en gardant à l'esprit dans le travail du cheval :</p>
        <blockquote>
            "Calme, en avant et droit" et "L'art ne s'apprend pas dans les livres, qui n'instruisent guère que ceux qui savent déjà"
        </blockquote>
        <p>Un principe cher à Nicolas Blondeau :</p>
        <blockquote>
            "Ne demander que ce que le dresseur ou cavalier est capable d'obtenir"
        </blockquote>

        <h2>Présentation des Chevaux</h2>
        <p>Possibilité de présenter vos chevaux aux modèles et allures ou de vous aider à les travailler pour les présenter.</p>
        
        <div class="images">
            <img src="assets/images/image_blondeau3.JPG" alt="Description de l'image 1">
            <img src="assets/images/image_blondeau2.JPG" alt="Description de l'image 2">
        </div>

        <h2>Tarifs de Débourrage et Remise en Confiance</h2>
        <ul>
        <?php
        foreach ($tarifs as $tarif) {
            if (in_array($tarif['categorie'], ['Remise en confiance', 'Débourrage'])) {
                echo "<li><strong>{$tarif['nom_tarif']}</strong>: {$tarif['prix']} €";
                if (!empty($tarif['description'])) {
                    echo " - {$tarif['description']}";
                }
                echo "</li>";
            }
        }
        ?>
        </ul>

        <h2>Tarifs de Pension</h2>
        <ul>
        <?php
        foreach ($tarifs as $tarif) {
            if ($tarif['categorie'] == 'Pension') {
                echo "<li><strong>{$tarif['nom_tarif']}</strong>: {$tarif['prix']} €";
                if (!empty($tarif['description'])) {
                    echo " - {$tarif['description']}";
                }
                echo "</li>";
            }
        }
        ?>
        </ul>
        <p>Possibilité de présenter vos poulains de 2 et 3 ans aux modèles et allures</p>
        <p>Règlements par chèque à l'ordre de l'EARL BURDIN</p>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>

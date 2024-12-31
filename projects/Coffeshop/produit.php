<?php
include 'db_connection.php';
include 'functions.php';

// Récupération de l'identifiant du produit à partir de l'URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Appele la fonction pour obtenir les détails du produit en utilisant la connexion et l'identifiant
$result = getProductDetails($conn, $id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Produit</title>
    <style>
    /* Styles de base pour un style magasin de café */
body {
    font-family: 'Georgia', serif;
    background-color: #f5f5dc; /* Beige clair pour un ton chaleureux */
    color: #4b3832; /* Brun foncé pour les textes */
    margin: 0;
    padding: 0;
}

h1, h2 {
    color: #3b2f2f; /* Brun café foncé pour les titres */
}

a {
    color: #6f4e37; /* Brun café pour les liens */
    text-decoration: none;
}

a:hover {
    color: #3b2f2f; /* Brun foncé au survol */
}

/* En-tête et navigation */
header {
    background-color: #6f4e37; /* Brun café pour l'en-tête */
    color: #fff;
    padding: 1rem 0;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 2rem;
}

/* Section produit */
.product-details {
    padding: 2rem;
    max-width: 800px;
    margin: 2rem auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Ombre douce pour un effet de carte */
    border: 2px solid #d1a384; /* Bordure brun clair pour rappeler les grains de café */
}

.product-details h1 {
    margin-top: 0;
    font-size: 1.8rem;
    color: #3b2f2f; /* Titre en brun foncé */
}

.product-details p {
    margin: 0.5rem 0;
    font-size: 1.1rem;
    line-height: 1.6;
    color: #4b3832;
}

/* Boutons */
.buttons {
    margin-top: 2rem;
    text-align: center;
}

.buttons a, .buttons form {
    display: inline-block;
    margin-right: 1rem;
}

.buttons a, .buttons button {
    background-color: #6f4e37; /* Brun café */
    color: #fff;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.buttons a:hover, .buttons button:hover {
    background-color: #3b2f2f; /* Brun café plus foncé au survol */
}

/* Formulaire de suppression */
form button {
    background-color: #b22222; /* Rouge pour le bouton supprimer */
}

form button:hover {
    background-color: #8b0000; /* Rouge foncé au survol */
}

    </style>
</head>
<body>
    <header>
        <h1>Fiche Produit</h1>
    </header>
    <main class="product-details">
        <?php
        displayProductDetails($result);
        closeConnection($conn);
        ?>
        <div class="buttons">
            <a href="index.php">Retour</a>
            <a href="modifier_produit.php?id=<?php echo htmlspecialchars($id); ?>">Modifier</a>
            <form method="POST" action="supprimer_produit.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <button type="submit">Supprimer</button>
            </form>
        </div>
    </main>
</body>
</html>
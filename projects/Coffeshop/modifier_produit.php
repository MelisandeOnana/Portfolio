<?php
// Fichier de connexion à la base de données
include 'db_connection.php';

// Vérifie si la connexion à la base de données a échoué
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error); // Arrêt de l'exécution et affichage un message d'erreur
}

// Vérifie si un identifiant de produit a été fourni dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Récupération de l'identifiant du produit

    // Requête pour récupérer les données du produit en fonction de l'identifiant
    $sql = "SELECT * FROM produit WHERE id = $id";
    $result = $conn->query($sql); // Exécuter la requête

    // Vérifie si le produit existe dans la base de données
    if ($result->num_rows > 0) {
        $produit = $result->fetch_assoc(); // Récupération des données du produit sous forme de tableau associatif
    } else {
        echo "Produit non trouvé"; // Affichage d'un message si le produit n'existe pas
        exit; 
    }
} else {
    echo "Aucun produit spécifié."; // Affichage d'un message si aucun identifiant n'est fourni
    exit; // Arrêt de l'exécution
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $quantite = isset($_POST['quantite']) ? $_POST['quantite'] : null; // Vérifie si une quantité a été fournie
    $note = isset($_POST['note']) ? $_POST['note'] : null; // Vérifie si une note a été fournie
    $gamme = $_POST['gamme'];
    $promotion = $_POST['promotion'];
    $pays = $_POST['pays'];
    $marque_id = $_POST['marque_id'];
    $categorie_id = $_POST['categorie_id'];

    // Requête pour mettre à jour les données du produit dans la base de données
    $sql = "UPDATE produit SET 
            nom = '$nom', 
            prix = '$prix', 
            quantite = '$quantite', 
            note = '$note', 
            gamme = '$gamme', 
            promotion = '$promotion', 
            pays = '$pays', 
            marque_id = '$marque_id', 
            categorie_id = '$categorie_id' 
            WHERE id = $id";

    // Exécution de la requête de mise à jour
    if ($conn->query($sql) === TRUE) {
        // Si la mise à jour est réussie, redirection vers la page d'index
        header("Location: index.php");
        exit(); // Arrêter l'exécution après la redirection
    } else {
        // Affichage d'un message d'erreur si la mise à jour échoue
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit</title>
    <style>
   /* Styles de base pour un style magasin de café */
body {
    font-family: 'Georgia', serif;
    background-color: #f5f5dc; /* Beige clair pour un ton chaleureux */
    color: #4b3832; /* Brun foncé pour les textes */
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #3b2f2f; /* Brun café foncé pour les titres */
    margin-top: 20px;
    font-size: 2rem;
}

form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff; /* Blanc pour le contraste avec le fond */
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Légère ombre pour un effet de carte */
    border: 2px solid #d1a384; /* Brun clair pour rappeler les grains de café */
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #3b2f2f; /* Brun foncé pour les étiquettes */
}

input, select {
    width: 95%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
    background-color: #f9f9f9; /* Légère touche de beige */
}

input:focus, select:focus {
    outline: none;
    border-color: #6f4e37; /* Brun café lors de la mise au focus */
}

button {
    width: 100%;
    padding: 12px;
    background-color: #6f4e37; /* Couleur brun café */
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #3b2f2f; /* Brun café plus foncé à l'hover */
}

input[type="submit"] {
    background-color: #6f4e37; /* Même style pour le bouton de soumission */
    font-weight: bold;
}

input[type="submit"]:hover {
    background-color: #3b2f2f; /* Changement de couleur au hover */
}


    </style>
</head>
<body>
    <h1>Modifier le produit: <?php echo htmlspecialchars($produit['nom']); ?></h1>
    <form action="modifier_produit.php?id=<?php echo $produit['id']; ?>" method="POST">
        <label for="nom">Nom du produit:</label>
        <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($produit['nom']); ?>" required>

        <label for="prix">Prix:</label>
        <input type="number" step="0.01" name="prix" id="prix" value="<?php echo htmlspecialchars($produit['prix']); ?>" required>

        <label for="quantite">Quantité:</label>
        <input type="number" name="quantite" id="quantite" value="<?php echo htmlspecialchars($produit['quantite']); ?>">

        <label for="note">Note:</label>
        <input type="number" min="0" max="10" name="note" id="note" value="<?php echo htmlspecialchars($produit['note']); ?>">

        <label for="gamme">Gamme:</label>
        <input type="text" name="gamme" id="gamme" value="<?php echo htmlspecialchars($produit['gamme']); ?>" required>

        <label for="promotion">En promotion:</label>
        <select name="promotion" id="promotion">
            <option value="0" <?php if ($produit['promotion'] == 0) echo 'selected'; ?>>Non</option>
            <option value="1" <?php if ($produit['promotion'] == 1) echo 'selected'; ?>>Oui</option>
        </select>

        <label for="pays">Pays d'origine:</label>
        <input type="text" name="pays" id="pays" value="<?php echo htmlspecialchars($produit['pays']); ?>" required>

        <label for="marque_id">Marque:</label>
        <select id="marque_id" name="marque_id">
            <option value="1" <?php if($produit['marque_id'] == "1") echo "selected"; ?>>Café Grand'Mère</option>
            <option value="2" <?php if($produit['marque_id'] == "2") echo "selected"; ?>>Cafés Richard</option>
            <option value="3" <?php if($produit['marque_id'] == "3") echo "selected"; ?>>illy Cafè</option>
            <option value="4" <?php if($produit['marque_id'] == "4") echo "selected"; ?>>LAVAZZA</option>
            <option value="5" <?php if($produit['marque_id'] == "5") echo "selected"; ?>>Pellini</option>
        </select>

        <label for="categorie_id">Catégorie:</label>
        <select id="categorie_id" name="categorie_id">
            <option value="1" <?php if($produit['categorie_id'] == "1") echo "selected"; ?>>Café en grain</option>
            <option value="2" <?php if($produit['categorie_id'] == "2") echo "selected"; ?>>Café moulu</option>
            <option value="3" <?php if($produit['categorie_id'] == "3") echo "selected"; ?>>Capsules</option>
        </select>

        <input type="submit" name="submit" value="Mettre à jour le produit">
    </form>
</body>
</html>

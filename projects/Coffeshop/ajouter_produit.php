
<?php
// Initialisation d'un tableau pour stocker les erreurs
$errors = [];

// Initialisation des variables pour stocker les valeurs des champs de formulaire
$nom = $prix = $quantite = $note = $gamme = $promotion = $pays = $marque_id = $categorie_id = '';

// Requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation des champs

    // Vérification du champ 'nom'
    if (empty($_POST["nom"])) {
        $errors["nom"] = "Le nom est obligatoire."; 
    } else {
        $nom = $_POST["nom"]; 
    }

    // Vérification du champ 'prix'
    if (empty($_POST["prix"]) || !is_numeric($_POST["prix"])) {
        $errors["prix"] = "Le prix est obligatoire et doit être un nombre."; 
    } else {
        $prix = $_POST["prix"]; 
    }

    // Vérification du champ 'quantite'
    if (!empty($_POST["quantite"]) && !is_numeric($_POST["quantite"])) {
        $errors["quantite"] = "La quantité doit être un nombre."; 
    } else {
        $quantite = $_POST["quantite"]; 
    }

    // Vérification du champ 'note'
    if (!empty($_POST["note"]) && (!is_numeric($_POST["note"]) || $_POST["note"] < 1 || $_POST["note"] > 10)) {
        $errors["note"] = "La note doit être un nombre entre 1 et 10."; 
    } else {
        $note = $_POST["note"]; 
    }

    // Vérification du champ 'gamme'
    if (empty($_POST["gamme"])) {
        $errors["gamme"] = "La gamme est obligatoire."; // Ajout d'une erreur si la gamme est vide
    } else {
        $gamme = $_POST["gamme"]; // Assignation de la valeur de la gamme
    }

    // Vérification du champ 'promotion'
    $promotion = isset($_POST["promotion"]) ? 1 : 0; // Assignation de 1 si promotion est sélectionné, sinon 0

    // Vérification du champ 'pays'
    if (empty($_POST["pays"])) {
        $errors["pays"] = "Le pays est obligatoire.";
    } else {
        $pays = $_POST["pays"]; // Assignation de la valeur du pays
    }

    // Vérification du champ 'marque_id'
    if (empty($_POST["marque_id"])) {
        $errors["marque_id"] = "La marque est obligatoire."; 
    } else {
        $marque_id = $_POST["marque_id"]; 
    }

    // Vérification du champ 'categorie_id'
    if (empty($_POST["categorie_id"])) {
        $errors["categorie_id"] = "La catégorie est obligatoire."; 
    } else {
        $categorie_id = $_POST["categorie_id"]; 
    }

    // Si aucune erreur n'est présente, insertion dans la base de données
    if (empty($errors)) {
        include 'db_connection.php'; // Fichier de connexion à la base de données

        // Vérification de la connexion
        if ($conn->connect_error) {
            die("Connexion échouée: " . $conn->connect_error); // Arrêter l'exécution si la connexion échoue
        }

        // Préparation de la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO produit (nom, prix, quantite, note, gamme, promotion, pays, marque_id, categorie_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisssiii", $nom, $prix, $quantite, $note, $gamme, $promotion, $pays, $marque_id, $categorie_id); // Liaison des paramètres

        // Exécution de la requête
        if ($stmt->execute()) {
            // Redirection vers la page index.php après ajout réussi
            header("Location: index.php");
            exit(); // Arrêt l'exécution après la redirection
        } else {
            echo "<p>Erreur lors de l'ajout du produit: " . $stmt->error . "</p>"; // Affichage d'une erreur si l'ajout échoue
        }

        $stmt->close();
        $conn->close();
    } else {
        // Affichage des erreurs pour le débogage
        echo "<pre>";
        print_r($errors);
        echo "</pre>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit</title>
    <style>
       body {
    font-family: 'Georgia', serif;
    background-color: #f4e1d2; /* Light coffee cream */
    color: #3b2f2f; /* Deep coffee brown */
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #4b3832; /* Rich brown */
}

form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff8e7; /* Soft beige background */
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid #a67c52; /* Coffee accent border */
}

label, input, select, span.error {
    display: block;
    margin-bottom: 12px;
}

input, select {
    width: 95%;
    padding: 10px;
    border: 2px solid #d1b38e; /* Light coffee brown */
    border-radius: 5px;
    background-color: #faf4ef; /* Soft cream color */
}

input:focus, select:focus {
    border-color: #8a5a44; /* Darker coffee brown */
    outline: none;
}

button {
    padding: 12px 25px;
    background-color: #6f4e37; /* Coffee brown */
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #3b2f2f; /* Darker brown on hover */
}

span.error {
    color: #d9534f; /* Error message in a red tone */
    font-size: 0.9em;
}

header {
    background-color: #6f4e37;
    padding: 15px;
    text-align: center;
    color: white;
}

main {
    background-color: #f5e4d7; /* Light tan */
    padding: 20px;
}

    </style>
</head>
<body>
    <header>
        <h1>Ajouter un Produit</h1>
    </header>
    <main>
        <section id="add-product">
            <h2>Ajouter un Nouveau Produit</h2>
            <form method="POST" action="">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>">
                <span class="error"><?php echo $errors["nom"] ?? ''; ?></span>

                <label for="prix">Prix:</label>
                <input type="text" id="prix" name="prix" value="<?php echo htmlspecialchars($prix); ?>">
                <span class="error"><?php echo $errors["prix"] ?? ''; ?></span>

                <label for="quantite">Quantité (kg):</label>
                <input type="text" id="quantite" name="quantite" value="<?php echo htmlspecialchars($quantite); ?>">
                <span class="error"><?php echo $errors["quantite"] ?? ''; ?></span>

                <label for="note">Note (1-10):</label>
                <input type="text" id="note" name="note" value="<?php echo htmlspecialchars($note); ?>">
                <span class="error"><?php echo $errors["note"] ?? ''; ?></span>

                <label for="gamme">Gamme:</label>
                <input type="text" id="gamme" name="gamme" value="<?php echo htmlspecialchars($gamme); ?>">
                <span class="error"><?php echo $errors["gamme"] ?? ''; ?></span>

                <label for="promotion">En promotion:</label>
                <select name="promotion" id="promotion">
                    <option value="0" <?php if ($promotion == 0) echo "selected"; ?>>Non</option>
                    <option value="1" <?php if ($promotion == 1) echo "selected"; ?>>Oui</option>
                </select>

                <label for="pays">Pays d'origine:</label>
                <input type="text" id="pays" name="pays" value="<?php echo htmlspecialchars($pays); ?>">
                <span class="error"><?php echo $errors["pays"] ?? ''; ?></span>

                <label for="marque_id">Marque:</label>
                <select id="marque_id" name="marque_id">
                    <option value="1" <?php if($marque_id == "1") echo "selected"; ?>>Café Grand'Mère</option>
                    <option value="2" <?php if($marque_id == "2") echo "selected"; ?>>Cafés Richard</option>
                    <option value="3" <?php if($marque_id == "3") echo "selected"; ?>>illy Cafè</option>
                    <option value="4" <?php if($marque_id == "4") echo "selected"; ?>>LAVAZZA</option>
                    <option value="5" <?php if($marque_id == "5") echo "selected"; ?>>Pellini</option>
                </select>
                <span class="error"><?php echo $errors["marque_id"] ?? ''; ?></span>

                <label for="categorie_id">Catégorie:</label>
                <select id="categorie_id" name="categorie_id">
                    <option value="1" <?php if($categorie_id == "1") echo "selected"; ?>>Café en grain</option>
                    <option value="2" <?php if($categorie_id == "2") echo "selected"; ?>>Café moulu</option>
                    <option value="3" <?php if($categorie_id == "3") echo "selected"; ?>>Capsules</option>
                </select>
                <span class="error"><?php echo $errors["categorie_id"] ?? ''; ?></span>

                <button type="submit">Ajouter le produit</button>
            </form>
        </section>
    </main>
</body>
</html>

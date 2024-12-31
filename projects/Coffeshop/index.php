<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
    <style>
    /* Styles de base */
    body {
        font-family: 'Georgia', serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5dc; /* Beige clair */
        color: #4b3832; /* Brun foncé */
        line-height: 1.6;
    }

    h1, h2 {
        color: #3b2f2f; /* Brun foncé */
    }

    a {
        color: #6f4e37; /* Brun café */
        text-decoration: none;
    }

    a:hover {
        color: #3b2f2f; /* Brun foncé */
    }

    /* En-tête */
    header {
        background-color: #6f4e37; /* Brun café */
        color: #fff;
        padding: 1.5rem 0;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    header h1 {
        margin: 0;
        font-size: 2.5rem;
    }

    /* Section de recherche */
    #search {
        padding: 2rem 1rem;
        background-color: #fff;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    #search h2 {
        margin-bottom: 1rem;
        font-size: 1.75rem;
        color: #3b2f2f;
    }

    #search form {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    #search input[type="text"] {
        padding: 0.7rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 0.5rem;
        width: 300px;
    }

    #search button {
        padding: 0.7rem 1.5rem;
        background-color: #6f4e37; /* Brun café */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #search button:hover {
        background-color: #3b2f2f; /* Brun foncé */
    }

    /* Section d'ajout de produit */
    #add-product {
        padding: 2rem;
        text-align: center;
    }

    #add-product .btn {
        padding: 0.7rem 1.5rem;
        background-color: #6f4e37; /* Brun café */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-size: 1rem;
        text-decoration: none;
    }

    #add-product .btn:hover {
        background-color: #3b2f2f; /* Brun foncé */
    }

    /* Section des produits */
    #products {
        padding: 2rem;
    }

    #products h2 {
        margin-bottom: 1.5rem;
        font-size: 2rem;
        color: #3b2f2f;
        text-align: center;
    }

    #products ul {
        list-style-type: none;
        padding: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    #products li {
        background-color: #fff;
        padding: 1.5rem;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    #products li:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    #products h3 {
        margin-top: 0;
        font-size: 1.25rem;
        color: #6f4e37; /* Brun café */
    }

    #products p {
        margin: 0.5rem 0;
        font-size: 1rem;
    }

    /* Boutons */
    button, .btn {
        font-family: 'Georgia', serif;
    }

    </style>
</head>
<body>
    <header>
        <h1>Liste des Produits</h1>
    </header>
    <main>
        <section id="search">
            <h2>Recherche de Produits</h2>
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Rechercher un produit par nom">
                <button type="submit">Rechercher</button>
            </form>
        </section>
        <section id="add-product">
            <a href="ajouter_produit.php" class="btn">Ajouter un Produit</a>
        </section>
        <section id="products">
            <h2>Nos Produits</h2>
            <ul>
                <?php
                include 'db_connection.php';

                    // Vérifie la connexion
                    if ($conn->connect_error) {
                        die("Connexion échouée: " . $conn->connect_error);
                    }

                    // Récupération du terme de la recherche
                    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

                    // Récupération des produits
                    $sql = "SELECT p.id, p.nom, p.prix, p.note, m.nom AS marque, c.nom AS categorie 
                            FROM produit p
                            JOIN marque m ON p.marque_id = m.id
                            JOIN categorie c ON p.categorie_id = c.id";
                    
                    if ($search) {
                        $sql .= " WHERE p.nom LIKE '%$search%'";
                    }

                    $result = $conn->query($sql);

                    // Affichage des produits
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<li>";
                            echo "<h3><a href='produit.php?id=" . $row["id"] . "'>" . $row["nom"] . "</a></h3>";
                            echo "<p>Prix: " . $row["prix"] . " €</p>";
                            if ($row["note"]) {
                                echo "<p>Note: " . $row["note"] . "/10</p>";
                            }
                            echo "<p>Marque: " . $row["marque"] . "</p>";
                            echo "<p>Catégorie: " . $row["categorie"] . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<p>Aucun produit trouvé.</p>";
                    }

                    // Fermeture la connexion
                    $conn->close();
                ?>
            </ul>
        </section>
    </main>
</body>
</html>

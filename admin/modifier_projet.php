<?php
session_start();
require '../config/config.php'; // Fichier de connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Récupérer l'ID du projet à modifier
$id_projet = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les informations du projet
$sql_projet = "SELECT * FROM projet WHERE id_projet = :id_projet";
$stmt_projet = $pdo->prepare($sql_projet);
$stmt_projet->execute([':id_projet' => $id_projet]);
$projet = $stmt_projet->fetch();

// Récupération des technologies disponibles
$sql_technologies = "SELECT * FROM technologie";
$stmt_technologies = $pdo->query($sql_technologies);
$technologies = $stmt_technologies->fetchAll();

// Récupération des technologies associées au projet
$sql_projet_technologies = "SELECT id_technologie FROM projet_technologie WHERE id_projet = :id_projet";
$stmt_projet_technologies = $pdo->prepare($sql_projet_technologies);
$stmt_projet_technologies->execute([':id_projet' => $id_projet]);
$projet_technologies = $stmt_projet_technologies->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'] ?? NULL;
    $visible = isset($_POST['visible']) ? 1 : 0;
    $technologies_selected = $_POST['technologies'] ?? [];

    // Mise à jour du projet dans la table 'projet'
    $sql_update_projet = "UPDATE projet SET titre = :titre, description = :description, date_debut = :date_debut, date_fin = :date_fin, visible = :visible WHERE id_projet = :id_projet";
    $stmt_update_projet = $pdo->prepare($sql_update_projet);
    $stmt_update_projet->execute([
        ':titre' => $titre,
        ':description' => $description,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
        ':visible' => $visible,
        ':id_projet' => $id_projet
    ]);

    // Suppression des anciennes technologies associées
    $sql_delete_projet_technologies = "DELETE FROM projet_technologie WHERE id_projet = :id_projet";
    $stmt_delete_projet_technologies = $pdo->prepare($sql_delete_projet_technologies);
    $stmt_delete_projet_technologies->execute([':id_projet' => $id_projet]);

    // Insertion des nouvelles technologies associées
    foreach ($technologies_selected as $id_technologie) {
        $sql_insert_projet_technologie = "INSERT INTO projet_technologie (id_projet, id_technologie) VALUES (:id_projet, :id_technologie)";
        $stmt_insert_projet_technologie = $pdo->prepare($sql_insert_projet_technologie);
        $stmt_insert_projet_technologie->execute([
            ':id_projet' => $id_projet,
            ':id_technologie' => $id_technologie
        ]);
    }

    echo "Projet modifié avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un projet</title>
    <link rel="stylesheet" href="../assets/css/projet.css">
</head>
<body>
    <h2>Modifier un projet</h2>
    <form method="post">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($projet['titre'] ?? '') ?>" required><br>

        <label for="description">Description :</label>
        <textarea name="description" required><?= htmlspecialchars($projet['description'] ?? '') ?></textarea><br>

        <label for="date_debut">Date de début :</label>
        <input type="date" name="date_debut" value="<?= htmlspecialchars($projet['date_debut'] ?? '') ?>" required><br>

        <label for="date_fin">Date de fin :</label>
        <input type="date" name="date_fin" value="<?= htmlspecialchars($projet['date_fin'] ?? '') ?>"><br>

        <label for="visible">Visible :</label>
        <input type="checkbox" name="visible" <?= $projet['visible'] ? 'checked' : '' ?>><br>

        <label for="technologies">Technologies :</label>
        <select id="technologies">
            <?php foreach ($technologies as $tech) : ?>
                <option value="<?= $tech['id_technologie'] ?>"><?= htmlspecialchars($tech['nom']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <button type="button" onclick="addTechnology()">Ajouter à la sélection</button><br>

        <label for="selectedTechnologies">Technologies sélectionnées :</label>
        <select id="selectedTechnologies" name="technologies[]" size="5" multiple class="technologies-list">
            <?php foreach ($technologies as $tech) : ?>
                <?php if (in_array($tech['id_technologie'], $projet_technologies)) : ?>
                    <option value="<?= $tech['id_technologie'] ?>" selected><?= htmlspecialchars($tech['nom']) ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select><br>

        <button type="button" onclick="removeTechnology()">Supprimer de la sélection</button><br>

        <a href="gestion_projets.php">Retour</a>
        <button type="submit">Modifier</button>
    </form>

    <script>
        // Fonction pour ajouter une technologie à la liste des sélectionnées
        function addTechnology() {
            var select = document.getElementById('technologies');
            var selected = select.options[select.selectedIndex];
            var list = document.getElementById('selectedTechnologies');

            // Empêche l'ajout si déjà sélectionné
            if (!Array.from(list.options).some(option => option.value === selected.value)) {
                var option = document.createElement("option");
                option.text = selected.text;
                option.value = selected.value;
                list.appendChild(option);
            }
        }

        // Fonction pour supprimer une technologie de la liste des sélectionnées
        function removeTechnology() {
            var list = document.getElementById('selectedTechnologies');
            list.remove(list.selectedIndex);
        }
    </script>
</body>
</html>
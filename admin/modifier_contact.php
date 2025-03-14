<?php
session_start();
include '../config/config.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../Connexion.php"); // Redirige vers la page de connexion si non authentifié
    exit();
}

// Récupérer l'ID du contact à modifier
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Récupérer les informations du contact depuis la base de données
    $stmt = $pdo->prepare('SELECT * FROM demandes_contact WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $contact = $stmt->fetch();

    if (!$contact) {
        echo "Contact non trouvé.";
        exit();
    }
} else {
    // Rediriger vers la page de gestion des contacts si l'ID n'est pas passé
    header("Location: gestion_contacts.php");
    exit();
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $objet = htmlspecialchars($_POST['objet']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Mettre à jour les informations de la demande de contact
    $stmt = $pdo->prepare("UPDATE demandes_contact SET nom = ?, prenom = ?, objet = ?, email = ?, message = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $objet, $email, $message, $id]);

    // Rediriger vers la page de gestion des contacts
    header("Location: gestion_contacts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la demande de contact</title>
    <link rel="stylesheet" href="../assets/css/modifier_contact.css">
</head>
<body>
    <h1>Modifier la demande de contact</h1>
    <form method="post" action="">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($contact['nom']) ?>" required>
        <br>
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($contact['prenom']) ?>" required>
        <br>
        <label for="objet">Objet :</label>
        <input type="text" id="objet" name="objet" value="<?= htmlspecialchars($contact['objet']) ?>" required>
        <br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($contact['email']) ?>" required>
        <br>
        <label for="message">Message :</label>
        <textarea id="message" name="message" required><?= htmlspecialchars($contact['message']) ?></textarea>
        <br>
        <a href="gestion_contacts.php">
            <button type="button">Retour</button>
        </a>
        <button type="submit">Enregistrer les modifications</button>
    </form>
</body>
</html>
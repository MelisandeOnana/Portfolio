<?php
// Démarrer la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['loggedin'])) {
    error_log("L'utilisateur n'est pas connecté."); // Message de débogage
    header('Location: ../connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérification si l'utilisateur a le rôle d'administrateur
if ($_SESSION['role'] !== 'Administrateur') {
    error_log("L'utilisateur n'a pas le rôle d'administrateur."); // Message de débogage
    header('Location: ../connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas administrateur
    exit();
}

// Définir la variable $isAdmin
$isAdmin = ($_SESSION['role'] === 'Administrateur');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration de la galerie</title>
    <link rel="stylesheet" href="../assets/css/Admin_galerie.css">
</head>
<body>
    <div class="gallery-container">
        <h1>Administration de la galerie</h1>
        <div class="text-back">
            <a href="admin_dashboard.php" class="btn-back">Retour au tableau de bord</a>
        </div>
        
        <!-- Formulaire d'upload d'une nouvelle image -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="file">Sélectionner une image :</label><br>
            <input type="file" id="file" name="file"><br>
            <input type="submit" value="Mettre en ligne">
        </form>
        
        <!-- Liste des images déjà téléchargées -->
        <h2>Images actuelles :</h2>
        <div class="current-images gallery">
        <?php
            // Chemin vers le dossier contenant les images administratives
            $dossier_images_admin = '../administration/uploads/';
            $message = '';

            // Vérifier si le formulaire a été soumis pour l'upload d'une nouvelle image
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
                $uploadOk = true;
                $erreur = "";

                // Chemin complet du fichier à télécharger
                $chemin_fichier = $dossier_images_admin . basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($chemin_fichier, PATHINFO_EXTENSION));

                // Vérifier le format du fichier
                if(!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $erreur = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                    $uploadOk = false;
                }

                // Si $uploadOk est à true, essayer de télécharger le fichier
                if ($uploadOk) {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $chemin_fichier)) {
                        $message = "Le fichier ". htmlspecialchars( basename( $_FILES["file"]["name"])). " a été téléchargé.";
                    } else {
                        $message = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
                    }
                } else {
                    $message = $erreur;
                }
            }

            // Traitement de la suppression de fichier si le formulaire de suppression est soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_file"])) {
                $file_to_delete = htmlspecialchars($_POST["delete_file"]);
                
                // Vérifier si le fichier existe avant de le supprimer
                if (file_exists($file_to_delete)) {
                    if (unlink($file_to_delete)) {
                        $message = "Le fichier $file_to_delete a été supprimé.";
                    } else {
                        $message = "Erreur lors de la suppression du fichier $file_to_delete.";
                    }
                } else {
                    $message = "Le fichier $file_to_delete n'existe pas.";
                }
            }

            // Récupération de tous les fichiers dans le dossier
            $images = glob($dossier_images_admin . '*');

            // Filtrer uniquement les images supportées
            $extensions_supportees = ['jpg', 'jpeg', 'png', 'gif'];
            $images_supportees = array_filter($images, function($image) use ($extensions_supportees) {
                $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                return in_array($extension, $extensions_supportees);
            });

            // Affichage des images avec leur formulaire de suppression
            foreach ($images_supportees as $image) {
                echo '<div class="image">';
                echo '<img src="' . $image . '" alt="Image">';
                echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">';
                echo '<input type="hidden" name="delete_file" value="' . htmlspecialchars($image) . '">';
                echo '<button type="submit" class="delete-btn">Supprimer</button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Popup pour les messages -->
    <div id="popup" class="popup">
        <p id="popup-message" class="popup-message"></p>
        <button class="close-btn" onclick="closePopup()">Fermer</button>
    </div>

    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Centre Equestre du Val d'Arré. Tous droits réservés.</p>
            <p><a href="#">Politique de confidentialité</a> | <a href="#">Mentions légales</a> | <a href="../assets/fichiers/reglement_interieur.pdf" download="Reglement_Interieur_Ecurie">Télécharger le règlement intérieur</a></p>
        </div>
    </footer>

    <script src="../assets/js/script.js"></script>
    <script>
        // Fonction pour afficher le popup
        function showPopup(message) {
            document.getElementById('popup-message').innerText = message;
            document.getElementById('popup').classList.add('show');
        }

        // Fonction pour fermer le popup
        function closePopup() {
            document.getElementById('popup').classList.remove('show');
        }

        // Fermer automatiquement la popup après 3 secondes
        function autoClosePopup() {
            setTimeout(closePopup, 3000);
        }

        // Attacher autoClosePopup à l'événement d'affichage de la popup
        document.getElementById('popup').addEventListener('transitionend', function() {
            if (document.getElementById('popup').classList.contains('show')) {
                autoClosePopup();
            }
        });

        // PHP pour afficher la popup avec le message
        <?php if (!empty($message)): ?>
            showPopup("<?php echo $message; ?>");
        <?php endif; ?>
    </script>
</body>
</html>

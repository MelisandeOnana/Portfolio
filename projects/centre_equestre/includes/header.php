<header>
    <div class="hamburger-menu">
        <span class="bar1"></span>
        <span class="bar2"></span>
        <span class="bar3"></span>
    </div>
    <div class="centre <?php echo !$isAdmin ? 'centre-not-connected' : ''; ?>">
        <div class="header-title">Écuries du Val d'Arré</div>
        <div class="header-line"></div>
        <div class="header-subtitle">Saint-Rémy en l'eau</div>
    </div>
    <div class="login-container">
        <?php if ($isAdmin): ?>
            <div class="dropdown">
                <button class="welcome-btn">Bienvenue <br><?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></button>
                <div class="dropdown-menu">
                    <a href="administration/admin_dashboard.php" class="admin-btn">Administration</a>
                    <a href="deconnexion.php" class="logout-btn">Déconnexion</a>
                </div>
            </div>
        <?php else: ?>
            <img class="login-btn" src="assets/images/icone_utilisateur.png" onclick="window.location.href='connexion.php'">
        <?php endif; ?>
    </div>
</header>

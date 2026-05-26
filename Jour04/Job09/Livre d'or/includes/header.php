<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/site.php';
?>
<header class="site-header">
    <a href="<?php echo BASE_PATH; ?>index.php" class="logo">
        <img src="<?php echo BASE_PATH; ?>assets/images/logo.png" alt="Logo" class="logo-image">
        Livre d'Or
    </a>
    <button class="nav-toggle" aria-label="Ouvrir le menu" onclick="document.body.classList.toggle('nav-open')">
        <span></span>
    </button>
    <nav class="main-nav">
        <a href="<?php echo BASE_PATH; ?>index.php">Accueil</a>
        <a href="<?php echo BASE_PATH; ?>pages/livre-or.php">Livre d'Or</a>
    </nav>
    <div class="header-actions">
        <?php if (isset($_SESSION['login'])): ?>
            <span class="user-welcome">Bienvenue, <?php echo htmlspecialchars($_SESSION['login']); ?></span>
            <a class="btn" href="<?php echo BASE_PATH; ?>pages/profil.php">Profil</a>
            <a class="btn" href="<?php echo BASE_PATH; ?>pages/deconnexion.php">Déconnexion</a>
        <?php else: ?>
            <a class="btn" href="<?php echo BASE_PATH; ?>pages/inscription.php">Inscription</a>
            <a class="btn" href="<?php echo BASE_PATH; ?>pages/connexion.php">Connexion</a>
        <?php endif; ?>
    </div>
</header>
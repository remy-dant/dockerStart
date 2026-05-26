<?php
session_start();
require_once __DIR__ . '/config/site.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre d'or - Accueil</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main class="container">
        <section class="hero-section">
            <h1>Présentation du site</h1>
            <p class="lead">Bienvenue sur notre livre d'or ! Ce site vous permet de laisser vos avis et commentaires. Inscrivez-vous pour participer et partager vos pensées avec la communauté !</p>
            
            <div class="cta-buttons">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_PATH; ?>pages/inscription.php" class="btn">
                        <span class="emoji">🚀</span> S'inscrire
                    </a>
                    <a href="<?php echo BASE_PATH; ?>pages/connexion.php" class="btn">
                        <span class="emoji">🔑</span> Se connecter
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_PATH; ?>pages/livre-or.php" class="btn">
                        <span class="emoji">📖</span> Voir le livre d'or
                    </a>
                    <a href="<?php echo BASE_PATH; ?>pages/commentaire.php" class="btn">
                        <span class="emoji">✍️</span> Laisser un message
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <section class="features-section">
            <h2>Fonctionnalités</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3>Commentaires</h3>
                    <p>Partagez vos impressions et lisez celles des autres membres</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Sécurisé</h3>
                    <p>Vos données sont protégées avec un système d'authentification robuste</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👤</div>
                    <h3>Profil</h3>
                    <p>Gérez votre compte et modifiez vos informations facilement</p>
                </div>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
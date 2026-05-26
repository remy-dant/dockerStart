<div class="hero">
    <div class="hero-content">
        <h1><?php e($message); ?></h1>
        <p class="hero-subtitle">Pour commencer inscrivez-vous ou connectez-vous !</p>
        <?php if (!is_logged_in()): ?>
            <div class="hero-buttons">
                <a href="<?php echo url('auth/register'); ?>" class="btn btn-primary">Inscription</a>
                <a href="<?php echo url('auth/login'); ?>" class="btn btn-secondary">Se connecter</a>
            </div>
        <?php else: ?>
                <p class="welcome-message">
                <i class="fas fa-user"></i>
                Bienvenue, <?php e($_SESSION['user_name'] ?? ''); ?> !
            </p>
        <?php endif; ?>
    </div>
</div>

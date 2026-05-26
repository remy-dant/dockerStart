<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><?php e($title); ?></h1>
            <p>Modifier votre profil</p>
        </div>

        <?php flash_messages(); ?>

        <form method="POST" class="auth-form profile-form" action="<?php echo url('home/profile'); ?>">
            <div class="form-group">
                <label for="login">Nom d'utilisateur</label>
                <input type="text" id="login" name="login" required
                       value="<?php echo esc($user['login'] ?? ''); ?>">
            </div>

            <h3>Changer le mot de passe</h3>
            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password" placeholder="Mot de passe actuel">
            </div>
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password" placeholder="Nouveau mot de passe">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe">
            </div>

            <button type="submit" class="btn btn-primary btn-full">Enregistrer les modifications</button>
        </form>
    </div>
</div>
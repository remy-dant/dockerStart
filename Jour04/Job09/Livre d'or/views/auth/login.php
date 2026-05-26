<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><?php e($title); ?></h1>
            <p>Connectez-vous à votre compte</p>
        </div>
        
        <form method="POST" class="auth-form" action="<?php echo url('auth/login'); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            
            <div class="form-group">
                <label for="login">Nom d'utilisateur</label>
                <input type="text" id="login" name="login" required 
                       value="<?php echo escape(post('login', '')); ?>"
                       placeholder="">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       placeholder="">
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-sign-in-alt"></i>
                Se connecter
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Pas encore de compte ? 
                <a href="<?php echo url('auth/register'); ?>">S'inscrire</a>
            </p>
        </div>
    </div>
</div> 
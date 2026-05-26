<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><?php e($title); ?></h1>
            <p>N'hésitez pas à nous envoyer un message. Nous vous répondrons dans les plus brefs délais.</p>
        </div>

        <?php flash_messages(); ?>

        <form method="POST" class="auth-form contact-form" action="<?php echo url('home/contact'); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" required
                       value="<?php echo escape(post('name', '')); ?>">
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required
                       value="<?php echo escape(post('email', '')); ?>">
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required><?php echo escape(post('message', '')); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-paper-plane"></i>
                Envoyer le message
            </button>
        </form>
    </div>
</div>
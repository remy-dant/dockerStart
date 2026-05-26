<div class="page-header">
    <div class="container">
        <h1><?php e($title); ?></h1>
        <?php flash_messages(); ?>

        <div class="content-main">
            <form method="POST" action="<?php echo url('media/commentaire'); ?>" class="guestbook-form">
                <div class="form-group">
                    <label for="commentaire">Votre commentaire</label>
                    <textarea id="commentaire" name="commentaire" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Publier</button>
                <a href="<?php echo url('media/livre_or'); ?>" class="btn">Retour au livre d'or</a>
            </form>
        </div>
    </div>
</div>

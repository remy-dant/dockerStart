<div class="page-header">
    <div class="container">
        <h1><?php e($title); ?></h1>
        <?php flash_messages(); ?>
    </div>
</div>

<section class="content">
    <div class="container">
        <div class="content-grid">
            <div class="content-main">
                <form method="post" action="<?php echo url('media/edit_comment?id=' . ($comment['id'] ?? '')); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <div class="form-group">
                        <label for="commentaire">Commentaire</label>
                        <textarea id="commentaire" name="commentaire" rows="6" class="form-control"><?php echo esc($comment['commentaire'] ?? ''); ?></textarea>
                    </div>
                    <div style="margin-top:1rem; text-align:right;">
                        <a class="btn btn-secondary" href="<?php echo url('media/livre_or'); ?>">Annuler</a>
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
            <div class="sidebar"></div>
        </div>
    </div>
</section>

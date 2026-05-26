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
                <p>Voulez-vous vraiment supprimer ce commentaire ?</p>
                <blockquote style="background:#f9fafb;padding:1rem;border-left:4px solid #eee;">
                    <?php echo nl2br(esc($comment['commentaire'] ?? '')); ?>
                </blockquote>

                <form method="post" action="<?php echo url('media/delete_comment?id=' . ($comment['id'] ?? '')); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <div style="margin-top:1rem; text-align:right;">
                        <a class="btn btn-secondary" href="<?php echo url('media/livre_or'); ?>">Annuler</a>
                        <button class="btn btn-primary" type="submit">Supprimer</button>
                    </div>
                </form>
            </div>
            <div class="sidebar"></div>
        </div>
    </div>
</section>

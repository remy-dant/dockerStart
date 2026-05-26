<div class="page-header no-page-header">
    <div class="container">
        <h1><?php e($title); ?></h1>
        <?php flash_messages(); ?>
    </div>
</div>

<section class="content guestbook">
    <div class="container">
        <div class="content-grid">
            <div class="content-main">
                <?php if (is_logged_in()): ?>
                    <!-- bouton déplacé en bas via .comment-actions -->
                <?php else: ?>
                    <p>Veuillez vous <a href="<?php echo url('auth/login'); ?>">connecter</a> pour laisser un message.</p>
                <?php endif; ?>

                <form method="post" action="<?php echo url('media/bulk_action'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <div class="comments-list">
                        <div class="comments-header">
                            <div class="col col-check"></div>
                            <div class="col col-date">Posté le :</div>
                            <div class="col col-user">Par utilisateur</div>
                            <div class="col col-text">Commentaires</div>
                        </div>

                        <?php foreach ($content as $c): ?>
                            <div class="comment-row">
                                <div class="col col-check">
                                    <?php if (is_logged_in() && (current_user_id() == $c['id_utilisateur'] || is_admin())): ?>
                                        <input type="checkbox" name="selected_comments[]" value="<?php echo esc($c['id']); ?>">
                                    <?php else: ?>
                                        <!-- pas de case pour les commentaires appartenant à un autre utilisateur -->
                                    <?php endif; ?>
                                </div>
                                <div class="col col-date">
                                    <?php echo esc(date('d/m/Y', strtotime($c['date'] ?? $c['created_at'] ?? 'now'))); ?>
                                </div>
                                <div class="col col-user">
                                    <div class="user-box">
                                        <div class="avatar">
                                            <?php echo esc(strtoupper(substr($c['login'] ?? 'A', 0, 1))); ?>
                                        </div>
                                        <div class="user-name"><?php echo esc($c['login'] ?? 'Anonyme'); ?></div>
                                    </div>
                                </div>
                                <div class="col col-text">
                                    <div class="comment-text"><?php e($c['commentaire']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (is_logged_in()): ?>
                        <div class="comment-actions" style="display:flex;gap:0.5rem;justify-content:space-between;align-items:center;margin-top:1rem;">
                            <div class="left-actions" style="display:flex;gap:0.5rem;align-items:center;">
                                <button id="bulk-edit-btn" type="submit" name="action" value="edit" class="btn btn-secondary" disabled>Modifier</button>
                                <button id="bulk-delete-btn" type="submit" name="action" value="delete" class="btn btn-danger" disabled>Supprimer</button>
                            </div>
                            <div class="right-actions">
                                <a class="btn btn-primary" href="<?php echo url('media/commentaire'); ?>">Ajouter un commentaire</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="sidebar">
                <!-- sidebar vide pour reproduire la largeur réduite (comme 'Nous contacter') -->
            </div>
        </div>
    </div>
</section>

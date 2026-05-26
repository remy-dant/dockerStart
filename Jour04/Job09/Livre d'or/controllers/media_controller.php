<?php
/**
 * Page Livre d'or (media/livre)
 */
function media_livre() {
    // Si formulaire POST pour ajouter un commentaire
    if (is_post()) {
        // Ne pas appliquer htmlspecialchars ici (évite double-encodage)
        // On enlève seulement les balises HTML et les espaces en début/fin
        $commentaire = strip_tags(trim(post('commentaire')));
        $user_id = current_user_id();

        if ($commentaire === '') {
            set_flash('error', 'Le commentaire ne peut pas être vide.');
            redirect('media/livre');
        }

        // Nécessite d'être connecté pour poster
        if (!$user_id) {
            set_flash('error', 'Vous devez être connecté pour poster un commentaire.');
            redirect('auth/login');
        }

        create_comment($commentaire, $user_id);
        set_flash('success', 'Merci, votre message a été ajouté au livre d\'or.');
        redirect('media/livre');
    }

    $all_comments = get_all_comments();
    $data = [
        'title' => 'Livre d\'or',
        'content' => $all_comments
    ];

    load_view_with_layout('media/livre', $data);
}

/**
 * Action compatible pour la vue `livre-or.php` (URL: media/livre_or)
 */
function media_livre_or() {
    // Affiche la page du livre d'or (liste des commentaires)
    $all_comments = get_all_comments();
    $data = [
        'title' => 'Livre d\'or',
        'content' => $all_comments
    ];

    load_view_with_layout('media/livre-or', $data);
}

/**
 * Page d'ajout de commentaire (GET affiche le formulaire, POST crée le commentaire)
 */
function media_commentaire() {
    // Accès réservé aux utilisateurs connectés
    if (!is_logged_in()) {
        set_flash('error', 'Vous devez être connecté pour accéder au formulaire d\'ajout de commentaire.');
        redirect('auth/login');
    }

    if (is_post()) {
        $commentaire = strip_tags(trim(post('commentaire')));
        $user_id = current_user_id();

        if ($commentaire === '') {
            set_flash('error', 'Le commentaire ne peut pas être vide.');
            redirect('media/commentaire');
        }

        // Créer le commentaire
        create_comment($commentaire, $user_id);
        set_flash('success', 'Merci, votre message a été ajouté au livre d\'or.');
        redirect('media/livre_or');
    }

    $data = [
        'title' => 'Ajouter un commentaire'
    ];

    load_view_with_layout('media/commentaire', $data);
}

/**
 * Editer un commentaire (GET affiche le formulaire, POST met à jour)
 */
function media_edit_comment() {
    if (!is_logged_in()) {
        set_flash('error', 'Vous devez être connecté pour éditer un commentaire.');
        redirect('auth/login');
    }

    $id = get('id');
    if (!$id) {
        set_flash('error', 'Identifiant de commentaire manquant.');
        redirect('media/livre_or');
    }

    $comment = get_comment_by_id($id);
    if (!$comment) {
        set_flash('error', 'Commentaire introuvable.');
        redirect('media/livre_or');
    }

    // Vérification d'autorisation : auteur ou admin
    if ($comment['id_utilisateur'] != current_user_id() && !is_admin()) {
        set_flash('error', 'Vous n\'êtes pas autorisé à modifier ce commentaire.');
        redirect('media/livre_or');
    }

    if (is_post()) {
        // CSRF
        $token = post('csrf_token');
        if (!verify_csrf_token($token)) {
            set_flash('error', 'Jeton CSRF invalide.');
            redirect('media/edit_comment?id=' . $id);
        }

        $commentaire = strip_tags(trim(post('commentaire')));
        if ($commentaire === '') {
            set_flash('error', 'Le commentaire ne peut pas être vide.');
            redirect('media/edit_comment?id=' . $id);
        }

        if (update_comment($id, $commentaire)) {
            set_flash('success', 'Le commentaire a été mis à jour.');
        } else {
            set_flash('error', 'Impossible de mettre à jour le commentaire.');
        }
        redirect('media/livre_or');
    }

    $data = [
        'title' => 'Modifier le commentaire',
        'comment' => $comment
    ];
    load_view_with_layout('media/edit_commentaire', $data);
}

/**
 * Supprimer un commentaire (GET confirmation, POST suppression)
 */
function media_delete_comment() {
    if (!is_logged_in()) {
        set_flash('error', 'Vous devez être connecté pour supprimer un commentaire.');
        redirect('auth/login');
    }

    $id = get('id');
    if (!$id) {
        set_flash('error', 'Identifiant de commentaire manquant.');
        redirect('media/livre_or');
    }

    $comment = get_comment_by_id($id);
    if (!$comment) {
        set_flash('error', 'Commentaire introuvable.');
        redirect('media/livre_or');
    }

    // Vérification d'autorisation : auteur ou admin
    if ($comment['id_utilisateur'] != current_user_id() && !is_admin()) {
        set_flash('error', 'Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        redirect('media/livre_or');
    }

    if (is_post()) {
        $token = post('csrf_token');
        if (!verify_csrf_token($token)) {
            set_flash('error', 'Jeton CSRF invalide.');
            redirect('media/delete_comment?id=' . $id);
        }

        if (delete_comment($id)) {
            set_flash('success', 'Le commentaire a été supprimé.');
        } else {
            set_flash('error', 'Impossible de supprimer le commentaire.');
        }
        redirect('media/livre_or');
    }

    $data = [
        'title' => 'Supprimer le commentaire',
        'comment' => $comment
    ];
    load_view_with_layout('media/delete_commentaire', $data);
}

/**
 * Traite les actions en masse (modifier ou supprimer) sur les commentaires sélectionnés
 */
function media_bulk_action() {
    if (!is_logged_in()) {
        set_flash('error', 'Vous devez être connecté pour effectuer cette action.');
        redirect('auth/login');
    }

    if (!is_post()) {
        redirect('media/livre_or');
    }

    $token = post('csrf_token');
    if (!verify_csrf_token($token)) {
        set_flash('error', 'Jeton CSRF invalide.');
        redirect('media/livre_or');
    }

    $selected = post('selected_comments') ?? [];
    if (!is_array($selected) || count($selected) === 0) {
        set_flash('error', 'Aucun commentaire sélectionné.');
        redirect('media/livre_or');
    }

    $action = post('action');
    if ($action === 'delete') {
        $deleted = 0;
        $not_allowed = 0;
        foreach ($selected as $id) {
            $c = get_comment_by_id($id);
            if (!$c) continue;
            if ($c['id_utilisateur'] != current_user_id() && !is_admin()) {
                $not_allowed++;
                continue;
            }
            if (delete_comment($id)) $deleted++;
        }

        $msg = '';
        if ($deleted) $msg .= "{$deleted} commentaire(s) supprimé(s). ";
        if ($not_allowed) $msg .= "{$not_allowed} commentaire(s) non autorisés à être supprimés.";
        set_flash('success', trim($msg));
        redirect('media/livre_or');
    }

    if ($action === 'edit') {
        // Pour l'édition, on n'autorise qu'un seul commentaire sélectionné
        if (count($selected) !== 1) {
            set_flash('error', 'Veuillez sélectionner exactement un commentaire à modifier.');
            redirect('media/livre_or');
        }
        $id = $selected[0];
        $c = get_comment_by_id($id);
        if (!$c) {
            set_flash('error', 'Commentaire introuvable.');
            redirect('media/livre_or');
        }
        if ($c['id_utilisateur'] != current_user_id() && !is_admin()) {
            set_flash('error', 'Vous n\'êtes pas autorisé à modifier ce commentaire.');
            redirect('media/livre_or');
        }
        // redirige vers la page d'édition pour ce commentaire
        redirect('media/edit_comment?id=' . $id);
    }

    // action inconnue
    set_flash('error', 'Action non reconnue.');
    redirect('media/livre_or');
}
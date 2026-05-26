<?php
// Contrôleur pour la page d'accueil

/**
 * Page d'accueil
 */
function home_index() {
    $data = [
        'title' => 'Accueil',
        'message' => 'Bienvenue sur mon livre d\'or !',
        
    ];
    
    load_view_with_layout('home/index', $data);
}

/**
 * Page à propos
 */
function home_about() {
    $data = [
        'title' => 'À propos',
        'content' => 'Cette application est un starter kit PHP MVC développé avec une approche procédurale.'
    ];
    
    load_view_with_layout('home/about', $data);
}

/**
 * Page contact
 */
function home_contact() {
    $data = [
        'title' => 'Contact'
    ];
    
    if (is_post()) {
        $name = clean_input(post('name'));
        $email = clean_input(post('email'));
        $message = clean_input(post('message'));
        
        // Validation simple
        if (empty($name) || empty($email) || empty($message)) {
            set_flash('error', 'Tous les champs sont obligatoires.');
        } elseif (!validate_email($email)) {
            set_flash('error', 'Adresse email invalide.');
        } else {
            // Envoi de l'email vers l'adresse demandée
                $to = 'remy.danton@laplateforme.io';
                $subject = '[Contact] ' . APP_NAME . ' - Nouveau message de ' . $name;
                $body = "Nom: $name\nEmail: $email\n\nMessage:\n" . $message;
                $headers = 'From: ' . $name . ' <' . $email . "\r\n";
                $headers .= 'Reply-To: ' . $email . "\r\n";
                $headers .= 'X-Mailer: PHP/' . phpversion();

                // Tentative d'envoi. Sur certains environnements (local) mail() peut ne pas être configuré.
                $sent = false;
                try {
                    $sent = mail($to, $subject, $body, $headers);
                } catch (Throwable $e) {
                    $sent = false;
                }

                if ($sent) {
                    set_flash('success', 'Votre message a été envoyé avec succès !');
                } else {
                    // Si l'envoi échoue localement, on affiche un message mais conserve la confirmation
                    set_flash('warning', 'Impossible d\'envoyer l\'email depuis cet environnement. Le message a été reçu localement.');
                }
                redirect('home/contact');
        }
    }
    
    load_view_with_layout('home/contact', $data);
} 


/**
 * Page profile
 */
function home_profile() {
    // Requiert d'être connecté
    if (!is_logged_in()) {
        set_flash('error', 'Vous devez être connecté pour accéder à votre profil.');
        redirect('auth/login');
    }

    $user = get_user_by_id(current_user_id());

    // Traitement du formulaire de modification
    if (is_post()) {
        $login = clean_input(post('login'));
        $current_password = post('current_password');
        $new_password = post('new_password');
        $confirm_password = post('confirm_password');

        // Validation de base
        if (empty($login)) {
            set_flash('error', 'Le login ne peut pas être vide.');
            redirect('home/profile');
        }

        // Si le login change, vérifier l'unicité
        if ($login !== $user['login'] && login_exists($login, $user['id'])) {
            set_flash('error', 'Ce login est déjà utilisé.');
            redirect('home/profile');
        }

        // Mettre à jour le login si nécessaire
        if ($login !== $user['login']) {
            if (!update_user_login($user['id'], $login)) {
                set_flash('error', 'Impossible de mettre à jour le login.');
                redirect('home/profile');
            }
            // Mettre à jour la session
            $_SESSION['user_name'] = $login;
            $user['login'] = $login;
        }

        // Si l'utilisateur souhaite changer le mot de passe
        if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                set_flash('error', 'Pour changer le mot de passe, complétez tous les champs de mot de passe.');
                redirect('home/profile');
            }
            // Vérifier le mot de passe actuel
            if (!verify_password($current_password, $user['password'])) {
                set_flash('error', 'Le mot de passe actuel est incorrect.');
                redirect('home/profile');
            }
            if (strlen($new_password) < 6) {
                set_flash('error', 'Le nouveau mot de passe doit contenir au moins 6 caractères.');
                redirect('home/profile');
            }
            if ($new_password !== $confirm_password) {
                set_flash('error', 'Les nouveaux mots de passe ne correspondent pas.');
                redirect('home/profile');
            }

            if (!update_user_password($user['id'], $new_password)) {
                set_flash('error', 'Impossible de mettre à jour le mot de passe.');
                redirect('home/profile');
            }
        }

        set_flash('success', 'Profil mis à jour avec succès.');
        redirect('home/profile');
    }

    $data = [
        'title' => 'Profil',
        'user' => $user
    ];

    load_view_with_layout('home/profile', $data);
} 

/**
 * Page test
 */
function home_test() {
    $data = [
        'title' => 'Page test',
        'message' => 'Bienvenue sur votre page test',
    ];
    
    load_view_with_layout('home/test', $data);
} 


<?php
// Modèle pour les utilisateurs adapté à la table `utilisateurs`

/**
 * Récupère un utilisateur par son login
 */
function get_user_by_login($login) {
    $query = "SELECT * FROM utilisateurs WHERE login = ? LIMIT 1";
    return db_select_one($query, [$login]);
}

/**
 * Récupère un utilisateur par son ID
 */
function get_user_by_id($id) {
    $query = "SELECT * FROM utilisateurs WHERE id = ? LIMIT 1";
    return db_select_one($query, [$id]);
}

/**
 * Crée un nouvel utilisateur (login + password)
 */
function create_user($login, $password) {
    $hashed_password = hash_password($password);
    $query = "INSERT INTO utilisateurs (login, password) VALUES (?, ?)";
    if (db_execute($query, [$login, $hashed_password])) {
        return db_last_insert_id();
    }
    return false;
}

/**
 * Met à jour le mot de passe d'un utilisateur
 */
function update_user_password($id, $password) {
    $hashed_password = hash_password($password);
    $query = "UPDATE utilisateurs SET password = ? WHERE id = ?";
    return db_execute($query, [$hashed_password, $id]);
}

/**
 * Supprime un utilisateur
 */
function delete_user($id) {
    $query = "DELETE FROM utilisateurs WHERE id = ?";
    return db_execute($query, [$id]);
}

/**
 * Met à jour le login d'un utilisateur
 */
function update_user_login($id, $login) {
    $query = "UPDATE utilisateurs SET login = ? WHERE id = ?";
    return db_execute($query, [$login, $id]);
}

/**
 * Vérifie si un login existe déjà
 */
function login_exists($login, $exclude_id = null) {
    $query = "SELECT COUNT(*) as count FROM utilisateurs WHERE login = ?";
    $params = [$login];
    if ($exclude_id) {
        $query .= " AND id != ?";
        $params[] = $exclude_id;
    }
    $result = db_select_one($query, $params);
    return !empty($result) && ($result['count'] ?? 0) > 0;
}

/**
 * Récupère tous les utilisateurs (simple)
 */
function get_all_users($limit = null, $offset = 0) {
    $query = "SELECT id, login, created_at FROM utilisateurs ORDER BY created_at DESC";
    if ($limit !== null) {
        $query .= " LIMIT $offset, $limit";
    }
    return db_select($query);
}

/**
 * Compte le nombre total d'utilisateurs
 */
function count_users() {
    $query = "SELECT COUNT(*) as total FROM utilisateurs";
    $result = db_select_one($query);
    return $result['total'] ?? 0;
}

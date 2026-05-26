<?php

/**
 * Récupère la liste de tous les commentaires (livre d'or)
 * joint avec le login de l'utilisateur si disponible
 */
function get_all_comments() {
    $query = "SELECT c.id, c.commentaire, c.id_utilisateur, c.date, u.login
              FROM commentaires c
              LEFT JOIN utilisateurs u ON u.id = c.id_utilisateur
              ORDER BY c.date DESC";
    return db_select($query);
}

/**
 * Crée un commentaire dans le livre d'or
 */
function create_comment($commentaire, $id_utilisateur = null) {
    $query = "INSERT INTO commentaires (commentaire, id_utilisateur, date) VALUES (?, ?, ?)";
    $date = date('Y-m-d H:i:s');
    if (db_execute($query, [$commentaire, $id_utilisateur, $date])) {
        return db_last_insert_id();
    }
    return false;
}

/**
 * Récupère un commentaire par son ID
 */
function get_comment_by_id($id) {
    $query = "SELECT c.id, c.commentaire, c.id_utilisateur, c.date, u.login
              FROM commentaires c
              LEFT JOIN utilisateurs u ON u.id = c.id_utilisateur
              WHERE c.id = ?
              LIMIT 1";
    return db_select_one($query, [$id]);
}

/**
 * Met à jour un commentaire
 */
function update_comment($id, $commentaire) {
    $query = "UPDATE commentaires SET commentaire = ? WHERE id = ?";
    return db_execute($query, [$commentaire, $id]);
}

/**
 * Supprime un commentaire
 */
function delete_comment($id) {
    $query = "DELETE FROM commentaires WHERE id = ?";
    return db_execute($query, [$id]);
}
<?php
// Script de réparation: décoder les entités HTML dans la colonne `commentaire` de la table `commentaires`.
// Usage: placez-le dans le dossier du projet et exécutez-le via le navigateur (temporaire) ou en CLI:
// php tools/fix_comments.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/database.php';

try {
    $pdo = db_connect();
    $rows = $pdo->query("SELECT id, commentaire FROM commentaires")->fetchAll(PDO::FETCH_ASSOC);
    $count = 0;

    foreach ($rows as $row) {
        $decoded = html_entity_decode($row['commentaire'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($decoded !== $row['commentaire']) {
            $stmt = $pdo->prepare("UPDATE commentaires SET commentaire = ? WHERE id = ?");
            $stmt->execute([$decoded, $row['id']]);
            $count++;
        }
    }

    echo "Opération terminée. $count commentaires mis à jour." . PHP_EOL;
} catch (Exception $e) {
    echo 'Erreur: ' . $e->getMessage() . PHP_EOL;
}

<?php
// charger la configuration centrale
require_once __DIR__ . '/../config/database.php';
// Exposer $db pour le code legacy si besoin
if (!isset($db) && isset($pdo)) {
    $db = $pdo;
}

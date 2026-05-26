<?php
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbName = getenv('DB_NAME') ?: 'livreor';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: '';

// Configuration centrale de la base de données. Ajustez les identifiants si nécessaire.
try {
    $db = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En production, loggez l'erreur au lieu de l'afficher
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Fournit aussi un alias $pdo pour compatibilité
$pdo = $db;

// Expose un chemin pratique vers le dossier database pour les imports/migrations
define('DATABASE_DIR', __DIR__ . '/../database');

<?php
phpinfo();

$host = getenv('MYSQL_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'dev';
$pass = getenv('MYSQL_PASSWORD') ?: 'devpassword';
$db   = getenv('MYSQL_DATABASE') ?: 'lamp_demo';

$mysqli = @new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    echo "<p style=\"color:red;\">Connexion MySQL échouée : " . htmlspecialchars($mysqli->connect_error) . "</p>";
} else {
    echo "<p style=\"color:green;\">Connexion MySQL réussie à la base '" . htmlspecialchars($db) . "' sur l'hôte '" . htmlspecialchars($host) . "' en tant que '" . htmlspecialchars($user) . "'</p>";
}

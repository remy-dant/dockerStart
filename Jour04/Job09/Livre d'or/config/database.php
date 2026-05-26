<?php
// Configuration de la base de données
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'livreor');
define('DB_USER', getenv('DB_USER') ?: 'livreor_user');
define('DB_PASS', getenv('DB_PASSWORD') ?: (getenv('DB_PASS') ?: 'change_me'));
define('DB_CHARSET', 'utf8');

// Configuration générale de l'application
$envBaseUrl = getenv('BASE_URL');

if ($envBaseUrl !== false && $envBaseUrl !== '') {
	$baseUrl = rtrim($envBaseUrl, '/') . '/';
} else {
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
	$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

	if ($scriptDir === '/' || $scriptDir === '.') {
		$scriptDir = '';
	}

	if (str_ends_with($scriptDir, '/public')) {
		$scriptDir = substr($scriptDir, 0, -7);
	}

	$baseUrl = rtrim($scheme . '://' . $host . $scriptDir, '/') . '/';
}

define('BASE_URL', $baseUrl);
define('APP_NAME', "LIVRE D'OR");
define('APP_VERSION', '1.0.0');

// Configuration des chemins
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CONTROLLER_PATH', ROOT_PATH . '/controllers');
define('MODEL_PATH', ROOT_PATH . '/models');
define('VIEW_PATH', ROOT_PATH . '/views');
define('INCLUDE_PATH', ROOT_PATH . '/includes');
define('CORE_PATH', ROOT_PATH . '/core');
define('PUBLIC_PATH', ROOT_PATH . '/public'); 

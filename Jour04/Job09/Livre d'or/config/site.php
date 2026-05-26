<?php
// Détermine automatiquement un BASE_PATH utilisable dans les href et redirections.
// L'idée : si la requête est servie depuis /.../pages/..., on considère la partie avant
// '/pages/' comme racine du site. Sinon on prend le dirname du script.
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$base = '/';
if ($script !== '') {
    if (strpos($script, '/pages/') !== false) {
        // coupe à la position de '/pages/' pour obtenir la racine du site
        $base = substr($script, 0, strpos($script, '/pages/')) . '/';
    } else {
        // cas classique (ex: /index.php) -> dirname('/index.php') === '/'
        $dir = rtrim(dirname($script), '/');
        $base = ($dir === '') ? '/' : $dir . '/';
    }
}
define('BASE_PATH', $base);

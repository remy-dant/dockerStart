<?php
// Debug helper pour la page Livre d'or
// Ouvrez dans votre navigateur : http://localhost/.../tools/debug_livre.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/view.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../models/livre_model.php';
require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../controllers/media_controller.php';

echo "<h2>Debug Livre d'or</h2>";

// Vérifications basiques
echo '<h3>Fichiers et fonctions</h3>';
$viewFile = VIEW_PATH . '/media/livre-or.php';
echo "View file: <strong>$viewFile</strong> -> " . (file_exists($viewFile) ? '<span style="color:green">OK</span>' : '<span style="color:red">MISSING</span>') . "<br>";
echo "Controller function media_livre_or exists: " . (function_exists('media_livre_or') ? '<span style="color:green">OK</span>' : '<span style="color:red">MISSING</span>') . "<br>";

// Essayer d'exécuter l'action en capturant les erreurs
echo '<h3>Exécution test de media_livre_or()</h3>';
try {
    // Appel direct (affichera la page) — capture output
    ob_start();
    media_livre_or();
    $output = ob_get_clean();
    echo "<div style='border:1px solid #ccc;padding:10px;margin:10px 0;'>";
    echo '<strong>Sortie générée (début)</strong><pre>' . htmlspecialchars(substr($output,0,2000)) . '</pre><strong>...(fin)</strong>';
    echo "</div>";
} catch (Throwable $e) {
    echo '<div style="color:red"><h4>Erreur attrapée :</h4>';
    echo '<pre>' . htmlspecialchars($e->getMessage() . "\n\n" . $e->getTraceAsString()) . '</pre></div>';
}

echo '<p>Copiez ici l\'erreur affichée si elle existe, ou dites-moi ce que vous voyez.</p>';

?>

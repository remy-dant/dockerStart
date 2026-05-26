<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/site.php';
require_once __DIR__ . '/../includes/csrf.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH . 'pages/connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$query = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
$query->execute([$user_id]);
$user = $query->fetch();

if (!$user) {
    header('Location: ' . BASE_PATH . 'pages/connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate($_POST['_csrf'] ?? null)) {
        $error = 'Requête invalide (token CSRF).';
    } else {
        $newLogin = trim($_POST['login'] ?? $user['login']);
        $newPassword = $_POST['password'] ?? '';

        if (mb_strtolower($newLogin) === 'enzo' && mb_strtolower($user['login']) !== 'enzo') {
            $error = 'Le nom d\'utilisateur "Enzo" est réservé.';
        }

        if (empty($error)) {
            if (mb_strtolower($newLogin) !== mb_strtolower($user['login'])) {
                $stmt = $pdo->prepare('SELECT COUNT(*) as cnt FROM utilisateurs WHERE LOWER(login) = LOWER(:login)');
                $stmt->execute(['login' => $newLogin]);
                $r = $stmt->fetch();
                if ($r && $r['cnt'] > 0) {
                    $error = 'Ce login est déjà utilisé';
                }
            }
        }

        if (empty($error)) {
            if ($newPassword !== '') {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE utilisateurs SET login = ?, password = ? WHERE id = ?');
                $stmt->execute([$newLogin, $hash, $user_id]);
            } else {
                $stmt = $pdo->prepare('UPDATE utilisateurs SET login = ? WHERE id = ?');
                $stmt->execute([$newLogin, $user_id]);
            }
            $_SESSION['login'] = $newLogin;
            header('Location: ' . BASE_PATH . 'pages/profil.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Profil de <?php echo htmlspecialchars($user['login'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main class="container">
        <h1>Profil de <?php echo htmlspecialchars($user['login'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php if (!empty($error)) echo '<p class="notice error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>'; ?>
        
        <form method="post" action="<?php echo BASE_PATH; ?>pages/profil.php" class="form">
            <?php echo csrf_input(); ?>
            
            <div class="form-group">
                <label for="login">Login (laisser pré-rempli si inchanger)</label>
                <input type="text" id="login" name="login" value="<?php echo htmlspecialchars($user['login'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" id="password" name="password" placeholder="Laisser vide pour garder l'ancien">
            </div>
            
            <div class="text-center">
                <button class="btn" type="submit">
                    <span class="emoji">💾</span> Mettre à jour
                </button>
            </div>
        </form>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
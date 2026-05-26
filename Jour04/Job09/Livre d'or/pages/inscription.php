<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/site.php';
require_once __DIR__ . '/../includes/csrf.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate($_POST['_csrf'] ?? null)) {
        $error = 'Requête invalide (token CSRF).';
    } else {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if ($login === '' || $password === '') {
            $error = 'Veuillez renseigner tous les champs.';
        } elseif ($password !== $confirm) {
            $error = 'Les mots de passe ne correspondent pas.';
        } elseif (mb_strtolower($login) === 'enzo') {
            $error = 'Le nom d\'utilisateur "Enzo" est réservé.';
        } else {
            $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM utilisateurs WHERE LOWER(login) = LOWER(:login)');
            $stmt->execute(['login' => $login]);
            $r = $stmt->fetch();
            if ($r && $r['cnt'] > 0) {
                $error = 'Ce login est déjà utilisé.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $pdo->prepare('INSERT INTO utilisateurs (login, password) VALUES (:login, :password)');
                $ins->execute(['login' => $login, 'password' => $hash]);
                header('Location: ' . BASE_PATH . 'pages/connexion.php');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Inscription</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main class="container">
        <h1>Inscription</h1>
        <?php if ($error) echo '<p class="notice error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>'; ?>
        
        <form method="post" action="<?php echo BASE_PATH; ?>pages/inscription.php" class="form">
            <?php echo csrf_input(); ?>
            
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" id="login" name="login" required placeholder="Ex: Gateau">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Min. 1 chiffre, 6 lettres, 1 majuscule, 1 caractère spécial">
            </div>
            
            <div class="form-group">
                <label for="confirm">Confirmer le mot de passe</label>
                <input type="password" id="confirm" name="confirm" required placeholder="Réécrivez votre mot de passe">
            </div>
            
            <div class="text-center">
                <button class="btn" type="submit">
                    <span class="emoji">🚀</span> S'inscrire
                </button>
            </div>
        </form>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
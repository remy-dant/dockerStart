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

        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE login = :login');
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['login'] = $user['login'];
            $_SESSION['user_id'] = $user['id'] ?? null;
            header('Location: ' . BASE_PATH . 'pages/livre-or.php');
            exit;
        } else {
            $error = 'Identifiants invalides';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main class="container">
        <h1>Connexion</h1>
        <?php if ($error) echo '<p class="notice error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>'; ?>
        
        <form method="post" action="<?php echo BASE_PATH; ?>pages/connexion.php" class="form">
            <?php echo csrf_input(); ?>
            
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" id="login" name="login" required placeholder="Votre nom d'utilisateur">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Votre mot de passe">
            </div>
            
            <div class="text-center">
                <button class="btn" type="submit">
                    <span class="emoji">🔑</span> Se connecter
                </button>
            </div>
        </form>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
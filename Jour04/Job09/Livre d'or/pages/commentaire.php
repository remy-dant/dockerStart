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
        $commentaire = trim($_POST['commentaire'] ?? '');
        if ($commentaire === '') {
            $error = 'Le commentaire est vide.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO commentaires (id_utilisateur, commentaire, date) VALUES (:uid, :comm, NOW())');
            $stmt->execute(['uid' => $user['id'], 'comm' => $commentaire]);
            header('Location: ' . BASE_PATH . 'pages/livre-or.php');
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
    <title>Ajouter un commentaire</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main class="container">
        <h1>Poster un commentaire</h1>
        <p class="user-info">✍️ Connecté en tant que <strong><?php echo htmlspecialchars($user['login'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <?php if (!empty($error)) echo '<p class="notice error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>'; ?>
        
        <form method="post" action="<?php echo BASE_PATH; ?>pages/commentaire.php" class="form">
            <?php echo csrf_input(); ?>
            
            <div class="form-group">
                <label for="commentaire">Votre message</label>
                <textarea id="commentaire" name="commentaire" required placeholder="Partagez vos impressions..."></textarea>
            </div>
            
            <div class="text-center">
                <button class="btn" type="submit">
                    <span class="emoji">📤</span> Envoyer
                </button>
            </div>
        </form>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
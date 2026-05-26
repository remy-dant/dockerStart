<?php
session_start();
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../config/site.php';

// Handler pour suppression de commentaire (seulement pour l'utilisateur Enzo)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    require_once __DIR__ . '/../config/database.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_PATH . 'pages/connexion.php');
        exit;
    }

    if (!csrf_validate($_POST['_csrf'] ?? null)) {
        header('Location: ' . BASE_PATH . 'pages/livre-or.php');
        exit;
    }

    $stmt = $pdo->prepare('SELECT login FROM utilisateurs WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if (!$user || $user['login'] !== 'Enzo') {
        header('Location: ' . BASE_PATH . 'pages/livre-or.php');
        exit;
    }

    $commentId = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    if ($commentId > 0) {
        $del = $pdo->prepare('DELETE FROM commentaires WHERE id = ?');
        $del->execute([$commentId]);
    }

    header('Location: ' . BASE_PATH . 'pages/livre-or.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Livre d'Or</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main class="container">
        <h1>Commentaires</h1>
        
        <div class="comments-list">
            <?php
            require_once __DIR__ . '/../config/database.php';

            $stmt = $pdo->query('SELECT c.*, u.login FROM commentaires c JOIN utilisateurs u ON c.id_utilisateur = u.id ORDER BY c.date DESC');
            $commentaires = $stmt->fetchAll();

            if (count($commentaires) === 0) {
                echo '<p class="text-center">Aucun commentaire pour le moment. Soyez le premier à laisser un message !</p>';
            }

            // Date du jour (pour tester)
            $today = new DateTime();
            $today->setTime(0, 0, 0);

            foreach ($commentaires as $commentaire) {
                $dt = new DateTime($commentaire['date']);
                $dateStr = $dt->format('d/m/Y');
                
                // Comparer les dates en format Y-m-d
                $commentDateOnly = $dt->format('Y-m-d');
                $todayOnly = $today->format('Y-m-d');
                $isToday = ($commentDateOnly === $todayOnly);
                $newClass = $isToday ? ' new' : '';
                
                echo '<article class="comment' . $newClass . '">';
                echo '<div class="comment-frame">';
                
                // Métadonnées
                echo '<div class="comment-meta">';
                echo '<span class="comment-date">' . $dateStr . '</span>';
                echo '<span class="comment-author">' . htmlspecialchars($commentaire['login'], ENT_QUOTES, 'UTF-8') . '</span>';
                echo '</div>';
                
                // Corps du commentaire
                echo '<div class="comment-body">';
                echo '<p>' . nl2br(htmlspecialchars($commentaire['commentaire'], ENT_QUOTES, 'UTF-8')) . '</p>';
                echo '</div>';
                
                // Actions (supprimer pour Enzo)
                if (isset($_SESSION['login']) && $_SESSION['login'] === 'Enzo') {
                    echo '<div class="comment-actions">';
                    echo '<form method="POST" onsubmit="return confirm(\'Confirmer la suppression ?\');">';
                    echo '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
                    echo '<input type="hidden" name="comment_id" value="' . intval($commentaire['id']) . '">';
                    echo '<button type="submit" name="delete_comment" class="delete-btn">';
                    echo '<span class="emoji">🗑️</span> Supprimer';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';
                }
                
                echo '</div>'; // .comment-frame
                echo '</article>';
            }
            ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="text-center mt-3">
                <a class="btn" href="<?php echo BASE_PATH; ?>pages/commentaire.php">
                    <span class="emoji">✍️</span> Ajouter un commentaire
                </a>
            </div>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
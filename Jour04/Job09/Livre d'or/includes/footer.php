<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/site.php'; 
?>
<footer class="site-footer">
    <p>&copy; 2025 Livre d'Or — Propriétaire : Enzo</p>
    <p>
        <a href="https://github.com/enzo-cys/livre-or" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo BASE_PATH; ?>assets/images/github.svg" alt="GitHub">
            GitHub
        </a>
    </p>
</footer>
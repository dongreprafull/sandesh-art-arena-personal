<?php
/**
 * Shared config: DB connection + shared session across all artarenastudio.com subdomains.
 * Lives outside the repo logic-wise (credentials only here) — keep this file out of version control.
 */

// ---- Shared session across main site + all subdomains ----
$cookieDomain = '.artarenastudio.com';
session_set_cookie_params([
    'lifetime' => 60 * 60 * 24 * 14, // 14 days
    'path'     => '/',
    'domain'   => $cookieDomain,
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_name('ARTARENA_SESSION');
session_start();

// ---- Database ----
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'u609460025_artarena_core');
define('DB_USER', 'u609460025_artarena_adm');
define('DB_PASS', 'Snd3sh#ArtVault92q');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}

// ---- Small helpers used across pages ----
function e(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function is_admin(): bool {
    return is_logged_in() && !empty($_SESSION['is_admin']);
}

function require_admin(): void {
    if (!is_admin()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function current_user(): ?array {
    if (!is_logged_in()) return null;
    static $u = null;
    if ($u === null) {
        $stmt = db()->prepare('SELECT id, name, email, role, is_admin FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $u = $stmt->fetch() ?: null;
    }
    return $u;
}

function base_url(): string {
    return '/';
}

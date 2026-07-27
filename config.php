<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load server-specific local configuration if it exists
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

// Database Credentials (Fallback defaults if not defined in config.local.php)
defined('DB_HOST')          || define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
defined('DB_PORT')          || define('DB_PORT', getenv('DB_PORT') ?: '3306');
defined('DB_NAME')          || define('DB_NAME', getenv('DB_NAME') ?: 'partner_ledger');
defined('DB_USER')          || define('DB_USER', getenv('DB_USER') ?: 'root');
defined('DB_PASS')          || define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : ''); 

defined('DEFAULT_PASSCODE') || define('DEFAULT_PASSCODE', '1234');

function is_authenticated() {
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

function require_auth() {
    if (!is_authenticated()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }
}

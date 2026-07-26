<?php
require_once __DIR__ . '/config.php';

function get_db() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            // First attempt direct connection to DB
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            // If DB doesn't exist, try connecting to MySQL server to create it
            if ($e->getCode() == 1049) {
                try {
                    $tmp_dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
                    $tmp_pdo = new PDO($tmp_dsn, DB_USER, DB_PASS);
                    $tmp_pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                } catch (PDOException $ex) {
                    throw new Exception("Database Connection Error: " . $ex->getMessage());
                }
            } else {
                throw new Exception("Database Connection Error: " . $e->getMessage());
            }
        }
        init_db($pdo);
    }
    return $pdo;
}

function init_db($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS ledger_store (
        key_name VARCHAR(64) PRIMARY KEY,
        data_value LONGTEXT NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS app_auth (
        id INT PRIMARY KEY,
        passcode_hash VARCHAR(255) NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Ensure default passcode exists if not set
    $stmt = $pdo->query("SELECT COUNT(*) FROM app_auth");
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash(DEFAULT_PASSCODE, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO app_auth (id, passcode_hash) VALUES (1, ?)");
        $stmt->execute([$hash]);
    }
}

function verify_passcode($passcode) {
    $passcode = (string)$passcode;
    $pdo = get_db();
    $stmt = $pdo->query("SELECT passcode_hash FROM app_auth WHERE id = 1");
    $row = $stmt->fetch();
    if ($row && !empty($row['passcode_hash'])) {
        return password_verify($passcode, trim($row['passcode_hash']));
    }
    return $passcode === DEFAULT_PASSCODE;
}

function update_passcode($new_passcode) {
    $pdo = get_db();
    $hash = password_hash($new_passcode, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO app_auth (id, passcode_hash) VALUES (1, ?) ON DUPLICATE KEY UPDATE passcode_hash = VALUES(passcode_hash)");
    return $stmt->execute([$hash]);
}

function get_ledger_data() {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT data_value FROM ledger_store WHERE key_name = 'main'");
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row && !empty($row['data_value'])) {
        return json_decode($row['data_value'], true);
    }
    return null;
}

function save_ledger_data($data) {
    $pdo = get_db();
    $json = json_encode($data);
    $stmt = $pdo->prepare("INSERT INTO ledger_store (key_name, data_value) VALUES ('main', ?) ON DUPLICATE KEY UPDATE data_value = VALUES(data_value)");
    return $stmt->execute([$json]);
}

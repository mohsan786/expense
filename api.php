<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Handle non-JSON input or raw POST body
$rawInput = file_get_contents('php://input');
$inputData = json_decode($rawInput, true) ?: $_POST;

if ($action === '') {
    $action = $inputData['action'] ?? '';
}

try {
    if ($action === 'login') {
        $passcode = $inputData['passcode'] ?? '';
        if (verify_passcode($passcode)) {
            $_SESSION['authenticated'] = true;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Incorrect passcode']);
        }
        exit;
    }

    if ($action === 'logout') {
        $_SESSION['authenticated'] = false;
        session_destroy();
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'check_auth') {
        echo json_encode(['authenticated' => is_authenticated()]);
        exit;
    }

    // All actions below require authentication
    require_auth();

    if ($action === 'load') {
        $data = get_ledger_data();
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    if ($action === 'save') {
        $data = $inputData['data'] ?? null;
        if ($data) {
            save_ledger_data($data);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No data provided']);
        }
        exit;
    }

    if ($action === 'update_passcode') {
        $current = $inputData['current_passcode'] ?? '';
        $new = $inputData['new_passcode'] ?? '';
        if (!verify_passcode($current)) {
            echo json_encode(['success' => false, 'error' => 'Current passcode is incorrect']);
            exit;
        }
        if (strlen($new) < 4) {
            echo json_encode(['success' => false, 'error' => 'New passcode must be at least 4 characters']);
            exit;
        }
        update_passcode($new);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Invalid action']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

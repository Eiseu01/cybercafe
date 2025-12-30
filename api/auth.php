<?php
// api/auth.php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';
session_start();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and password required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            echo json_encode(['success' => true, 'message' => 'Login successful', 'role' => $user['role']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}

if ($action === 'logout') {
    session_destroy();
    header("Location: ../pages/login.php");
    exit;
}

// Quick seed for dev (REMOVE IN PRODUCTION)
// Call api/auth.php?action=seed_admin to create user: admin / admin123
if ($action === 'seed_admin') {
    $pass = password_hash('admin123', PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES ('admin', ?, 'Admin')");
        $stmt->execute([$pass]);
        echo json_encode(['success' => true, 'message' => 'Admin created (admin/admin123)']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'seed_staff') {
    $pass = password_hash('staff123', PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES ('staff', ?, 'Staff')");
        $stmt->execute([$pass]);
        echo json_encode(['success' => true, 'message' => 'Staff created (staff/staff123)']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>

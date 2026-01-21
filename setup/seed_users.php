<?php
// setup/seed_users.php
require_once '../includes/db_connect.php';

try {
    // Create Admin User (admin / admin123)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    
    if ($stmt->fetchColumn() == 0) {
        $password = 'admin123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'Admin')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['admin', $hash]);
        
        echo "Admin user created successfully.<br>";
        echo "Username: <strong>admin</strong><br>";
        echo "Password: <strong>admin123</strong><br>";
    } else {
        echo "Admin user already exists.<br>";
    }

    // Create Staff User (staff / staff123)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['staff']);
    
    if ($stmt->fetchColumn() == 0) {
        $password = 'staff123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'Staff')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['staff', $hash]);
        
        echo "Staff user created successfully.<br>";
        echo "Username: <strong>staff</strong><br>";
        echo "Password: <strong>staff123</strong><br>";
    } else {
        echo "Staff user already exists.<br>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

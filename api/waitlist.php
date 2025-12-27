<?php
// api/waitlist.php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// GET: Fetch waitlist
if ($method === 'GET') {
    // Return only 'Waiting' or 'Notified'
    $stmt = $pdo->query("SELECT * FROM waitlist WHERE status IN ('Waiting', 'Notified') ORDER BY request_time ASC");
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

// POST: Add or Update
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Add new
    if ($action === 'add') {
        $name = $input['customer_name'] ?? '';
        if ($name) {
            $stmt = $pdo->prepare("INSERT INTO waitlist (customer_name) VALUES (?)");
            $stmt->execute([$name]);
            echo json_encode(['success' => true, 'message' => 'Added to waitlist']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Name required']);
        }
        exit;
    }
    
    // Cancel/Remove
    if ($action === 'cancel') {
        $id = $input['id'] ?? 0;
        $stmt = $pdo->prepare("UPDATE waitlist SET status = 'Cancelled' WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Cancelled']);
        exit;
    }
    
    // Fulfill (Mark as done when assigned to station)
    // This might be called when starting a session, OR we can provide an endpoint to just update status
    if ($action === 'fulfill') {
        $id = $input['id'] ?? 0;
        $stmt = $pdo->prepare("UPDATE waitlist SET status = 'Fulfilled' WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Fulfilled']);
        exit;
    }
}
?>

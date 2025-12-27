<?php
// api/stations.php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php'; // Ensure user is logged in

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// GET: Fetch all stations with current session details
if ($method === 'GET') {
    try {
        // We join with sessions to get start time if occupied
        // Using LEFT JOIN on current_session_id
        $sql = "
            SELECT 
                s.id, 
                s.station_name, 
                s.status, 
                sess.id as session_id,
                sess.customer_name, 
                sess.start_time,
                sess.total_price
            FROM stations s
            LEFT JOIN sessions sess ON s.current_session_id = sess.id
            ORDER BY s.id ASC
        ";
        
        $stmt = $pdo->query($sql);
        $stations = $stmt->fetchAll();
        
        // Add calculated duration/current fee to the response?
        // PHP can calculate it, or we rely on JS.
        // Let's rely on JS for real-time timer, but send server time
        $serverTime = date('Y-m-d H:i:s');
        
        echo json_encode([
            'success' => true, 
            'server_time' => $serverTime,
            'data' => $stations
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// POST: Start or End Session
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($action === 'start') {
        $stationId = $input['station_id'] ?? null;
        $customerName = $input['customer_name'] ?? 'Guest';
        
        if (!$stationId) {
            echo json_encode(['success' => false, 'message' => 'Station ID required']);
            exit;
        }
        
        try {
            // Call Stored Procedure
            $stmt = $pdo->prepare("CALL StartSession(?, ?)");
            $stmt->execute([$stationId, $customerName]);
            $result = $stmt->fetch(); // Assuming procedure selects 'Success'
            
            echo json_encode(['success' => true, 'message' => 'Session started']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
    
    if ($action === 'end') {
        $sessionId = $input['session_id'] ?? null;
        
        if (!$sessionId) {
            echo json_encode(['success' => false, 'message' => 'Session ID required']);
            exit;
        }
        
        try {
            // Call Stored Procedure
            $stmt = $pdo->prepare("CALL EndSession(?)");
            $stmt->execute([$sessionId]);
            $result = $stmt->fetch(); // Returns total_fee
            
            echo json_encode([
                'success' => true, 
                'message' => 'Session ended',
                'fee' => $result['total_fee'] ?? 0
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid Request']);
?>

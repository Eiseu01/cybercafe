<?php
// api/computers.php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// GET: Fetch all computers with current session details
if ($method === 'GET') {
    try {
        // Query against 'computers' table
        $sql = "
            SELECT 
                c.id, 
                c.computer_name, 
                c.status, 
                c.hourly_rate as default_rate, 
                sess.id as session_id,
                sess.customer_name, 
                sess.start_time,
                sess.total_price,
                sess.hourly_rate as session_rate
            FROM computers c
            LEFT JOIN sessions sess ON c.current_session_id = sess.id
            ORDER BY c.id ASC
        ";
        
        $stmt = $pdo->query($sql);
        $computers = $stmt->fetchAll();

        // Get Waitlist Count
        $wSql = "SELECT COUNT(*) as count FROM waitlist WHERE status = 'Waiting'";
        $wStmt = $pdo->query($wSql);
        $wCount = $wStmt->fetch()['count'];

        // Format dates
        $serverTime = date('c'); 
        foreach ($computers as &$pc) {
            if (!empty($pc['start_time'])) {
                $pc['start_time'] = date('c', strtotime($pc['start_time']));
            }
            // Normalize rate output for frontend (use session rate if active, else default)
            $pc['current_rate'] = $pc['session_rate'] ? floatval($pc['session_rate']) : floatval($pc['default_rate']);
        }
        
        echo json_encode([
            'success' => true, 
            'server_time' => $serverTime,
            'waiting_count' => $wCount,
            'data' => $computers
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// POST Actions
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Add Computer
    if ($action === 'add_computer') {
        $name = $input['name'] ?? '';
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Computer name required']);
            exit;
        }
        
        try {
            // Updated to insert into 'computers'
            // Default rate set by DB schema (20.00)
            $stmt = $pdo->prepare("INSERT INTO computers (computer_name, status) VALUES (?, 'Available')");
            $stmt->execute([$name]);
            echo json_encode(['success' => true, 'message' => 'Computer added']);
        } catch (PDOException $e) {
             echo json_encode(['success' => false, 'message' => 'Error adding computer: ' . $e->getMessage()]);
        }
        exit;
    }

    // Edit Computer (Name, Rate, Status)
    if ($action === 'edit_computer') {
        $id = $input['id'] ?? null;
        $name = $input['name'] ?? '';
        $rate = $input['rate'] ?? null;
        $status = $input['status'] ?? null;
        
        if (!$id || empty($name)) {
            echo json_encode(['success' => false, 'message' => 'ID and Name required']);
            exit;
        }

        try {
            // 1. Update Name
            $stmt = $pdo->prepare("UPDATE computers SET computer_name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            
            // 2. Update Rate (Simultaneous Update: Persistent + Active Session)
            if ($rate !== null && is_numeric($rate)) {
                
                // A. Update Persistent Rate (in computers table)
                $updPc = $pdo->prepare("UPDATE computers SET hourly_rate = ? WHERE id = ?");
                $updPc->execute([$rate, $id]);
                
                // B. Update Active Session Rate (if exists)
                $chk = $pdo->query("SELECT current_session_id FROM computers WHERE id = $id");
                $pc = $chk->fetch();
                if ($pc && $pc['current_session_id']) {
                    $updSess = $pdo->prepare("UPDATE sessions SET hourly_rate = ? WHERE id = ?");
                    $updSess->execute([$rate, $pc['current_session_id']]);
                }
            }
            
            // 3. Update Status (Only if provided and NOT Occupied)
            if ($status && in_array($status, ['Available', 'Maintenance'])) {
                 $chk = $pdo->prepare("SELECT status FROM computers WHERE id = ?");
                 $chk->execute([$id]);
                 $cur = $chk->fetch();
                 
                 if ($cur && $cur['status'] !== 'Occupied') {
                     $updStat = $pdo->prepare("UPDATE computers SET status = ? WHERE id = ?");
                     $updStat->execute([$status, $id]);
                 }
            }
            
            echo json_encode(['success' => true, 'message' => 'Computer updated']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Delete Computer
    if ($action === 'delete_computer') {
        $id = $input['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID required']);
            exit;
        }

        try {
            $check = $pdo->prepare("SELECT status FROM computers WHERE id = ?");
            $check->execute([$id]);
            $s = $check->fetch();
            
            if (!$s) { echo json_encode(['success' => false, 'message' => 'Computer not found']); exit; }
            if ($s['status'] !== 'Available') {
                echo json_encode(['success' => false, 'message' => 'Cannot delete active computer. Stop session first.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM computers WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Computer deleted']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Start Session
    if ($action === 'start') {
        $id = $input['station_id'] ?? $input['computer_id'] ?? null; // Support both for now
        $customer = $input['customer_name'] ?? 'Guest';
        
        if (!$id) { echo json_encode(['success' => false, 'message' => 'ID required']); exit; }
        
        try {
            // Unique Customer Check
            $check = $pdo->prepare("SELECT id FROM sessions WHERE customer_name = ? AND status = 'Active'");
            $check->execute([$customer]);
            if ($check->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Customer is already in an active session']);
                exit;
            }

            $stmt = $pdo->prepare("CALL StartSession(?, ?)");
            $stmt->execute([$id, $customer]);
            echo json_encode(['success' => true, 'message' => 'Session started']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
    
    // End Session
    if ($action === 'end') {
        $sessionId = $input['session_id'] ?? null;
        if (!$sessionId) { echo json_encode(['success' => false, 'message' => 'ID required']); exit; }
        
        try {
            $stmt = $pdo->prepare("CALL EndSession(?)");
            $stmt->execute([$sessionId]);
            $result = $stmt->fetch();
            
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

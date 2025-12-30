<?php
// api/dashboard_stats.php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';

try {
    $stats = [];
    
    // 1. Revenue Stats (Today vs Yesterday)
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    
    // Today
    $stmt = $pdo->prepare("SELECT SUM(amount) as revenue, COUNT(*) as tx_count FROM transactions WHERE DATE(payment_time) = ?");
    $stmt->execute([$today]);
    $curr = $stmt->fetch();
    $stats['revenue_today'] = $curr['revenue'] ?? 0;
    $stats['tx_today'] = $curr['tx_count'] ?? 0;
    
    // Yesterday
    $stmt->execute([$yesterday]);
    $prev = $stmt->fetch();
    $stats['revenue_yesterday'] = $prev['revenue'] ?? 0;
    
    // Growth Calc
    if ($stats['revenue_yesterday'] > 0) {
        $growth = (($stats['revenue_today'] - $stats['revenue_yesterday']) / $stats['revenue_yesterday']) * 100;
        $stats['revenue_growth'] = round($growth, 1);
    } else {
        $stats['revenue_growth'] = $stats['revenue_today'] > 0 ? 100 : 0;
    }

    // 2. Computers Status Counts
    $res = $pdo->query("SELECT status, COUNT(*) as cnt FROM computers GROUP BY status");
    $statuses = $res->fetchAll(PDO::FETCH_KEY_PAIR); // ['Available' => 5, 'Occupied' => 3, 'Maintenance' => 1]
    
    // Ensure all keys exist
    $occupied = $statuses['Occupied'] ?? 0;
    $available = $statuses['Available'] ?? 0; // "Available" status in DB means it is NOT in Maintenance
    $maintenance = $statuses['Maintenance'] ?? 0;
    
    $total = $occupied + $available + $maintenance;
    
    $stats['pc_total'] = $total;
    $stats['pc_occupied'] = $occupied;
    $stats['pc_available'] = $available; // Explicitly just 'Available' ones
    $stats['pc_maintenance'] = $maintenance;
    
    // 3. Waitlist Count (Dynamic)
    $stmtWait = $pdo->query("SELECT COUNT(*) FROM waitlist WHERE status = 'Waiting'");
    $stats['waitlist_count'] = $stmtWait->fetchColumn();

    // 4. Recent Transactions (Feed)
    $stmt = $pdo->query("
        SELECT 
            t.payment_time as time,
            s.customer_name,
            c.computer_name,
            t.amount
        FROM transactions t
        JOIN sessions s ON t.session_id = s.id
        LEFT JOIN computers c ON s.computer_id = c.id
        ORDER BY t.payment_time DESC
        LIMIT 10
    ");
    $stats['recent_transactions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 5. Elite Users
    $stmt = $pdo->query("
        SELECT customer_name, SUM(amount) as total_spent, COUNT(*) as visits
        FROM transactions t
        JOIN sessions s ON t.session_id = s.id
        GROUP BY customer_name
        ORDER BY total_spent DESC
        LIMIT 10
    ");
    $stats['top_customers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $stats]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

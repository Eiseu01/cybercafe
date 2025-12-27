<?php
// api/transactions.php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Get stats
    $stats = [];
    try {
        $stmt = $pdo->query("SELECT * FROM v_DailyRevenue ORDER BY report_date DESC LIMIT 1");
        $today = $stmt->fetch();
        $stats['today_revenue'] = $today['total_revenue'] ?? 0;
        $stats['today_count'] = $today['total_transactions'] ?? 0;
    } catch (Exception $e) { $stats['error'] = $e->getMessage(); }
    
    // Get recent transactions
    // Join with sessions to get station name if needed, but transactions table has minimal info.
    // Let's Join with Sessions and Stations to be verbose.
    $sql = "
        SELECT 
            t.id, 
            t.amount, 
            t.payment_time, 
            s.customer_name,
            st.station_name
        FROM transactions t
        JOIN sessions s ON t.session_id = s.id
        JOIN stations st ON s.station_id = st.id
        ORDER BY t.payment_time DESC
        LIMIT 50
    ";
    
    $stmt = $pdo->query($sql);
    $history = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true, 
        'stats' => $stats, 
        'data' => $history
    ]);
}
?>

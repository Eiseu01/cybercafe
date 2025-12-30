<?php
// api/transactions.php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Unauthorized access");
    }

    // Params array for binding
    $params = [];
    $conditions = [];

    // 1. Date Filter (Default: Today)
    $dateFilter = $_GET['date'] ?? date('Y-m-d');
    
    // If user explicitly asks for 'all', we skip the date condition
    if ($dateFilter !== 'all') {
        $conditions[] = "DATE(t.payment_time) = ?";
        $params[] = $dateFilter;
    }

    // 2. Search Filter
    if(isset($_GET['q']) && !empty($_GET['q'])) {
        $search = "%" . $_GET['q'] . "%";
        $conditions[] = "(s.customer_name LIKE ? OR st.computer_name LIKE ?)";
        $params[] = $search;
        $params[] = $search;
    }

    // Build WHERE clause
    $whereSql = "";
    if (!empty($conditions)) {
        $whereSql = "WHERE " . implode(" AND ", $conditions);
    }
    
    // 3. Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    if ($limit < 1) $limit = 10;
    if ($page < 1) $page = 1;
    
    $offset = ($page - 1) * $limit;
    
    // Count Total (for pagination)
    $countSql = "
        SELECT COUNT(*) 
        FROM transactions t
        JOIN sessions s ON t.session_id = s.id
        LEFT JOIN computers st ON s.computer_id = st.id
        $whereSql
    ";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalRecords = $countStmt->fetchColumn();
    $totalPages = ceil($totalRecords / $limit);

    // Main Query
    $sql = "
        SELECT 
            t.id, 
            t.amount, 
            t.payment_method,
            t.payment_time, 
            s.customer_name,
            s.start_time,
            s.end_time,
            st.computer_name
        FROM transactions t
        JOIN sessions s ON t.session_id = s.id
        LEFT JOIN computers st ON s.computer_id = st.id
        $whereSql
        ORDER BY t.payment_time DESC
        LIMIT $limit OFFSET $offset
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true, 
        'data' => $transactions,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_records' => $totalRecords
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

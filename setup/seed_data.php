<?php
// seed_data.php
require_once '../includes/db_connect.php';

echo "<h1>Seeding Data...</h1>";
set_time_limit(300); // 5 mins max

try {
    // 1. Clear old data (Optional? No, let's just append or ignore)
    // $pdo->exec("TRUNCATE TABLE transactions"); 
    // $pdo->exec("TRUNCATE TABLE sessions");
    
    $stations = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // IDs
    
    // 2. Generate Sessions & Transactions
    $count = 0;
    
    $stmtSession = $pdo->prepare("INSERT INTO sessions (station_id, customer_name, start_time, end_time, total_price, status) VALUES (?, ?, ?, ?, ?, 'Completed')");
    $stmtTrans = $pdo->prepare("INSERT INTO transactions (session_id, amount, payment_method, payment_time) VALUES (?, ?, 'Cash', ?)");
    
    for ($i = 0; $i < 1200; $i++) {
        $sid = $stations[array_rand($stations)];
        // ... (rest of logic is fine)
        $name = "Customer_" . rand(1000, 9999);
        
        // Random date in last 30 days
        $daysAgo = rand(0, 30);
        $hoursAgo = rand(1, 23);
        
        $start = date('Y-m-d H:i:s', strtotime("-{$daysAgo} days -{$hoursAgo} hours"));
        $duration = rand(30, 240); // minutes
        $end = date('Y-m-d H:i:s', strtotime($start . " +{$duration} minutes"));
        
        // Calculate fee (Simple: $20/hr)
        $rate = 20;
        $fee = round(($duration / 60) * $rate, 2);
        
        // Insert Session
        $stmtSession->execute([$sid, $name, $start, $end, $fee]);
        $sessionId = $pdo->lastInsertId();
        
        // Insert Transaction
        $stmtTrans->execute([$sessionId, $fee, $end]);
        
        $count++;
    }
    
    echo "<p style='color:green'>Successfully seeded $count records.</p>";
    
    // Refresh Views if needed? Views are virtual, so no need.
    
    echo "<p><a href='../pages/dashboard.php'>Go to Dashboard</a></p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>

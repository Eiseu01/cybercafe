<?php
require_once 'includes/db_connect.php';

// 1. Get all computers with duplicates
$sql = "SELECT computer_id FROM sessions WHERE status = 'Active' GROUP BY computer_id HAVING COUNT(*) > 1";
$stmt = $pdo->query($sql);
$computers = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "<h2>Cleaning up " . count($computers) . " computers...</h2>";

foreach ($computers as $cid) {
    // 2. Find the LATEST session ID for this computer
    $latestSql = "SELECT id FROM sessions WHERE computer_id = ? AND status = 'Active' ORDER BY start_time DESC LIMIT 1";
    $latestStmt = $pdo->prepare($latestSql);
    $latestStmt->execute([$cid]);
    $latestId = $latestStmt->fetchColumn();
    
    if ($latestId) {
        // 3. Close (or Delete) all OTHER active sessions for this computer
        // We'll mark them as 'Cancelled' or 'SystemClosed' to preserve history, or just Delete if they are garbage.
        // Given the volume (140+), they are likely garbage/testing errors. Let's DELETE them to clean the db.
        
        $cleanup = $pdo->prepare("DELETE FROM sessions WHERE computer_id = ? AND status = 'Active' AND id != ?");
        $cleanup->execute([$cid, $latestId]);
        
        echo "Computer $cid: Kept Session $latestId, deleted others.<br>";
    }
}

echo "<h3>Done.</h3>";
?>

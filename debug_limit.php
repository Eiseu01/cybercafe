<?php
require_once 'includes/db_connect.php';
// Mark Active sessions as Completed for computers that are Available (Fixes zombies)
$sql = "UPDATE sessions SET status = 'Completed', end_time = NOW() WHERE status = 'Active' AND computer_id IN (SELECT id FROM computers WHERE status = 'Available')";
$stmt = $pdo->exec($sql);
echo "Cleanup complete. Updated rows: " . $stmt;
?>

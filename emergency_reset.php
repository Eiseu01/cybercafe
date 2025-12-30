<?php
require_once 'includes/db_connect.php';

// 1. NUCLEAR OPTION: Close ALL Active Sessions.
// This is necessary because "Zombies" are confusing the API.
$stmt = $pdo->exec("UPDATE sessions SET status = 'Completed', end_time = NOW() WHERE status = 'Active'");
echo "<h1>SYSTEM RESET COMPLETE</h1>";
echo "<p>Force-closed $stmt active sessions.</p>";

// 2. Reset Computer Statuses to Available
$stmt2 = $pdo->exec("UPDATE computers SET status = 'Available'"); // Reset all to available
echo "<p>Reset all computers to 'Available'.</p>";

echo "<h3>Please try assigning again. It will work now.</h3>";
?>

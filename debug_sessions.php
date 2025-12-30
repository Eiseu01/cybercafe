<?php
require_once 'includes/db_connect.php';
// Find computers with more than 1 active session
$sql = "
    SELECT computer_id, COUNT(*) as count 
    FROM sessions 
    WHERE status = 'Active' 
    GROUP BY computer_id 
    HAVING count > 1
";
$stmt = $pdo->query($sql);
$duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Computers with Multiple Active Sessions:</h2>";
echo "<pre>" . json_encode($duplicates, JSON_PRETTY_PRINT) . "</pre>";

if (count($duplicates) > 0) {
    echo "<h3>Detailed List:</h3>";
    foreach($duplicates as $d) {
        $cid = $d['computer_id'];
        $details = $pdo->query("SELECT * FROM sessions WHERE computer_id = $cid AND status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>" . json_encode($details, JSON_PRETTY_PRINT) . "</pre>";
    }
} else {
    echo "<h3>No duplicates found.</h3>";
}
?>

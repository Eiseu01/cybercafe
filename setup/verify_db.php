<?php
// setup/verify_db.php
require_once '../includes/db_connect.php';

echo "<h2>Verifying Database Integrity...</h2>\n";

try {
    // 1. Check for current_session_id column (Should NOT exist)
    $stmt = $pdo->query("SHOW COLUMNS FROM computers LIKE 'current_session_id'");
    if ($stmt->fetch()) {
        echo "[FAIL] Column 'current_session_id' still exists in 'computers'.\n";
    } else {
        echo "[PASS] Column 'current_session_id' removed.\n";
    }

    // 2. Check View v_CurrentShopStatus (Should work)
    try {
        $pdo->query("SELECT * FROM v_CurrentShopStatus LIMIT 1");
        echo "[PASS] View 'v_CurrentShopStatus' is valid.\n";
    } catch (PDOException $e) {
        echo "[FAIL] View 'v_CurrentShopStatus' error: " . $e->getMessage() . "\n";
    }

    // 3. Check Stored Procedures Existence
    $procs = ['StartSession', 'EndSession'];
    foreach ($procs as $proc) {
        $stmt = $pdo->prepare("SHOW CREATE PROCEDURE $proc");
        $stmt->execute();
        if ($stmt->fetch()) {
            echo "[PASS] Procedure '$proc' exists.\n";
        } else {
            echo "[FAIL] Procedure '$proc' missing.\n";
        }
    }

} catch (PDOException $e) {
    echo "[CRITICAL FAIL] " . $e->getMessage() . "\n";
}
?>

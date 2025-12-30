<?php
// setup/repair_procedures.php
require_once '../includes/db_connect.php';

echo "<h2>Repairing Database Schema & Stored Procedures...</h2>";

try {
    // 0. Fix Schema (3NF) - Remove current_session_id from computers if exists
    // We check if column exists first to avoid error
    $checkCol = $pdo->query("SHOW COLUMNS FROM computers LIKE 'current_session_id'");
    if ($checkCol->fetch()) {
        $pdo->exec("ALTER TABLE computers DROP COLUMN current_session_id");
        echo "<p style='color:green'>Schema Normalized: Dropped 'current_session_id' from computers.</p>";
    } else {
        echo "<p style='color:blue'>Schema Check: 'current_session_id' already removed.</p>";
    }

    // 0.1 Fix View v_CurrentShopStatus
    $sqlView = "
    CREATE OR REPLACE VIEW v_CurrentShopStatus AS
    SELECT 
        c.computer_name,
        c.status,
        s.customer_name,
        s.start_time,
        TIMESTAMPDIFF(MINUTE, s.start_time, NOW()) AS duration_minutes
    FROM computers c
    LEFT JOIN sessions s ON c.id = s.computer_id AND s.status = 'Active';
    ";
    $pdo->exec($sqlView);
    echo "<p style='color:green'>View v_CurrentShopStatus Repaired.</p>";


    // 1. Drop existing procedure
    $pdo->exec("DROP PROCEDURE IF EXISTS EndSession");
    
    // 2. Re-create EndSession with strict logic (No current_session_id update)
    $sql = "
    CREATE PROCEDURE EndSession(IN p_session_id INT)
    BEGIN
        DECLARE v_start_time DATETIME;
        DECLARE v_end_time DATETIME;
        DECLARE v_rate DECIMAL(10,2);
        DECLARE v_hours DECIMAL(10,4);
        DECLARE v_total DECIMAL(10,2);
        DECLARE v_computer_id INT;

        -- Get session details
        SELECT start_time, hourly_rate, computer_id 
        INTO v_start_time, v_rate, v_computer_id
        FROM sessions 
        WHERE id = p_session_id;

        -- Calculate time and fee
        SET v_end_time = NOW();
        SET v_hours = TIMESTAMPDIFF(MINUTE, v_start_time, v_end_time) / 60.0;
        
        SET v_total = v_hours * v_rate;
        SET v_total = ROUND(v_total, 2);

        -- Update Session
        UPDATE sessions 
        SET end_time = v_end_time, 
            total_price = v_total, 
            status = 'Completed' 
        WHERE id = p_session_id;

        -- Update Computer (Just set availability, no session ID clearing needed)
        UPDATE computers 
        SET status = 'Available' 
        WHERE id = v_computer_id;
        
        -- Return fee
        SELECT v_total as total_fee;
    END;
    ";
    $pdo->exec($sql);
    echo "<p style='color:green'>EndSession Procedure Repaired.</p>";

    // 3. Drop and Re-create StartSession
    $pdo->exec("DROP PROCEDURE IF EXISTS StartSession");
    
    $sqlStart = "
    CREATE PROCEDURE StartSession(IN p_computer_id INT, IN p_customer_name VARCHAR(100))
    BEGIN
        DECLARE v_rate DECIMAL(10,2);
        
        -- Get Rate
        SELECT hourly_rate INTO v_rate FROM computers WHERE id = p_computer_id;
        
        -- FAILSAFE: Close any existing 'Active' sessions for this computer (Zombie Kill)
        UPDATE sessions SET status = 'Completed', end_time = NOW() 
        WHERE computer_id = p_computer_id AND status = 'Active';

        -- Create Session
        INSERT INTO sessions (computer_id, customer_name, start_time, hourly_rate, status)
        VALUES (p_computer_id, p_customer_name, NOW(), v_rate, 'Active');
        
        -- Update Computer Status
        UPDATE computers 
        SET status = 'Occupied' 
        WHERE id = p_computer_id;
        
        SELECT LAST_INSERT_ID() as session_id;
    END;
    ";
    $pdo->exec($sqlStart);
    echo "<p style='color:green'>StartSession Procedure Repaired.</p>";
    
    // 4. Force Reset 'Stuck' Computers 
    // Logic: Free up computers that are marked Occupied but have no Active session
    $pdo->exec("
        UPDATE computers c
        SET c.status = 'Available'
        WHERE c.status = 'Occupied' 
        AND NOT EXISTS (
            SELECT 1 FROM sessions s 
            WHERE s.computer_id = c.id AND s.status = 'Active'
        )
    ");
    echo "<p style='color:green'>Stuck Computers Logic Reset.</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>

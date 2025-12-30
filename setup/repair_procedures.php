<?php
require_once '../includes/db_connect.php';

echo "<h2>Repairing Stored Procedures...</h2>";

try {
    // 1. Drop existing procedure
    $pdo->exec("DROP PROCEDURE IF EXISTS EndSession");
    
    // 2. Re-create EndSession with strict logic
    $sql = "
    CREATE PROCEDURE EndSession(IN p_session_id INT)
    BEGIN
        DECLARE v_start_time DATETIME;
        DECLARE v_end_time DATETIME;
        DECLARE v_rate DECIMAL(10,2);
        DECLARE v_hours DECIMAL(10,4);
        DECLARE v_total DECIMAL(10,2);
        DECLARE v_computer_id INT;

        -- Get session details (Schema Cleaned: sessions uses computer_id)
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

        -- Update Computer (Use v_computer_id obtained from station_id)
        UPDATE computers 
        SET current_session_id = NULL, 
            status = 'Available' 
        WHERE id = v_computer_id;
        
        -- Return fee
        SELECT v_total as total_fee;
    END;
    ";
    $pdo->exec($sql);
    echo "<p style='color:green'>EndSession Procedure Repaired (Schema Fixed).</p>";

    // 3. Drop and Re-create StartSession
    $pdo->exec("DROP PROCEDURE IF EXISTS StartSession");
    
    $sqlStart = "
    CREATE PROCEDURE StartSession(IN p_computer_id INT, IN p_customer_name VARCHAR(100))
    BEGIN
        DECLARE v_rate DECIMAL(10,2);
        DECLARE v_status VARCHAR(20);
        DECLARE v_session_id INT;

        -- Check Status
        SELECT status, hourly_rate INTO v_status, v_rate FROM computers WHERE id = p_computer_id;
        
        IF v_status != 'Available' THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Station is not available';
        END IF;

        -- Create Session (Schema Cleaned: sessions uses computer_id)
        INSERT INTO sessions (computer_id, customer_name, start_time, hourly_rate, status)
        VALUES (p_computer_id, p_customer_name, NOW(), v_rate, 'Active');
        
        SET v_session_id = LAST_INSERT_ID();

        -- Update Computer
        UPDATE computers 
        SET current_session_id = v_session_id, 
            status = 'Occupied' 
        WHERE id = p_computer_id;
        
        SELECT v_session_id as session_id;
    END;
    ";
    $pdo->exec($sqlStart);
    echo "<p style='color:green'>StartSession Procedure Repaired (Schema Fixed).</p>";
    
    // 4. Force Reset 'Stuck' Computers (Safety Net)
    $pdo->exec("
        UPDATE computers c
        SET c.status = 'Available', c.current_session_id = NULL
        WHERE c.status = 'Occupied' 
        AND (c.current_session_id IS NULL OR c.current_session_id NOT IN (SELECT id FROM sessions WHERE status = 'Active'))
    ");
    echo "<p style='color:green'>Stuck Computers Logic Reset.</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>

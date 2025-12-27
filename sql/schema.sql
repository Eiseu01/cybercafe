-- sql/schema.sql

-- Create Database
CREATE DATABASE IF NOT EXISTS computer_cafe_db;
USE computer_cafe_db;

-- 1. Users Table (Staff/Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Staff') NOT NULL DEFAULT 'Staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Rates Table (Pricing)
CREATE TABLE IF NOT EXISTS rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rate_name VARCHAR(50) NOT NULL,
    price_per_hour DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Rate
INSERT IGNORE INTO rates (rate_name, price_per_hour) VALUES ('Standard', 20.00);

-- 3. Stations Table
CREATE TABLE IF NOT EXISTS stations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    station_name VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('Available', 'Occupied', 'Maintenance') NOT NULL DEFAULT 'Available',
    current_session_id INT DEFAULT NULL
);

-- Seed Stations (10 Stations)
INSERT IGNORE INTO stations (station_name) VALUES 
('PC-01'), ('PC-02'), ('PC-03'), ('PC-04'), ('PC-05'),
('PC-06'), ('PC-07'), ('PC-08'), ('PC-09'), ('PC-10');

-- 4. Sessions Table
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    station_id INT NOT NULL,
    customer_name VARCHAR(100),
    start_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    end_time DATETIME NULL,
    total_price DECIMAL(10, 2) DEFAULT 0.00,
    status ENUM('Active', 'Completed') NOT NULL DEFAULT 'Active',
    FOREIGN KEY (station_id) REFERENCES stations(id)
);

-- Index for performance
CREATE INDEX idx_session_start ON sessions(start_time);
CREATE INDEX idx_session_status ON sessions(status);

-- 5. Transactions Table
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Cash',
    payment_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES sessions(id)
);

-- 6. Waitlist Table
CREATE TABLE IF NOT EXISTS waitlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    request_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Waiting', 'Notified', 'Cancelled', 'Fulfilled') NOT NULL DEFAULT 'Waiting'
);

-- ==========================================
-- ADVANCED SQL FEATURES
-- ==========================================

-- A. Stored Function: Calculate Rental Fee
DROP FUNCTION IF EXISTS CalculateRentalFee;
DELIMITER //
CREATE FUNCTION CalculateRentalFee(start_dt DATETIME, end_dt DATETIME, rate DECIMAL(10,2)) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE hours DECIMAL(10,2);
    DECLARE fee DECIMAL(10,2);
    
    -- Calculate difference in hours (including partials)
    SET hours = TIMESTAMPDIFF(MINUTE, start_dt, end_dt) / 60.0;
    
    -- Minimum charge for 1 hour if less
    IF hours < 0.02 THEN -- Just a tiny threshold for testing, usually 1 hr min
        SET hours = 0;
    END IF;
    
    -- Simple calculation: hours * rate
    SET fee = hours * rate;
    
    RETURN ROUND(fee, 2);
END //
DELIMITER ;

-- B. Stored Procedure: Start Session
DROP PROCEDURE IF EXISTS StartSession;
DELIMITER //
CREATE PROCEDURE StartSession(IN p_station_id INT, IN p_customer_name VARCHAR(100))
BEGIN
    DECLARE v_station_status VARCHAR(20);
    
    -- Check if station is available
    SELECT status INTO v_station_status FROM stations WHERE id = p_station_id;
    
    IF v_station_status = 'Available' THEN
        -- Insert new session
        INSERT INTO sessions (station_id, customer_name, start_time, status)
        VALUES (p_station_id, p_customer_name, NOW(), 'Active');
        
        -- Update station status
        UPDATE stations 
        SET status = 'Occupied', current_session_id = LAST_INSERT_ID()
        WHERE id = p_station_id;
        
        SELECT 'Success' AS message, LAST_INSERT_ID() as session_id;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Station is not available';
    END IF;
END //
DELIMITER ;

-- C. Stored Procedure: End Session
DROP PROCEDURE IF EXISTS EndSession;
DELIMITER //
CREATE PROCEDURE EndSession(IN p_session_id INT)
BEGIN
    DECLARE v_start_time DATETIME;
    DECLARE v_end_time DATETIME;
    DECLARE v_rate DECIMAL(10,2);
    DECLARE v_fee DECIMAL(10,2);
    DECLARE v_station_id INT;
    
    -- Get session details
    SELECT start_time, station_id INTO v_start_time, v_station_id 
    FROM sessions WHERE id = p_session_id;
    
    SET v_end_time = NOW();
    
    -- Get Standard Rate (Assuming ID 1 for now, or fetch latest)
    SELECT price_per_hour INTO v_rate FROM rates LIMIT 1;
    
    -- Calculate Fee
    SET v_fee = CalculateRentalFee(v_start_time, v_end_time, v_rate);
    
    -- Update Session
    UPDATE sessions 
    SET end_time = v_end_time, total_price = v_fee, status = 'Completed'
    WHERE id = p_session_id;
    
    -- Update Station Status
    UPDATE stations 
    SET status = 'Available', current_session_id = NULL 
    WHERE id = v_station_id;
    
    SELECT v_fee AS total_fee;
END //
DELIMITER ;

-- D. Trigger: After Session Ends (Auto-create Transaction Record)
-- Note: Some instructors prefer manual payment transaction creation. 
-- We'll create a trigger that logs it automatically for "Pay on End" model, 
-- or we can leave it to the application.
-- Let's use a trigger to log an audit or just default transaction if not paid separately.
-- For this requirement "Trigger", let's create an Audit log or update stats.
-- actually, the requirement asks for a Trigger. 
-- Let's create a trigger that moves Completed sessions to a 'transactions' table if not already handled?
-- Better: Trigger to Auto-Cancelled Waitlist if too old? No.
-- Let's do: When a session completes, insert into transactions automatically (Assuming cash payment at end).

DROP TRIGGER IF EXISTS AfterSessionComplete;
DELIMITER //
CREATE TRIGGER AfterSessionComplete
AFTER UPDATE ON sessions
FOR EACH ROW
BEGIN
    IF NEW.status = 'Completed' AND OLD.status = 'Active' THEN
        INSERT INTO transactions (session_id, amount, payment_method, payment_time)
        VALUES (NEW.id, NEW.total_price, 'Cash', NOW());
    END IF;
END //
DELIMITER ;

-- E. View: Daily Revenue
CREATE OR REPLACE VIEW v_DailyRevenue AS
SELECT 
    DATE(payment_time) AS report_date,
    COUNT(id) AS total_transactions,
    SUM(amount) AS total_revenue
FROM transactions
GROUP BY DATE(payment_time);

-- F. View: Current Shop Status
CREATE OR REPLACE VIEW v_CurrentShopStatus AS
SELECT 
    s.station_name,
    s.status,
    sess.customer_name,
    sess.start_time,
    TIMESTAMPDIFF(MINUTE, sess.start_time, NOW()) AS duration_minutes
FROM stations s
LEFT JOIN sessions sess ON s.current_session_id = sess.id;

-- G. Subquery Example (Used in Analytics)
-- "Find customers who spent more than average"
-- SELECT customer_name FROM sessions WHERE total_price > (SELECT AVG(total_price) FROM sessions);


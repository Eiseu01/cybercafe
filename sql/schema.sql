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

-- 3. Computers Table (Renamed from Stations)
CREATE TABLE IF NOT EXISTS computers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    computer_name VARCHAR(50) NOT NULL UNIQUE,
    hourly_rate DECIMAL(10, 2) NOT NULL DEFAULT 20.00,
    status ENUM('Available', 'Occupied', 'Maintenance') NOT NULL DEFAULT 'Available'
    -- 3NF Fix: Removed current_session_id (derived from sessions table)
);

-- Seed Computers (10 units)
INSERT IGNORE INTO computers (computer_name) VALUES 
('PC-01'), ('PC-02'), ('PC-03'), ('PC-04'), ('PC-05'),
('PC-06'), ('PC-07'), ('PC-08'), ('PC-09'), ('PC-10');

-- 4. Sessions Table
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    computer_id INT NOT NULL, -- Renamed from station_id
    customer_name VARCHAR(100),
    start_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    end_time DATETIME NULL,
    hourly_rate DECIMAL(10, 2) NOT NULL DEFAULT 20.00, -- Snapshot rate
    total_price DECIMAL(10, 2) DEFAULT 0.00,
    status ENUM('Active', 'Completed') NOT NULL DEFAULT 'Active',
    FOREIGN KEY (computer_id) REFERENCES computers(id)
);

-- Index for performance
CREATE INDEX idx_session_start ON sessions(start_time);
CREATE INDEX idx_session_status ON sessions(status);
CREATE INDEX idx_session_computer ON sessions(computer_id);

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

-- A. Stored Function: CalculateRentalFee
DROP FUNCTION IF EXISTS CalculateRentalFee;
CREATE FUNCTION CalculateRentalFee(start_dt DATETIME, end_dt DATETIME, rate DECIMAL(10,2)) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE hours DECIMAL(10,2);
    DECLARE fee DECIMAL(10,2);
    DECLARE rounded_fee DECIMAL(10,2);
    
    SET hours = TIMESTAMPDIFF(MINUTE, start_dt, end_dt) / 60.0;
    
    IF hours < 0 THEN 
        SET hours = 0;
    END IF;
    
    SET fee = hours * rate;
    SET rounded_fee = ROUND(fee * 4) / 4;
    
    RETURN ROUND(rounded_fee, 2);
END;

-- B. Stored Procedure: StartSession
DROP PROCEDURE IF EXISTS StartSession;
CREATE PROCEDURE StartSession(IN p_computer_id INT, IN p_customer_name VARCHAR(100))
BEGIN
    DECLARE v_status VARCHAR(20);
    DECLARE v_rate DECIMAL(10, 2);
    
    -- Check if computer is available
    SELECT status, hourly_rate INTO v_status, v_rate FROM computers WHERE id = p_computer_id;
    
    IF v_status = 'Available' THEN
        -- Insert new session
        INSERT INTO sessions (computer_id, customer_name, start_time, hourly_rate, status)
        VALUES (p_computer_id, p_customer_name, NOW(), v_rate, 'Active');
        
        -- Update computer status
        UPDATE computers 
        SET status = 'Occupied'
        WHERE id = p_computer_id;
        
        SELECT 'Success' AS message, LAST_INSERT_ID() as session_id;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Computer is not available';
    END IF;
END;

-- C. Stored Procedure: EndSession
DROP PROCEDURE IF EXISTS EndSession;
CREATE PROCEDURE EndSession(IN p_session_id INT)
BEGIN
    DECLARE v_start_time DATETIME;
    DECLARE v_end_time DATETIME;
    DECLARE v_rate DECIMAL(10,2);
    DECLARE v_fee DECIMAL(10,2);
    DECLARE v_computer_id INT;
    
    -- Get session details
    SELECT start_time, hourly_rate, computer_id INTO v_start_time, v_rate, v_computer_id 
    FROM sessions WHERE id = p_session_id;
    
    SET v_end_time = NOW();
    
    -- Calculate Fee
    SET v_fee = CalculateRentalFee(v_start_time, v_end_time, v_rate);
    
    -- Update Session
    UPDATE sessions 
    SET end_time = v_end_time, total_price = v_fee, status = 'Completed'
    WHERE id = p_session_id;
    
    -- Update Computer Status
    UPDATE computers 
    SET status = 'Available'
    WHERE id = v_computer_id;
    
    SELECT v_fee AS total_fee;
END;

-- D. Trigger: AfterSessionComplete
DROP TRIGGER IF EXISTS AfterSessionComplete;
CREATE TRIGGER AfterSessionComplete
AFTER UPDATE ON sessions
FOR EACH ROW
BEGIN
    IF NEW.status = 'Completed' AND OLD.status = 'Active' THEN
        INSERT INTO transactions (session_id, amount, payment_method, payment_time)
        VALUES (NEW.id, NEW.total_price, 'Cash', NOW());
    END IF;
END;

-- E. View: Daily Revenue
CREATE OR REPLACE VIEW v_DailyRevenue AS
SELECT 
    DATE(payment_time) AS report_date,
    COUNT(id) AS total_transactions,
    SUM(amount) AS total_revenue
FROM transactions
GROUP BY DATE(payment_time);

-- F. View: Current Shop Status (Fixed & 3NF)
CREATE OR REPLACE VIEW v_CurrentShopStatus AS
SELECT 
    c.computer_name,
    c.status,
    s.customer_name,
    s.start_time,
    TIMESTAMPDIFF(MINUTE, s.start_time, NOW()) AS duration_minutes
FROM computers c
LEFT JOIN sessions s ON c.id = s.computer_id AND s.status = 'Active';

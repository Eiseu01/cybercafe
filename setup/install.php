<?php
//install.php
require_once '../includes/db_connect.php';

echo "<h1>Computer Cafe System - Installation</h1>";

try {
    
    // Generic SQL Parser to handle DELIMITER //
    $rawSql = file_get_contents('../sql/schema.sql');
    
    // 1. Split by "DELIMITER //"
    // The schema structure is:
    // [Standard SQL]
    // DELIMITER //
    // [Procedure/Trigger] //
    // DELIMITER ;
    // [Standard SQL or more delimiters]
    
    // Normalize newlines
    $rawSql = str_replace("\r\n", "\n", $rawSql);
    
    // Split into chunks based on the custom delimiter definition
    // Note: This is tailored to the specific schema.sql format provided
    $segments = explode('DELIMITER //', $rawSql);
    
    // Process the first segment (Standard SQL before any procedure)
    $preamble = $segments[0];
    execute_sql_segment($pdo, $preamble);
    
    // Process the rest
    for ($i = 1; $i < count($segments); $i++) {
        // Each segment looks like: "CREATE PROCEDURE ... END // \n DELIMITER ; \n [Other SQL]"
        // We need to split by "DELIMITER ;" to isolate the procedure and the following SQL
        $parts = explode('DELIMITER ;', $segments[$i]);
        
        // Part 0 is the Procedure/Trigger content ending with //
        $procQuery = trim($parts[0]);
        // Remove the trailing //
        if (substr($procQuery, -2) == '//') {
            $procQuery = substr($procQuery, 0, -2);
        }
        
        // Execute the Procedure/Trigger as a single block
        if (!empty($procQuery)) {
            try {
                $pdo->exec($procQuery);
            } catch (PDOException $e) {
                // Ignore "Function already exists" errors if re-running
                if (strpos($e->getMessage(), '1304') !== false || strpos($e->getMessage(), '1359') !== false) {
                    // exists
                } else {
                    throw $e;
                }
            }
        }
        
        // Part 1 (if exists) is standard SQL after the procedure (e.g., inserts or next create)
        if (isset($parts[1])) {
            execute_sql_segment($pdo, $parts[1]);
        }
    }
    
    echo "<p style='color:green'>Database and Advanced Features installed successfully!</p>";

} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}

function execute_sql_segment($pdo, $sql) {
    $queries = explode(';', $sql);
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                $pdo->exec($query);
            } catch (PDOException $e) {
                 // Ignore "Table exists" or "Database exists"
                 // Code 1007 = db exists, 1050 = table exists, 1061 = duplicate key name
                 if (strpos($e->getMessage(), '1007') !== false || strpos($e->getMessage(), '1050') !== false || strpos($e->getMessage(), '1061') !== false) {
                     continue; 
                 }
                 // Rethrow critical errors
                 // But for non-critical (like 'USE'), just continue if possible?
                 // 'USE' might fail if DB creation failed.
                 
                 // Let's print warning but continue?
                 echo "<p style='color:orange'>Warning: " . $e->getMessage() . "</p>";
            }
        }
    }
}


echo "<p><a href='../pages/dashboard.php'>Go to Dashboard</a></p>";
?>

<?php
// includes/auth_check.php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    // Using simple relative path assuming this is included by files in subdirectories one level deep (pages/, api/)
    // If included from root, this might be tricky, but we moved everything to subdirs.
    header("Location: ../pages/login.php");
    exit;
}
?>

<?php
// logout.php - Secure Logout Script
session_start();

// 1. Regenerate session ID to prevent fixation (optional but recommended)
session_regenerate_id(true);

// 2. Unset all session variables
$_SESSION = array();

// 3. Destroy the session cookie (if any)
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// 4. Destroy the session completely
session_destroy();

// 5. Optional: Add a success message (flash)
$_SESSION = []; // Re-init for flash message
session_start();
$_SESSION['message'] = "You have been logged out successfully.";
$_SESSION['msg_type'] = "success";

// 6. Redirect to homepage
header("Location: index.php");
exit;
?>
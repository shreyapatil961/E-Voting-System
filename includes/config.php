<?php
// Database configuration
define('DB_HOST', 'sql107.infinityfree.com');
define('DB_USER', 'if0_41158376');
define('DB_PASS', '8NkmOJl0id4');
define('DB_NAME', 'if0_41158376_evoting_db');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Global functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($path) {
    header("Location: " . $path);
    exit();
}

session_start();
?>

<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dss_hospital');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;padding:40px;text-align:center;color:#e74c3c;">
        <h2>⚠️ Database Connection Failed</h2>
        <p>Please ensure XAMPP MySQL is running and run <code>db/setup.sql</code> first.</p>
        <p>Error: ' . $conn->connect_error . '</p>
    </div>');
}

$conn->set_charset("utf8");

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isDoctorLoggedIn() {
    return isset($_SESSION['doctor_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($conn, $data) {
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

function generateRef() {
    return 'DSS' . strtoupper(substr(md5(uniqid()), 0, 8));
}
?>

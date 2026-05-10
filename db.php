<?php
$sessionPath = __DIR__ . '/sessions';
if (!file_exists($sessionPath)) { mkdir($sessionPath, 0777, true); }

session_save_path($sessionPath);

// This tells PHP to allow the session ID to be passed via cookies OR the URL
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 0); 
ini_set('session.use_trans_sid', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = mysqli_connect("localhost", "root", "", "disciplinary_system");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "disciplinary_system";

$conn = mysqli_connect($host, $user, $pass, $dbname);
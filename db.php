<?php
// Connects to the database and starts the session
session_start();
$pdo = new PDO('mysql:host=localhost; dbname=attendance_db; charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Call this at the top of a page that needs login. Example: check_login('student');
function check_login($role = '') {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    if ($role != '' && $_SESSION['user']['role'] != $role) {
        die('Access denied');
    }
}

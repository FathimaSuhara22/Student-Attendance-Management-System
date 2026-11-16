<?php
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    die('Please login as a student.');
}
$me = $_SESSION['user']['user_id'];
$code = $_POST['otp'] ?? '';

// find a code that is not expired and belongs to a course I am enrolled in
$sql = "SELECT o.lecture_id FROM otp_sessions o
        JOIN lectures l ON l.lecture_id = o.lecture_id
        JOIN course_enrollments ce ON ce.course_id = l.course_id
        WHERE o.otp_code = ? AND o.expires_at > NOW() AND ce.student_id = ?
        ORDER BY o.otp_id DESC LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$code, $me]);
$row = $stmt->fetch();

if (!$row) {
    echo 'Invalid or expired code.';
} else {
    $stmt = $pdo->prepare("REPLACE INTO attendance_records (lecture_id, student_id, status) VALUES (?, ?, 'present')");
    $stmt->execute([$row['lecture_id'], $me]);
    echo 'Attendance marked. Thank you!';
}

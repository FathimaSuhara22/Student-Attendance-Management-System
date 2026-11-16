<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Attendance System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="topbar">
  <a class="logo" href="index.php">Student Attendance System</a>
  <nav>
    <a href="index.php">Home</a>
    <?php if (isset($_SESSION['user'])) { $role = $_SESSION['user']['role']; ?>
      <a href="profile.php">Profile</a>
      <?php if ($role == 'student') { ?>
        <a href="student_attendance.php">My Attendance</a>
        <a href="submit_attendance.php">Submit Attendance</a>
      <?php } elseif ($role == 'lecturer') { ?>
        <a href="lecturer_attendance.php">View Attendance</a>
        <a href="mark_attendance.php">Mark Attendance</a>
      <?php } else { ?>
        <a href="admin_attendance.php">Update Attendance</a>
        <a href="admin_add_student.php">Add Student</a>
      <?php } ?>
      <a href="logout.php">Logout</a>
    <?php } else { ?>
      <a href="login.php">Login</a>
    <?php } ?>
  </nav>
</div>
<div class="container">

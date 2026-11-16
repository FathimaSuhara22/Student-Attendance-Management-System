<?php
include 'db.php';
check_login('admin');
$message = '';

$courses = $pdo->query("SELECT * FROM courses")->fetchAll();

if (isset($_POST['add'])) {
    $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (?, ?, ?, 'student')");
        $stmt->execute([$_POST['username'], $hash, $_POST['email']]);
        $new_id = $pdo->lastInsertId();

        // enroll in the ticked courses
        foreach ($_POST['courses'] ?? [] as $course_id) {
            $stmt = $pdo->prepare("INSERT INTO course_enrollments (course_id, student_id) VALUES (?, ?)");
            $stmt->execute([$course_id, $new_id]);
        }
        $message = 'Student added.';
    } catch (PDOException $e) {
        $message = 'Username or email already exists.';
    }
}

include 'header.php';
?>
<div class="card narrow">
  <h2>Add Student</h2>
  <?php if ($message != '') echo "<p class='msg'>$message</p>"; ?>
  <form method="post">
    <label>Username</label>
    <input type="text" name="username" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <label>Courses</label>
    <?php foreach ($courses as $c) { ?>
      <label><input type="checkbox" name="courses[]" value="<?php echo $c['course_id']; ?>">
        <?php echo $c['course_code']; echo " (".$c['course_name'].")"; ?></label>
    <?php } ?>
    <button class="btn" name="add">Add student</button>
  </form>
</div>
<?php include 'footer.php'; ?>

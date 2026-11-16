<?php
include 'db.php';
check_login('admin');
$message = '';
$lecture_id = $_REQUEST['lecture_id'] ?? 0;

// save changes
if (isset($_POST['save'])) {
    foreach ($_POST['status'] as $student_id => $status) {
        $stmt = $pdo->prepare("REPLACE INTO attendance_records (lecture_id, student_id, status) VALUES (?, ?, ?)");
        $stmt->execute([$lecture_id, $student_id, $status]);
    }
    $message = 'Attendance updated.';
}

$lectures = $pdo->query("SELECT l.lecture_id, l.lecture_date, c.course_code FROM lectures l
                         JOIN courses c ON c.course_id = l.course_id ORDER BY l.lecture_date DESC")->fetchAll();

$sql = "SELECT u.user_id, u.username, a.status FROM lectures l
        JOIN course_enrollments ce ON ce.course_id = l.course_id
        JOIN users u ON u.user_id = ce.student_id
        LEFT JOIN attendance_records a ON a.student_id = u.user_id AND a.lecture_id = l.lecture_id
        WHERE l.lecture_id = ? ORDER BY u.username";
$stmt = $pdo->prepare($sql);
$stmt->execute([$lecture_id]);
$students = $stmt->fetchAll();

include 'header.php';
?>
<h2>View and Update Attendance</h2>
<?php if ($message != '') echo "<p class='msg'>$message</p>"; ?>

<form method="get">
  <select name="lecture_id" onchange="this.form.submit()">
    <option value="">-- select lecture --</option>
    <?php foreach ($lectures as $l) { ?>
      <option value="<?php echo $l['lecture_id']; ?>" <?php if ($l['lecture_id'] == $lecture_id) echo 'selected'; ?>>
        <?php echo $l['course_code'] . ' - ' . $l['lecture_date']; ?>
      </option>
    <?php } ?>
  </select>
</form>

<?php if ($students) { ?>
<div class="card">
  <form method="post">
    <input type="hidden" name="lecture_id" value="<?php echo $lecture_id; ?>">
    <table>
      <tr><th>Student</th><th>Status</th></tr>
      <?php foreach ($students as $s) { $status = $s['status'] ?? 'absent'; ?>
        <tr>
          <td><?php echo $s['username']; ?></td>
          <td>
            <select name="status[<?php echo $s['user_id']; ?>]">
              <option value="present" <?php if ($status == 'present') echo 'selected'; ?>>Present</option>
              <option value="absent" <?php if ($status == 'absent') echo 'selected'; ?>>Absent</option>
            </select>
          </td>
        </tr>
      <?php } ?>
    </table>
    <button class="btn" name="save">Save changes</button>
  </form>
</div>
<?php } ?>
<?php include 'footer.php'; ?>

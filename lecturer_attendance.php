<?php
include 'db.php';
check_login('lecturer');
$me = $_SESSION['user']['user_id'];

$stmt = $pdo->prepare("SELECT * FROM courses WHERE lecturer_id = ?");
$stmt->execute([$me]);
$courses = $stmt->fetchAll();

$course_id = $_GET['course_id'] ?? ($courses[0]['course_id'] ?? 0);

// attendance of every student in the chosen course (only my own courses)
$sql = "SELECT u.username,
          (SELECT COUNT(*) FROM lectures WHERE course_id = c.course_id AND lecture_date <= CURDATE()) AS total,
          (SELECT COUNT(*) FROM attendance_records a JOIN lectures l ON l.lecture_id = a.lecture_id
            WHERE l.course_id = c.course_id AND a.student_id = u.user_id AND a.status = 'present') AS present
        FROM courses c
        JOIN course_enrollments ce ON ce.course_id = c.course_id
        JOIN users u ON u.user_id = ce.student_id
        WHERE c.course_id = ? AND c.lecturer_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$course_id, $me]);
$students = $stmt->fetchAll();

// download as CSV
if (isset($_GET['csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=attendance.csv');
    echo "Student,Present,Total,Percentage\n";
    foreach ($students as $s) {
        $p = $s['total'] > 0 ? round($s['present'] * 100 / $s['total']) : 0;
        echo "{$s['username']},{$s['present']},{$s['total']},$p%\n";
    }
    exit;
}
include 'header.php';
?>
<h2>View Attendance</h2>
<form method="get">
  <select name="course_id" onchange="this.form.submit()">
    <?php foreach ($courses as $c) { ?>
      <option value="<?php echo $c['course_id']; ?>" <?php if ($c['course_id'] == $course_id) echo 'selected'; ?>>
        <?php echo $c['course_code'] . ' - ' . $c['course_name']; ?>
      </option>
    <?php } ?>
  </select>
  <a class="btn" href="?course_id=<?php echo $course_id; ?>&csv=1">Download CSV</a>
</form>

<div class="card">
<table>
  <tr>
    <th>Student</th>
    <th>Present</th>
    <th>Total</th>
    <th>Percentage</th>
    <th>Status</th>
  </tr>
  <?php foreach ($students as $s) {
      $percent = $s['total'] > 0 ? round($s['present'] * 100 / $s['total']) : 0; ?>
    <tr>
      <td><?php echo $s['username']; ?></td>
      <td><?php echo $s['present']; ?></td>
      <td><?php echo $s['total']; ?></td>
      <td><?php echo $percent; ?>%</td>
      <td><?php if ($percent < 75){
                  echo "<span class='badge absent'>Low attendance</span>";
                }  else {
                  echo "<span class='badge present'>Highest attendance</span>";
                } ?>
          </td>
    </tr>
  <?php } ?>
</table>
</div>
<?php include 'footer.php'; ?>

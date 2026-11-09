<?php
include 'db.php';
check_login('student');
$me = $_SESSION['user']['user_id'];

// for each course: total lectures held and how many I attended
$sql = "SELECT c.course_code, c.course_name,
          (SELECT COUNT(*) FROM lectures WHERE course_id = c.course_id AND lecture_date <= CURDATE()) AS total,
          (SELECT COUNT(*) FROM attendance_records a JOIN lectures l ON l.lecture_id = a.lecture_id
            WHERE l.course_id = c.course_id AND a.student_id = ? AND a.status = 'present') AS present
        FROM course_enrollments ce JOIN courses c ON c.course_id = ce.course_id
        WHERE ce.student_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$me, $me]);
$courses = $stmt->fetchAll();

include 'header.php';
?>
<h2>My Attendance</h2>
<div class="card">
<table>
  <tr>
    <th>Course</th>
    <th>Present</th>
    <th>Total</th>
    <th>Percentage</th>
    <th>Status</th>
  </tr>
  <?php foreach ($courses as $c) {
      $percent = $c['total'] > 0 ? round($c['present'] * 100 / $c['total']) : 0; ?>
    <tr>
      <td><?php echo $c['course_code'] . ' - ' . $c['course_name']; ?></td>
      <td><?php echo $c['present']; ?></td>
      <td><?php echo $c['total']; ?></td>
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

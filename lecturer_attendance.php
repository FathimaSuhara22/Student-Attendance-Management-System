<?php
include 'db.php';
check_login('lecturer');
$me = $_SESSION['user']['user_id'];

$stmt = $pdo->prepare("SELECT * FROM courses WHERE lecturer_id = ?");
$stmt->execute([$me]);
$courses = $stmt->fetchAll();

$course_id = $_GET['course_id'] ?? ($courses[0]['course_id'] ?? 0);
$search = trim($_GET['search'] ?? '');

// attendance of every student in the chosen course (with search filter)
$sql = "SELECT u.username,
          (SELECT COUNT(*) FROM lectures WHERE course_id = c.course_id AND lecture_date <= CURDATE()) AS total,
          (SELECT COUNT(*) FROM attendance_records a JOIN lectures l ON l.lecture_id = a.lecture_id
            WHERE l.course_id = c.course_id AND a.student_id = u.user_id AND a.status = 'present') AS present
        FROM courses c
        JOIN course_enrollments ce ON ce.course_id = c.course_id
        JOIN users u ON u.user_id = ce.student_id
        WHERE c.course_id = ? AND c.lecturer_id = ?";

$params = [$course_id, $me];

if (!empty($search)) {
    $sql .= " AND u.username LIKE ?";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

// download as CSV (Downloads the full list or filtered list depending on active search)
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

<!-- Course Selection & Search Toolbar -->
<form method="get" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; margin-bottom: 20px;">
  <select name="course_id" onchange="this.form.submit()" style="width: auto; margin: 0;">
    <?php foreach ($courses as $c) { ?>
      <option value="<?php echo $c['course_id']; ?>" <?php if ($c['course_id'] == $course_id) echo 'selected'; ?>>
        <?php echo htmlspecialchars($c['course_code'] . ' - ' . $c['course_name']); ?>
      </option>
    <?php } ?>
  </select>

  <!-- Search Input Bar with Clear Reg No Placeholder -->
  <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
         placeholder="Search Reg No (e.g. 2023/ict/100)" 
         style="width: 280px; margin: 0; padding: 6px;">
  
  <button type="submit" class="btn" style="margin: 0;">Search</button>

  <!-- Reset Button (Appears only when searching) -->
  <?php if (!empty($search)) { ?>
    <a class="btn" href="?course_id=<?php echo $course_id; ?>" style="margin: 0; background-color: #6c757d; text-decoration: none;">Reset</a>
  <?php } ?>
</form>

<!-- Card Header with Download CSV Button cleanly placed above table -->
<div class="card">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h3 style="margin: 0; font-size: 18px;">Student Attendance List</h3>
    <a class="btn" href="?course_id=<?php echo $course_id; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>&csv=1" style="text-decoration: none; font-size: 14px; padding: 6px 12px;">Download CSV</a>
  </div>

  <table>
    <tr>
      <th>Student</th>
      <th>Present</th>
      <th>Total</th>
      <th>Percentage</th>
      <th>Status</th>
    </tr>
    <?php if (empty($students)) { ?>
      <tr>
        <td colspan="5" style="text-align: center;">No students found.</td>
      </tr>
    <?php } else { ?>
      <?php foreach ($students as $s) {
          $percent = $s['total'] > 0 ? round($s['present'] * 100 / $s['total']) : 0; ?>
        <tr>
          <td><?php echo htmlspecialchars($s['username']); ?></td>
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
    <?php } ?>
  </table>
</div>
<?php include 'footer.php'; ?>
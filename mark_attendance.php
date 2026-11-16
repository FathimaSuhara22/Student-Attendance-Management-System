<?php
include 'db.php';
check_login('lecturer');
$me = $_SESSION['user']['user_id'];
$message = '';

// my courses
$stmt = $pdo->prepare("SELECT * FROM courses WHERE lecturer_id = ?");
$stmt->execute([$me]);
$courses = $stmt->fetchAll();

// 1. create a new lecture
if (isset($_POST['create'])) {
    $stmt = $pdo->prepare("SELECT course_id FROM courses WHERE course_id = ? AND lecturer_id = ?");
    $stmt->execute([$_POST['course_id'], $me]);
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO lectures (course_id, lecture_date, start_time, end_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['course_id'], $_POST['date'], $_POST['start'], $_POST['end']]);
        header('Location: mark_attendance.php?lecture_id=' . $pdo->lastInsertId());
        exit;
    }
}

// the lecture we are working on (must belong to me)
$lecture_id = $_REQUEST['lecture_id'] ?? 0;
$stmt = $pdo->prepare("SELECT l.*, c.course_code FROM lectures l
                       JOIN courses c ON c.course_id = l.course_id
                       WHERE l.lecture_id = ? AND c.lecturer_id = ?");
$stmt->execute([$lecture_id, $me]);
$lecture = $stmt->fetch();

if ($lecture) {
    // 2. make a new OTP
    if (isset($_POST['make_otp'])) {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $minutes = (int)$_POST['minutes'];
        $stmt = $pdo->prepare("INSERT INTO otp_sessions (lecture_id, otp_code, expires_at)
                               VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE))");
        $stmt->execute([$lecture_id, $code, $minutes]);
    }

    // 3. save attendance from the checkboxes
    if (isset($_POST['save'])) {
        $stmt = $pdo->prepare("SELECT student_id FROM course_enrollments WHERE course_id = ?");
        $stmt->execute([$lecture['course_id']]);
        foreach ($stmt->fetchAll() as $row) {
            $id = $row['student_id'];
            $status = isset($_POST['present'][$id]) ? 'present' : 'absent';
            $save = $pdo->prepare("REPLACE INTO attendance_records (lecture_id, student_id, status) VALUES (?, ?, ?)");
            $save->execute([$lecture_id, $id, $status]);
        }
        $message = 'Attendance saved.';
    }

    // current OTP (if not expired)
    $stmt = $pdo->prepare("SELECT otp_code FROM otp_sessions WHERE lecture_id = ? AND expires_at > NOW()
                           ORDER BY otp_id DESC LIMIT 1");
    $stmt->execute([$lecture_id]);
    $otp = $stmt->fetch();

    // students and whether they are present
    $sql = "SELECT u.user_id, u.username, a.status FROM course_enrollments ce
            JOIN users u ON u.user_id = ce.student_id
            LEFT JOIN attendance_records a ON a.student_id = u.user_id AND a.lecture_id = ?
            WHERE ce.course_id = ? ORDER BY u.username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lecture_id, $lecture['course_id']]);
    $students = $stmt->fetchAll();
}

// list of my lectures for the dropdown
$stmt = $pdo->prepare("SELECT l.lecture_id, l.lecture_date, c.course_code FROM lectures l
                       JOIN courses c ON c.course_id = l.course_id
                       WHERE c.lecturer_id = ? ORDER BY l.lecture_date DESC");
$stmt->execute([$me]);
$lectures = $stmt->fetchAll();

include 'header.php';
?>
<h2>Mark Attendance</h2>
<?php if ($message != '') echo "<p class='msg'>$message</p>"; ?>

<div class="card">
  <h3>Choose a lecture</h3>
  <form method="get">
    <select name="lecture_id" onchange="this.form.submit()">
      <option value="">-- select --</option>
      <?php foreach ($lectures as $l) { ?>
        <option value="<?php echo $l['lecture_id']; ?>" <?php if ($l['lecture_id'] == $lecture_id) echo 'selected'; ?>>
          <?php echo $l['course_code'] . ' - ' . $l['lecture_date']; ?>
        </option>
      <?php } ?>
    </select>
  </form>
</div>

<div class="card">
  <h3>Or create a new lecture</h3>
  <form method="post">
    <select name="course_id">
      <?php foreach ($courses as $c) echo "<option value='{$c['course_id']}'>{$c['course_code']} {$c['course_name']}</option>"; ?>
    </select>
    <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>">
    <input type="time" name="start" value="09:00">
    <input type="time" name="end" value="11:00">
    <button class="btn" name="create">Create</button>
  </form>
</div>

<?php if ($lecture) { ?>
<div class="card">
  <h3>OTP for <?php echo $lecture['course_code'] . ' - ' . $lecture['lecture_date']; ?></h3>
  <?php if ($otp) { ?>
    <p class="otp-big"><?php echo $otp['otp_code']; ?></p>
  <?php } else { ?>
    <p>No active code.</p>
  <?php } ?>
  <form method="post">
    <input type="hidden" name="lecture_id" value="<?php echo $lecture_id; ?>">
    Valid for <input type="number" name="minutes" value="5" min="1" max="60" style="width:70px"> minutes
    <button class="btn" name="make_otp">Generate OTP</button>
  </form>
</div>

<div class="card">
  <h3>Students <small>(refresh the page to see new check-ins)</small></h3>
  <form method="post">
    <input type="hidden" name="lecture_id" value="<?php echo $lecture_id; ?>">
    <table>
      <tr><th>Student</th><th>Present</th></tr>
      <?php foreach ($students as $s) { ?>
        <tr>
          <td><?php echo $s['username']; ?></td>
          <td><input type="checkbox" name="present[<?php echo $s['user_id']; ?>]"
               <?php if ($s['status'] == 'present') echo 'checked'; ?>></td>
        </tr>
      <?php } ?>
    </table>
    <button class="btn" name="save">Save</button>
  </form>
</div>
<?php } ?>
<?php include 'footer.php'; ?>

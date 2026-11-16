<?php
include 'db.php';
check_login();
$user = $_SESSION['user'];
include 'header.php';
?>
<div class="card">
  <h2><?php echo ucfirst($user['role']); ?> Profile</h2>
  <p><b>Username:</b> <?php echo htmlspecialchars($user['username']); ?></p>
  <p><b>Email:</b> <?php echo htmlspecialchars($user['email']); ?></p>
  <p><b>Role:</b> <?php echo $user['role']; ?></p>
</div>
<?php include 'footer.php'; ?>

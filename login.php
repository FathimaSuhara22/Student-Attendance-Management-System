<?php
include 'db.php';
$error = '';

if (isset($_POST['username'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password_hash'])) {
        $_SESSION['user'] = $user;
        header('Location: profile.php');
        exit;
    }
    $error = 'Wrong username or password';
}
include 'header.php';
?>
<div class="card narrow">
  <h2>Login</h2>
  <?php if ($error != '') echo "<p class='msg error'>$error</p>"; ?>
  <form method="post">
    <label>Username</label>
    <input type="text" name="username" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button class="btn">Login</button>
  </form>
</div>
<?php include 'footer.php'; ?>

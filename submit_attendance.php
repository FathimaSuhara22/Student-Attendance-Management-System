<?php
include 'db.php';
check_login('student');
include 'header.php';
?>
<div class="card narrow">
  <h2>Submit Attendance</h2>
  <p>Enter the 6-digit code shown by your lecturer.</p>
  <input type="text" id="otp" maxlength="6" class="otp-input" placeholder="123456">
  <button class="btn" onclick="sendOtp()">Submit</button>
  <p id="result"></p>
</div>

<script>
// sends the code to submit_otp.php without reloading the page
function sendOtp() {
  var code = document.getElementById('otp').value;
  var data = new FormData();
  data.append('otp', code);

  fetch('submit_otp.php', { method: 'POST', body: data })
    .then(function (response) { return response.text(); })
    .then(function (text) {
      document.getElementById('result').innerText = text;
    });
}
</script>
<?php include 'footer.php'; ?>

<?php
session_start();
require_once "pdo.php";

if (isset($_POST['email']) && isset($_POST['pass'])) {

  if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
    $_SESSION['error'] = "Email and password are required";
    header("Location: login.php");
    return;
  }

  $stmt = $pdo->prepare(
    "SELECT user_id FROM users
     WHERE email = :em AND password = :pw"
  );
  $stmt->execute([
    ':em' => $_POST['email'],
    ':pw' => $_POST['pass']
  ]);

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row !== false) {
    $_SESSION['user_id'] = $row['user_id'];
    header("Location: index.php");
    return;
  } else {
    $_SESSION['error'] = "Incorrect password";
    header("Location: login.php");
    return;
  }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>

<h1>Please Log In</h1>

<?php
if (isset($_SESSION['error'])) {
  echo '<p style="color:red">'.$_SESSION['error'].'</p>';
  unset($_SESSION['error']);
}
?>

<form method="POST">
  <input type="text" name="email" placeholder="Email"><br>
  <input type="password" name="pass" placeholder="Password"><br>
  <button type="submit">Log In</button>
</form>

</body>
</html>

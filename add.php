<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['user_id'])) {
  die("ACCESS DENIED");
}

if (isset($_POST['first_name'])) {

  if (
    strlen($_POST['first_name']) < 1 ||
    strlen($_POST['last_name']) < 1 ||
    strlen($_POST['email']) < 1
  ) {
    $_SESSION['error'] = "All fields are required";
    header("Location: add.php");
    return;
  }

  if (strpos($_POST['email'], '@') === false) {
    $_SESSION['error'] = "Email must have @";
    header("Location: add.php");
    return;
  }

  $stmt = $pdo->prepare(
    "INSERT INTO Profile
     (user_id, first_name, last_name, email, headline, summary)
     VALUES (:uid, :fn, :ln, :em, :he, :su)"
  );

  $stmt->execute([
    ':uid' => $_SESSION['user_id'],
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':he' => $_POST['headline'],
    ':su' => $_POST['summary']
  ]);

  header("Location: index.php");
  return;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Profile</title>

<script>
function validateForm() {
  if (
    document.getElementById('first_name').value == '' ||
    document.getElementById('last_name').value == '' ||
    document.getElementById('email').value == ''
  ) {
    alert("All fields are required");
    return false;
  }
  if (document.getElementById('email').value.indexOf('@') == -1) {
    alert("Email must contain @");
    return false;
  }
  return true;
}
</script>
</head>

<body>
<h1>Add Profile</h1>

<?php
if (isset($_SESSION['error'])) {
  echo '<p style="color:red">'.$_SESSION['error'].'</p>';
  unset($_SESSION['error']);
}
?>

<form method="POST" onsubmit="return validateForm();">
  First Name: <input type="text" name="first_name" id="first_name"><br>
  Last Name: <input type="text" name="last_name" id="last_name"><br>
  Email: <input type="text" name="email" id="email"><br>
  Headline: <input type="text" name="headline"><br>
  Summary:<br>
  <textarea name="summary"></textarea><br>
  <button type="submit">Add</button>
</form>

<a href="index.php">Cancel</a>
</body>
</html>

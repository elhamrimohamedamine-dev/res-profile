<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['user_id'])) {
  die("ACCESS DENIED");
}

if (!isset($_GET['profile_id'])) {
  die("Missing profile_id");
}

$stmt = $pdo->prepare(
  "SELECT * FROM Profile
   WHERE profile_id = :pid AND user_id = :uid"
);
$stmt->execute([
  ':pid' => $_GET['profile_id'],
  ':uid' => $_SESSION['user_id']
]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if ($profile === false) {
  die("Profile not found");
}

if (isset($_POST['first_name'])) {

  if (
    strlen($_POST['first_name']) < 1 ||
    strlen($_POST['last_name']) < 1 ||
    strlen($_POST['email']) < 1
  ) {
    $_SESSION['error'] = "All fields are required";
    header("Location: edit.php?profile_id=".$_GET['profile_id']);
    return;
  }

  if (strpos($_POST['email'], '@') === false) {
    $_SESSION['error'] = "Email must contain @";
    header("Location: edit.php?profile_id=".$_GET['profile_id']);
    return;
  }

  $stmt = $pdo->prepare(
    "UPDATE Profile
     SET first_name = :fn,
         last_name = :ln,
         email = :em,
         headline = :he,
         summary = :su
     WHERE profile_id = :pid AND user_id = :uid"
  );

  $stmt->execute([
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':he' => $_POST['headline'],
    ':su' => $_POST['summary'],
    ':pid' => $_GET['profile_id'],
    ':uid' => $_SESSION['user_id']
  ]);

  header("Location: index.php");
  return;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>

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
<h1>Edit Profile</h1>

<?php
if (isset($_SESSION['error'])) {
  echo '<p style="color:red">'.$_SESSION['error'].'</p>';
  unset($_SESSION['error']);
}
?>

<form method="POST" onsubmit="return validateForm();">
  First Name:
  <input type="text" name="first_name" id="first_name"
         value="<?= htmlentities($profile['first_name']) ?>"><br>

  Last Name:
  <input type="text" name="last_name" id="last_name"
         value="<?= htmlentities($profile['last_name']) ?>"><br>

  Email:
  <input type="text" name="email" id="email"
         value="<?= htmlentities($profile['email']) ?>"><br>

  Headline:
  <input type="text" name="headline"
         value="<?= htmlentities($profile['headline']) ?>"><br>

  Summary:<br>
  <textarea name="summary"><?= htmlentities($profile['summary']) ?></textarea><br>

  <button type="submit">Save</button>
</form>

<a href="index.php">Cancel</a>
</body>
</html>

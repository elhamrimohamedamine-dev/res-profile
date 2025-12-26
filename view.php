<?php
require_once "pdo.php";

if (!isset($_GET['profile_id'])) {
  die("Missing profile_id");
}

$stmt = $pdo->prepare(
  "SELECT first_name, last_name, email, headline, summary
   FROM Profile
   WHERE profile_id = :pid"
);
$stmt->execute([
  ':pid' => $_GET['profile_id']
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
  die("Profile not found");
}
?>

<!DOCTYPE html>
<html>
<head><title>View Profile</title></head>
<body>

<h1>Profile Information</h1>

<p><strong>First Name:</strong>
<?= htmlentities($row['first_name']) ?></p>

<p><strong>Last Name:</strong>
<?= htmlentities($row['last_name']) ?></p>

<p><strong>Email:</strong>
<?= htmlentities($row['email']) ?></p>

<p><strong>Headline:</strong><br>
<?= htmlentities($row['headline']) ?></p>

<p><strong>Summary:</strong><br>
<?= htmlentities($row['summary']) ?></p>

<a href="index.php">Back</a>

</body>
</html>

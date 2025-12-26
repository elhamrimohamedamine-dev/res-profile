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
  "SELECT profile_id FROM Profile
   WHERE profile_id = :pid AND user_id = :uid"
);
$stmt->execute([
  ':pid' => $_GET['profile_id'],
  ':uid' => $_SESSION['user_id']
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
  die("Profile not found");
}

if (isset($_POST['delete'])) {
  $stmt = $pdo->prepare(
    "DELETE FROM Profile
     WHERE profile_id = :pid AND user_id = :uid"
  );
  $stmt->execute([
    ':pid' => $_GET['profile_id'],
    ':uid' => $_SESSION['user_id']
  ]);
  header("Location: index.php");
  return;
}
?>

<!DOCTYPE html>
<html>
<head><title>Delete Profile</title></head>
<body>

<h1>Delete Profile</h1>
<p>Are you sure you want to delete this profile?</p

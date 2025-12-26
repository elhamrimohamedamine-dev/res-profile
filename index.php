<?php
session_start();
require_once "pdo.php";
?>

<!DOCTYPE html>
<html>
<head><title>Profiles</title></head>
<body>

<h1>Profiles</h1>

<?php
if (isset($_SESSION['user_id'])) {
  echo '<a href="add.php">Add New Profile</a> | ';
  echo '<a href="logout.php">Logout</a><br><br>';
} else {
  echo '<a href="login.php">Please log in</a><br><br>';
}

$stmt = $pdo->query(
  "SELECT profile_id, first_name, last_name, headline FROM Profile"
);

echo "<table border='1'>";
echo "<tr><th>Name</th><th>Headline</th><th>Action</th></tr>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  echo "<tr>";
  echo "<td>";
  echo '<a href="view.php?profile_id='.$row['profile_id'].'">';
  echo htmlentities($row['first_name'].' '.$row['last_name']);
  echo "</a>";
  echo "</td>";
  echo "<td>".htmlentities($row['headline'])."</td>";
  echo "<td>";
  if (isset($_SESSION['user_id'])) {
    echo '<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> ';
    echo '<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>';
  }
  echo "</td>";
  echo "</tr>";
}
echo "</table>";
?>

</body>
</html>

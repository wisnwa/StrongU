<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(["error" => "Unauthorized"]);
  exit;
}

$userID = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

// Validasi data dasar
$username = $data['username'];
$email = $data['email'];
$noTelp = $data['noTelp'];
$birthday = $data['birthday'];
$zipcode = $data['zipcode'];
$level = $data['level'];
$sessions = $data['session']; // array
$days = $data['day'];         // array
$goals = $data['goal'];       // array

// Validasi duplikat
function isDuplicate($conn, $field, $value, $userID) {
  $sql = "SELECT COUNT(*) FROM users WHERE $field = ? AND userID != ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $value, $userID);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  return $count > 0;
}

if (isDuplicate($conn, 'username', $username, $userID)) {
  echo json_encode(["error" => "Username already exists"]);
  exit;
}
if (isDuplicate($conn, 'email', $email, $userID)) {
  echo json_encode(["error" => "Email already exists"]);
  exit;
}
if (isDuplicate($conn, 'noTelp', $noTelp, $userID)) {
  echo json_encode(["error" => "Phone number already exists"]);
  exit;
}

// Update users table
$sql = "UPDATE users SET username=?, email=?, noTelp=?, birthday=?, zipcode=?, level=? WHERE userID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $username, $email, $noTelp, $birthday, $zipcode, $level, $userID);
$stmt->execute();

// Update relasi: bersihkan dulu, lalu insert ulang
function updateRelation($conn, $table, $userID, $ids) {
  $conn->query("DELETE FROM $table WHERE id_user = $userID");
  if (!empty($ids)) {
    foreach ($ids as $id) {
      $col = "id_" . substr($table, 5);
      $stmt = $conn->prepare("INSERT INTO $table (id_user, $col) VALUES (?, ?)");
      $stmt->bind_param("ii", $userID, $id);
      $stmt->execute();
    }
  }
}
updateRelation($conn, 'user_session', $userID, $sessions);
updateRelation($conn, 'user_day', $userID, $days);
updateRelation($conn, 'user_goal', $userID, $goals);

echo json_encode(["success" => true]);
?>

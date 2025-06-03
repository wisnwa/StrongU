<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(["error" => "Unauthorized"]);
  exit;
}

$userID = $_SESSION['user_id'];

// Ambil data utama user
$sqlUser = "SELECT username, email, noTelp, birthday, zipcode, level FROM users WHERE userID = ?";
$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
  echo json_encode(["error" => "User not found"]);
  exit;
}

// Ambil session, day, goal dari relasi
function getUserRelations($conn, $table, $userID) {
  $query = "SELECT id_" . substr($table, 5) . " AS id FROM $table WHERE id_user = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $userID);
  $stmt->execute();
  $result = $stmt->get_result();
  $ids = [];
  while ($row = $result->fetch_assoc()) {
    $ids[] = $row['id'];
  }
  return $ids;
}

$userData['session'] = getUserRelations($conn, 'user_session', $userID);
$userData['day']     = getUserRelations($conn, 'user_day', $userID);
$userData['goal']    = getUserRelations($conn, 'user_goal', $userID);

echo json_encode($userData);
?>

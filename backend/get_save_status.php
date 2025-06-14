<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit(json_encode(["isSaved" => false])); }

$userID = $_SESSION['user_id'];
$trainerID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$trainerID) { exit(json_encode(["isSaved" => false])); }

$stmt = $conn->prepare("SELECT * FROM saved_trainers WHERE userID = ? AND trainerID = ?");
$stmt->bind_param("ii", $userID, $trainerID);
$stmt->execute();
$is_saved = $stmt->get_result()->num_rows > 0;
$stmt->close();

echo json_encode(["isSaved" => $is_saved]);
$conn->close();
?>
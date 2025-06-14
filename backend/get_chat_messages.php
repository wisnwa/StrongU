<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit(json_encode(["error" => "Unauthorized"])); }
$myUserID = $_SESSION['user_id'];
$roomID = filter_input(INPUT_GET, 'roomID', FILTER_VALIDATE_INT);

if (!$roomID) { exit(json_encode(["error" => "Invalid Room ID"])); }

// Tandai pesan dari lawan bicara sebagai sudah dibaca
$stmt_read = $conn->prepare("UPDATE messages SET isRead = 1 WHERE roomID = ? AND senderID != ? AND isRead = 0");
$stmt_read->bind_param("ii", $roomID, $myUserID);
$stmt_read->execute();
$stmt_read->close();

// Ambil semua pesan DENGAN status isRead
$stmt_msg = $conn->prepare("SELECT messageID, senderID, messageText, sentAt, isRead FROM messages WHERE roomID = ? ORDER BY sentAt ASC");
$stmt_msg->bind_param("i", $roomID);
$stmt_msg->execute();
$result = $stmt_msg->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);
$stmt_msg->close();

echo json_encode($messages);
$conn->close();
?>
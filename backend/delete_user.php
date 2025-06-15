<?php
session_start();
require "db.php";
// Logika otentikasi admin...
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$userID = $data['userID'] ?? 0;

if (!$userID) { exit(json_encode(["success" => false, "message" => "ID tidak valid."])); }

$stmt = $conn->prepare("DELETE FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Pengguna berhasil dihapus."]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menghapus pengguna."]);
}
$stmt->close();
$conn->close();
?>
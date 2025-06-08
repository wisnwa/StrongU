<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit(json_encode(["success" => false, "message" => "Unauthorized"])); }

$userID = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$classID = $data['classID'] ?? 0;

if (empty($classID)) { exit(json_encode(["success" => false, "message" => "ID Kelas tidak valid."])); }

$stmt = $conn->prepare("DELETE FROM class WHERE classID = ? AND id_pt = ?");
$stmt->bind_param("ii", $classID, $userID);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Kelas berhasil dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Kelas tidak ditemukan atau Anda tidak berhak menghapusnya."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Gagal menghapus kelas."]);
}
$stmt->close();
$conn->close();
?>
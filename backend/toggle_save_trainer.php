<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit(json_encode(["success" => false, "message" => "Silakan login untuk menyimpan trainer."])); }

$userID = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$trainerID = $data['trainerID'] ?? 0;

if (!$trainerID) { exit(json_encode(["success" => false, "message" => "ID Trainer tidak valid."])); }

// Cek apakah sudah disimpan
$stmt_check = $conn->prepare("SELECT * FROM saved_trainers WHERE userID = ? AND trainerID = ?");
$stmt_check->bind_param("ii", $userID, $trainerID);
$stmt_check->execute();
$is_saved = $stmt_check->get_result()->num_rows > 0;
$stmt_check->close();

if ($is_saved) {
    // Jika sudah ada, hapus (unsave)
    $stmt = $conn->prepare("DELETE FROM saved_trainers WHERE userID = ? AND trainerID = ?");
    $stmt->bind_param("ii", $userID, $trainerID);
    $stmt->execute();
    echo json_encode(["success" => true, "saved" => false, "message" => "Trainer dihapus dari daftar."]);
} else {
    // Jika belum ada, tambahkan (save)
    $stmt = $conn->prepare("INSERT INTO saved_trainers (userID, trainerID) VALUES (?, ?)");
    $stmt->bind_param("ii", $userID, $trainerID);
    $stmt->execute();
    echo json_encode(["success" => true, "saved" => true, "message" => "Trainer berhasil disimpan."]);
}
$stmt->close();
$conn->close();
?>
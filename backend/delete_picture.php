<?php
session_start();
require "db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

$userID = $_SESSION['user_id'];

// Ambil path file lama
$stmt = $conn->prepare("SELECT profile_pict FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $filePath = $row['profile_pict'];
    // Hapus file fisik jika ada
    if (!empty($filePath) && file_exists($filePath)) {
        unlink($filePath);
    }
    
    // Set kolom di database menjadi NULL
    $stmt_update = $conn->prepare("UPDATE users SET profile_pict = NULL WHERE userID = ?");
    $stmt_update->bind_param("i", $userID);
    $stmt_update->execute();
    $stmt_update->close();
    
    echo json_encode(["success" => true, "message" => "Foto profil berhasil dihapus."]);
} else {
    echo json_encode(["success" => false, "error" => "User tidak ditemukan."]);
}

$stmt->close();
$conn->close();
?>
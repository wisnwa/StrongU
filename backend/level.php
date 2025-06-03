<?php
// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once("db.php");

$response = [];

try {
    if (!isset($_SESSION["user_id"])) {
        throw new Exception("User belum login.");
    }

    $userID = $_SESSION["user_id"];

    if (!isset($_POST["level"])) {
        throw new Exception("Tidak ada level yang dikirim.");
    }

    $level = strtolower(trim($_POST["level"]));

    $validLevels = ['beginner', 'intermediate', 'expert'];
    if (!in_array($level, $validLevels)) {
        throw new Exception("Level tidak valid.");
    }

    // Pastikan koneksi berhasil
    if (!$conn) {
        throw new Exception("Gagal terhubung ke database.");
    }

    // ⚠️ Perhatikan: Gunakan "userID" sesuai nama kolom di database
    $stmt = $conn->prepare("UPDATE users SET level = ? WHERE userID = ?");
    if (!$stmt) {
        throw new Exception("Query gagal disiapkan: " . $conn->error);
    }

    $stmt->bind_param("si", $level, $userID);
    if (!$stmt->execute()) {
        throw new Exception("Gagal menyimpan level: " . $stmt->error);
    }

    $response = ["status" => "success", "message" => "Level berhasil disimpan."];

} catch (Exception $e) {
    $response = ["status" => "error", "message" => $e->getMessage()];
}

echo json_encode($response);
?>
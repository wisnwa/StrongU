<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit(json_encode(["success" => false, "message" => "Unauthorized"])); }

$userID = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$certID = $data['id_certif'] ?? 0;

if (empty($certID)) { exit(json_encode(["success" => false, "message" => "ID tidak valid."])); }

$stmt_path = $conn->prepare("SELECT filepath FROM certificate WHERE id_certif = ? AND id_pt = ?");
$stmt_path->bind_param("ii", $certID, $userID);
$stmt_path->execute();
$result = $stmt_path->get_result();

if ($row = $result->fetch_assoc()) {
    $filePath = $row['filepath'];
    $stmt_del = $conn->prepare("DELETE FROM certificate WHERE id_certif = ?");
    $stmt_del->bind_param("i", $certID);
    if ($stmt_del->execute()) {
        if (file_exists($filePath)) { unlink($filePath); }
        echo json_encode(["success" => true, "message" => "Sertifikat berhasil dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus dari database."]);
    }
    $stmt_del->close();
} else {
    echo json_encode(["success" => false, "message" => "Sertifikat tidak ditemukan."]);
}
$stmt_path->close();
$conn->close();
?>
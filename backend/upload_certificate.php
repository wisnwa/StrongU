<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit(json_encode(["success" => false, "message" => "Unauthorized"])); }
if (!isset($_FILES['certificate_file'])) { exit(json_encode(["success" => false, "message" => "File tidak ditemukan."])); }

$userID = $_SESSION['user_id'];
$file = $_FILES['certificate_file'];

if ($file['error'] !== UPLOAD_ERR_OK) { exit(json_encode(["success" => false, "message" => "Error saat upload."])); }

$uploadDir = 'sertif_trainer/';
$allowedTypes = ['image/jpeg', 'image/png'];
if (!in_array($file['type'], $allowedTypes)) { exit(json_encode(["success" => false, "message" => "Hanya file JPG dan PNG yang diizinkan."]));}

// 1. Insert record dengan path sementara untuk mendapatkan ID
$tempPath = "temp";
$stmt = $conn->prepare("INSERT INTO certificate (id_pt, filepath) VALUES (?, ?)");
$stmt->bind_param("is", $userID, $tempPath);
$stmt->execute();
$newCertifID = $conn->insert_id;
$stmt->close();

if ($newCertifID > 0) {
    // 2. Buat nama file final menggunakan ID baru
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $finalFileName = "sertif" . $newCertifID . "_" . $userID . "." . strtolower($extension);
    $finalFilePath = $uploadDir . $finalFileName;

    // 3. Pindahkan file ke lokasi final
    if (move_uploaded_file($file['tmp_name'], $finalFilePath)) {
        // 4. Update record di database dengan path final
        $stmt_update = $conn->prepare("UPDATE certificate SET filepath = ? WHERE id_certif = ?");
        $stmt_update->bind_param("si", $finalFilePath, $newCertifID);
        $stmt_update->execute();
        $stmt_update->close();
        echo json_encode(["success" => true, "message" => "Sertifikat berhasil diunggah."]);
    } else {
        // Jika gagal pindah file, hapus record yang sudah dibuat
        $conn->query("DELETE FROM certificate WHERE id_certif = $newCertifID");
        echo json_encode(["success" => false, "message" => "Gagal menyimpan file."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Gagal membuat record di database."]);
}
$conn->close();
?>
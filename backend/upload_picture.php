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

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['profile_picture'];
    $uploadDir = 'foto_profile/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5 MB

    if (!in_array($file['type'], $allowedTypes) || $file['size'] > $maxSize) {
        echo json_encode(["success" => false, "error" => 'Tipe file tidak valid atau ukuran terlalu besar.']);
        exit;
    }

    // Hapus file lama jika ada
    $stmt_old = $conn->prepare("SELECT profile_pict FROM users WHERE userID = ?");
    $stmt_old->bind_param("i", $userID);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    if ($old_pic = $result_old->fetch_assoc()) {
        if (!empty($old_pic['profile_pict']) && file_exists($old_pic['profile_pict'])) {
            unlink($old_pic['profile_pict']);
        }
    }
    $stmt_old->close();

    // Buat nama file baru dan pindahkan
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = $uploadDir . 'profile_' . $userID . '.' . $extension;
    
    if (move_uploaded_file($file['tmp_name'], $newFileName)) {
        // Update path file di database
        $stmt_new = $conn->prepare("UPDATE users SET profile_pict = ? WHERE userID = ?");
        $stmt_new->bind_param("si", $newFileName, $userID);
        $stmt_new->execute();
        $stmt_new->close();
        
        echo json_encode([
            "success" => true,
            "message" => "Foto profil berhasil diunggah.",
            "new_picture_url" => 'http://localhost/StrongU_Project/backend/' . $newFileName
        ]);
    } else {
        echo json_encode(["success" => false, "error" => 'Gagal menyimpan file.']);
    }
} else {
    echo json_encode(["success" => false, "error" => 'Tidak ada file yang diunggah atau terjadi error.']);
}

$conn->close();
?>
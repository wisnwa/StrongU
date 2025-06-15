<?php
session_start();
require "db.php";
// Otentikasi admin...
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$userID = $data['userID'] ?? 0;
$newRole = $data['newRole'] ?? '';

if (!$userID || ($newRole !== 'user' && $newRole !== 'pt')) {
    exit(json_encode(["success" => false, "message" => "Data tidak valid."]));
}

$conn->begin_transaction();
try {
    // 1. Update role di tabel users
    $stmt_update = $conn->prepare("UPDATE users SET role = ? WHERE userID = ?");
    $stmt_update->bind_param("si", $newRole, $userID);
    $stmt_update->execute();
    $stmt_update->close();

    // 2. Jika role baru adalah 'pt', pastikan ada entri di personal_trainer
    if ($newRole === 'pt') {
        $stmt_insert_pt = $conn->prepare("INSERT IGNORE INTO personal_trainer (ptID) VALUES (?)");
        $stmt_insert_pt->bind_param("i", $userID);
        $stmt_insert_pt->execute();
        $stmt_insert_pt->close();
    }

    $conn->commit();
    echo json_encode(["success" => true, "message" => "Peran pengguna berhasil diubah."]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan database."]);
}

$conn->close();
?>
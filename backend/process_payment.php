<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    exit(json_encode(["success" => false, "message" => "Unauthorized. Silakan login terlebih dahulu."]));
}

$data = json_decode(file_get_contents("php://input"), true);
$userID = $_SESSION['user_id'];

// Validasi data yang diterima dari frontend
$classID = $data['classID'] ?? 0;
$trainerID = $data['trainerID'] ?? 0;
$method = $data['method'] ?? '';
$subtotal = $data['subtotal'] ?? 0;
$adminFee = $data['adminFee'] ?? 0;
$totalPayment = $data['totalPayment'] ?? 0;

if (empty($classID) || empty($trainerID) || empty($method)) {
    exit(json_encode(["success" => false, "message" => "Data tidak lengkap."]));
}

$stmt = $conn->prepare(
    "INSERT INTO payment (id_user, id_trainer, id_class, method, subtotal, adminFee, totalPayment, status) 
     VALUES (?, ?, ?, ?, ?, ?, ?, 'success')"
);
$stmt->bind_param("iiisdds", $userID, $trainerID, $classID, $method, $subtotal, $adminFee, $totalPayment);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Pembayaran berhasil diproses!"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menyimpan data pembayaran."]);
}

$stmt->close();
$conn->close();
?>
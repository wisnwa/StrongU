<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit(json_encode(["success" => false, "message" => "Unauthorized"])); }

$userID = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

$sessionCount = filter_var($data['sessionCount'] ?? 0, FILTER_VALIDATE_INT);
$pricePerSession = filter_var($data['pricePerSession'] ?? 0, FILTER_VALIDATE_FLOAT);

if ($sessionCount <= 0 || $pricePerSession <= 0) {
    exit(json_encode(["success" => false, "message" => "Jumlah sesi dan harga harus lebih dari 0."]));
}

$stmt = $conn->prepare("INSERT INTO class (id_pt, sessionCount, pricePerSession) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $userID, $sessionCount, $pricePerSession);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Kelas baru berhasil ditambahkan."]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan kelas."]);
}
$stmt->close();
$conn->close();
?>
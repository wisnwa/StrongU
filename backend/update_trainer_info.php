<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$userID = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

$description = $data['description'] ?? '';
$address = $data['address'] ?? '';
$experience = filter_var($data['experience'] ?? 0, FILTER_VALIDATE_INT);

$stmt = $conn->prepare("UPDATE personal_trainer SET description = ?, address = ?, experience = ? WHERE ptID = ?");
$stmt->bind_param("ssii", $description, $address, $experience, $userID);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Informasi trainer berhasil diperbarui."]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal memperbarui informasi."]);
}
$stmt->close();
$conn->close();
?>
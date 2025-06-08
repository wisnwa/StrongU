<?php
session_start();
require "db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$userID = $_SESSION['user_id'];
$response = [];

// 1. Ambil data dari tabel personal_trainer
$stmt_pt = $conn->prepare("SELECT address, experience, description FROM personal_trainer WHERE ptID = ?");
$stmt_pt->bind_param("i", $userID);
$stmt_pt->execute();
$result_pt = $stmt_pt->get_result();
$response['details'] = $result_pt->fetch_assoc() ?: ['address' => '', 'experience' => 0, 'description' => ''];
$stmt_pt->close();

// 2. Ambil semua sertifikat milik trainer ini
$stmt_cert = $conn->prepare("SELECT id_certif, filepath FROM certificate WHERE id_pt = ? ORDER BY upload_at DESC");
$stmt_cert->bind_param("i", $userID);
$stmt_cert->execute();
$result_cert = $stmt_cert->get_result();
$certificates = [];
while ($row = $result_cert->fetch_assoc()) {
    $row['file_url'] = 'http://localhost/StrongU_Project/backend/' . $row['filepath'];
    $certificates[] = $row;
}
$response['certificates'] = $certificates;
$stmt_cert->close();

// 3. Ambil semua kelas milik trainer ini
$stmt_class = $conn->prepare("SELECT classID, sessionCount, pricePerSession FROM class WHERE id_pt = ? ORDER BY classID");
$stmt_class->bind_param("i", $userID);
$stmt_class->execute();
$result_class = $stmt_class->get_result();
$response['classes'] = $result_class->fetch_all(MYSQLI_ASSOC);
$stmt_class->close();

echo json_encode($response);
$conn->close();
?>
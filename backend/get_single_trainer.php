<?php
require "db.php";
header('Content-Type: application/json');

// Ambil ID trainer dari parameter URL (?id=...)
$trainerID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$trainerID) {
    http_response_code(400);
    echo json_encode(["error" => "ID Trainer tidak valid."]);
    exit;
}

$response = [];

// 1. Ambil Info Utama Trainer (dari users & personal_trainer)
$stmt_main = $conn->prepare("
    SELECT u.username, u.profile_pict, pt.address, pt.experience, pt.description
    FROM users u
    JOIN personal_trainer pt ON u.userID = pt.ptID
    WHERE u.userID = ? AND u.role = 'pt'
");
$stmt_main->bind_param("i", $trainerID);
$stmt_main->execute();
$result_main = $stmt_main->get_result();
$main_info = $result_main->fetch_assoc();

if (!$main_info) {
    http_response_code(404);
    echo json_encode(["error" => "Trainer tidak ditemukan."]);
    exit;
}

// Proses URL foto profil
if (!empty($main_info['profile_pict'])) {
    $main_info['profile_pict_url'] = 'http://localhost/StrongU_Project/backend/' . $main_info['profile_pict'];
} else {
    $main_info['profile_pict_url'] = 'http://localhost/StrongU_Project/frontend/images/Trainerfoto.png';
}
$response['info'] = $main_info;
$stmt_main->close();

// 2. Ambil semua kelas yang dimiliki trainer
$stmt_class = $conn->prepare("SELECT classID, sessionCount, pricePerSession FROM class WHERE id_pt = ? ORDER BY sessionCount ASC");
$stmt_class->bind_param("i", $trainerID);
$stmt_class->execute();
$response['classes'] = $stmt_class->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_class->close();

// 3. Ambil semua sertifikat
$stmt_cert = $conn->prepare("SELECT filepath FROM certificate WHERE id_pt = ?");
$stmt_cert->bind_param("i", $trainerID);
$stmt_cert->execute();
$result_cert = $stmt_cert->get_result();
$certificates = [];
while ($row = $result_cert->fetch_assoc()) {
    $row['file_url'] = 'http://localhost/StrongU_Project/backend/' . $row['filepath'];
    $certificates[] = $row;
}
$response['certificates'] = $certificates;
$stmt_cert->close();

// 4. Ambil semua spesialisasi (goals)
$stmt_goal = $conn->prepare("
    SELECT g.name, g.description
    FROM user_goal ug
    JOIN goal g ON ug.id_goal = g.id
    WHERE ug.id_user = ?
");
$stmt_goal->bind_param("i", $trainerID);
$stmt_goal->execute();
$response['goals'] = $stmt_goal->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_goal->close();


echo json_encode($response);
$conn->close();
?>
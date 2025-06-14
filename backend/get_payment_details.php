<?php
require "db.php";
header('Content-Type: application/json');

$classID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$classID) {
    http_response_code(400);
    echo json_encode(["error" => "ID Kelas tidak valid."]);
    exit;
}

// Mengambil detail kelas beserta info trainernya
$sql = "
    SELECT 
        c.classID, c.sessionCount, c.pricePerSession,
        u.userID AS trainerID, u.username AS trainerName, u.profile_pict
    FROM class c
    JOIN users u ON c.id_pt = u.userID
    WHERE c.classID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $classID);
$stmt->execute();
$result = $stmt->get_result();
$details = $result->fetch_assoc();
$stmt->close();

if (!$details) {
    http_response_code(404);
    echo json_encode(["error" => "Detail kelas tidak ditemukan."]);
    exit;
}

// Menyiapkan URL foto profil
if (!empty($details['profile_pict'])) {
    $details['profile_pict_url'] = 'http://localhost/StrongU_Project/backend/' . $details['profile_pict'];
} else {
    $details['profile_pict_url'] = 'http://localhost/StrongU_Project/frontend/images/Trainerfoto.png';
}

echo json_encode($details);
$conn->close();
?>
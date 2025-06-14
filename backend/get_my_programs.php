<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(["error" => "Unauthorized"]));
}
$userID = $_SESSION['user_id'];
$response = [];

// 1. Ambil Program yang Sudah Dibeli (Program Taken) - Kueri Diperbaiki
$stmt_taken = $conn->prepare("
    SELECT DISTINCT
        u.userID,
        u.username,
        u.profile_pict,
        pt.address
    FROM payment p
    JOIN users u ON p.id_trainer = u.userID
    LEFT JOIN personal_trainer pt ON u.userID = pt.ptID
    WHERE p.id_user = ? AND p.status = 'success'
");
$stmt_taken->bind_param("i", $userID);
$stmt_taken->execute();
$response['taken'] = $stmt_taken->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_taken->close();

// 2. Ambil Trainer yang Disimpan (Saved Program) - Tidak ada perubahan
$stmt_saved = $conn->prepare("
    SELECT u.userID, u.username, u.profile_pict, pt.address, MIN(c.pricePerSession) AS startingPrice
    FROM saved_trainers st
    JOIN users u ON st.trainerID = u.userID
    JOIN personal_trainer pt ON st.trainerID = pt.ptID
    LEFT JOIN class c ON st.trainerID = c.id_pt
    WHERE st.userID = ?
    GROUP BY u.userID, u.username, u.profile_pict, pt.address
");
$stmt_saved->bind_param("i", $userID);
$stmt_saved->execute();
$response['saved'] = $stmt_saved->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_saved->close();

// Fungsi untuk memproses URL gambar
function process_image_urls(&$trainers_array) {
    foreach ($trainers_array as $key => $trainer) {
        $default_image = 'http://localhost/StrongU_Project/frontend/images/Trainerfoto.png';
        if (!empty($trainer['profile_pict'])) {
            $trainers_array[$key]['profile_pict_url'] = 'http://localhost/StrongU_Project/backend/' . $trainer['profile_pict'];
        } else {
            $trainers_array[$key]['profile_pict_url'] = $default_image;
        }
    }
}

process_image_urls($response['taken']);
process_image_urls($response['saved']);

echo json_encode($response);
$conn->close();
?>
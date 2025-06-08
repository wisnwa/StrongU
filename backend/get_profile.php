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

// REVISI DI SINI: Tambahkan 'role' ke dalam query SELECT
$sqlUser = "SELECT username, email, noTelp, birthday, zipcode, level, role, profile_pict FROM users WHERE userID = ?";
$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

// Tambahkan path lengkap untuk gambar jika ada
if (!empty($userData['profile_pict'])) {
    $userData['profile_pict_url'] = 'http://localhost/StrongU_Project/backend/' . $userData['profile_pict'];
} else {
    $userData['profile_pict_url'] = 'http://localhost/StrongU_Project/frontend/images/profile-pict.png';
}

function getUserRelations($conn, $table, $userID) {
    $colName = "id_" . substr($table, 5);
    $query = "SELECT $colName AS id FROM $table WHERE id_user = ?";
    $stmt_rel = $conn->prepare($query);
    $stmt_rel->bind_param("i", $userID);
    $stmt_rel->execute();
    $result_rel = $stmt_rel->get_result();
    $ids = [];
    while ($row = $result_rel->fetch_assoc()) {
        $ids[] = $row['id'];
    }
    $stmt_rel->close();
    return $ids;
}

$userData['session'] = getUserRelations($conn, 'user_session', $userID);
$userData['day']     = getUserRelations($conn, 'user_day', $userID);
$userData['goal']    = getUserRelations($conn, 'user_goal', $userID);

echo json_encode($userData);
$stmt->close();
$conn->close();
?>
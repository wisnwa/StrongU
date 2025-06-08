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

// Ambil data dari body request JSON
$data = json_decode(file_get_contents("php://input"), true);

// Pastikan data yang diterima tidak null
if (!$data) {
    echo json_encode(["success" => false, "error" => "Data tidak valid."]);
    exit;
}

// Ambil semua data dengan fallback ke string kosong atau array kosong jika tidak ada
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$noTelp = $data['noTelp'] ?? '';
$birthday = $data['birthday'] ?? null;
$zipcode = $data['zipcode'] ?? '';
$level = $data['level'] ?? '';
$sessions = $data['session'] ?? [];
$days = $data['day'] ?? [];
$goals = $data['goal'] ?? [];

// Update tabel utama users
$sql = "UPDATE users SET username=?, email=?, noTelp=?, birthday=?, zipcode=?, level=? WHERE userID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $username, $email, $noTelp, $birthday, $zipcode, $level, $userID);
$stmt->execute();
$stmt->close();

// Fungsi untuk update relasi (user_day, user_goal, dll)
function updateRelation($conn, $table, $userID, $ids) {
    $conn->query("DELETE FROM $table WHERE id_user = $userID");
    if (!empty($ids)) {
        $col = "id_" . substr($table, 5);
        $stmt_rel = $conn->prepare("INSERT INTO $table (id_user, $col) VALUES (?, ?)");
        foreach ($ids as $id) {
            $stmt_rel->bind_param("ii", $userID, $id);
            $stmt_rel->execute();
        }
        $stmt_rel->close();
    }
}

updateRelation($conn, 'user_session', $userID, $sessions);
updateRelation($conn, 'user_day', $userID, $days);
updateRelation($conn, 'user_goal', $userID, $goals);

echo json_encode(["success" => true, "message" => "Profil berhasil diperbarui."]);

$conn->close();
?>
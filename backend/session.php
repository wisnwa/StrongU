<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "Belum login"]);
    exit;
}

$userID = $_SESSION["user_id"];
$sessions = $_POST["sessions"] ?? [];

if (empty($sessions)) {
    echo json_encode(["status" => "error", "message" => "Tidak ada sesi dipilih"]);
    exit;
}

// Hapus data lama dulu kalau ada
mysqli_query($conn, "DELETE FROM user_session WHERE id_user = $userID");

// Insert yang baru
foreach ($sessions as $sessionName) {
    $safe = mysqli_real_escape_string($conn, $sessionName);
    $getSessionID = mysqli_query($conn, "SELECT id FROM session WHERE name = '$safe'");
    if ($row = mysqli_fetch_assoc($getSessionID)) {
        $id_session = $row['id'];
        mysqli_query($conn, "INSERT INTO user_session (id_user, id_session) VALUES ($userID, $id_session)");
    }
}

echo json_encode(["status" => "success"]);
?>

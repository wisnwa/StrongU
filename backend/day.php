<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "User belum login."]);
    exit;
}

$userID = $_SESSION["user_id"];

if (!isset($_POST["days"])) {
    echo json_encode(["status" => "error", "message" => "Tidak ada hari yang dikirim."]);
    exit;
}

$days = json_decode($_POST["days"], true); // array dari js

// Mapping hari ke ID (dari informasi kamu)
$dayMap = [
    "sunday"    => 1,
    "monday"    => 2,
    "tuesday"   => 3,
    "wednesday" => 4,
    "thursday"  => 5,
    "friday"    => 6,
    "saturday"  => 7
];

// Hapus dulu semua data lama user (biar tidak dobel)
$conn->query("DELETE FROM user_day WHERE id_user = $userID");

// Masukkan data baru
$stmt = $conn->prepare("INSERT INTO user_day (id_user, id_day) VALUES (?, ?)");
foreach ($days as $day) {
    if (isset($dayMap[$day])) {
        $dayID = $dayMap[$day];
        $stmt->bind_param("ii", $userID, $dayID);
        $stmt->execute();
    }
}

echo json_encode(["status" => "success", "message" => "Hari berhasil disimpan."]);
?>

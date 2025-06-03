<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "User belum login."]);
    exit;
}

$userID = $_SESSION["user_id"];

if (!isset($_POST["goals"])) {
    echo json_encode(["status" => "error", "message" => "Tidak ada tujuan yang dikirim."]);
    exit;
}

$goals = json_decode($_POST["goals"], true); // array dari js

// Mapping goal ke ID (sesuai informasi kamu)
$goalMap = [
    "weight_loss"         => 1,
    "firming_toning"      => 2,
    "muscle_strength"     => 3,
    "strength"            => 4,
    "endurance_training"  => 5,
    "weight_gain"         => 6,
    "flexibility"         => 7,
    "aerobic_fitness"     => 8,
    "body_building"       => 9
];

// Hapus dulu semua data lama user (biar tidak dobel)
$conn->query("DELETE FROM user_goal WHERE id_user = $userID");

// Masukkan data baru
$stmt = $conn->prepare("INSERT INTO user_goal (id_user, id_goal) VALUES (?, ?)");
foreach ($goals as $goal) {
    if (isset($goalMap[$goal])) {
        $goalID = $goalMap[$goal];
        $stmt->bind_param("ii", $userID, $goalID);
        $stmt->execute();
    }
}

echo json_encode(["status" => "success", "message" => "Tujuan berhasil disimpan."]);
?>
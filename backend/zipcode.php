<?php
header('Content-Type: application/json');
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"] ?? null;
    $zipcode = $_POST["zipcode"] ?? '';

    if (!$user_id) {
        echo json_encode(["status" => "error", "message" => "User belum login"]);
        exit;
    }

    if (!preg_match('/^\d{5}$/', $zipcode)) {
        echo json_encode(["status" => "error", "message" => "Zipcode tidak valid"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET zipcode = ? WHERE userID = ?");
    $stmt->bind_param("si", $zipcode, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
}
?>

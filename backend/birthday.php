<?php
header('Content-Type: application/json');
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userID = $_SESSION["user_id"] ?? null;
    $birthday = $_POST["birthday"] ?? '';

    if (!$userID) {
        echo json_encode(["status" => "error", "message" => "User belum login"]);
        exit;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
        echo json_encode(["status" => "error", "message" => "Format tanggal tidak valid"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET birthday = ? WHERE userID = ?");
    $stmt->bind_param("si", $birthday, $userID);

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

<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($username) || empty($email) || empty($phone) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Semua field harus diisi"]);
        exit;
    }

    // Cek email duplikat
    $stmt = $conn->prepare("SELECT userID FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "field" => "email", "message" => "Email sudah terdaftar"]);
        exit;
    }

    // Cek no telepon duplikat
    $stmt = $conn->prepare("SELECT userID FROM users WHERE noTelp = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "field" => "phone", "message" => "Nomor telepon sudah terdaftar"]);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if (!$hashedPassword) {
        echo json_encode(["status" => "error", "message" => "Gagal enkripsi password"]);
        exit;
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO users (username, email, noTelp, password, role) VALUES (?, ?, ?, ?, 'user')");
    $stmt->bind_param("ssss", $username, $email, $phone, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
}
?>
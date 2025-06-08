<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php'; // Pastikan file db.php terhubung dengan benar
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "field" => "username", "message" => "Username dan password harus diisi"]);
        exit;
    }

    // Modifikasi query untuk mengambil kolom 'level'
    $stmt = $conn->prepare("SELECT userID, password, role, level FROM users WHERE username = ?");
    if (!$stmt) {
        // Tambahkan error handling jika prepare statement gagal
        echo json_encode(["status" => "error", "message" => "Database query preparation failed: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Tambahkan variabel $level pada bind_result
        $stmt->bind_result($userID, $hashedPassword, $role, $level);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["user_id"] = $userID;
            $_SESSION["role"] = $role;

            // Kirim 'level' dalam respons JSON
            echo json_encode(["status" => "success", "role" => $role, "level" => $level]);
        } else {
            echo json_encode(["status" => "error", "field" => "password", "message" => "Password salah"]);
        }
    } else {
        echo json_encode(["status" => "error", "field" => "username", "message" => "Username tidak terdaftar"]);
    }
    $stmt->close(); // Tutup statement
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
}
$conn->close(); // Tutup koneksi database
?>

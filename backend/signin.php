<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "field" => "username", "message" => "Username dan password harus diisi"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT userID, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userID, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["user_id"] = $userID;
            $_SESSION["role"] = $role;

            echo json_encode(["status" => "success", "role" => $role]);
        } else {
            echo json_encode(["status" => "error", "field" => "password", "message" => "Password salah"]);
        }
    } else {
        echo json_encode(["status" => "error", "field" => "username", "message" => "Username tidak terdaftar"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
}
?>

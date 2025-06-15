<?php
session_start();
require "db.php";
// Di sini Anda perlu logika untuk memeriksa apakah user adalah admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { exit('Access Denied'); }

header('Content-Type: application/json');

$response = [];

// 1. Hitung jumlah user biasa
$result_users = $conn->query("SELECT COUNT(userID) as total FROM users WHERE role = 'user'");
$response['total_users'] = $result_users->fetch_assoc()['total'];

// 2. Hitung jumlah trainer
$result_trainers = $conn->query("SELECT COUNT(userID) as total FROM users WHERE role = 'pt'");
$response['total_trainers'] = $result_trainers->fetch_assoc()['total'];

// 3. Hitung pendapatan hari ini
$today_date = date('Y-m-d');
$stmt_income = $conn->prepare("SELECT SUM(totalPayment) as income FROM payment WHERE DATE(paidAt) = ? AND status = 'success'");
$stmt_income->bind_param("s", $today_date);
$stmt_income->execute();
$income_result = $stmt_income->get_result()->fetch_assoc();
$response['today_income'] = $income_result['income'] ?? 0;

// 4. Ambil info admin yang login
$adminID = $_SESSION['user_id'] ?? 0; // Ambil dari session
$stmt_admin = $conn->prepare("SELECT username, profile_pict FROM users WHERE userID = ?");
$stmt_admin->bind_param("i", $adminID);
$stmt_admin->execute();
$admin_data = $stmt_admin->get_result()->fetch_assoc();
if ($admin_data) {
    $response['admin_username'] = $admin_data['username'];
    $response['admin_profile_pict_url'] = !empty($admin_data['profile_pict']) 
        ? 'http://localhost/StrongU_Project/backend/' . $admin_data['profile_pict'] 
        : 'http://localhost/StrongU_Project/frontend/images/profile-pict.png';
}

echo json_encode($response);
$conn->close();
?>
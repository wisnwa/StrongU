<?php
session_start();
require "db.php";
header('Content-Type: application/json');

// --- Logika otentikasi admin ---
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
//     http_response_code(403);
//     exit(json_encode(["error" => "Access Denied"])); 
// }
// ------------------------------------

$response = [];

// 1. Ambil Info Admin
$adminID = $_SESSION['user_id'] ?? 1; // Ganti dengan ID admin default jika sesi tidak ada
// REVISI DI SINI: Tambahkan email dan noTelp ke dalam SELECT
$stmt_admin = $conn->prepare("SELECT username, email, noTelp, profile_pict FROM users WHERE userID = ?");
$stmt_admin->bind_param("i", $adminID);
$stmt_admin->execute();
$admin_data = $stmt_admin->get_result()->fetch_assoc();
if ($admin_data) {
    // Proses URL gambar, tidak ada perubahan di sini
    $admin_data['profile_pict_url'] = !empty($admin_data['profile_pict']) 
        ? 'http://localhost/StrongU_Project/backend/' . $admin_data['profile_pict'] 
        : '../images/profile-pict.png';
    $response['adminInfo'] = $admin_data;
}

// 2. Ambil Statistik Dashboard
$response['stats']['total_users'] = $conn->query("SELECT COUNT(userID) as total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$response['stats']['total_trainers'] = $conn->query("SELECT COUNT(userID) as total FROM users WHERE role = 'pt'")->fetch_assoc()['total'];
$today_income_result = $conn->query("SELECT SUM(totalPayment) as income FROM payment WHERE DATE(paidAt) = CURDATE() AND status = 'success'")->fetch_assoc();
$response['stats']['today_income'] = $today_income_result['income'] ?? 0;

// 3. Ambil Semua Personal Trainer
$response['allPTs'] = $conn->query("SELECT userID, username, email, profile_pict, created_at FROM users WHERE role = 'pt' ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

// 4. Ambil Semua User Biasa
$response['allUsers'] = $conn->query("SELECT userID, username, email, profile_pict, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

// 5. Ambil Semua Transaksi
$response['allTransactions'] = $conn->query("
    SELECT p.paymentID, p.paidAt, p.totalPayment, p.status, 
           buyer.username as buyerName, trainer.username as trainerName
    FROM payment p
    JOIN users buyer ON p.id_user = buyer.userID
    JOIN users trainer ON p.id_trainer = trainer.userID
    ORDER BY p.paidAt DESC
")->fetch_all(MYSQLI_ASSOC);

// Proses URL gambar untuk list PT dan User
function process_image_urls(&$array) {
    foreach ($array as $key => $item) {
        $array[$key]['profile_pict_url'] = !empty($item['profile_pict']) 
            ? 'http://localhost/StrongU_Project/backend/' . $item['profile_pict'] 
            : '../images/profile-pict.png';
    }
}
process_image_urls($response['allPTs']);
process_image_urls($response['allUsers']);

echo json_encode($response);
$conn->close();
?>
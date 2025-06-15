<?php
require "db.php";
header('Content-Type: application/json');

$period = filter_input(INPUT_GET, 'period', FILTER_VALIDATE_INT) ?: 30; // Default 30 hari
$type = $_GET['type'] ?? 'visitor'; // 'visitor' atau 'income'

$labels = [];
$date_data = [];

// Buat label untuk setiap hari dalam periode
for ($i = $period - 1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('d M', strtotime($date));
    $date_data[$date] = ($type === 'visitor') ? 0 : ['income' => 0, 'profit' => 0];
}

if ($type === 'visitor') {
    $sql = "SELECT DATE(created_at) as date, COUNT(userID) as count FROM users WHERE created_at >= CURDATE() - INTERVAL ? DAY GROUP BY date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $period);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $date_data[$row['date']] = $row['count'];
    }
    $stmt->close();
    $response = ['labels' => $labels, 'values' => array_values($date_data)];
} else { // type 'income'
    $sql = "SELECT DATE(paidAt) as date, SUM(totalPayment) as total, SUM(adminFee) as profit FROM payment WHERE status = 'success' AND paidAt >= CURDATE() - INTERVAL ? DAY GROUP BY date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $period);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $date_data[$row['date']] = ['income' => $row['total'], 'profit' => $row['profit']];
    }
    $stmt->close();
    $response = [
        'labels' => $labels, 
        'income' => array_column(array_values($date_data), 'income'),
        'profit' => array_column(array_values($date_data), 'profit')
    ];
}

echo json_encode($response);
$conn->close();
?>
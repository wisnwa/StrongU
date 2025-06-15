<?php
require "db.php";
// Logika otentikasi admin...

header('Content-Type: application/json');

$sql = "
    SELECT u.userID, u.username, u.profile_pict, u.created_at, pt.address, pt.experience
    FROM users u
    JOIN personal_trainer pt ON u.userID = pt.ptID
    WHERE u.role = 'pt'
    ORDER BY u.created_at DESC
";

$result = $conn->query($sql);
$trainers = $result->fetch_all(MYSQLI_ASSOC);

// Proses URL gambar
foreach ($trainers as $key => $trainer) {
    if (!empty($trainer['profile_pict'])) {
        $trainers[$key]['profile_pict_url'] = 'http://localhost/StrongU_Project/backend/' . $trainer['profile_pict'];
    } else {
        $trainers[$key]['profile_pict_url'] = 'http://localhost/StrongU_Project/frontend/images/profile-pict.png';
    }
}

echo json_encode($trainers);
$conn->close();
?>
<?php
require "db.php";
header('Content-Type: application/json');

// Query ini menggabungkan 3 tabel:
// 1. personal_trainer: untuk mendapatkan daftar semua ID trainer.
// 2. users: untuk mendapatkan username dan foto profil berdasarkan ID.
// 3. class: untuk mendapatkan harga terendah yang ditawarkan oleh setiap trainer.
$sql = "
    SELECT 
        u.userID,
        u.username,
        u.profile_pict,
        pt.address,
        MIN(c.pricePerSession) AS startingPrice
    FROM 
        personal_trainer pt
    JOIN 
        users u ON pt.ptID = u.userID
    LEFT JOIN 
        class c ON pt.ptID = c.id_pt
    GROUP BY 
        u.userID, u.username, u.profile_pict, pt.address
    ORDER BY 
        u.userID DESC;
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$trainers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Proses data untuk menambahkan URL gambar lengkap
foreach ($trainers as $key => $trainer) {
    if (!empty($trainer['profile_pict'])) {
        $trainers[$key]['profile_pict_url'] = 'http://localhost/StrongU_Project/backend/' . $trainer['profile_pict'];
    } else {
        // Gunakan gambar default jika trainer tidak punya foto profil
        $trainers[$key]['profile_pict_url'] = 'http://localhost/StrongU_Project/frontend/images/Trainerfoto.png';
    }
}

echo json_encode($trainers);
$conn->close();
?>
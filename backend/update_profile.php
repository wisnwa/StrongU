<?php
session_start();
require "db.php";
header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$userID = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    exit(json_encode(["success" => false, "message" => "Data input tidak valid."]));
}

$conn->begin_transaction();

try {
    // === Bagian 1: Update Tabel 'users' secara dinamis ===
    $fields = [];
    $params = [];
    $types = "";

    // Cek setiap kemungkinan field dan tambahkan ke query jika ada di data input
    if (isset($data['username'])) { $fields[] = 'username = ?'; $params[] = $data['username']; $types .= 's'; }
    if (isset($data['email'])) { $fields[] = 'email = ?'; $params[] = $data['email']; $types .= 's'; }
    if (isset($data['noTelp'])) { $fields[] = 'noTelp = ?'; $params[] = $data['noTelp']; $types .= 's'; }
    if (isset($data['birthday'])) { $fields[] = 'birthday = ?'; $params[] = $data['birthday']; $types .= 's'; }
    if (isset($data['zipcode'])) { $fields[] = 'zipcode = ?'; $params[] = $data['zipcode']; $types .= 's'; }
    if (isset($data['level'])) { $fields[] = 'level = ?'; $params[] = $data['level']; $types .= 's'; }

    // Hanya jalankan query UPDATE jika ada field yang perlu diubah
    if (!empty($fields)) {
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE userID = ?";
        $params[] = $userID;
        $types .= 'i';

        $stmt = $conn->prepare($sql);
        // Gunakan spread operator (...) untuk bind parameter dinamis
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
    }

    // === Bagian 2: Update Tabel Relasi (jika datanya dikirim) ===
    function updateRelation($conn, $table, $userID, $ids) {
        $conn->query("DELETE FROM $table WHERE id_user = $userID");
        if (!empty($ids)) {
            $col = "id_" . substr($table, 5);
            $stmt_rel = $conn->prepare("INSERT INTO $table (id_user, $col) VALUES (?, ?)");
            foreach ($ids as $id) {
                $stmt_rel->bind_param("ii", $userID, $id);
                $stmt_rel->execute();
            }
            $stmt_rel->close();
        }
    }

    if (isset($data['session'])) {
        updateRelation($conn, 'user_session', $userID, $data['session']);
    }
    if (isset($data['day'])) {
        updateRelation($conn, 'user_day', $userID, $data['day']);
    }
    if (isset($data['goal'])) {
        updateRelation($conn, 'user_goal', $userID, $data['goal']);
    }

    $conn->commit();
    echo json_encode(["success" => true, "message" => "Profil berhasil diperbarui."]);

} catch (Exception $e) {
    $conn->rollback();
    // Untuk debugging, Anda bisa menampilkan error asli: $e->getMessage()
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan pada server."]);
}

$conn->close();
?>
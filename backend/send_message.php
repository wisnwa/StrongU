<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    exit(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$myUserID = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

$messageText = trim($data['messageText'] ?? '');
if (empty($messageText)) {
    exit(json_encode(["success" => false, "message" => "Pesan tidak boleh kosong."]));
}

$roomID = $data['roomID'] ?? null;
$otherUserID = $data['withUserID'] ?? null;

// Jika roomID tidak ada (pesan pertama), cari atau buat room baru
if (is_null($roomID) && !is_null($otherUserID)) {
    $otherUserID = filter_var($otherUserID, FILTER_VALIDATE_INT);

    // Query untuk mencari room 1-on-1 yang sudah ada
    $sql_find = "
        SELECT roomID FROM room_participants WHERE userID IN (?, ?)
        GROUP BY roomID HAVING COUNT(DISTINCT userID) = 2
        LIMIT 1
    ";
    $stmt_find = $conn->prepare($sql_find);
    $stmt_find->bind_param("ii", $myUserID, $otherUserID);
    $stmt_find->execute();
    $result = $stmt_find->get_result();
    
    if ($existing_room = $result->fetch_assoc()) {
        $roomID = $existing_room['roomID'];
    } else {
        // Buat room baru jika tidak ada
        $conn->begin_transaction();
        try {
            $conn->query("INSERT INTO rooms (createdAt) VALUES (NOW())");
            $newRoomID = $conn->insert_id;

            $stmt_add = $conn->prepare("INSERT INTO room_participants (roomID, userID) VALUES (?, ?), (?, ?)");
            $stmt_add->bind_param("iiii", $newRoomID, $myUserID, $newRoomID, $otherUserID);
            $stmt_add->execute();
            
            $conn->commit();
            $roomID = $newRoomID;
        } catch (Exception $e) {
            $conn->rollback();
            exit(json_encode(["success" => false, "message" => "Gagal membuat ruang obrolan."]));
        }
    }
    $stmt_find->close();
}

if (empty($roomID)) {
    exit(json_encode(["success" => false, "message" => "Room tidak valid."]));
}

// Masukkan pesan ke dalam room
$stmt_insert = $conn->prepare("INSERT INTO messages (roomID, senderID, messageText) VALUES (?, ?, ?)");
$stmt_insert->bind_param("iis", $roomID, $myUserID, $messageText);

if ($stmt_insert->execute()) {
    echo json_encode(["success" => true, "message" => "Pesan terkirim.", "roomID" => $roomID]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal mengirim pesan."]);
}

$stmt_insert->close();
$conn->close();
?>
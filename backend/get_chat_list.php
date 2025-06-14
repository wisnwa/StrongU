<?php
session_start();
require "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit(json_encode(["error" => "Unauthorized"])); }
$myUserID = $_SESSION['user_id'];

// Query kompleks untuk mengambil daftar chat, pesan terakhir, dan status unread
$sql = "
    SELECT 
        r.roomID,
        other_user.userID AS otherUserID,
        other_user.username AS otherUsername,
        other_user.profile_pict,
        last_message.messageText AS lastMessage,
        (SELECT COUNT(*) FROM messages m WHERE m.roomID = r.roomID AND m.senderID != ? AND m.isRead = 0) AS unreadCount
    FROM rooms r
    JOIN room_participants rp1 ON r.roomID = rp1.roomID AND rp1.userID = ?
    JOIN room_participants rp2 ON r.roomID = rp2.roomID AND rp2.userID != ?
    JOIN users other_user ON rp2.userID = other_user.userID
    LEFT JOIN (
        SELECT roomID, messageText, ROW_NUMBER() OVER(PARTITION BY roomID ORDER BY sentAt DESC) as rn
        FROM messages
    ) last_message ON r.roomID = last_message.roomID AND last_message.rn = 1
    ORDER BY last_message.rn IS NULL, (SELECT MAX(sentAt) FROM messages WHERE roomID = r.roomID) DESC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $myUserID, $myUserID, $myUserID);
$stmt->execute();
$result = $stmt->get_result();
$chats = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Proses URL gambar
foreach ($chats as $key => $chat) {
    if (!empty($chat['profile_pict'])) {
        $chats[$key]['profile_pict_url'] = 'http://localhost/StrongU_Project/backend/' . $chat['profile_pict'];
    } else {
        $chats[$key]['profile_pict_url'] = 'http://localhost/StrongU_Project/frontend/images/profile-pict.png';
    }
}

echo json_encode($chats);
$conn->close();
?>
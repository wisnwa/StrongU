<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['userID'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['userID'];

$data = json_decode(file_get_contents('php://input'), true);

$requiredFields = ['username', 'email', 'phone', 'birthday', 'zipcode', 'sessions', 'days', 'goals', 'level'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

try {
    $pdo->beginTransaction();
    
    // Check uniqueness constraints
    $checkFields = ['username', 'email', 'phone'];
    foreach ($checkFields as $field) {
        $stmt = $pdo->prepare("SELECT userID FROM users WHERE $field = ? AND userID != ?");
        $stmt->execute([$data[$field], $user_id]);
        
        if ($stmt->fetch()) {
            $pdo->rollBack();
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => "This " . ($field === 'phone' ? 'phone number' : $field) . " is already taken"]);
            exit;
        }
    }
    
    // Validate date
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['birthday'])) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid birthday format. Use YYYY-MM-DD']);
        exit;
    }
    
    $birthDate = new DateTime($data['birthday']);
    if ($birthDate > new DateTime()) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Birthday cannot be in the future']);
        exit;
    }
    
    // Validate zipcode
    if (!preg_match('/^\d{5}$/', $data['zipcode'])) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Zipcode must be exactly 5 digits']);
        exit;
    }
    
    // Update main user information
    $stmt = $pdo->prepare("
        UPDATE users 
        SET username = ?, email = ?, noTelp = ?, birthday = ?, zipcode = ?, level = ?
        WHERE userID = ?
    ");
    $stmt->execute([
        $data['username'],
        $data['email'],
        $data['phone'],
        $data['birthday'],
        $data['zipcode'],
        $data['level'],
        $user_id
    ]);
    
    // Delete existing relationships
    $tablesToDeleteFrom = ['user_session', 'user_day', 'user_goal'];
    foreach ($tablesToDeleteFrom as $table) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id_user = ?");
        $stmt->execute([$user_id]);
    }
    
    // Insert new sessions
    if (!empty($data['sessions'])) {
        foreach ($data['sessions'] as $sessionName) {
            $stmt = $pdo->prepare("SELECT id FROM session WHERE name = ?");
            $stmt->execute([$sessionName]);
            $session = $stmt->fetch();
            
            if (!$session) {
                $pdo->rollBack();
                throw new Exception("Session '$sessionName' does not exist");
            }
            
            $stmt = $pdo->prepare("INSERT INTO user_session (id_user, id_session) VALUES (?, ?)");
            $stmt->execute([$user_id, $session['id']]);
        }
    }
    
    // Insert new days
    if (!empty($data['days'])) {
        foreach ($data['days'] as $dayName) {
            $stmt = $pdo->prepare("SELECT id FROM day WHERE name = ?");
            $stmt->execute([$dayName]);
            $day = $stmt->fetch();
            
            if (!$day) {
                $pdo->rollBack();
                throw new Exception("Day '$dayName' does not exist");
            }
            
            $stmt = $pdo->prepare("INSERT INTO user_day (id_user, id_day) VALUES (?, ?)");
            $stmt->execute([$user_id, $day['id']]);
        }
    }
    
    // Insert new goals
    if (!empty($data['goals'])) {
        foreach ($data['goals'] as $goalName) {
            $stmt = $pdo->prepare("SELECT id FROM goal WHERE name = ?");
            $stmt->execute([$goalName]);
            $goal = $stmt->fetch();
            
            if (!$goal) {
                $pdo->rollBack();
                throw new Exception("Goal '$goalName' does not exist");
            }
            
            $stmt = $pdo->prepare("INSERT INTO user_goal (id_user, id_goal) VALUES (?, ?)");
            $stmt->execute([$user_id, $goal['id']]);
        }
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => "Profile updated successfully"
    ]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
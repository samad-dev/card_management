<?php
include("../db.php"); // Database connection
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Use `$_POST` instead of JSON input
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required field: id']);
        exit;
    }
    if (!isset($_POST['new_password']) || empty($_POST['new_password'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required field: new_password']);
        exit;
    }

    $id = (int) $_POST['id'];
    $new_password = $_POST['new_password'];

    // ✅ Check if User Exists
    $query = "SELECT id FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit;
    }

    // ✅ Update Password (No Hashing)
    $update_query = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("si", $new_password, $id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database update error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

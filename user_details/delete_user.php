<?php
include("../db.php"); // Ensure database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ Check ID in POST
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing ID parameter']);
        exit;
    }

    $id = mysqli_real_escape_string($db, $_POST['id']);

    // 🔄 Delete Query
    $query = "DELETE FROM users WHERE id = '$id'";
    
    if ($db->query($query)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database delete error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

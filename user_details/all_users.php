<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Query to fetch all users
    $query = "SELECT id, name, username, email, last_login, created_at, updated_at FROM users";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        http_response_code(200);
        echo json_encode(['status' => 'success', 'users' => $users]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'No users found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

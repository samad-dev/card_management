<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['plastic_type_id']) || empty($_GET['plastic_type_id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing plastic_type_id']);
        exit;
    }

    $id = intval($_GET['plastic_type_id']);
    $query = "SELECT * FROM plastic_types WHERE plastic_type_id = $id";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Plastic type not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

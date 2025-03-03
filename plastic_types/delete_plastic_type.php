<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Using POST instead of DELETE
    if (!isset($_POST['plastic_type_id']) || empty($_POST['plastic_type_id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing plastic_type_id']);
        exit;
    }

    $id = intval($_POST['plastic_type_id']);
    $query = "DELETE FROM plastic_types WHERE plastic_type_id = $id";

    if ($db->query($query)) {
        echo json_encode(['status' => 'success', 'message' => 'Plastic type deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database delete error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

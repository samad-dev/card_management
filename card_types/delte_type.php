<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Use POST instead of DELETE for simplicity
    if (!isset($_POST['card_type_id']) || empty($_POST['card_type_id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing card_type_id']);
        exit;
    }

    $id = intval($_POST['card_type_id']);
    $query = "DELETE FROM card_types WHERE card_type_id = $id";

    if ($db->query($query)) {
        echo json_encode(['status' => 'success', 'message' => 'Card type deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database delete error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

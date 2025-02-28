<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['card_name']) || empty($_POST['card_name'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing card_name']);
        exit;
    }

    $card_name = mysqli_real_escape_string($db, $_POST['card_name']);
    $query = "INSERT INTO card_types (card_name, created_at, updated_at) VALUES ('$card_name', NOW(), NOW())";

    if ($db->query($query)) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Card type created successfully', 'id' => $db->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database insert error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

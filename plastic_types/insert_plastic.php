<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['plastic_name']) || empty($_POST['plastic_name'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing plastic_name']);
        exit;
    }

    $plastic_name = mysqli_real_escape_string($db, $_POST['plastic_name']);
    $query = "INSERT INTO plastic_types (plastic_name, created_at, updated_at) VALUES ('$plastic_name', NOW(), NOW())";

    if ($db->query($query)) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Plastic type created successfully', 'id' => $db->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database insert error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

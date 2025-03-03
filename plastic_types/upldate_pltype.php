<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Using POST instead of PUT
    if (!isset($_POST['plastic_type_id']) || !isset($_POST['plastic_name']) || empty($_POST['plastic_type_id']) || empty($_POST['plastic_name'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing plastic_type_id or plastic_name']);
        exit;
    }

    $id = intval($_POST['plastic_type_id']);
    $plastic_name = mysqli_real_escape_string($db, $_POST['plastic_name']);
    $query = "UPDATE plastic_types SET plastic_name = '$plastic_name', updated_at = NOW() WHERE plastic_type_id = $id";

    if ($db->query($query)) {
        echo json_encode(['status' => 'success', 'message' => 'Plastic type updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database update error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

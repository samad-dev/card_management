<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Use POST since PUT is tricky with form data
    if (!isset($_POST['card_type_id']) || !isset($_POST['card_name']) || empty($_POST['card_type_id']) || empty($_POST['card_name'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing card_type_id or card_name']);
        exit;
    }

    $id = intval($_POST['card_type_id']);
    $card_name = mysqli_real_escape_string($db, $_POST['card_name']);
    $query = "UPDATE card_types SET card_name = '$card_name', updated_at = NOW() WHERE card_type_id = $id";

    if ($db->query($query)) {
        echo json_encode(['status' => 'success', 'message' => 'Card type updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database update error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

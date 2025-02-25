<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required Fields
    $required_fields = ['status_types', 'selec_comp', 'selec_acc', 'card_number', 'current_card_status', 'new_card_status'];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
            exit;
        }
    }

    // ✅ Sanitize Inputs
    $status_types = mysqli_real_escape_string($db, $_POST['status_types']);
    $selec_comp = (int) $_POST['selec_comp'];
    $selec_acc = (int) $_POST['selec_acc'];
    $card_number = (int) $_POST['card_number'];
    $current_card_status = mysqli_real_escape_string($db, $_POST['current_card_status']);
    $new_card_status = mysqli_real_escape_string($db, $_POST['new_card_status']);

    // ✅ Insert Query
    $query = "INSERT INTO card_status (status_types, selec_comp, selec_acc, card_number, current_card_status, new_card_status) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("siiiss", $status_types, $selec_comp, $selec_acc, $card_number, $current_card_status, $new_card_status);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Card status inserted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert card status']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

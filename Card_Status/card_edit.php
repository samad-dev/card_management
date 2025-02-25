<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Check if 'card_number' is provided
    if (!isset($_POST['card_number']) || empty($_POST['card_number'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Missing field: card_number"]);
        exit;
    }

    $card_number = (int) $_POST['card_number'];

    // ✅ Fetch the existing record
    $query = "SELECT * FROM card_status WHERE card_number = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $card_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Record not found for the given card_number']);
        exit;
    }

    $existing_data = $result->fetch_assoc();

    // ✅ Fields to Update (Only if Provided)
    $status_types = isset($_POST['status_types']) ? mysqli_real_escape_string($db, $_POST['status_types']) : $existing_data['status_types'];
    $selec_comp = isset($_POST['selec_comp']) ? (int) $_POST['selec_comp'] : $existing_data['selec_comp'];
    $selec_acc = isset($_POST['selec_acc']) ? (int) $_POST['selec_acc'] : $existing_data['selec_acc'];
    $current_card_status = isset($_POST['current_card_status']) ? mysqli_real_escape_string($db, $_POST['current_card_status']) : $existing_data['current_card_status'];
    $new_card_status = isset($_POST['new_card_status']) ? mysqli_real_escape_string($db, $_POST['new_card_status']) : $existing_data['new_card_status'];

    // ✅ Update Query
    $update_query = "UPDATE card_status SET 
                        status_types = ?, 
                        selec_comp = ?, 
                        selec_acc = ?, 
                        current_card_status = ?, 
                        new_card_status = ?, 
                        updated_at = NOW() 
                    WHERE card_number = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("siissi", $status_types, $selec_comp, $selec_acc, $current_card_status, $new_card_status, $card_number);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Card status updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update card status']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

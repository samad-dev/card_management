<?php
include("../db.php"); // Database connection
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required fields (Must be provided)
    $required_fields = ['category', 'card_type_id', 'plastic_type_id', 'batch_decrip', 'records'];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
            exit;
        }
    }

    // ✅ Sanitize Required Inputs
    $category = mysqli_real_escape_string($db, $_POST['category']);
    $card_type_id = (int) $_POST['card_type_id'];
    $plastic_type_id = (int) $_POST['plastic_type_id'];
    $batch_decrip = mysqli_real_escape_string($db, $_POST['batch_decrip']);
    $records = (int) $_POST['records'];

    // ✅ Handle Optional Fields (Allow NULL if not provided)
    $batch_no = isset($_POST['batch_no']) && !empty($_POST['batch_no']) ? (int) $_POST['batch_no'] : NULL;
    $selec_acc = isset($_POST['selec_acc']) && !empty($_POST['selec_acc']) ? (int) $_POST['selec_acc'] : NULL;
    $selec_comp = isset($_POST['selec_comp']) && !empty($_POST['selec_comp']) ? (int) $_POST['selec_comp'] : NULL;

    // ✅ Check Foreign Key Constraints
    // Validate `card_type_id` (Check `card_type_id` instead of `id`)
    $check_card_type = "SELECT card_type_id FROM card_types WHERE card_type_id = ?";
    $stmt = $db->prepare($check_card_type);
    $stmt->bind_param("i", $card_type_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows == 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid card_type_id. It does not exist in card_types table.']);
        exit;
    }

    // Validate `plastic_type_id` (Check `plastic_type_id` instead of `id`)
    $check_plastic_type = "SELECT plastic_type_id FROM plastic_types WHERE plastic_type_id = ?";
    $stmt = $db->prepare($check_plastic_type);
    $stmt->bind_param("i", $plastic_type_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows == 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid plastic_type_id. It does not exist in plastic_types table.']);
        exit;
    }

    // ✅ Insert Query (Handle NULL Values Properly)
    $query = "INSERT INTO batch_manag 
        (category, card_type_id, plastic_type_id, batch_no, selec_acc, selec_comp, batch_decrip, records) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("siiiissi", $category, $card_type_id, $plastic_type_id, $batch_no, $selec_acc, $selec_comp, $batch_decrip, $records);

    if ($stmt->execute()) {
        http_response_code(201); // Created
        echo json_encode(['status' => 'success', 'message' => 'Batch created successfully', 'batch_id' => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database insert error']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

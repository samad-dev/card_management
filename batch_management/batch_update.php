<?php
include("../db.php"); // Database connection
include("../header.php"); // Database connection
// include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required fields for update
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Missing field: id"]);
        exit;
    }

    $id = (int) $_POST['id'];

    // ✅ Sanitize Inputs
    $category = isset($_POST['category']) ? mysqli_real_escape_string($db, $_POST['category']) : NULL;
    $card_type_id = isset($_POST['card_type_id']) ? (int) $_POST['card_type_id'] : NULL;
    $plastic_type_id = isset($_POST['plastic_type_id']) ? (int) $_POST['plastic_type_id'] : NULL;
    $batch_no = isset($_POST['batch_no']) ? (int) $_POST['batch_no'] : NULL;
    $selec_acc = isset($_POST['selec_acc']) ? (int) $_POST['selec_acc'] : NULL;
    $selec_comp = isset($_POST['selec_comp']) ? (int) $_POST['selec_comp'] : NULL;
    $batch_decrip = isset($_POST['batch_decrip']) ? mysqli_real_escape_string($db, $_POST['batch_decrip']) : NULL;
    $records = isset($_POST['records']) ? (int) $_POST['records'] : NULL;

    // ✅ Validate `card_type_id` if provided
    if ($card_type_id) {
        $check_card_type = "SELECT card_type_id FROM card_types WHERE card_type_id = ?";
        $stmt = $db->prepare($check_card_type);
        $stmt->bind_param("i", $card_type_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows == 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid card_type_id. It does not exist in card_types table.']);
            exit;
        }
    }

    // ✅ Validate `plastic_type_id` if provided
    if ($plastic_type_id) {
        $check_plastic_type = "SELECT plastic_type_id FROM plastic_types WHERE plastic_type_id = ?";
        $stmt = $db->prepare($check_plastic_type);
        $stmt->bind_param("i", $plastic_type_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows == 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid plastic_type_id. It does not exist in plastic_types table.']);
            exit;
        }
    }

    // ✅ Validate `selec_acc` if provided (Reference `acc_entry` table)
    if ($selec_acc) {
        $check_acc_entry = "SELECT id FROM acc_entry WHERE id = ?";
        $stmt = $db->prepare($check_acc_entry);
        $stmt->bind_param("i", $selec_acc);
        $stmt->execute();
        if ($stmt->get_result()->num_rows == 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid selec_acc. It does not exist in acc_entry table.']);
            exit;
        }
    }

    // ✅ Validate `selec_comp` if provided (Reference `comp_info` table)
    if ($selec_comp) {
        $check_comp_info = "SELECT id FROM comp_info WHERE id = ?";
        $stmt = $db->prepare($check_comp_info);
        $stmt->bind_param("i", $selec_comp);
        $stmt->execute();
        if ($stmt->get_result()->num_rows == 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid selec_comp. It does not exist in comp_info table.']);
            exit;
        }
    }

    // ✅ Update Query (Only updates provided fields)
    $query = "UPDATE batch_manag SET 
        category = COALESCE(?, category),
        card_type_id = COALESCE(?, card_type_id),
        plastic_type_id = COALESCE(?, plastic_type_id),
        batch_no = COALESCE(?, batch_no),
        selec_acc = COALESCE(?, selec_acc),
        selec_comp = COALESCE(?, selec_comp),
        batch_decrip = COALESCE(?, batch_decrip),
        records = COALESCE(?, records),
        updated_at = NOW() 
        WHERE id = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param(
        "siiiissii",
        $category,
        $card_type_id,
        $plastic_type_id,
        $batch_no,
        $selec_acc,
        $selec_comp,
        $batch_decrip,
        $records,
        $id
    );

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Batch updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database update error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Check if required fields are provided
    $required_fields = ['instrument_type', 'bank_name', 'instrument_ref_no', 'tot_up_amm', 'selec_category', 'comp_types_id', 'card_types_id', 'topup_amm'];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
            exit;
        }
    }

    // ✅ Sanitize Inputs
    $instrument_type = mysqli_real_escape_string($db, $_POST['instrument_type']);
    $bank_name = mysqli_real_escape_string($db, $_POST['bank_name']);
    $instrument_ref_no = (int) $_POST['instrument_ref_no'];
    $tot_up_amm = (int) $_POST['tot_up_amm'];
    $selec_category = mysqli_real_escape_string($db, $_POST['selec_category']);
    $comp_types_id = (int) $_POST['comp_types_id'];
    $card_types_id = (int) $_POST['card_types_id'];
    $topup_amm = (int) $_POST['topup_amm'];

    // ✅ Insert Query
    $query = "INSERT INTO card_topup_maker (instrument_type, bank_name, instrument_ref_no, tot_up_amm, selec_category, comp_types_id, card_types_id, topup_amm) 
              VALUES ('$instrument_type', '$bank_name', '$instrument_ref_no', '$tot_up_amm', '$selec_category', '$comp_types_id', '$card_types_id', '$topup_amm')";

    if ($db->query($query)) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Record inserted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert record']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

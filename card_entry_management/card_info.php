<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // ✅ Check if 'card' is provided
    if (!isset($_GET['card']) || empty($_GET['card'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing field: card']);
        exit;
    }

    // ✅ Sanitize Input
    $card = mysqli_real_escape_string($db, $_GET['card']);

    // ✅ Fetch Specific Data
    $query = "SELECT 
                card, selec_comp, category_type, selec_batch, selec_acc, 
                card_type_id, plastic_type_id, batch_desc, total_cards, 
                first_name, last_name, gender, card_issuance, card_expiry, dob, 
                marital_status, card_name, address, city, cnic, msisdn, email, 
                card_status_id, last_maintenance, card_tracking, pin_enabled, 
                loyalty_points 
              FROM card_entry 
              WHERE card='$card'";
              
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $card_entry = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $card_entry]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Card not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

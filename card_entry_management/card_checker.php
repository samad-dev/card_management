<?php
include("../db.php"); // Database connection
include("../header.php");

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
                first_name, last_name, gender, dob, marital_status, 
                card_name, address, city, cnic, msisdn, email, products_id, 
                daily_freq, weekly_freq, limit_type, txn_limit, daily_limit, 
                weekly_limit, monthly_limit, non_fuel_limit
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

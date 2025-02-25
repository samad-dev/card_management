<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['acc_no']) || empty($_GET['acc_no'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing acc_no']);
        exit;
    }

    // ✅ Sanitize Input
    $acc_no = mysqli_real_escape_string($db, $_GET['acc_no']);

    // ✅ Fetch Account Entry (Excluding acc_no, status_id, created_at, updated_at)
    $query = "SELECT id, comp_info_id, legal_name, comp_name_card, entity, buisness_type, 
                     address, address_2, city, phone_1, phone_2, mobile, email, fax, 
                     person_name, designation, ntn, sales_tax, authorize_signatory, auth_design, 
                     due_date, billing_frequency_id, credit_limit, charges,status_id
              FROM acc_entry WHERE acc_no = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $acc_no); // Use "s" since acc_no is a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $account = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $account]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Account not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['acc_no']) || empty($_GET['acc_no'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing account number']);
        exit;
    }

    $acc_no = mysqli_real_escape_string($db, $_GET['acc_no']);

    // Fetch account details without showing `acc_no`
    $query = "SELECT id, comp_info_id, legal_name, comp_name_card, entity, buisness_type, 
                     address, address_2, city, phone_1, phone_2, mobile, email, fax, 
                     person_name, designation, ntn, sales_tax, authorize_signatory, auth_design, 
                     due_date, billing_frequency_id, credit_limit, charges
              FROM acc_entry WHERE acc_no = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $acc_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Account not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['company_name']) || empty($_GET['company_name'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing company_name']);
        exit;
    }

    $company_name = mysqli_real_escape_string($db, $_GET['company_name']);

    // Get the company ID from comp_info table
    $company_query = "SELECT id FROM comp_info WHERE company_name = '$company_name'";
    $company_result = $db->query($company_query);

    if ($company_result->num_rows > 0) {
        $company = $company_result->fetch_assoc();
        $comp_info_id = $company['id'];

        // Get all acc_no from acc_entry table for this company
        $acc_query = "SELECT acc_no FROM acc_entry WHERE comp_info_id = $comp_info_id";
        $acc_result = $db->query($acc_query);
        
        $account_numbers = [];
        while ($row = $acc_result->fetch_assoc()) {
            $account_numbers[] = $row['acc_no'];
        }

        echo json_encode(['status' => 'success', 'company_name' => $company_name, 'account_numbers' => $account_numbers]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Company not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

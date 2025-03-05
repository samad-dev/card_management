<?php
include("../db.php"); // Ensure database connection

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['company_name']) && !empty(trim($_GET['company_name']))) {
    $company_name = trim($_GET['company_name']);

    // Get company ID
    $company_query = $db->prepare("SELECT id FROM comp_info WHERE company_name = ?");
    $company_query->bind_param("s", $company_name);
    $company_query->execute();
    $company_result = $company_query->get_result();

    if ($company_result->num_rows > 0) {
        $company = $company_result->fetch_assoc();
        $comp_info_id = $company['id'];

        // Fetch all account numbers for the company
        $acc_query = $db->prepare("SELECT acc_no FROM acc_entry WHERE comp_info_id = ?");
        $acc_query->bind_param("i", $comp_info_id);
        $acc_query->execute();
        $acc_result = $acc_query->get_result();

        $account_numbers = [];
        while ($row = $acc_result->fetch_assoc()) {
            $account_numbers[] = $row['acc_no'];
        }

        echo json_encode(["company_name" => $company_name, "account_numbers" => $account_numbers]);
    } else {
        echo json_encode(["error" => "Company not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>

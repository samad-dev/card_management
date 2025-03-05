<?php
include("../db.php"); // Ensure database connection

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['company_id']) && is_numeric($_GET['company_id'])) {
    $company_id = intval($_GET['company_id']); // Convert input to integer

    // Check if company exists
    $company_query = $db->prepare("SELECT company_name FROM comp_info WHERE id = ?");
    $company_query->bind_param("i", $company_id);
    $company_query->execute();
    $company_result = $company_query->get_result();

    if ($company_result->num_rows > 0) {
        $company = $company_result->fetch_assoc();
        $company_name = $company['company_name'];

        // Fetch all account details for the company
        $acc_query = $db->prepare("SELECT id, acc_no, legal_name, comp_name_card, entity, buisness_type, city, phone_1, mobile, email 
                                   FROM acc_entry WHERE comp_info_id = ?");
        $acc_query->bind_param("i", $company_id);
        $acc_query->execute();
        $acc_result = $acc_query->get_result();

        $account_details = [];
        while ($row = $acc_result->fetch_assoc()) {
            $account_details[] = $row; // Store full account details
        }

        echo json_encode([
            "company_id" => $company_id,
            "company_name" => $company_name,
            "accounts" => $account_details
        ]);
    } else {
        echo json_encode(["error" => "Company not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>

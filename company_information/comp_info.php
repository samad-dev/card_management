<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required Fields
    $required_fields = ['company_code', 'company_name', 'address', 'city', 'email', 'sales_tax', 'company_desc', 'card_comp_name', 'ntn'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
            exit;
        }
    }

    // ✅ Sanitize Inputs
    $company_code = mysqli_real_escape_string($db, $_POST['company_code']);
    $company_name = mysqli_real_escape_string($db, $_POST['company_name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $city = mysqli_real_escape_string($db, $_POST['city']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $sales_tax = mysqli_real_escape_string($db, $_POST['sales_tax']);
    $company_desc = mysqli_real_escape_string($db, $_POST['company_desc']);
    $card_comp_name = mysqli_real_escape_string($db, $_POST['card_comp_name']);
    $ntn = mysqli_real_escape_string($db, $_POST['ntn']);

    // ✅ Check for Duplicate Entries (Company Code, Name, or Email)
    $dup_check = "SELECT * FROM comp_info WHERE company_code='$company_code' OR company_name='$company_name' OR email='$email'";
    $dup_result = $db->query($dup_check);

    if ($dup_result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Company Code, Name, or Email already exists']);
        exit;
    }

    // ✅ Insert Data
    $query = "INSERT INTO comp_info (company_code, company_name, address, city, email, sales_tax, company_desc, card_comp_name, ntn, created_at, updated_at) 
              VALUES ('$company_code', '$company_name', '$address', '$city', '$email', '$sales_tax', '$company_desc', '$card_comp_name', '$ntn', NOW(), NOW())";

    if ($db->query($query)) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Company created successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Database insert error']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

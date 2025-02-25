<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required Fields
    $required_fields = [
        'acc_no', 'comp_info_id', 'legal_name', 'comp_name_card', 'entity', 
        'buisness_type', 'address', 'city', 'phone_1', 
        'mobile', 'email', 'person_name', 'designation', 'ntn', 
        'sales_tax', 'authorize_signatory', 'auth_design', 'due_date', 
        'billing_frequency_id', 'credit_limit', 'charges'
    ];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
            exit;
        }
    }

    // ✅ Sanitize Inputs
    $acc_no = mysqli_real_escape_string($db, $_POST['acc_no']);
    $comp_info_id = mysqli_real_escape_string($db, $_POST['comp_info_id']);
    $legal_name = mysqli_real_escape_string($db, $_POST['legal_name']);
    $comp_name_card = mysqli_real_escape_string($db, $_POST['comp_name_card']);
    $entity = mysqli_real_escape_string($db, $_POST['entity']);
    $buisness_type = mysqli_real_escape_string($db, $_POST['buisness_type']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $city = mysqli_real_escape_string($db, $_POST['city']);
    $phone_1 = mysqli_real_escape_string($db, $_POST['phone_1']);
    $mobile = mysqli_real_escape_string($db, $_POST['mobile']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $person_name = mysqli_real_escape_string($db, $_POST['person_name']);
    $designation = mysqli_real_escape_string($db, $_POST['designation']);
    $ntn = mysqli_real_escape_string($db, $_POST['ntn']);
    $sales_tax = mysqli_real_escape_string($db, $_POST['sales_tax']);
    $authorize_signatory = mysqli_real_escape_string($db, $_POST['authorize_signatory']);
    $auth_design = mysqli_real_escape_string($db, $_POST['auth_design']);
    $due_date = mysqli_real_escape_string($db, $_POST['due_date']);
    $billing_frequency_id = mysqli_real_escape_string($db, $_POST['billing_frequency_id']);
    $credit_limit = mysqli_real_escape_string($db, $_POST['credit_limit']);
    $charges = mysqli_real_escape_string($db, $_POST['charges']);

    // ✅ Optional Fields (Set NULL if missing)
    $address_2 = isset($_POST['address_2']) ? mysqli_real_escape_string($db, $_POST['address_2']) : null;
    $phone_2 = isset($_POST['phone_2']) ? mysqli_real_escape_string($db, $_POST['phone_2']) : null;
    $fax = isset($_POST['fax']) ? mysqli_real_escape_string($db, $_POST['fax']) : null;

    // ✅ Check for Duplicate `acc_no` or `email`
    $dup_check = "SELECT * FROM acc_entry WHERE acc_no='$acc_no' OR email='$email'";
    $dup_result = $db->query($dup_check);

    if ($dup_result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Account number or Email already exists']);
        exit;
    }

    // ✅ Insert Data (Matching Column Count)
    $query = "INSERT INTO acc_entry (
                acc_no, comp_info_id, legal_name, comp_name_card, entity, 
                buisness_type, address, address_2, city, phone_1, phone_2, mobile, email, fax, 
                person_name, designation, ntn, sales_tax, authorize_signatory, auth_design, 
                due_date, billing_frequency_id, credit_limit, charges
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param(
        "iissssssssssssssssssiiis", 
        $acc_no, $comp_info_id, $legal_name, $comp_name_card, $entity, 
        $buisness_type, $address, $address_2, $city, $phone_1, $phone_2, $mobile, $email, $fax, 
        $person_name, $designation, $ntn, $sales_tax, $authorize_signatory, $auth_design, 
        $due_date, $billing_frequency_id, $credit_limit, $charges
    );

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Account entry created successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Database insert error']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

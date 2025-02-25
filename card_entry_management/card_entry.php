<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required Fields
    $required_fields = [
        'category_type', 'first_name', 'last_name', 'dob', 'marital_status',
        'address', 'city', 'cnic', 'msisdn', 'email',
        'products_id', 'daily_freq',
        'limit_type', 'txn_limit', 'daily_limit', 'weekly_limit', 'monthly_limit', 'non_fuel_limit'
    ];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
            exit;
        }
    } // ✅ Closing bracket added here for foreach

    // 🔒 Secure & Sanitize Inputs
    $card = isset($_POST['card']) ? mysqli_real_escape_string($db, $_POST['card']) : null;
    $selec_comp = isset($_POST['selec_comp']) ? mysqli_real_escape_string($db, $_POST['selec_comp']) : null;
    $category_type = mysqli_real_escape_string($db, $_POST['category_type']);
    $selec_batch = isset($_POST['selec_batch']) ? mysqli_real_escape_string($db, $_POST['selec_batch']) : null;
    $selec_acc = isset($_POST['selec_acc']) ? mysqli_real_escape_string($db, $_POST['selec_acc']) : null;
    $card_type_id = isset($_POST['card_type_id']) ? mysqli_real_escape_string($db, $_POST['card_type_id']) : null;
    $plastic_type_id = isset($_POST['plastic_type_id']) ? mysqli_real_escape_string($db, $_POST['plastic_type_id']) : null;
    $batch_desc = isset($_POST['batch_desc']) ? mysqli_real_escape_string($db, $_POST['batch_desc']) : null;
    $total_cards = isset($_POST['total_cards']) ? mysqli_real_escape_string($db, $_POST['total_cards']) : null;
    $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
    $gender = isset($_POST['gender']) ? mysqli_real_escape_string($db, $_POST['gender']) : null;
    $dob = mysqli_real_escape_string($db, $_POST['dob']);
    $marital_status = mysqli_real_escape_string($db, $_POST['marital_status']);
    $card_name = isset($_POST['card_name']) ? mysqli_real_escape_string($db, $_POST['card_name']) : null;
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $city = mysqli_real_escape_string($db, $_POST['city']);
    $cnic = mysqli_real_escape_string($db, $_POST['cnic']);
    $msisdn = mysqli_real_escape_string($db, $_POST['msisdn']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $products_id = (int) $_POST['products_id'];
    $daily_freq = (int) $_POST['daily_freq'];
    $weekly_freq = isset($_POST['weekly_freq']) ? mysqli_real_escape_string($db, $_POST['weekly_freq']) : null;
    $limit_type = mysqli_real_escape_string($db, $_POST['limit_type']);
    $txn_limit = (int) $_POST['txn_limit'];
    $daily_limit = (int) $_POST['daily_limit'];
    $weekly_limit = (int) $_POST['weekly_limit'];
    $monthly_limit = (int) $_POST['monthly_limit'];
    $non_fuel_limit = (int) $_POST['non_fuel_limit'];

    // 🔄 Check for Duplicate CNIC, MSISDN, Email, or Card
    $dup_check = "SELECT id FROM card_entry WHERE cnic='$cnic' OR msisdn='$msisdn' OR email='$email' OR card='$card'";
    $dup_result = $db->query($dup_check);

    if ($dup_result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'CNIC, MSISDN, Email, or Card already exists']);
        exit;
    }

    // 🔄 Insert Query
    $query = "INSERT INTO card_entry (
        card, selec_comp, category_type, selec_batch, selec_acc, 
        card_type_id, plastic_type_id, batch_desc, total_cards, 
        first_name, last_name, gender, dob, marital_status, 
        card_name, address, city, cnic, msisdn, email, 
        products_id, daily_freq, weekly_freq, 
        limit_type, txn_limit, daily_limit, weekly_limit, 
        monthly_limit, non_fuel_limit, created_at, updated_at
    ) VALUES (
        '$card', '$selec_comp', '$category_type', '$selec_batch', '$selec_acc', 
        '$card_type_id', '$plastic_type_id', '$batch_desc', '$total_cards', 
        '$first_name', '$last_name', '$gender', '$dob', '$marital_status', 
        '$card_name', '$address', '$city', '$cnic', '$msisdn', '$email', 
        '$products_id', '$daily_freq', '$weekly_freq', 
        '$limit_type', '$txn_limit', '$daily_limit', '$weekly_limit', 
        '$monthly_limit', '$non_fuel_limit', NOW(), NOW()
    )";

    if ($db->query($query)) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Card entry created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database insert error: ' . $db->error]);
    }
} else { // ✅ Closing bracket added here
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
} // ✅ Closing bracket for outermost if
?>

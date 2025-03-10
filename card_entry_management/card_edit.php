<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   


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

    // 🔍 Fetch Card Details
    $check_query = "SELECT id, card, products_id, limit_type FROM card_entry WHERE card = '$card'";
    $check_result = $db->query($check_query);

    if ($check_result->num_rows > 0) {

        $query = "UPDATE card_entry
        SET
            selec_comp = '$selec_comp',
            category_type = '$category_type',
            selec_batch = '$selec_batch',
            selec_acc = '$selec_acc',
            card_type_id = '$card_type_id',
            plastic_type_id = '$plastic_type_id',
            batch_desc = '$batch_desc',
            total_cards = '$total_cards',
            first_name = '$first_name',
            last_name = '$last_name',
            gender = '$gender',
            dob = '$dob',
            marital_status = '$marital_status',
            card_name = '$card_name',
            address = '$address',
            city = '$city',
            cnic = '$cnic',
            msisdn = '$msisdn',
            email = '$email',
            products_id = '$products_id',
            daily_freq = '$daily_freq',
            weekly_freq = '$weekly_freq',
            limit_type = '$limit_type',
            txn_limit = '$txn_limit',
            daily_limit = '$daily_limit',
            weekly_limit = '$weekly_limit',
            monthly_limit = '$monthly_limit',
            non_fuel_limit = '$non_fuel_limit',
            updated_at = NOW()
        WHERE
            card = '$card'; -- Assuming 'card' is a unique identifier. Replace with your actual unique key.";
       
        if ($db->query($query)) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Card entry updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database insert error: ' . $db->error]);
        }
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Card not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

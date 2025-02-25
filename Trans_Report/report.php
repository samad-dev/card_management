<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required Fields
    $required_fields = ['acc_type_id', 'card', 'dealer', 'trans_from', 'trans_to', 'report_type'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
            exit;
        }
    }

    // ✅ Sanitize Inputs
    $acc_type_id = (int) $_POST['acc_type_id'];
    $card = (int) $_POST['card'];
    $dealer = mysqli_real_escape_string($db, $_POST['dealer']);
    $trans_from = mysqli_real_escape_string($db, $_POST['trans_from']);
    $trans_to = mysqli_real_escape_string($db, $_POST['trans_to']);
    $report_type = mysqli_real_escape_string($db, $_POST['report_type']);

    // ✅ Insert Query
    $query = "INSERT INTO trans_reports (acc_type_id, card, dealer, trans_from, trans_to, report_type) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iissss", $acc_type_id, $card, $dealer, $trans_from, $trans_to, $report_type);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Transaction report added successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert report']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

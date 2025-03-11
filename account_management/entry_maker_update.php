<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required: id must be provided
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing id']);
        exit;
    }

    // ✅ Sanitize Input
    $id = mysqli_real_escape_string($db, $_POST['id']);

    // ✅ Check if the Account Exists
    $check_query = "SELECT * FROM acc_entry WHERE id = ?";
    $stmt = $db->prepare($check_query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Account not found']);
        exit;
    }

    $existing_data = $result->fetch_assoc(); // Fetch current data

    // ✅ Fields that can be updated
    $updatable_fields = [
        'acc_no', 'comp_info_id', 'legal_name', 'comp_name_card', 'entity', 'buisness_type',
        'address', 'address_2', 'city', 'phone_1', 'phone_2', 'mobile', 'email', 'fax',
        'person_name', 'designation', 'ntn', 'sales_tax', 'authorize_signatory', 'auth_design',
        'due_date', 'billing_frequency_id', 'credit_limit', 'charges'
    ];

    $fields = [];
    $values = [];
    $changes_made = false;

    foreach ($updatable_fields as $field) {
        if (isset($_POST[$field]) && $_POST[$field] !== '') {
            $new_value = mysqli_real_escape_string($db, $_POST[$field]);
            
            // ✅ Check if the new value is different from the current database value
            if ($existing_data[$field] !== $new_value) {
                $fields[] = "$field = ?";
                $values[] = $new_value;
                $changes_made = true;
            }
        }
    }

    // ✅ If no changes were made, return "Already Updated"
    if (!$changes_made) {
        http_response_code(200);
        echo json_encode(['status' => 'info', 'message' => 'No changes detected, already updated']);
        exit;
    }

    // ✅ Construct SQL Query Dynamically
    $query = "UPDATE acc_entry SET " . implode(", ", $fields) . ", updated_at = NOW() WHERE id = ?";
    $values[] = $id; // Add id for the WHERE clause

    $stmt = $db->prepare($query);
    $types = str_repeat("s", count($values)); // Assume all fields are strings
    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Account updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database update error']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

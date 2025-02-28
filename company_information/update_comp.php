<?php
include("../db.php"); // Database connection
include("../header.php"); // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing ID parameter']);
        exit;
    }

    $id = mysqli_real_escape_string($db, $_POST['id']);
    $fields = [];

    // Loop through fields and add only non-empty ones to update query
    foreach ($_POST as $key => $value) {
        if ($key !== 'id' && !empty($value)) {
            $safe_value = mysqli_real_escape_string($db, $value);
            $fields[] = "$key='$safe_value'";
        }
    }

    if (count($fields) == 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
        exit;
    }

    // 🔄 Update Query
    $query = "UPDATE comp_info SET " . implode(", ", $fields) . ", updated_at=NOW() WHERE id='$id'";

    if ($db->query($query)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Company updated successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Database update error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

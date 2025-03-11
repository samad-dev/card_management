<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing ID parameter']);
        exit;
    }

    $id = mysqli_real_escape_string($db, $_POST['id']);

    // ✅ Check if ID exists
    $checkQuery = "SELECT id FROM comp_info WHERE id = '$id'";
    $checkResult = $db->query($checkQuery);

    if ($checkResult->num_rows == 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
        exit;
    }

    // 🔄 Delete Query
    $query = "DELETE FROM comp_info WHERE id = '$id'";

    if ($db->query($query)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Company deleted successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Database delete error']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

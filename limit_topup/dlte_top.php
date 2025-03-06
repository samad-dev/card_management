<?php
include("../db.php"); // Database connection
include("../header.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Check if 'id' is provided
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing field: id']);
        exit;
    }

    // ✅ Sanitize input
    $id = (int) $_POST['id'];

    // ✅ Check if record exists
    $check_query = $db->prepare("SELECT id FROM card_topup_maker WHERE id = ?");
    $check_query->bind_param("i", $id);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Record not found']);
        exit;
    }

    // ✅ Delete record
    $delete_query = $db->prepare("DELETE FROM card_topup_maker WHERE id = ?");
    $delete_query->bind_param("i", $id);

    if ($delete_query->execute()) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete record']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

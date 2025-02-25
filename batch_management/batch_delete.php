<?php
include("../db.php"); // Database connection
header('Content-Type: application/json');

// ✅ Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// ✅ Try to get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// ✅ Fallback: Check if `id` was sent via form-data
if (!$data && isset($_POST['id'])) {
    $data['id'] = $_POST['id'];
}

// ✅ Debug: If still no data, return error
if (!$data || !isset($data['id']) || empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => "Invalid or missing JSON body"]);
    exit;
}

$id = (int) $data['id'];

// ✅ Check if batch exists
$check_query = "SELECT id FROM batch_manag WHERE id = ?";
$stmt = $db->prepare($check_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Batch not found']);
    exit;
}

// ✅ Delete batch
$delete_query = "DELETE FROM batch_manag WHERE id = ?";
$stmt = $db->prepare($delete_query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Batch deleted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database delete error']);
}
?>

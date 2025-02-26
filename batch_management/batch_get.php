<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // ✅ Check if ID is provided
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Missing field: id"]);
        exit;
    }

    $id = (int) $_GET['id'];

    // ✅ Fetch Batch Details Excluding `selec_batch`
    $query = "SELECT id, category, card_type_id, plastic_type_id, batch_no, selec_acc, selec_comp, batch_decrip, records, created_at, updated_at 
              FROM batch_manag WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $batch = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $batch]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Batch not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

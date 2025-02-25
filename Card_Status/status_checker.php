<?php
include("../db.php"); // Database connection
include("../header.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // ✅ Check if 'card_number' is provided
    if (!isset($_GET['card_number']) || empty($_GET['card_number'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing field: card_number']);
        exit;
    }

    $card_number = (int) $_GET['card_number'];

    // ✅ Fetch Data
    $query = "SELECT id, status_types, selec_comp, selec_acc, card_number, current_card_status, new_card_status, created_at, updated_at 
              FROM card_status 
              WHERE card_number = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $card_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'No record found for the given card_number']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

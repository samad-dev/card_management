<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Check if 'card' is provided
    if (!isset($_POST['card']) || empty($_POST['card'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing field: card']);
        exit;
    }

    // ✅ Sanitize Input
    $card = mysqli_real_escape_string($db, $_POST['card']);

    // ✅ Check if the card exists
    $checkQuery = "SELECT id FROM card_entry WHERE card = '$card'";
    $checkResult = $db->query($checkQuery);

    if ($checkResult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Card not found']);
        exit;
    }

    // ✅ Delete the record
    $deleteQuery = "DELETE FROM card_entry WHERE card = '$card'";
    if ($db->query($deleteQuery)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Card deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete card']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

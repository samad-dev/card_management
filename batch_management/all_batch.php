<?php
include("../db.php"); // Database connection
include("../header.php");

// 🔍 Fetch All Records
$query = "SELECT bm.*,ct.card_name,pt.plastic_name FROM `batch_manag` bm join card_types ct on ct.card_type_id = bm.card_type_id join plastic_types pt on pt.plastic_type_id = bm.plastic_type_id;";
$result = $db->query($query);

if ($result->num_rows > 0) {
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    http_response_code(200);
    echo json_encode(['status' => 'success', 'data' => $data]);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'No records found']);
}
?>

<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

// 🔍 Fetch All Records
$query = "SELECT ce.*, ct.card_name, pt.plastic_name, ci.company_name FROM `card_entry` ce INNER JOIN `card_types` as ct on ce.card_type_id = ct.card_type_id INNER JOIN `plastic_types` pt ON ce.plastic_type_id = pt.plastic_type_id INNER JOIN `comp_info` ci ON ce.selec_comp = ci.id;";
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

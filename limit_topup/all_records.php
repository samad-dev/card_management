<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

// 🔍 Fetch All Records
$query = "SELECT ct.*, ci.company_name, ctt.card_name, bm.category FROM `card_topup_maker` ct INNER JOIN `comp_info`ci ON ct.comp_types_id = ci.id INNER JOIN `card_types` ctt ON ct.card_types_id = ctt.card_type_id INNER JOIN `batch_manag` bm on ct.selec_category = bm.id;";
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

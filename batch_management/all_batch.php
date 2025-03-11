<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

// 🔍 Fetch All Records
$query = "SELECT * FROM batch_manag";
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

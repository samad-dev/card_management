<?php
include("../db.php"); // Database connection
include("../header.php"); // Header file (if needed)

// 🔍 Fetch Only selec_comp (assuming it's company_code from comp_info)
$query = "SELECT * FROM comp_info";
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

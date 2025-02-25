<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // ✅ Check if ID is provided (optional)
    $condition = "";
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = (int) $_GET['id'];
        $condition = "WHERE id = $id";
    }

    // ✅ Fetch Data Query
    $query = "SELECT 
                instrument_type, bank_name, instrument_ref_no, 
                tot_up_amm, selec_category, card_types_id, topup_amm 
              FROM card_topup_maker $condition";
    
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $records = [];
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $records], JSON_PRETTY_PRINT);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'No records found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

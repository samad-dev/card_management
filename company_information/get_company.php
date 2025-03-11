<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing ID parameter']);
        exit;
    }

    $id = mysqli_real_escape_string($db, $_GET['id']);
    $query = "SELECT * FROM comp_info WHERE id = '$id'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $company = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $company]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Company not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

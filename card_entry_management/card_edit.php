<?php
include("../db.php"); // Database connection
header('Content-Type: application/json'); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['card']) || empty($_POST['card'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Missing required field: card"]);
        exit;
    }

    $card = mysqli_real_escape_string($db, $_POST['card']);

    // 🔍 Fetch Card Details
    $check_query = "SELECT id, card, products_id, limit_type FROM card_entry WHERE card = '$card'";
    $check_result = $db->query($check_query);

    if ($check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();

        // ✅ If Only Fetching Details
        if (!isset($_POST['products_id']) && !isset($_POST['limit_type'])) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $row]);
            exit;
        }

        $update_fields = [];

        // ✅ Validate `products_id`
        if (isset($_POST['products_id']) && $_POST['products_id'] != $row['products_id']) {
            $products_id = mysqli_real_escape_string($db, $_POST['products_id']);
            
            // 🛑 Check if `products_id` exists in `products` table
            $prod_check = "SELECT id FROM products WHERE id = '$products_id'";
            $prod_result = $db->query($prod_check);

            if ($prod_result->num_rows > 0) {
                $update_fields[] = "products_id = '$products_id'";
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid products_id. No matching record found in products table']);
                exit;
            }
        }

        // ✅ Update `limit_type`
        if (isset($_POST['limit_type']) && $_POST['limit_type'] != $row['limit_type']) {
            $limit_type = mysqli_real_escape_string($db, $_POST['limit_type']);
            $update_fields[] = "limit_type = '$limit_type'";
        }

        // 🔄 Update Query Execution
        if (!empty($update_fields)) {
            $update_query = "UPDATE card_entry SET " . implode(", ", $update_fields) . ", updated_at = NOW() WHERE card = '$card'";
            if ($db->query($update_query)) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Card details updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Database update error: ' . $db->error]);
            }
        } else {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'No changes were made']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Card not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

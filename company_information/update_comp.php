<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing ID parameter']);
        exit;
    }

    $id = mysqli_real_escape_string($db, $_POST['id']);
    $fields = [];
    
    if (!empty($_POST['company_code'])) {
        $company_code = mysqli_real_escape_string($db, $_POST['company_code']);
        $fields[] = "company_code='$company_code'";
    }
    if (!empty($_POST['company_name'])) {
        $company_name = mysqli_real_escape_string($db, $_POST['company_name']);
        $fields[] = "company_name='$company_name'";
    }
    if (!empty($_POST['address'])) {
        $address = mysqli_real_escape_string($db, $_POST['address']);
        $fields[] = "address='$address'";
    }
    if (!empty($_POST['city'])) {
        $city = mysqli_real_escape_string($db, $_POST['city']);
        $fields[] = "city='$city'";
    }
    if (!empty($_POST['sales_tax'])) {
        $sales_tax = mysqli_real_escape_string($db, $_POST['sales_tax']);
        $fields[] = "sales_tax='$sales_tax'";
    }
    if (!empty($_POST['ntn'])) {
        $ntn = mysqli_real_escape_string($db, $_POST['ntn']);
        $fields[] = "ntn='$ntn'";
    }

    if (count($fields) == 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
        exit;
    }

    // ✅ Dynamic Duplicate Check (Only if relevant fields exist)
    $dup_conditions = [];
    if (isset($company_code)) {
        $dup_conditions[] = "company_code='$company_code'";
    }
    if (isset($company_name)) {
        $dup_conditions[] = "company_name='$company_name'";
    }
    if (isset($ntn)) {
        $dup_conditions[] = "ntn='$ntn'";
    }

    if (!empty($dup_conditions)) {
        $dup_check = "SELECT * FROM comp_info WHERE (" . implode(" OR ", $dup_conditions) . ") AND id != '$id'";
        $dup_result = $db->query($dup_check);
        if ($dup_result->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Company Code, Name, or NTN already exists']);
            exit;
        }
    }

    // 🔄 Update Query
    $query = "UPDATE comp_info SET " . implode(", ", $fields) . ", updated_at=NOW() WHERE id='$id'";

    if ($db->query($query)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Company updated successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Database update error']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

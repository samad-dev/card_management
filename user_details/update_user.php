<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required ID
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing ID parameter']);
        exit;
    }

    $id = mysqli_real_escape_string($db, $_POST['id']);
    $fields = [];

    if (!empty($_POST['name'])) {
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $fields[] = "name='$name'";
    }
    if (!empty($_POST['username'])) {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $fields[] = "username='$username'";

        // ✅ Check for duplicate username (excluding current user)
        $dup_username_check = "SELECT id FROM users WHERE username='$username' AND id != '$id'";
        $dup_username_result = $db->query($dup_username_check);
        if ($dup_username_result->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Username already exists']);
            exit;
        }
    }
    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $fields[] = "email='$email'";

        // ✅ Check for duplicate email (excluding current user)
        $dup_email_check = "SELECT id FROM users WHERE email='$email' AND id != '$id'";
        $dup_email_result = $db->query($dup_email_check);
        if ($dup_email_result->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
            exit;
        }
    }
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($db, $_POST['password']);
        $fields[] = "password='$password'";
    }

    if (count($fields) == 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
        exit;
    }

    // 🔄 Update Query
    $query = "UPDATE users SET " . implode(", ", $fields) . ", updated_at=NOW() WHERE id='$id'";

    if ($db->query($query)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database update error']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
<?php
include("../db.php"); // Database connection
include("../header.php"); // JSON Response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ✅ Required Fields
    $required_fields = ['name', 'username', 'email', 'password'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
            exit;
        }
    }

    $name = mysqli_real_escape_string($db, $_POST['name']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // 🔄 Check for Duplicate Email or Username
    $dup_check = "SELECT * FROM users WHERE email='$email' OR username='$username'";
    $dup_result = $db->query($dup_check);

    if ($dup_result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Email or Username already exists']);
        exit;
    }

    // 🔄 Insert User
    $query = "INSERT INTO users (name, username, email, password, created_at, updated_at) 
              VALUES ('$name', '$username', '$email', '$password', NOW(), NOW())";
    
    if ($db->query($query)) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Database insert error']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

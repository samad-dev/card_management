<?php
$host = "localhost"; // Ya jo bhi host ho
$user = "root"; // MySQL username
$password = ""; // MySQL ka password (agar hai to likho, warna blank rakho)
$database = "card_management"; // Tumhara database name

$db = new mysqli($host, $user, $password, $database);

// Check connection
if ($db->connect_error) {
    die("Database Connection Failed: " . $db->connect_error);
}
?>

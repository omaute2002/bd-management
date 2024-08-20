<?php
$servername = "localhost"; // Replace with your database server
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$database = "bd_management"; // Replace with your database name
define('BASE_URL', '/bd-management/');
define("DIR_URL", $_SERVER['DOCUMENT_ROOT'] . '/');
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

// Create connection using OOP style
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection using OOP error checking
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

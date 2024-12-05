<?php
include("db.php");

$name = trim($_POST['name']);
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$description = trim($_POST['description']);

if (empty($name) || empty($quantity) || empty($price)) {
    die("Error: All fields are required.");
}

$stmt = $conn->prepare("INSERT INTO products (name, quantity, price, description) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sids", $name, $quantity, $price, $description);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

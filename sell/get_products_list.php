<?php
include('../db.php');  // Ensure the database connection

// Query to fetch available products
$sql = "SELECT id, name, quantity FROM products";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return the product list as JSON
echo json_encode($products);

$conn->close();
?>

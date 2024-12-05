<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$name = $data['name'];
$quantity = $data['quantity'];
$price = $data['price'];
$description = $data['description'];

$query = "UPDATE products SET name = ?, quantity = ?, price = ?, description = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('siisi', $name, $quantity, $price, $description, $id);

if ($stmt->execute()) {
    echo "Product updated successfully.";
} else {
    echo "Error updating product.";
}

$conn->close();
?>

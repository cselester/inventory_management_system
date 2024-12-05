<?php
include 'db.php'; // Ensure this file connects to your database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $query = "UPDATE products SET name=?, quantity=?, price=?, description=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sidsi", $name, $quantity, $price, $description, $id);

    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error updating product: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

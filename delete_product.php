<?php
include 'db.php';

$id = $_GET['id'];

$query = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo "Product deleted successfully.";
} else {
    echo "Error deleting product.";
}

$conn->close();
?>

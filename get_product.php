<?php
include 'db.php'; // Ensure this file connects to your database

// Get the product ID from the query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(["error" => "Product not found."]);
    }
} else {
    echo json_encode(["error" => "Invalid product ID."]);
}

$conn->close();
?>

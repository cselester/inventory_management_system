<?php
include('../db.php');  // Ensure the database connection

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM customers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_customers.php"); // Redirect back to the customer list page
        exit();
    } else {
        echo "Error deleting customer: " . $stmt->error;
    }
}

$conn->close();
?>
    
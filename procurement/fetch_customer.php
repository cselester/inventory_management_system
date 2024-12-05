<?php
include('../db.php');  // Ensure the database connection

// Check if 'id' parameter is provided in the request
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare SQL statement to fetch customer details
    $sql = "SELECT * FROM customers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Fetch the customer data and return as JSON
            $customer = $result->fetch_assoc();
            echo json_encode($customer);
        } else {
            echo json_encode(["error" => "Customer not found"]);
        }
    } else {
        echo json_encode(["error" => "Failed to execute query"]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "No customer ID provided"]);
}
?>

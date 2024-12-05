<?php
include('../db.php');  // Ensure the database connection

if (isset($_POST['customer_id'])) {
    // Get the data from the POST request
    $customer_id = $_POST['customer_id'];
    $owner_name = $_POST['owner_name'];
    $company_name = $_POST['company_name'];
    $contact_number = $_POST['contact_number'];
    $email_id = $_POST['email_id'];
    $shipping_address = $_POST['shipping_address'];

    // Prepare the update query
    $sql = "UPDATE customers 
            SET owner_name = ?, 
                company_name = ?, 
                contact_number = ?, 
                email_id = ?, 
                shipping_address = ? 
            WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters to the prepared statement
    $stmt->bind_param("sssssi", $owner_name, $company_name, $contact_number, $email_id, $shipping_address, $customer_id);

    // Execute the query and check if successful
    if ($stmt->execute()) {
        echo 'success';  // Return success message
    } else {
        echo 'error';  // Return error message
    }

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
}
?>

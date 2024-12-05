<?php
include('../db.php');  // Ensure the database connection
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <link rel="stylesheet" href="pstyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=cancel" />
    <script>
        $(document).ready(function() {
            // Open modal and populate fields on clicking the update button
            $('.update-btn').on('click', function() {
                var customerId = $(this).data('id');
                
                // Send AJAX request to fetch customer data
                $.ajax({
                    url: 'fetch_customer.php',
                    type: 'GET',
                    data: { id: customerId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data) {
                            $('#owner_name').val(data.owner_name);
                            $('#company_name').val(data.company_name);
                            $('#contact_number').val(data.contact_number);
                            $('#email_id').val(data.email_id);
                            $('#shipping_address').val(data.shipping_address);
                            $('#customer_id').val(data.id);
                            $('#updateModal').show();
                        }
                    }
                });
            });

            // Handle form submission for update
            $('#updateForm').on('submit', function(event) {
                event.preventDefault();

                var formData = $(this).serialize(); // Collect form data

                $.ajax({
                    url: 'update_customer.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Error updating customer!');
                        }
                    }
                });
            });

            // Handle delete button click using AJAX
            $('.delete-btn').on('click', function() {
                var customerId = $(this).data('id');
                
                if (confirm("Are you sure you want to delete this customer?")) {
                    $.ajax({
                        url: 'delete_customer.php',
                        type: 'POST',
                        data: { id: customerId },
                        success: function(response) {
                            if (response == 'success') {
                                // Remove the row from the table
                                $('#row_' + customerId).remove();
                            } else {
                                alert('Error deleting customer!');
                            }
                        }
                    });
                }
            });

            // Close modal
            $('#closeModal').on('click', function() {
                $('#updateModal').hide();
            });
        });
    </script>
</head>
<body>

<h1>Customer List</h1>

<?php
// Fetch customer data from the database
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Output data for each customer
    echo "<table>
            <tr>
                <th>Owner Name</th>
                <th>Company Name</th>
                <th>Contact Number</th>
                <th>Email ID</th>
                <th>Shipping Address</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr id='row_" . $row['id'] . "'>
                <td>" . htmlspecialchars($row['owner_name']) . "</td>
                <td>" . htmlspecialchars($row['company_name']) . "</td>
                <td>" . htmlspecialchars($row['contact_number']) . "</td>
                <td>" . htmlspecialchars($row['email_id']) . "</td>
                <td>" . htmlspecialchars($row['shipping_address']) . "</td>
                <td>
                    <button class='update-btn' data-id='" . $row['id'] . "'>Update</button>
                    <button class='delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No customers found.";
}

// Close the database connection
$conn->close();
?>
<a href="add_customer.php">
    <button>Add Customer</button>
</a>

<!-- Modal for updating customer -->
<div id="updateModal">
    <div>
        <h2>Update Customer</h2>
        <form id="updateForm">
            <input type="hidden" id="customer_id" name="customer_id">
            <label for="owner_name">Owner Name:</label>
            <input type="text" name="owner_name" id="owner_name" required><br><br>

            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" id="company_name" required><br><br>

            <label for="contact_number">Contact Number:</label>
            <input type="text" name="contact_number" id="contact_number"><br><br>

            <label for="email_id">Email ID:</label>
            <input type="email" name="email_id" id="email_id"><br><br>

            <label for="shipping_address">Shipping Address:</label>
            <textarea name="shipping_address" id="shipping_address" required></textarea><br><br>

            <button type="submit">Update Customer</button>
        </form>
        <button id="closeModal"><span class="material-symbols-outlined">cancel</span></button>
    </div>
</div>

</body>
</html>

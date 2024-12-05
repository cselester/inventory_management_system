<?php
include('../db.php'); // Ensure the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pstyle.css">
    <title>Add Customer</title>
</head>
<body>

<a href="view_customers.php" style="max-width: 400px; display: inline-block;">
    <button style="width: 100%; max-width: 100%;">Customer List</button>
</a>


<div class="container">
    <h1>Add Customer</h1>

    <form action="add_customer.php" method="POST">
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

        <button type="submit">Add Customer</button>
        
    </form>
</div>

<?php
    $message = "";
    $messageClass = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collect form data and validate
        $owner_name = trim($_POST['owner_name']);
        $company_name = trim($_POST['company_name']);
        $contact_number = trim($_POST['contact_number']);
        $email_id = trim($_POST['email_id']);
        $shipping_address = trim($_POST['shipping_address']);

        // Check if any required field is empty
        if (empty($owner_name) || empty($company_name) || empty($shipping_address)) {
            $message = "Owner Name, Company Name, and Shipping Address are required fields.";
            $messageClass = "error-box";
        } else {
            // Validate email format
            if (!empty($email_id) && !filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
                $message = "Invalid email format.";
                $messageClass = "error-box";
            } elseif (!empty($contact_number) && !preg_match("/^\d{10}$/", $contact_number)) {
                // Validate contact number (10 digits)
                $message = "Invalid contact number format. It should be 10 digits.";
                $messageClass = "error-box";
            } else {
                // Function to calculate similarity (Levenshtein distance)
                function isSimilarCompany($conn, $company_name) {
                    $threshold = 95; // Set similarity percentage (95%)
                    $similar = false;

                    $sql = "SELECT company_name FROM customers";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $db_company = $row['company_name'];

                            // Calculate similarity
                            similar_text(strtolower($company_name), strtolower($db_company), $percent);

                            if ($percent >= $threshold) {
                                $similar = $db_company;
                                break;
                            }
                        }
                    }

                    return $similar;
                }

                // Function to check if mobile number exists
                function isDuplicateMobile($conn, $contact_number) {
                    $sql = "SELECT contact_number FROM customers WHERE contact_number = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $contact_number);
                    $stmt->execute();
                    $stmt->store_result();

                    return $stmt->num_rows > 0;
                }

                // Check for duplicate mobile or similar company
                $duplicateMobile = !empty($contact_number) && isDuplicateMobile($conn, $contact_number);
                $similarCompany = isSimilarCompany($conn, $company_name);

                if ($duplicateMobile) {
                    $message = "A customer with this contact number already exists.";
                    $messageClass = "error-box";
                } elseif ($similarCompany) {
                    $message = "A company with a similar name ('$similarCompany') already exists.";
                    $messageClass = "error-box";
                } else {
                    // Prepare the SQL query to insert the customer details
                    $sql = "INSERT INTO customers (owner_name, company_name, contact_number, email_id, shipping_address) 
                            VALUES (?, ?, ?, ?, ?)";

                    // Prepare and bind the statement to avoid SQL injection
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("sssss", $owner_name, $company_name, $contact_number, $email_id, $shipping_address);

                        // Execute the statement
                        if ($stmt->execute()) {
                            $message = "Customer added successfully!";
                            $messageClass = "success-box";
                        } else {
                            $message = "Error executing the query: " . $stmt->error;
                            $messageClass = "error-box";
                        }

                        // Close the statement
                        $stmt->close();
                    } else {
                        $message = "Error preparing the SQL statement: " . $conn->error;
                        $messageClass = "error-box";
                    }
                }
            }
        }

        // Close the database connection
        $conn->close();
    }
    ?>

    <!-- Display Message -->
    <?php if (!empty($message)): ?>
        <div class="message-box <?php echo $messageClass; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <a href="../index.php">
    <button id="back-home" style="position: absolute; top: 20px; right: 20px;">
        Back to home
    </button>
    </a>

</body>
</html>

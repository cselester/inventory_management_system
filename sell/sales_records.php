<?php
include('../db.php');  // Ensure the database connection

// Fetch sales records from the database
$sales_query = "SELECT s.id, p.name AS product_name, s.quantity_sold, c.owner_name AS customer_name, s.sale_date 
                FROM sales s 
                JOIN products p ON s.product_id = p.id 
                JOIN customers c ON s.wholesaler_id = c.id  -- Assuming wholesaler_id links to the customer table
                ORDER BY s.sale_date DESC";

$sales_result = $conn->query($sales_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="salesstyle.css">
    <title>Sales Records</title>
</head>
<body>

    <h1>Sales Records</h1>

    <!-- Table to display sales records -->
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity Sold</th>
                <th>Customer Name</th>
                <th>Sale Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($sales_result->num_rows > 0): ?>
                <?php while ($sale = $sales_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($sale['quantity_sold']); ?></td>
                        <td><?php echo htmlspecialchars($sale['customer_name']); ?></td> <!-- Corrected field -->
                        <td><?php echo htmlspecialchars($sale['sale_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No sales records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="sell.php">Back to Sell Products</a>

</body>
</html>

<?php
$conn->close();
?>

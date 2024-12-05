<?php
include('../db.php');  // Ensure the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Products</title>
    <link rel="stylesheet" href="salesstyle.css">
    <script>
        // JavaScript to add rows for multiple products dynamically
        function addProductRow() {
            const table = document.getElementById("productTable").getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();

            const productCell = newRow.insertCell(0);
            const quantityCell = newRow.insertCell(1);
            const deleteCell = newRow.insertCell(2);

            // Product dropdown
            productCell.innerHTML = `
                <select name="product_id[]" required>
                    <option value="">Select a product</option>
                    <?php
                        $result = $conn->query("SELECT id, name FROM products");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    ?>
                </select>
            `;

            // Quantity input
            quantityCell.innerHTML = '<input type="number" name="quantity[]" placeholder="Quantity" min="1" required>';

            // Delete button
            deleteCell.innerHTML = '<button type="button" onclick="deleteProductRow(this)">Delete</button>';
        }

        // JavaScript to delete a specific row
        function deleteProductRow(button) {
            const row = button.parentElement.parentElement; // Get the parent row
            row.remove(); // Remove the row from the table
        }
    </script>
</head>
<body>

    <h1>Sell Multiple Products</h1>

    <!-- Form for multiple products -->
    <form id="sellForm" method="POST" action="sell_product.php">
        <label for="wholesaler_id">Customer Name:</label>
        <select name="wholesaler_id" required>
            <option value="">Select Wholesaler</option>
            <?php
                $result = $conn->query("SELECT id, company_name FROM customers");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['company_name'] . "</option>";
                }
            ?>
        </select>

        <!-- Table to display products and quantities -->
        <table id="productTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="product_id[]" required>
                            <option value="">Select a product</option>
                            <?php
                                $result = $conn->query("SELECT id, name FROM products");
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="quantity[]" placeholder="Quantity" min="1" required>
                    </td>
                    <td>
                        <button type="button" onclick="deleteProductRow(this)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" onclick="addProductRow()">Add Product</button>
        <br><br>
        <button type="submit">Sell Products</button>
    </form>
    <a href="../index.php">
    <button id="back-home" style="position: absolute; top: 20px; right: 20px; padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Back to home
    </button>
    </a>

</body>
</html>

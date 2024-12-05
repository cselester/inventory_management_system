<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
    <a href="index.php"><button class="home">Back to home</button></a>
        <h1>Inventory Management System</h1>

        <!-- Form to Add Product -->
        <form id="productForm" method="POST" action="add_product.php">
            <h2>Add New Product</h2>
            <input type="text" name="name" id="name" placeholder="Product Name" required>
            <input type="number" name="quantity" id="quantity" placeholder="Quantity" required>
            <input type="number" name="price" id="price" placeholder="Price" required>
            <textarea name="description" id="description" placeholder="Description"></textarea>
            <button type="submit">Add Product</button>
        </form>

        <!-- Product List -->
        <h2>Product List</h2>
        <table id="productTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Edit</th>
                </tr>
            </thead>

            <tbody id="productList"></tbody>
        </table>
    </div>

    <!-- Edit Modal -->
<div id="editModal" style="display: none;">
    <h2>Edit Product</h2>
    <input type="hidden" id="editProductId">
    <input type="text" id="editName" placeholder="Product Name" required>
    <input type="number" id="editQuantity" placeholder="Quantity" required>
    <input type="number" id="editPrice" placeholder="Price" required>
    <textarea id="editDescription" placeholder="Description"></textarea>
    <button onclick="saveEditedProduct()">Save Changes</button>
    <button onclick="document.getElementById('editModal').style.display='none'">Cancel</button>
</div>


    <script src="scripts.js"></script>
</body>
</html>

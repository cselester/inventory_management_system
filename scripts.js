// Fetch Products and Populate Table
function fetchProducts() {
    fetch('get_products.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('productList').innerHTML = data;
        })
        .catch(error => console.error('Error fetching products:', error));
}

// Function to Edit a Product
function editProduct(productId) {
    // Fetch product details by ID
    fetch(`get_product.php?id=${productId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(product => {
            if (product.error) {
                alert(product.error);
                return;
            }

            // Populate form fields
            document.getElementById('editName').value = product.name;
            document.getElementById('editQuantity').value = product.quantity;
            document.getElementById('editPrice').value = product.price;
            document.getElementById('editDescription').value = product.description;
            document.getElementById('editProductId').value = product.id;

            // Show the modal
            document.getElementById('editModal').style.display = 'block';
        })
        .catch(error => console.error('Error fetching product details:', error));
}


// Function to Save Edited Product
function saveEditedProduct() {
    const productId = document.getElementById('editProductId').value;
    const updatedProduct = {
        id: productId,
        name: document.getElementById('editName').value,
        quantity: document.getElementById('editQuantity').value,
        price: document.getElementById('editPrice').value,
        description: document.getElementById('editDescription').value,
    };

    fetch('update_product.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedProduct),
    })
        .then(response => response.text())
        .then(message => {
            alert(message); // Show success message
            fetchProducts(); // Refresh product list
            document.getElementById('editModal').style.display = 'none'; // Hide modal
        })
        .catch(error => console.error('Error updating product:', error));
}



// Fetch products when the page loads
window.onload = fetchProducts;


//dynamic handling of adding products

document.getElementById('productForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent form from submitting normally

    // Create FormData object
    const formData = new FormData(this);

    // Send data via AJAX
    fetch('add_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(data => {
        console.log('Response data:', data); // Log backend response for debugging
        if (data.trim() === "success") {
            alert("Product added successfully");
            this.reset(); // Clear form
            fetchProducts(); // Refresh product list
        } else {
            alert("Error: " + data);
        }
    })
    .catch(error => console.error('Error:', error));
    
});

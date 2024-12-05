
function populateProductDropdown() {
    fetch('get_products_list.php') // Backend script to return product list as JSON
        .then(response => response.json())
        .then(data => {
            const productDropdown = document.getElementById('product');
            productDropdown.innerHTML = ''; // Clear existing options
            data.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} (Available: ${product.quantity})`;
                productDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching product list:', error));
}

window.onload = populateProductDropdown; // Populate on page load


// Fetch product data from the server to populate the dropdown
function fetchProducts() {
    fetch('get_products_list.php')
        .then(response => response.json())
        .then(data => {
            const productDropdown = document.getElementById('product');
            productDropdown.innerHTML = ''; // Clear the dropdown first
            data.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} (Available: ${product.quantity})`;
                productDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching products:', error));
}




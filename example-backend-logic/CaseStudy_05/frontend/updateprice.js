// updateprice.js - Handle price update functionality

document.addEventListener('DOMContentLoaded', function () {
  loadProducts();
});

// Load all products from database
async function loadProducts() {
  try {
    const response = await fetch('../backend/api_products.php');
    const data = await response.json();

    if (data.success) {
      displayProducts(data.products);
    } else {
      showMessage('Error loading products: ' + data.message, 'error');
    }
  } catch (error) {
    showMessage('Error connecting to server: ' + error.message, 'error');
  }
}

// Display products in the UI
function displayProducts(products) {
  const container = document.getElementById('productsContainer');
  container.innerHTML = '';
  container.classList.remove('loading');

  products.forEach(product => {
    const card = createProductCard(product);
    container.appendChild(card);
  });
}

// Create a product card element
function createProductCard(product) {
  const card = document.createElement('div');
  card.className = 'product-card';

  const priceDisplay = product.has_options
    ? `Single: $${parseFloat(product.price_single).toFixed(2)} | Double: $${parseFloat(product.price_double).toFixed(2)}`
    : `Price: $${parseFloat(product.price_single).toFixed(2)}`;

  card.innerHTML = `
        <h3>${escapeHtml(product.product_name)}</h3>
        <p class="product-description">${escapeHtml(product.description)}</p>
        
        <div class="current-prices">
            <strong>Current Prices:</strong><br>
            ${priceDisplay}
        </div>
        
        <form class="price-form" onsubmit="updatePrice(event, ${product.product_id})">
            <div class="price-input-group">
                <label for="price_single_${product.product_id}">
                    ${product.has_options ? 'Single Price:' : 'Price:'}
                </label>
                <input type="number" 
                       step="0.01" 
                       min="0" 
                       name="price_single" 
                       id="price_single_${product.product_id}"
                       value="${parseFloat(product.price_single).toFixed(2)}"
                       required>
            </div>
            
            ${
              product.has_options
                ? `
                <div class="price-input-group">
                    <label for="price_double_${product.product_id}">Double Price:</label>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           name="price_double" 
                           id="price_double_${product.product_id}"
                           value="${parseFloat(product.price_double).toFixed(2)}"
                           required>
                </div>
            `
                : ''
            }
            
            <button type="submit" class="update-btn">Update Price</button>
        </form>
    `;

  return card;
}

// Update price for a product
async function updatePrice(event, productId) {
  event.preventDefault();

  const form = event.target;
  const submitBtn = form.querySelector('.update-btn');

  // Disable button during update
  submitBtn.disabled = true;
  submitBtn.textContent = 'Updating...';

  const formData = new FormData(form);
  formData.append('product_id', productId);

  try {
    const response = await fetch('../backend/api_update_price.php', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    if (data.success) {
      showMessage('Price updated successfully!', 'success');
      // Reload products to show updated prices
      loadProducts();
    } else {
      showMessage('Error updating price: ' + data.message, 'error');
    }
  } catch (error) {
    showMessage('Error connecting to server: ' + error.message, 'error');
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Update Price';
  }
}

// Show message to user
function showMessage(message, type) {
  const messageBox = document.getElementById('messageBox');
  messageBox.textContent = message;
  messageBox.className = `message ${type} show`;

  // Auto-hide after 5 seconds
  setTimeout(() => {
    messageBox.classList.remove('show');
  }, 5000);
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

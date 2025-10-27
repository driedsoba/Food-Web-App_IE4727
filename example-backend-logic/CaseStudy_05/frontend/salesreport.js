// salesreport.js - Handle sales report generation

document.addEventListener('DOMContentLoaded', function () {
  // Set default date to today
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('reportDate').value = today;
});

// Generate report for selected date
async function generateReport() {
  const date = document.getElementById('reportDate').value;

  if (!date) {
    alert('Please select a date');
    return;
  }

  try {
    const response = await fetch(`../backend/api_sales_report.php?date=${date}`);
    const data = await response.json();

    if (data.success) {
      displaySalesByProduct(data.sales_by_product, data.product_details);
      displaySalesByCategory(data.sales_by_category);
      displayBestSelling(data.best_selling);
    } else {
      alert('Error loading report: ' + data.message);
    }
  } catch (error) {
    alert('Error connecting to server: ' + error.message);
  }
}

// Display Sales by Product table
function displaySalesByProduct(products, productDetails) {
  const section = document.getElementById('salesByProduct');
  const content = document.getElementById('productContent');

  if (!products || products.length === 0) {
    content.innerHTML = '<div class="no-data">No sales data available for this date.</div>';
    section.style.display = 'block';
    return;
  }

  let html = `
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Total Dollar Sales</th>
                    <th>Quantity Sales</th>
                    <th>Breakdown</th>
                </tr>
            </thead>
            <tbody>
    `;

  products.forEach(product => {
    // Get breakdown details for this product
    const details = productDetails.filter(d => d.product_name === product.product_name);
    let breakdown = '';

    if (details.length > 0) {
      breakdown = details.map(d => {
        const option = d.option_type === null ? 'endless' : d.option_type;
        return `${option}: ${d.qty}`;
      }).join('<br>');
    }

    html += `
            <tr>
                <td>${escapeHtml(product.product_name)}</td>
                <td>$${parseFloat(product.total_revenue).toFixed(2)}</td>
                <td>${product.total_qty}</td>
                <td style="font-size: 0.9em; color: #666;">${breakdown}</td>
            </tr>
        `;
  });

  html += `
            </tbody>
        </table>
    `;

  content.innerHTML = html;
  section.style.display = 'block';
}

// Display Sales by Category table (Single, Double, Null)
function displaySalesByCategory(categories) {
  const section = document.getElementById('salesByCategory');
  const content = document.getElementById('categoryContent');

  if (!categories || categories.length === 0) {
    content.innerHTML = '<div class="no-data">No sales data available for this date.</div>';
    section.style.display = 'block';
    return;
  }

  let html = `
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total Dollar Sales</th>
                    <th>Quantity Sales</th>
                </tr>
            </thead>
            <tbody>
    `;

  categories.forEach((category) => {
    // Display "endless" instead of "Null" for products without options (Just Java - endless cup)
    const categoryDisplay =
      category.category === 'Null' ? 'endless' : escapeHtml(category.category);
    html += `
            <tr>
                <td>${categoryDisplay}</td>
                <td>$${parseFloat(category.total_revenue).toFixed(2)}</td>
                <td>${category.total_qty}</td>
            </tr>
        `;
  });

  html += `
            </tbody>
        </table>
    `;

  content.innerHTML = html;
  section.style.display = 'block';
}

// Display Best Selling Product and its most popular option
function displayBestSelling(bestSelling) {
  const section = document.getElementById('bestSelling');
  const content = document.getElementById('bestSellingContent');

  if (!bestSelling) {
    content.innerHTML = '<div class="no-data">No sales data available for this date.</div>';
    section.style.display = 'block';
    return;
  }

  const optionDisplay =
    bestSelling.popular_option === 'null' ||
      bestSelling.popular_option === 'N/A' ||
      bestSelling.popular_option === null
      ? 'endless'
      : bestSelling.popular_option.charAt(0).toUpperCase() +
      bestSelling.popular_option.slice(1);

  const html = `
        <div class="best-selling-box">
            <p><strong>Popular (with highest quantity sold) option (category) of best selling (highest $$) product is:</strong></p>
            <p class="highlight">
                ${optionDisplay} of ${escapeHtml(bestSelling.product_name)}
            </p>
            <p style="margin-top: 15px; color: #666;">
                <em>Note: "${escapeHtml(bestSelling.product_name)}" had the highest revenue ($${parseFloat(bestSelling.total_revenue).toFixed(2)}), 
                and "${optionDisplay}" option had the highest quantity sold (${bestSelling.popular_option_qty} units) for this product.</em>
            </p>
        </div>
    `;

  content.innerHTML = html;
  section.style.display = 'block';
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
  if (!text) return '';
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

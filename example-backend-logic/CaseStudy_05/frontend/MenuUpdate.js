// MenuUpdate.js - simple calculator for menu.html
// Reads quantities and shot selections, calculates per-item subtotals and total price

// Prices object - will be updated from database
let prices = {
  justjava: 2.0,
  cafelait: { single: 2.0, double: 3.0 },
  capp: { single: 4.75, double: 5.75 }
};

document.addEventListener('DOMContentLoaded', async () => {
  // Load current prices from database
  await loadPrices();

  // Elements
  const qtyJust = document.getElementById('qty_justjava');
  const subJust = document.getElementById('sub_justjava');

  const qtyCafe = document.getElementById('qty_cafelait');
  const subCafe = document.getElementById('sub_cafelait');
  const cafeSingle = document.getElementById('cafelait_single');
  const cafeDouble = document.getElementById('cafelait_double');

  const qtyCapp = document.getElementById('qty_capp');
  const subCapp = document.getElementById('sub_capp');
  const cappSingle = document.getElementById('capp_single');
  const cappDouble = document.getElementById('capp_double');

  const totalField = document.getElementById('total_price');

  // Helper: parse integer quantity (safe)
  function readQty(el) {
    const v = parseInt(el.value, 10);
    return isNaN(v) || v < 0 ? 0 : v;
  }

  function update() {
    // Just Java
    const qJust = readQty(qtyJust);
    const sJust = qJust * prices.justjava;
    subJust.value = sJust.toFixed(2);

    // Cafe au Lait
    const qCafe = readQty(qtyCafe);
    const cafeShot = cafeDouble.checked ? 'double' : 'single';
    const sCafe = qCafe * prices.cafelait[cafeShot];
    subCafe.value = sCafe.toFixed(2);

    // Iced Cappuccino
    const qCapp = readQty(qtyCapp);
    const cappShot = cappDouble.checked ? 'double' : 'single';
    const sCapp = qCapp * prices.capp[cappShot];
    subCapp.value = sCapp.toFixed(2);

    // Total
    const total = sJust + sCafe + sCapp;
    totalField.value = total.toFixed(2);
  }

  // Attach events
  [qtyJust, qtyCafe, qtyCapp].forEach(el => el.addEventListener('input', update));
  [cafeSingle, cafeDouble, cappSingle, cappDouble].forEach(el =>
    el.addEventListener('change', update)
  );

  // Initial update (will run after prices are loaded)
  update();
});

// Load prices from database
async function loadPrices() {
  try {
    const response = await fetch('../backend/api_products.php');
    if (!response.ok) {
      throw new Error('Failed to load prices');
    }

    const data = await response.json();

    if (!data.success || !data.products) {
      throw new Error('Invalid response from API');
    }

    const products = data.products;

    // Update prices object with database values
    products.forEach((product) => {
      if (product.product_name === 'Just Java') {
        prices.justjava = parseFloat(product.price_single);
      } else if (product.product_name === 'Cafe au Lait') {
        prices.cafelait = {
          single: parseFloat(product.price_single),
          double: parseFloat(product.price_double)
        };
      } else if (product.product_name === 'Iced Cappuccino') {
        prices.capp = {
          single: parseFloat(product.price_single),
          double: parseFloat(product.price_double)
        };
      }
    });

    updateDisplayedPrices();
  } catch (error) {
    console.error('Error loading prices:', error);
    // Keep default hardcoded prices if fetch fails
  }
}

// Update the prices shown in the HTML
function updateDisplayedPrices() {
  // Update Just Java price display
  const justJavaPrice = document.getElementById('price_justjava');
  if (justJavaPrice) {
    justJavaPrice.textContent = `Endless Cup $${prices.justjava.toFixed(2)}`;
  }

  // Update Cafe au Lait price display
  const cafeLaitPrice = document.getElementById('price_cafelait');
  if (cafeLaitPrice) {
    cafeLaitPrice.textContent = `Single $${prices.cafelait.single.toFixed(2)} Double $${prices.cafelait.double.toFixed(2)}`;
  }

  // Update Iced Cappuccino price display
  const cappPrice = document.getElementById('price_capp');
  if (cappPrice) {
    cappPrice.textContent = `Single $${prices.capp.single.toFixed(2)} Double $${prices.capp.double.toFixed(2)}`;
  }
}

// Checkout function - saves order to database
async function checkOut() {
  const qtyJust = parseInt(document.getElementById('qty_justjava').value) || 0;
  const qtyCafe = parseInt(document.getElementById('qty_cafelait').value) || 0;
  const qtyCapp = parseInt(document.getElementById('qty_capp').value) || 0;

  const totalQty = qtyJust + qtyCafe + qtyCapp;

  if (totalQty === 0) {
    showCheckoutMessage('Please add items to your order before checking out.', 'error');
    return;
  }

  // Get selected options
  const cafeOption = document.getElementById('cafelait_double').checked ? 'double' : 'single';
  const cappOption = document.getElementById('capp_double').checked ? 'double' : 'single';

  // Build order data
  const orderData = new FormData();

  if (qtyJust > 0) {
    orderData.append(
      'items[]',
      JSON.stringify({
        product_id: 1,
        product_name: 'Just Java',
        quantity: qtyJust,
        option: null
      })
    );
  }

  if (qtyCafe > 0) {
    orderData.append(
      'items[]',
      JSON.stringify({
        product_id: 2,
        product_name: 'Cafe au Lait',
        quantity: qtyCafe,
        option: cafeOption
      })
    );
  }

  if (qtyCapp > 0) {
    orderData.append(
      'items[]',
      JSON.stringify({
        product_id: 3,
        product_name: 'Iced Cappuccino',
        quantity: qtyCapp,
        option: cappOption
      })
    );
  }

  try {
    const response = await fetch('../backend/api_checkout.php', {
      method: 'POST',
      body: orderData
    });

    const data = await response.json();

    if (data.success) {
      showCheckoutMessage(`Order placed successfully! Total: $${data.total}`, 'success');
      document.getElementById('qty_justjava').value = 0;
      document.getElementById('qty_cafelait').value = 0;
      document.getElementById('qty_capp').value = 0;
      document.getElementById('qty_justjava').dispatchEvent(new Event('input'));
    } else {
      showCheckoutMessage('Error: ' + data.message, 'error');
    }
  } catch (error) {
    showCheckoutMessage('Error connecting to server: ' + error.message, 'error');
  }
}

function showCheckoutMessage(message, type) {
  const messageBox = document.getElementById('checkoutMessage');
  messageBox.textContent = message;
  messageBox.style.display = 'block';
  messageBox.style.background = type === 'success' ? '#d4edda' : '#f8d7da';
  messageBox.style.color = type === 'success' ? '#155724' : '#721c24';
  messageBox.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
  setTimeout(() => {
    messageBox.style.display = 'none';
  }, 5000);
}

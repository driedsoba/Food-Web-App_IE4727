const API_BASE = import.meta.env.VITE_API_BASE ?? "/api";

function isJson(res) {
  return (res.headers.get("content-type") || "").includes("application/json");
}

export async function createOrderFromCartItems(orderData) {
  const res = await fetch(`${API_BASE}/orders.php?action=create`, {
    method: "POST",
    credentials: "include",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(orderData),
  });
  if (!res.ok || !isJson(res)) {
    const text = await res.text().catch(() => "");
    throw new Error(text || `Failed to create order (${res.status})`);
  }
  return res.json();
}

export async function getOrders() {
  const res = await fetch(`${API_BASE}/orders.php`, {
    credentials: "include",
    headers: { "Content-Type": "application/json" },
  });
  if (!res.ok || !isJson(res)) {
    const text = await res.text().catch(() => "");
    throw new Error(text || `Failed to load orders (${res.status})`);
  }
  return res.json();
}

export async function advanceOrderStatus(orderId) {
  const res = await fetch(`${API_BASE}/orders.php?action=advance&id=${orderId}`, {
    method: "POST",
    credentials: "include",
    headers: { "Content-Type": "application/json" },
  });
  if (!res.ok || !isJson(res)) {
    const text = await res.text().catch(() => "");
    throw new Error(text || `Failed to advance status (${res.status})`);
  }
  return res.json();
}
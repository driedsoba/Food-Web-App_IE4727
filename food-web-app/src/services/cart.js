const API_BASE = import.meta.env.VITE_API_BASE ?? "/api";

function isJson(res) {
  return (res.headers.get("content-type") || "").includes("application/json");
}

export async function getCart() {
  const res = await fetch(`${API_BASE}/cart.php`, {
    credentials: "include",
    headers: { "Content-Type": "application/json" },
  });
  if (!res.ok || !isJson(res)) {
    const text = await res.text().catch(() => "");
    throw new Error(text || `Cart API error (${res.status})`);
  }
  return res.json();
}
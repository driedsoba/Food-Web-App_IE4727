import React, { useEffect, useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";
import { getCart } from "../services/cart";
import { createOrderFromCartItems } from "../services/orders";

export default function CheckoutPage() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState("");
  const navigate = useNavigate();

  useEffect(() => {
    let mounted = true;
    (async () => {
      try {
        const data = await getCart();
        const cartItems = (data.items || []).map((it) => ({
          // Be tolerant of cart payload shapes
          menu_item_id: it.menu_item_id ?? it.id,
          name: it.name,
          quantity: Number(it.quantity ?? 1),
          price: Number(it.price ?? 0),
        }));
        if (mounted) setItems(cartItems);
      } catch (e) {
        setError(e.message || "Failed to load cart");
      } finally {
        if (mounted) setLoading(false);
      }
    })();
    return () => (mounted = false);
  }, []);

  const total = useMemo(
    () => items.reduce((sum, it) => sum + (it.price || 0) * (it.quantity || 1), 0),
    [items]
  );

  const onPlaceOrder = async () => {
    setSubmitting(true);
    setError("");
    try {
      const payload = items.map(({ menu_item_id, quantity, price }) => ({
        menu_item_id,
        quantity,
        price,
      }));
      const result = await createOrderFromCartItems(payload);
      if (result?.success) {
        navigate("/order-status", { replace: true });
      } else {
        throw new Error("Order creation failed");
      }
    } catch (e) {
      setError(e.message || "Checkout failed");
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) return <div style={{ padding: 24 }}>Loading checkout…</div>;
  if (error) return <div role="alert" style={{ padding: 24, color: "#b00020" }}>{error}</div>;

  return (
    <div style={{ padding: 24 }}>
      <h1>Checkout</h1>
      {items.length === 0 ? (
        <p>Your cart is empty.</p>
      ) : (
        <>
          <ul>
            {items.map((it, idx) => (
              <li key={`${it.menu_item_id}-${idx}`}>
                {it.name || `Item ${it.menu_item_id}`} × {it.quantity} — ${it.price.toFixed(2)}
              </li>
            ))}
          </ul>
          <h3>Total: ${total.toFixed(2)}</h3>
          <button onClick={onPlaceOrder} disabled={submitting}>
            {submitting ? "Placing…" : "Place Order"}
          </button>
        </>
      )}
    </div>
  );
}
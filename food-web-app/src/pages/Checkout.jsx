import React, { useEffect, useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";
import { cartAPI, ordersAPI } from "../services/api";

export default function CheckoutPage() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState("");
  const [address, setAddress] = useState("");
  const [phoneNumber, setPhoneNumber] = useState("");
  const [validationErrors, setValidationErrors] = useState({});
  const navigate = useNavigate();

  useEffect(() => {
    let mounted = true;
    (async () => {
      try {
        const data = await cartAPI.getCart();
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

  // Validation functions
  const validateAddress = (addr) => {
    if (!addr || addr.trim().length < 10) {
      return "Address must be at least 10 characters";
    }
    return "";
  };

  const validatePhoneNumber = (phone) => {
    // Remove any whitespace
    const cleanPhone = phone.replace(/\s/g, "");
    
    // Check if exactly 8 digits
    if (!/^\d{8}$/.test(cleanPhone)) {
      return "Phone number must be exactly 8 digits (e.g., 67489380)";
    }
    return "";
  };

  const validateForm = () => {
    const errors = {};
    
    const addressError = validateAddress(address);
    if (addressError) errors.address = addressError;
    
    const phoneError = validatePhoneNumber(phoneNumber);
    if (phoneError) errors.phoneNumber = phoneError;
    
    setValidationErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const onPlaceOrder = async () => {
    // Validate form before submitting
    if (!validateForm()) {
      setError("Please fix the validation errors below");
      return;
    }

    setSubmitting(true);
    setError("");
    try {
      const payload = items.map(({ menu_item_id, quantity, price }) => ({
        menu_item_id,
        quantity,
        price,
      }));
      const result = await ordersAPI.createOrder(payload);
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

  return (
    <div style={{ padding: 24 }}>
      <h1>Checkout</h1>
      
      {error && (
        <div role="alert" style={{ padding: 12, marginBottom: 16, color: "#b00020", backgroundColor: "#fdecea", borderRadius: 4 }}>
          {error}
        </div>
      )}

      {items.length === 0 ? (
        <p>Your cart is empty.</p>
      ) : (
        <>
          <h2>Order Summary</h2>
          <ul style={{ marginBottom: 24 }}>
            {items.map((it, idx) => (
              <li key={`${it.menu_item_id}-${idx}`}>
                {it.name || `Item ${it.menu_item_id}`} × {it.quantity} — ${it.price.toFixed(2)}
              </li>
            ))}
          </ul>
          <h3 style={{ marginBottom: 24 }}>Total: ${total.toFixed(2)}</h3>

          <h2>Delivery Information</h2>
          <div style={{ marginBottom: 24 }}>
            <div style={{ marginBottom: 16 }}>
              <label htmlFor="address" style={{ display: "block", marginBottom: 4, fontWeight: "bold" }}>
                Delivery Address *
              </label>
              <input
                id="address"
                type="text"
                value={address}
                onChange={(e) => {
                  setAddress(e.target.value);
                  // Clear error when user starts typing
                  if (validationErrors.address) {
                    setValidationErrors({ ...validationErrors, address: "" });
                  }
                }}
                placeholder="Enter your full delivery address"
                style={{
                  width: "100%",
                  maxWidth: 500,
                  padding: 8,
                  fontSize: 16,
                  border: validationErrors.address ? "2px solid #b00020" : "1px solid #ccc",
                  borderRadius: 4
                }}
              />
              {validationErrors.address && (
                <div style={{ color: "#b00020", fontSize: 14, marginTop: 4 }}>
                  {validationErrors.address}
                </div>
              )}
              <div style={{ fontSize: 12, color: "#666", marginTop: 4 }}>
                Minimum 10 characters
              </div>
            </div>

            <div style={{ marginBottom: 16 }}>
              <label htmlFor="phoneNumber" style={{ display: "block", marginBottom: 4, fontWeight: "bold" }}>
                Phone Number *
              </label>
              <input
                id="phoneNumber"
                type="tel"
                value={phoneNumber}
                onChange={(e) => {
                  setPhoneNumber(e.target.value);
                  // Clear error when user starts typing
                  if (validationErrors.phoneNumber) {
                    setValidationErrors({ ...validationErrors, phoneNumber: "" });
                  }
                }}
                placeholder="e.g., 67489380"
                maxLength={8}
                style={{
                  width: "100%",
                  maxWidth: 500,
                  padding: 8,
                  fontSize: 16,
                  border: validationErrors.phoneNumber ? "2px solid #b00020" : "1px solid #ccc",
                  borderRadius: 4
                }}
              />
              {validationErrors.phoneNumber && (
                <div style={{ color: "#b00020", fontSize: 14, marginTop: 4 }}>
                  {validationErrors.phoneNumber}
                </div>
              )}
              <div style={{ fontSize: 12, color: "#666", marginTop: 4 }}>
                Must be exactly 8 digits
              </div>
            </div>
          </div>

          <button 
            onClick={onPlaceOrder} 
            disabled={submitting}
            style={{
              padding: "12px 24px",
              fontSize: 16,
              fontWeight: "bold",
              backgroundColor: submitting ? "#ccc" : "#4CAF50",
              color: "white",
              border: "none",
              borderRadius: 4,
              cursor: submitting ? "not-allowed" : "pointer"
            }}
          >
            {submitting ? "Placing…" : "Place Order"}
          </button>
        </>
      )}
    </div>
  );
}
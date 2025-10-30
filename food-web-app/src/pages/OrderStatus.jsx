import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { ordersAPI } from "../services/api";
import "./OrderStatus.css";

export default function OrderStatusPage() {
  const navigate = useNavigate();
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const load = async () => {
    setError("");
    setLoading(true);
    try {
      const data = await ordersAPI.getOrders();
      setOrders(data.orders || []);
    } catch (e) {
      setError(e.message || "Failed to load orders");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    load();
  }, []);

  const onAdvance = async (id) => {
    try {
      await ordersAPI.advanceOrderStatus(id);
      await load();
    } catch (e) {
      setError(e.message || "Failed to advance status");
    }
  };

  const getStatusDisplay = (status) => {
    const statusMap = {
      'order placed': 'Order Placed',
      'order accepted': 'Order Accepted',
      'preparing order': 'Preparing Order',
      'delivering order': 'Out for Delivery',
      'order delivered': 'Order Delivered'
    };
    return statusMap[status.toLowerCase()] || status;
  };

  const isDelivered = (status) => {
    return status.toLowerCase() === 'order delivered';
  };

  if (loading) {
    return (
      <div className="order-status-page">
        <div className="order-status-container">
          <p className="loading-message">Loading orders...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="order-status-page">
      <div className="order-status-container">
        <h1>Order Status</h1>

        {error && (
          <div className="error-message" role="alert">
            {error}
          </div>
        )}

        {orders.length === 0 ? (
          <div className="no-orders">
            <p>No orders yet.</p>
            <button onClick={() => navigate('/menu')} className="browse-menu-btn">
              Browse Menu
            </button>
          </div>
        ) : (
          <div className="orders-list">
            {orders.map((order) => (
              <div key={order.id} className="order-card">
                <div className="order-header">
                  <h3>Order #{order.id}</h3>
                  {order.created_at && (
                    <small className="order-date">
                      {new Date(order.created_at).toLocaleString()}
                    </small>
                  )}
                </div>

                <div className="order-status">
                  <span className="status-label">Status:</span>
                  <span className={`status-value status-${order.status.toLowerCase().replace(/\s+/g, '-')}`}>
                    {getStatusDisplay(order.status)}
                  </span>
                </div>

                <div className="order-items">
                  <h4>Items:</h4>
                  <ul>
                    {(order.items || []).map((item) => (
                      <li key={item.id}>
                        <span className="item-name">
                          {item.item_name || `Item ${item.menu_item_id}`}
                        </span>
                        <span className="item-quantity"> × {item.quantity}</span>
                        <span className="item-price"> — ${Number(item.price).toFixed(2)}</span>
                      </li>
                    ))}
                  </ul>
                </div>

                <div className="order-total">
                  <span>Total:</span>
                  <span className="total-amount">
                    ${Number(order.total_amount || 0).toFixed(2)}
                  </span>
                </div>

                {!isDelivered(order.status) && (
                  <button
                    onClick={() => onAdvance(order.id)}
                    className="advance-status-btn"
                  >
                    Advance to Next Status
                  </button>
                )}
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
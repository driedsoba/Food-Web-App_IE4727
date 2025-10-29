import './App.css'
import Header from './components/header/Header'
import Footer from './components/footer/Footer'
import HomePage from './pages/HomePage'
import Menu from './components/menu/Menu'
import Catering from './components/catering/Catering'
import LoginPage from './pages/Login'
import FeedbackPage from './pages/Feedback'
import Cart from './pages/Cart'
import Checkout from './pages/Checkout'
import { AuthProvider } from './context/AuthContext'
import { Routes, Route, Navigate } from 'react-router-dom'
import PrivateRoute from './components/auth/PrivateRoute'
import OrderStatus from "./pages/OrderStatus";

function App() {
  return (
    <AuthProvider>
      <div className="app">
        <Header />
        <main className="main-content">
          <Routes>
            <Route path="/" element={<HomePage />} />
            <Route path="/menu" element={<Menu />} />
            <Route path="/catering" element={<Catering />} />
            <Route path="/login" element={<LoginPage />} />
            <Route path="/feedback" element={<FeedbackPage />} />
            <Route
              path="/cart"
              element={
                <PrivateRoute>
                  <Cart />
                </PrivateRoute>
              }
            />
            <Route
              path="/checkout"
              element={
                <PrivateRoute>
                  <Checkout />
                </PrivateRoute>
              }
            />
            <Route
              path="/order-status"
              element={
                <PrivateRoute>
                  <OrderStatus />
                </PrivateRoute>
              }
            />
            {/* <Route path="*" element={<Navigate to="/" replace />} /> */}
          </Routes>
        </main>
        <Footer />
      </div>
    </AuthProvider>
  )
}

export default App

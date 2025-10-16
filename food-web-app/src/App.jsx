import './App.css'
import Header from './components/header/Header'
import Footer from './components/footer/Footer'
import HomePage from './pages/HomePage'
import Menu from './components/menu/Menu'
import Catering from './components/catering/Catering'
import Feedback from './pages/Feedback'
import { Routes, Route, Navigate } from 'react-router-dom'

function App() {
  return (
    <div className="app">
      <Header />
      <main className="main-content">
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/menu" element={<Menu />} />
          <Route path="/catering" element={<Catering />} />
          <Route path="/feedback" element={<Feedback />} />
          {/* <Route path="*" element={<Navigate to="/" replace />} /> */}
        </Routes>
      </main>
      <Footer />
    </div>
  )
}

export default App

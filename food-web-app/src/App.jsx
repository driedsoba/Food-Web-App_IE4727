import './App.css'
import Header from './components/header/Header'
import Footer from './components/footer/Footer'
import HomePage from './pages/HomePage'

function App() {
  return (
    <div className="app">
      <Header />
      <main className="main-content">
        <HomePage />
      </main>
      <Footer />
    </div>
  )
}

export default App

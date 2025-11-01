import { useState, useEffect, useContext, useRef } from 'react'
import { useSearchParams, useNavigate } from 'react-router-dom'
import { menuAPI, cartAPI } from '../services/api'
import { AuthContext } from '../context/AuthContext'
import './Menu.css'

const categories = ['All', 'Mains', 'Starters', 'Sides', 'Desserts']

const Menu = () => {
  const [searchParams] = useSearchParams()
  const navigate = useNavigate()
  const { isAuthenticated } = useContext(AuthContext)
  const initialized = useRef(false)

  // Initialize search from URL params
  const urlSearch = searchParams.get('search') || ''

  const [searchInput, setSearchInput] = useState(urlSearch) // What user types
  const [searchTerm, setSearchTerm] = useState(urlSearch) // What actually filters
  const [selectedCategory, setSelectedCategory] = useState('All')
  const [sortBy, setSortBy] = useState('name') // name, price-low, price-high, rating
  const [menuItems, setMenuItems] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  // Only set search from URL on first mount
  useEffect(() => {
    if (!initialized.current) {
      const query = searchParams.get('search')
      console.log('Initial URL search param:', query) // Debug log
      if (query) {
        setSearchInput(query)
        setSearchTerm(query)
        console.log('Initial search term set to:', query) // Debug log
      }
      initialized.current = true
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  // Fetch menu items from API
  useEffect(() => {
    const fetchMenuItems = async () => {
      try {
        setLoading(true)
        setError(null)

        // Build filters for API
        const filters = {}
        if (selectedCategory !== 'All') {
          filters.category = selectedCategory
        }
        if (searchTerm) {
          filters.search = searchTerm
        }

        console.log('Fetching menu with filters:', filters) // Debug log

        // Map sortBy to API format
        if (sortBy === 'price-low' || sortBy === 'price-high') {
          filters.sortBy = 'price'
          filters.sortOrder = sortBy === 'price-low' ? 'asc' : 'desc'
        } else {
          filters.sortBy = sortBy
          filters.sortOrder = sortBy === 'rating' ? 'desc' : 'asc'
        }

        const data = await menuAPI.getItems(filters)
        console.log('Received menu items:', data.length) // Debug log
        setMenuItems(data)
      } catch (err) {
        setError(err.message || 'Failed to load menu items')
        console.error('Error fetching menu items:', err)
      } finally {
        setLoading(false)
      }
    }

    fetchMenuItems()
  }, [selectedCategory, searchTerm, sortBy])

  // Handle add to cart
  const handleAddToCart = async (itemId) => {
    // Check if user is authenticated
    if (!isAuthenticated) {
      const confirmLogin = window.confirm(
        'Please login to add items to cart. Would you like to go to the login page?'
      )
      if (confirmLogin) {
        navigate('/login')
      }
      return
    }

    try {
      await cartAPI.addItem(itemId, 1)
      alert('Item added to cart!')
    } catch (err) {
      alert('Failed to add item to cart: ' + err.message)
    }
  }

  return (
    <div className="menu-page">
      <div className="menu-header">
        <h1>Our Menu</h1>
        <p>Authentic German cuisine, made fresh daily</p>
      </div>

      <div className="menu-controls">
        <div className="search-box">
          <input
            type="text"
            placeholder="Search dishes..."
            value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)}
            onKeyDown={(e) => {
              if (e.key === 'Enter') {
                setSearchTerm(searchInput)
              }
            }}
            className="search-input"
          />
        </div>

        <div className="filter-group">
          <label htmlFor="sort-select">Sort by:</label>
          <select
            id="sort-select"
            value={sortBy}
            onChange={(e) => setSortBy(e.target.value)}
            className="sort-select"
          >
            <option value="name">Name</option>
            <option value="price-low">Price: Low to High</option>
            <option value="price-high">Price: High to Low</option>
            <option value="rating">Rating</option>
          </select>
        </div>
      </div>

      <div className="category-tabs">
        {categories.map((category) => (
          <button
            key={category}
            className={`category-tab ${selectedCategory === category ? 'active' : ''}`}
            onClick={() => setSelectedCategory(category)}
          >
            {category}
          </button>
        ))}
      </div>

      {loading && (
        <div className="loading-state">
          <p>Loading menu items...</p>
        </div>
      )}

      {error && (
        <div className="error-state">
          <p>Error: {error}</p>
          <button className="retry-button" onClick={() => window.location.reload()}>
            Retry
          </button>
        </div>
      )}

      {!loading && !error && (
        <div className="results-info">
          Showing {menuItems.length} {menuItems.length === 1 ? 'dish' : 'dishes'}
        </div>
      )}

      {!loading && !error && (
        <div className="menu-grid">
          {menuItems.length > 0 ? (
            menuItems.map((item) => (
              <article key={item.id} className="menu-card">
                <div className="menu-card__media">
                  <img src={item.image_url} alt={item.name} loading="lazy" />
                  <span className="menu-card__rating">
                    <span aria-hidden="true">⭐</span>
                    {parseFloat(item.rating).toFixed(1)}
                  </span>
                </div>
                <div className="menu-card__content">
                  <span className="menu-card__category">{item.category}</span>
                  <h3>{item.name}</h3>
                  <p className="menu-card__description">{item.description}</p>
                  <div className="menu-card__meta">
                    <strong>${parseFloat(item.price).toFixed(2)}</strong>
                  </div>
                  <button
                    className="menu-card__button"
                    type="button"
                    onClick={() => handleAddToCart(item.id)}
                  >
                    <span aria-hidden="true">＋</span> Add to Cart
                  </button>
                </div>
              </article>
            ))
          ) : (
            <div className="no-results">
              <p>No dishes found matching your search.</p>
              <button
                className="reset-button"
                onClick={() => {
                  setSearchInput('')
                  setSearchTerm('')
                  setSelectedCategory('All')
                }}
              >
                Reset Filters
              </button>
            </div>
          )}
        </div>
      )}
    </div>
  )
}

export default Menu

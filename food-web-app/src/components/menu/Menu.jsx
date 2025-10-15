import { useState, useEffect } from 'react'
import { useSearchParams } from 'react-router-dom'
import './Menu.css'

// Full menu dataset with categories
const menuItems = [
  {
    id: 1,
    name: 'Wiener Schnitzel',
    category: 'Mains',
    price: 16.99,
    time: '25-35 min',
    rating: 4.9,
    description: 'Classic breaded veal cutlet, served with potato salad and lemon',
    image: 'https://images.unsplash.com/photo-1604908177176-a46aa23c0d12?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 2,
    name: 'Bratwurst Platter',
    category: 'Mains',
    price: 14.99,
    time: '20-30 min',
    rating: 4.7,
    description: 'Grilled German sausages with sauerkraut and mustard',
    image: 'https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 3,
    name: 'Käsespätzle',
    category: 'Mains',
    price: 13.99,
    time: '20-25 min',
    rating: 4.8,
    description: 'Traditional egg noodles with melted cheese and crispy onions',
    image: 'https://images.unsplash.com/photo-1505576633757-0ac1084af824?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 4,
    name: 'Sauerbraten',
    category: 'Mains',
    price: 17.49,
    time: '30-40 min',
    rating: 4.7,
    description: 'Tender pot roast marinated in vinegar with red cabbage',
    image: 'https://images.unsplash.com/photo-1525755662778-989d0524087e?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 5,
    name: 'Black Forest Cake',
    category: 'Desserts',
    price: 8.99,
    time: '20-25 min',
    rating: 5.0,
    description: 'Chocolate sponge cake with cherries and whipped cream',
    image: 'https://images.unsplash.com/photo-1544280979-4c0ae7abb8fc?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 6,
    name: 'Apple Strudel',
    category: 'Desserts',
    price: 7.99,
    time: '18-22 min',
    rating: 4.8,
    description: 'Flaky pastry filled with spiced apples and raisins',
    image: 'https://images.unsplash.com/photo-1504753793650-d4a2b783c15e?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 7,
    name: 'Bavarian Pretzel Board',
    category: 'Starters',
    price: 9.49,
    time: '15-20 min',
    rating: 4.6,
    description: 'Warm soft pretzels with assorted mustards and cheese dip',
    image: 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 8,
    name: 'German Potato Salad',
    category: 'Sides',
    price: 8.49,
    time: '15-20 min',
    rating: 4.5,
    description: 'Warm potato salad with bacon, onions, and vinegar dressing',
    image: 'https://images.unsplash.com/photo-1505935428862-770b6f24f629?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 9,
    name: 'Currywurst',
    category: 'Mains',
    price: 12.99,
    time: '15-20 min',
    rating: 4.6,
    description: 'Sliced bratwurst with curry ketchup and fries',
    image: 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 10,
    name: 'Rouladen',
    category: 'Mains',
    price: 16.49,
    time: '35-45 min',
    rating: 4.8,
    description: 'Beef rolls stuffed with bacon, onions, and pickles',
    image: 'https://images.unsplash.com/photo-1432139555190-58524dae6a55?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 11,
    name: 'Sauerkraut',
    category: 'Sides',
    price: 6.99,
    time: '10-15 min',
    rating: 4.4,
    description: 'Traditional fermented cabbage with caraway seeds',
    image: 'https://images.unsplash.com/photo-1567016526105-22da7c13161a?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 12,
    name: 'Maultaschen',
    category: 'Starters',
    price: 11.99,
    time: '20-25 min',
    rating: 4.7,
    description: 'German dumplings filled with meat and spinach in broth',
    image: 'https://images.unsplash.com/photo-1496412705862-e0088f16f791?auto=format&fit=crop&w=800&q=80',
  },
]

const categories = ['All', 'Mains', 'Starters', 'Sides', 'Desserts']

const Menu = () => {
  const [searchParams] = useSearchParams()
  const [searchTerm, setSearchTerm] = useState('')
  const [selectedCategory, setSelectedCategory] = useState('All')
  const [sortBy, setSortBy] = useState('name') // name, price-low, price-high, rating

  // Set search term from URL params on mount
  useEffect(() => {
    const query = searchParams.get('search')
    if (query) {
      setSearchTerm(query)
    }
  }, [searchParams])

  // Filter and sort logic
  const filteredItems = menuItems
    .filter((item) => {
      const matchesSearch = item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        item.description.toLowerCase().includes(searchTerm.toLowerCase())
      const matchesCategory = selectedCategory === 'All' || item.category === selectedCategory
      return matchesSearch && matchesCategory
    })
    .sort((a, b) => {
      switch (sortBy) {
        case 'price-low':
          return a.price - b.price
        case 'price-high':
          return b.price - a.price
        case 'rating':
          return b.rating - a.rating
        case 'name':
        default:
          return a.name.localeCompare(b.name)
      }
    })

  return (
    <div className="menu-page">
      <div className="menu-header">
        <h1>Our Menu</h1>
        <p>Authentic German cuisine, made fresh daily</p>
      </div>

      {/* Search and filter controls */}
      <div className="menu-controls">
        <div className="search-box">
          <input
            type="text"
            placeholder="Search dishes..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="search-input"
          />
          <span className="search-icon">🔍</span>
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

      {/* Category tabs */}
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

      {/* Results count */}
      <div className="results-info">
        Showing {filteredItems.length} {filteredItems.length === 1 ? 'dish' : 'dishes'}
      </div>

      {/* Menu items grid */}
      <div className="menu-grid">
        {filteredItems.length > 0 ? (
          filteredItems.map((item) => (
            <article key={item.id} className="menu-card">
              <div className="menu-card__media">
                <img src={item.image} alt={item.name} loading="lazy" />
                <span className="menu-card__rating">
                  <span aria-hidden="true">⭐</span>
                  {item.rating}
                </span>
              </div>
              <div className="menu-card__content">
                <span className="menu-card__category">{item.category}</span>
                <h3>{item.name}</h3>
                <p className="menu-card__description">{item.description}</p>
                <div className="menu-card__meta">
                  <strong>${item.price.toFixed(2)}</strong>
                  <span>{item.time}</span>
                </div>
                <button className="menu-card__button" type="button">
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
                setSearchTerm('')
                setSelectedCategory('All')
              }}
            >
              Reset Filters
            </button>
          </div>
        )}
      </div>
    </div>
  )
}

export default Menu

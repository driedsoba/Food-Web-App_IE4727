import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import './home.css'

const stats = [
  { label: 'Orders Delivered', value: '5,000+' },
  { label: 'Restaurants', value: '200+' },
  { label: 'Average Rating', value: '4.8★' },
]

const Hero = () => {
  const navigate = useNavigate()
  const [searchQuery, setSearchQuery] = useState('')

  const handleSearch = (e) => {
    e.preventDefault()
    if (searchQuery.trim()) {
      navigate(`/menu?search=${encodeURIComponent(searchQuery.trim())}`)
    }
  }

  return (
    <section className="home-hero" id="home">
      <div className="home-hero__content">
        <span className="home-badge">Free Delivery on First Order</span>
        <h1 className="home-hero__title">
          Delicious Food <span>Delivered Fast</span>
        </h1>
        <p className="home-hero__subtitle">
          Order from your favorite restaurants and get fresh, hot meals delivered right to your doorstep. Quick, easy,
          and satisfying!
        </p>
        {/* Simple search bar. Replace alert/submit logic later if needed. */}
        <form className="home-search" role="search" onSubmit={handleSearch}>
          <input
            id="hero-search"
            type="search"
            placeholder="Search for food"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
          />
          <button
            type="button"
            className="btn-secondary"
            onClick={() => {
              if (searchQuery.trim()) {
                navigate(`/menu?search=${encodeURIComponent(searchQuery.trim())}`)
              } else {
                navigate('/menu')
              }
            }}
          >
            Browse Menu
          </button>
        </form>
        <dl className="home-hero__stats">
          {stats.map((stat) => (
            <div key={stat.label} className="home-stat">
              <dt>{stat.label}</dt>
              <dd>{stat.value}</dd>
            </div>
          ))}
        </dl>
      </div>
      <div className="home-hero__media">
        <div className="home-hero__image-wrapper">
          <img
            alt="Selection of German dishes"
            className="home-hero__image"
            src="https://images.unsplash.com/photo-1528738064262-9f834cbdfda1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxkZWxpY2lvdXMlMjBmb29kJTIwcGxhdGV8ZW58MXx8fHwxNzU5NzIzOTk4fDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
          />
        </div>
      </div>
    </section>
  )
}

export default Hero

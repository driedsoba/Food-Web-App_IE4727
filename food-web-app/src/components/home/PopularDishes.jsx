import './home.css'

const dishes = [
  {
    name: 'Wiener Schnitzel',
    restaurant: 'Lecker Haus',
    price: '$16.99',
    time: '25-35 min',
    rating: '4.9',
    image: 'https://images.unsplash.com/photo-1604908177176-a46aa23c0d12?auto=format&fit=crop&w=800&q=80',
  },
  {
    name: 'Bratwurst Platter',
    restaurant: 'Lecker Haus',
    price: '$14.99',
    time: '20-30 min',
    rating: '4.7',
    image: 'https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=800&q=80',
  },
  {
    name: 'Käsespätzle',
    restaurant: 'Lecker Haus',
    price: '$13.99',
    time: '20-25 min',
    rating: '4.8',
    image: 'https://images.unsplash.com/photo-1505576633757-0ac1084af824?auto=format&fit=crop&w=800&q=80',
  },
  {
    name: 'Black Forest Cake',
    restaurant: 'Lecker Haus',
    price: '$8.99',
    time: '20-25 min',
    rating: '5.0',
    image: 'https://images.unsplash.com/photo-1544280979-4c0ae7abb8fc?auto=format&fit=crop&w=800&q=80',
  }, 
  {
    name: 'Bavarian Pretzel Board',
    restaurant: 'Lecker Haus',
    price: '$9.49',
    time: '15-20 min',
    rating: '4.6',
    image: 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?auto=format&fit=crop&w=800&q=80',
  },
  {
    name: 'Apple Strudel',
    restaurant: 'Lecker Haus',
    price: '$7.99',
    time: '18-22 min',
    rating: '4.8',
    image: 'https://images.unsplash.com/photo-1504753793650-d4a2b783c15e?auto=format&fit=crop&w=800&q=80',
  },
  {
    name: 'Sauerbraten',
    restaurant: 'Lecker Haus',
    price: '$17.49',
    time: '30-40 min',
    rating: '4.7',
    image: 'https://images.unsplash.com/photo-1525755662778-989d0524087e?auto=format&fit=crop&w=800&q=80',
  },
  {
    name: 'German Potato Salad',
    restaurant: 'Lecker Haus',
    price: '$8.49',
    time: '15-20 min',
    rating: '4.5',
    image: 'https://images.unsplash.com/photo-1505935428862-770b6f24f629?auto=format&fit=crop&w=800&q=80',
  },
]

const PopularDishes = () => {
  return (
    // Cards are generated from the dishes array above to keep JSX short and reusable.
    <section className="home-section home-section--dishes" id="menu" aria-labelledby="dishes-heading">
      <div className="home-section__header">
        <span className="home-eyebrow">Popular Dishes</span>
        <h2 id="dishes-heading">Our customers&apos; favorite German specialties</h2>
        <p>Prepared fresh daily with authentic recipes.</p>
      </div>
      <div className="home-dishes">
        {dishes.map((dish) => (
          <article key={dish.name} className="home-dish-card">
            <div className="home-dish-card__media">
              <img src={dish.image} alt={dish.name} loading="lazy" />
              <span className="home-dish-card__rating">
                <span aria-hidden="true">⭐</span>
                {dish.rating}
              </span>
            </div>
            <div className="home-dish-card__content">
              <h3>{dish.name}</h3>
              <span className="home-dish-card__restaurant">{dish.restaurant}</span>
              <div className="home-dish-card__meta">
                <strong>{dish.price}</strong>
                <span>{dish.time}</span>
              </div>
              <button className="home-dish-card__button" type="button">
                <span aria-hidden="true">＋</span> Add to Cart
              </button>
            </div>
          </article>
        ))}
      </div>
      <div className="home-dishes__footer">
        <a className="home-button home-button--outline" href="#menu">
          View Full Menu
        </a>
      </div>
    </section>
  )
}

export default PopularDishes

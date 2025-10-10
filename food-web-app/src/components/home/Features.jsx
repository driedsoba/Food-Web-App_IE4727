import './home.css'

const features = [
  {
    title: 'Fast Delivery',
    description: 'Get your food delivered hot and fresh within 30 minutes.',
    icon: '⏱️',
  },
  {
    title: 'Top Rated',
    description: 'All restaurants are verified and highly rated by customers.',
    icon: '⭐',
  },
  {
    title: 'Easy to Use',
    description: 'Simple ordering process with real-time tracking.',
    icon: '📱',
  },
  {
    title: 'Order Tracking',
    description: 'Track your order in real-time from kitchen to doorstep.',
    icon: '🚚',
  },
  {
    title: 'Secure Payment',
    description: 'Multiple payment options with 100% secure transactions.',
    icon: '💳',
  },
  {
    title: 'Event Catering',
    description: 'Professional catering services for all your special events.',
    icon: '🍽️',
  },
]

const Features = () => {
  return (
    // Features are listed in a simple 2-row grid (see CSS for layout tweaks).
    <section className="home-section home-section--features" aria-labelledby="features-heading">
      <div className="home-section__header">
        <span className="home-eyebrow">Why Choose Lecker Haus?</span>
        <h2 id="features-heading">Authentic German cuisine delivered with care</h2>
        <p>Here&apos;s what makes us special.</p>
      </div>
      <div className="home-features">
        {features.map((feature) => (
          <article key={feature.title} className="home-feature-card">
            <span className="home-feature-card__icon" aria-hidden="true">
              {feature.icon}
            </span>
            <div>
              <h3>{feature.title}</h3>
              <p>{feature.description}</p>
            </div>
          </article>
        ))}
      </div>
    </section>
  )
}

export default Features

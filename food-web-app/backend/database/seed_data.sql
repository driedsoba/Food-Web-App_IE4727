USE food_web_app;

-- Insert sample menu items
INSERT INTO
  menu_items (
    name,
    category,
    price,
    time,
    rating,
    description,
    image_url
  )
VALUES
  (
    'Wiener Schnitzel',
    'Mains',
    16.99,
    '25-35 min',
    4.9,
    'Classic breaded veal cutlet, served with potato salad and lemon',
    'https://images.unsplash.com/photo-1604908177176-a46aa23c0d12?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Bratwurst Platter',
    'Mains',
    14.99,
    '20-30 min',
    4.7,
    'Grilled German sausages with sauerkraut and mustard',
    'https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Käsespätzle',
    'Mains',
    13.99,
    '20-25 min',
    4.8,
    'Traditional egg noodles with melted cheese and crispy onions',
    'https://images.unsplash.com/photo-1505576633757-0ac1084af824?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Sauerbraten',
    'Mains',
    17.49,
    '30-40 min',
    4.7,
    'Tender pot roast marinated in vinegar with red cabbage',
    'https://images.unsplash.com/photo-1525755662778-989d0524087e?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Black Forest Cake',
    'Desserts',
    8.99,
    '20-25 min',
    5.0,
    'Chocolate sponge cake with cherries and whipped cream',
    'https://images.unsplash.com/photo-1544280979-4c0ae7abb8fc?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Apple Strudel',
    'Desserts',
    7.99,
    '18-22 min',
    4.8,
    'Flaky pastry filled with spiced apples and raisins',
    'https://images.unsplash.com/photo-1504753793650-d4a2b783c15e?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Bavarian Pretzel Board',
    'Starters',
    9.49,
    '15-20 min',
    4.6,
    'Warm soft pretzels with assorted mustards and cheese dip',
    'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'German Potato Salad',
    'Sides',
    8.49,
    '15-20 min',
    4.5,
    'Warm potato salad with bacon, onions, and vinegar dressing',
    'https://images.unsplash.com/photo-1505935428862-770b6f24f629?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Currywurst',
    'Mains',
    12.99,
    '15-20 min',
    4.6,
    'Sliced bratwurst with curry ketchup and fries',
    'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Rouladen',
    'Mains',
    16.49,
    '35-45 min',
    4.8,
    'Beef rolls stuffed with bacon, onions, and pickles',
    'https://images.unsplash.com/photo-1432139555190-58524dae6a55?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Sauerkraut',
    'Sides',
    6.99,
    '10-15 min',
    4.4,
    'Traditional fermented cabbage with caraway seeds',
    'https://images.unsplash.com/photo-1567016526105-22da7c13161a?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Maultaschen',
    'Starters',
    11.99,
    '20-25 min',
    4.7,
    'German dumplings filled with meat and spinach in broth',
    'https://images.unsplash.com/photo-1496412705862-e0088f16f791?auto=format&fit=crop&w=800&q=80'
  );

-- Insert sample user (password: password123)
INSERT INTO
  users (username, email, password, full_name, phone)
VALUES
  (
    'testuser',
    'test@example.com',
    'test123',
    'Test User',
    '+1234567890'
  );

-- Insert some approved feedbacks for display
INSERT INTO
  feedbacks (
    user_id,
    name,
    email,
    rating,
    order_number,
    feedback,
    approved
  )
VALUES
  (
    1,
    'Test User',
    'test@example.com',
    5,
    'ORD001',
    'Amazing food! The Wiener Schnitzel was perfectly cooked and delicious.',
    TRUE
  ),
  (
    NULL,
    'Jane Smith',
    'jane@example.com',
    5,
    'ORD002',
    'Best German food in town! Will definitely order again.',
    TRUE
  ),
  (
    NULL,
    'Mike Johnson',
    'mike@example.com',
    4,
    'ORD003',
    'Great flavors and generous portions. Delivery was quick too!',
    TRUE
  );
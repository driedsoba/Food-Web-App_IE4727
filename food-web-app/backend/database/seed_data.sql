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
    'https://images.unsplash.com/photo-1599921841143-819065a55cc6?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Bratwurst Platter',
    'Mains',
    14.99,
    '20-30 min',
    4.7,
    'Grilled German sausages with sauerkraut, mustard and green peas',
    'https://images.unsplash.com/photo-1658925111653-2c08083c08ff?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Sauerbraten',
    'Mains',
    17.49,
    '30-40 min',
    4.7,
    'Tender pot roast marinated in vinegar with red cabbage',
    'https://images.unsplash.com/photo-1622003184404-bc0c66144534?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Black Forest Cake',
    'Desserts',
    8.99,
    '20-25 min',
    5.0,
    'Chocolate sponge cake with cherries and whipped cream',
    'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Apple Strudel',
    'Desserts',
    7.99,
    '18-22 min',
    4.8,
    'Flaky pastry filled with spiced apples and raisins',
    'https://images.unsplash.com/photo-1657313938000-23c4322dbe22?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Rinderbraten',
    'Mains',
    9.49,
    '15-20 min',
    4.6,
    'Tender beef roast with mushroom sauce',
    'https://images.unsplash.com/photo-1746214093457-2305c2f8605e?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Krautsalat',
    'Starters',
    6.99,
    '10-15 min',
    4.5,
    'Traditional German coleslaw with caraway seeds',
    'https://images.unsplash.com/photo-1537784969314-05a37106f68e?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'German Potato Salad',
    'Sides',
    8.49,
    '15-20 min',
    4.5,
    'Warm potato salad with bacon, onions, and vinegar dressing',
    'https://images.unsplash.com/photo-1623501742030-65c324e08846?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Bratkartoffeln',
    'Sides',
    7.49,
    '15-20 min',
    4.6,
    'Pan-fried potatoes with onions and herbs',
    'https://images.unsplash.com/photo-1761315414257-e402bedaa43e?auto=format&fit=crop&w=800&q=80'
  ),
  (
    'Currywurst',
    'Mains',
    12.99,
    '15-20 min',
    4.6,
    'Sliced bratwurst with curry ketchup and fries',
    'https://images.unsplash.com/photo-1561701034-24ceb3e34433?auto=format&fit=crop&w=800&q=80'
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
    'Königsberger Klopse',
    'Starters',
    15.99,
    '25-30 min',
    4.7,
    'Traditional meatballs in creamy caper sauce',
    'https://images.unsplash.com/photo-1598511726903-ef08ef6eff94?auto=format&fit=crop&w=800&q=80'
  );

-- Insert sample user (password: password123)
INSERT INTO
  users (username, email, password, full_name)
VALUES
  (
    'testuser',
    'test@example.com',
    '$2y$10$jUIX.u4ncPl1vzcVSxIThu4Xr1lidxVzc51rBqodeYmbteFcgDk8y',
    'Test User'
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
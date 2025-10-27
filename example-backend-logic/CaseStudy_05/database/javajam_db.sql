-- JavaJam Coffee House Database Setup
-- Run this SQL in phpMyAdmin to create the database and tables
CREATE DATABASE IF NOT EXISTS javajam;

USE javajam;

-- Products table to store coffee blend information
CREATE TABLE IF NOT EXISTS products (
  product_id INT AUTO_INCREMENT PRIMARY KEY,
  product_name VARCHAR(100) NOT NULL,
  description TEXT,
  price_single DECIMAL(10, 2) DEFAULT NULL,
  price_double DECIMAL(10, 2) DEFAULT NULL,
  has_options BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sales table to track daily sales
CREATE TABLE IF NOT EXISTS sales (
  sale_id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  product_name VARCHAR(100) NOT NULL,
  option_type VARCHAR(20),
  quantity INT NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL,
  total_amount DECIMAL(10, 2) NOT NULL,
  sale_date DATE NOT NULL,
  sale_time TIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products (product_id) ON DELETE CASCADE
);

-- Insert initial product data
INSERT INTO
  products (
    product_name,
    description,
    price_single,
    price_double,
    has_options
  )
VALUES
  (
    'Just Java',
    'Regular house blend, decaffeinated coffee, or flavor of the day.',
    2.00,
    NULL,
    FALSE
  ),
  (
    'Cafe au Lait',
    'House blended coffee infused into a smooth, steamed milk.',
    2.00,
    3.00,
    TRUE
  ),
  (
    'Iced Cappuccino',
    'Sweetened espresso blended with icy-cold milk and served in a chilled glass.',
    4.75,
    5.75,
    TRUE
  );
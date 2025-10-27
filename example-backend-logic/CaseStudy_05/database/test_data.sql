-- Sample test data to match the example from the slides
-- Run this after setting up the database to test the sales report

USE javajam;

-- Clear any existing sales data (optional)
-- DELETE FROM sales;

-- Insert sample sales matching the slide example
-- Current date and time
SET @today = CURDATE();
SET @time1 = '09:00:00';
SET @time2 = '10:30:00';
SET @time3 = '14:00:00';

-- Just Java: 25 cups at $2.00 each = $50.00
INSERT INTO sales (product_id, product_name, option_type, quantity, unit_price, total_amount, sale_date, sale_time) 
VALUES (1, 'Just Java', NULL, 25, 2.00, 50.00, @today, @time1);

-- Cafe au Lait: 20 Single at $2.00 + 10 Double at $3.00 = $70.00
INSERT INTO sales (product_id, product_name, option_type, quantity, unit_price, total_amount, sale_date, sale_time) 
VALUES 
(2, 'Cafe au Lait', 'single', 20, 2.00, 40.00, @today, @time2),
(2, 'Cafe au Lait', 'double', 10, 3.00, 30.00, @today, @time2);

-- Iced Cappuccino: 20 Single at $4.75 + 10 Double at $5.75 = $152.50
INSERT INTO sales (product_id, product_name, option_type, quantity, unit_price, total_amount, sale_date, sale_time) 
VALUES 
(3, 'Iced Cappuccino', 'single', 20, 4.75, 95.00, @today, @time3),
(3, 'Iced Cappuccino', 'double', 10, 5.75, 57.50, @today, @time3);

-- Expected Results for Sales Report:
-- 
-- Sales by Product:
-- Just Java: $50.00, 25 units
-- Cafe au Lait: $70.00, 30 units
-- Iced Cappuccino: $152.50, 30 units
--
-- Sales by Category:
-- Endless: $50.00, 25 units (Just Java - endless cup)
-- Single: $135.00, 40 units (20 Cafe + 20 Capp)
-- Double: $87.50, 20 units (10 Cafe + 10 Capp)
--
-- Best Selling Product:
-- Iced Cappuccino ($152.50 - highest revenue)
-- Popular Option: Single (20 units vs 10 double)

SELECT 'Test data inserted successfully!' as message;

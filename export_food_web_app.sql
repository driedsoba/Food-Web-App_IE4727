-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 03:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_web_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `session_id`, `menu_item_id`, `quantity`, `created_at`) VALUES
(10, 4, 'rke0metjec70hqhdtu74j97783', 4, 1, '2025-10-30 17:44:57'),
(39, 8, '6bl0o86bitpu6e94dbua3d32at', 2, 1, '2025-11-09 17:17:49'),
(56, 1, NULL, 5, 1, '2025-11-14 12:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `order_number` varchar(50) DEFAULT NULL,
  `feedback` text NOT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `name`, `email`, `rating`, `order_number`, `feedback`, `approved`, `created_at`) VALUES
(1, 1, 'Test User', 'test@example.com', 5, 'ORD001', 'Amazing food! The Wiener Schnitzel was perfectly cooked and delicious.', 1, '2025-10-30 06:23:56'),
(2, NULL, 'Jane Smith', 'jane@example.com', 5, 'ORD002', 'Best German food in town! Will definitely order again.', 1, '2025-10-30 06:23:56'),
(3, NULL, 'Mike Johnson', 'mike@example.com', 4, 'ORD003', 'Great flavors and generous portions. Delivery was quick too!', 1, '2025-10-30 06:23:56'),
(8, 10, 'iloveie4727', 'iloveie4727@gmail.com', 5, 'ORD039', 'I love german food and ie4727 web app.', 1, '2025-11-14 14:34:51');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('Mains','Starters','Sides','Desserts') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `time` varchar(50) NOT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `category`, `price`, `time`, `rating`, `description`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Wiener Schnitzel', 'Mains', 16.99, '25-35 min', 4.90, 'Classic breaded veal cutlet, served with potato salad and lemon', 'https://images.unsplash.com/photo-1599921841143-819065a55cc6?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(2, 'Bratwurst Platter', 'Mains', 14.99, '20-30 min', 4.70, 'Grilled German sausages with sauerkraut, mustard and green peas', 'https://images.unsplash.com/photo-1658925111653-2c08083c08ff?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(3, 'Sauerbraten', 'Mains', 17.49, '30-40 min', 4.70, 'Tender pot roast marinated in vinegar with red cabbage', 'https://images.unsplash.com/photo-1622003184404-bc0c66144534?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(4, 'Black Forest Cake', 'Desserts', 8.99, '20-25 min', 5.00, 'Chocolate sponge cake with cherries and whipped cream', 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(5, 'Apple Strudel', 'Desserts', 7.99, '18-22 min', 4.80, 'Flaky pastry filled with spiced apples and raisins', 'https://images.unsplash.com/photo-1657313938000-23c4322dbe22?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(6, 'Rinderbraten', 'Mains', 9.49, '15-20 min', 4.60, 'Tender beef roast with mushroom sauce', 'https://images.unsplash.com/photo-1746214093457-2305c2f8605e?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(7, 'Krautsalat', 'Starters', 6.99, '10-15 min', 4.50, 'Traditional German coleslaw with caraway seeds', 'https://images.unsplash.com/photo-1537784969314-05a37106f68e?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(8, 'German Potato Salad', 'Sides', 8.49, '15-20 min', 4.50, 'Warm potato salad with bacon, onions, and vinegar dressing', 'https://images.unsplash.com/photo-1623501742030-65c324e08846?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(9, 'Bratkartoffeln', 'Sides', 7.49, '15-20 min', 4.60, 'Pan-fried potatoes with onions and herbs', 'https://images.unsplash.com/photo-1761315414257-e402bedaa43e?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(10, 'Currywurst', 'Mains', 12.99, '15-20 min', 4.60, 'Sliced bratwurst with curry ketchup and fries', 'https://images.unsplash.com/photo-1561701034-24ceb3e34433?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(11, 'Rouladen', 'Mains', 16.49, '35-45 min', 4.80, 'Beef rolls stuffed with bacon, onions, and pickles', 'https://images.unsplash.com/photo-1432139555190-58524dae6a55?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56'),
(12, 'Königsberger Klopse', 'Starters', 15.99, '25-30 min', 4.70, 'Traditional meatballs in creamy caper sauce', 'https://images.unsplash.com/photo-1598511726903-ef08ef6eff94?auto=format&fit=crop&w=800&q=80', 1, '2025-10-30 06:23:56', '2025-10-30 06:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'order placed',
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `created_at`, `updated_at`) VALUES
(1, 1, 33.47, 'order delivered', 'Test User', 'test@example.com', '+6528788327', 'Blk 123 Tampines', '2025-10-30 09:42:54', '2025-10-30 09:42:58'),
(2, 1, 16.98, 'order delivered', 'testuser', 'test@example.com', '+6528788327', 'Blk 123 Tampines', '2025-10-30 11:12:59', '2025-10-30 11:13:02'),
(3, 1, 7.99, 'order delivered', 'testuser', 'test@example.com', '+6528788327', 'Blk 123 Tampines', '2025-10-30 11:16:39', '2025-10-30 11:17:17'),
(7, NULL, 21.98, 'order placed', 'testingjunle', 'junleliw@hhattesting.com', 'dtrdtrd', 'trdhtrd', '2025-11-01 08:56:34', '2025-11-01 08:56:34'),
(8, 1, 16.98, 'order placed', 'Test User', 'test@example.com', '879879', 'hiuhuih', '2025-11-01 13:02:25', '2025-11-01 13:02:25'),
(9, 1, 7.99, 'order delivered', 'testuser', 'test@example.com', 'iojkiojioj', 'iojoijoij', '2025-11-01 15:00:25', '2025-11-01 15:00:27'),
(10, 1, 8.99, 'order placed', 'Test User', 'test@example.com', 'weaewaewawe', 'eses', '2025-11-01 15:17:18', '2025-11-01 15:17:18'),
(11, 1, 8.99, 'order placed', 'Test User', 'test@example.com', 'AS', 'esers', '2025-11-01 15:17:32', '2025-11-01 15:17:32'),
(12, 1, 7.99, 'order placed', 'Test User', 'test@example.com', '876876', 'klmlk', '2025-11-01 15:20:05', '2025-11-01 15:20:05'),
(13, 1, 21.98, 'order placed', 'testuser', 'test@example.com', 'ewaew', 'ewaew', '2025-11-01 15:52:38', '2025-11-01 15:52:38'),
(14, 1, 12.99, 'order placed', 'testuser', 'test@example.com', 'iuhui', 'huih', '2025-11-01 15:53:11', '2025-11-01 15:53:11'),
(15, 1, 8.99, 'order delivered', 'testuser', 'test@example.com', '76876876', 'weaew87687', '2025-11-01 15:53:56', '2025-11-01 15:54:05'),
(16, 1, 7.99, 'preparing order', 'testuser', 'test@example.com', '34234234', 'aewaewaewa', '2025-11-01 15:56:03', '2025-11-01 15:56:07'),
(17, 1, 30.47, 'order placed', 'Test User', 'test@example.com', '87987987', 'hhuihuihuihiu', '2025-11-01 16:30:15', '2025-11-01 16:30:15'),
(18, 1, 8.99, 'order placed', 'testuser', 'test@example.com', '67867868', 'gftftrfttrtrd', '2025-11-01 16:30:51', '2025-11-01 16:30:51'),
(19, 1, 7.99, 'order placed', 'Test User', 'test@example.com', '78868768', 'yuguyugyugyug', '2025-11-01 16:38:21', '2025-11-01 16:38:21'),
(20, 1, 7.99, 'order delivered', 'testuser', 'test@example.com', '65764456', '455trtretre', '2025-11-01 16:45:58', '2025-11-01 16:46:09'),
(21, 1, 8.99, 'order delivered', 'testuser', 'test@example.com', '56757657', 'dtrdtrdhfd', '2025-11-01 16:46:27', '2025-11-01 16:46:40'),
(22, 1, 8.99, 'order placed', 'testuser', 'test@example.com', '66565456', 'fdrdsersrsers', '2025-11-01 16:46:49', '2025-11-01 16:46:49'),
(23, 1, 7.99, 'order delivered', 'testuser', 'test@example.com', '89789789', '987897987987', '2025-11-09 13:39:07', '2025-11-09 13:39:15'),
(24, 1, 12.99, 'order delivered', 'testuser', 'test@example.com', '89789798', 'huhiuhiuuhuh', '2025-11-09 13:39:46', '2025-11-09 13:39:50'),
(25, 1, 7.99, 'preparing order', 'testuser', 'test@example.com', '87987897', '98987897897', '2025-11-09 13:40:07', '2025-11-09 13:40:11'),
(26, 1, 39.97, 'order placed', 'testuser', 'test@example.com', '88469676', '56r65r65rhyuh', '2025-11-09 14:44:47', '2025-11-09 14:44:47'),
(27, 1, 13.79, 'order placed', 'testuser', 'test@example.com', '65456456465465464654', 'uihuihuihiuh', '2025-11-09 18:06:39', '2025-11-09 18:06:39'),
(28, 1, 22.58, 'order placed', 'testuser', 'test@example.com', '65456456465465464654', 'jiojijoijojoij', '2025-11-09 18:08:11', '2025-11-09 18:08:11'),
(29, 1, 41.26, 'preparing order', 'testuser', 'test@example.com', '65456456465465464654', 'htfytfytfy', '2025-11-09 18:12:52', '2025-11-09 18:50:10'),
(30, 1, 49.44, 'order placed', 'testuser', 'test@example.com', '76786876', 'ftyftyftyfty', '2025-11-09 18:50:05', '2025-11-09 18:50:05'),
(31, 1, 7.99, 'order placed', 'testuser', 'test@example.com', '78886876', '687678687y87', '2025-11-09 18:51:18', '2025-11-09 18:51:18'),
(32, 1, 14.89, 'preparing', 'testuser', 'test@example.com', '54354354', 'erwerwerwrewr', '2025-11-09 18:54:41', '2025-11-09 18:54:45'),
(33, 1, 14.89, 'order placed', 'testuser', 'test@example.com', '32432432', '43243243242', '2025-11-09 19:45:59', '2025-11-09 19:45:59'),
(34, 1, 14.89, 'order placed', 'testuser', 'test@example.com', '87897897', 'huhuhyugyguy87y87', '2025-11-10 01:01:10', '2025-11-10 01:01:10'),
(35, 1, 14.89, 'order placed', 'testuser', 'test@example.com', '86876876', 'gyugyugyuguyg', '2025-11-10 01:11:28', '2025-11-10 01:11:28'),
(36, 1, 15.44, 'order placed', 'testuser', 'test@example.com', '76589789', 'tuyghyuuyhuy', '2025-11-10 01:18:00', '2025-11-10 01:18:00'),
(37, 1, 13.24, 'order placed', 'testuser', 'test@example.com', '98098098', 'jiokoikiokkiokk', '2025-11-10 01:19:43', '2025-11-10 01:19:43'),
(38, 1, 33.02, 'preparing', 'testuser', 'test@example.com', '87987987', 'testing1234566', '2025-11-10 01:45:46', '2025-11-10 01:46:02'),
(39, 10, 78.10, 'order placed', 'iloveie4727', 'iloveie4727@gmail.com', '12345678', 'Traunsteiner Strasse 9', '2025-11-14 14:34:11', '2025-11-14 14:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_item_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 16.99),
(2, 1, 4, 1, 8.99),
(3, 1, 9, 1, 7.49),
(4, 2, 5, 1, 7.99),
(5, 2, 4, 1, 8.99),
(6, 3, 5, 1, 7.99),
(11, 7, 4, 1, 8.99),
(12, 7, 10, 1, 12.99),
(13, 8, 5, 1, 7.99),
(14, 8, 4, 1, 8.99),
(15, 9, 5, 1, 7.99),
(16, 10, 4, 1, 8.99),
(17, 11, 4, 1, 8.99),
(18, 12, 5, 1, 7.99),
(19, 13, 5, 1, 7.99),
(20, 13, 4, 1, 8.99),
(21, 14, 5, 1, 7.99),
(22, 15, 4, 1, 8.99),
(23, 16, 5, 1, 7.99),
(24, 17, 10, 1, 12.99),
(25, 17, 4, 1, 8.99),
(26, 17, 8, 1, 8.49),
(27, 18, 4, 1, 8.99),
(28, 19, 5, 1, 7.99),
(29, 20, 5, 1, 7.99),
(30, 21, 4, 1, 8.99),
(31, 22, 4, 1, 8.99),
(32, 23, 5, 1, 7.99),
(33, 24, 10, 1, 12.99),
(34, 25, 5, 1, 7.99),
(35, 26, 10, 2, 12.99),
(36, 26, 4, 1, 8.99),
(37, 27, 5, 1, 7.99),
(38, 28, 5, 2, 7.99),
(39, 29, 4, 1, 8.99),
(40, 29, 5, 3, 7.99),
(41, 30, 4, 3, 8.99),
(42, 30, 9, 3, 7.49),
(43, 31, 5, 1, 7.99),
(44, 32, 4, 1, 8.99),
(45, 33, 4, 1, 8.99),
(46, 34, 4, 1, 8.99),
(47, 35, 4, 1, 8.99),
(48, 36, 6, 1, 9.49),
(49, 37, 9, 1, 7.49),
(50, 38, 4, 2, 8.99),
(51, 38, 9, 1, 7.49),
(52, 39, 5, 1, 7.99),
(53, 39, 3, 1, 17.49),
(54, 39, 2, 1, 14.99),
(55, 39, 4, 1, 8.99),
(56, 39, 1, 1, 16.99);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `created_at`, `updated_at`) VALUES
(1, 'testuser', 'test@example.com', '$2y$10$jUIX.u4ncPl1vzcVSxIThu4Xr1lidxVzc51rBqodeYmbteFcgDk8y', 'Test User', '2025-10-30 06:23:56', '2025-10-30 07:33:20'),
(2, 'Jun Le', 'junleliw.work@gmail.com', '$2y$10$jUIX.u4ncPl1vzcVSxIThu4Xr1lidxVzc51rBqodeYmbteFcgDk8y', NULL, '2025-10-30 07:32:59', '2025-10-30 07:32:59'),
(4, 'testing', 'testing@gmail.com', '$2y$10$MuDN7G9xIc6SISRt9jF7w.NzJWrGzoVjhUz/5vZDv03K3tGGH72jq', NULL, '2025-10-30 15:16:24', '2025-10-30 15:16:24'),
(8, 'james', 'james@gmail.com', '$2y$10$Xcpk.1pQJ1GKe2dC4qhNy.MqppwVuYkYc/WDpWmVQt9C17/Fjg2WK', NULL, '2025-11-09 17:17:14', '2025-11-09 17:17:14'),
(9, 'james2', 'james2@gmail.com', '$2y$10$/T0hFqmcuJugPU6nHDVZd.Cm0eIn8r/tjp/vjWN46Prfu9gzuqPqm', NULL, '2025-11-09 18:45:28', '2025-11-09 18:45:28'),
(10, 'iloveie4727', 'iloveie4727@gmail.com', '$2y$10$w7OhojevJKkLKTeYZlXWJOq5RDbW.y9NDfmAQWRIeNv3e8Ete.lAq', NULL, '2025-11-14 14:32:55', '2025-11-14 14:32:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_item_id` (`menu_item_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_session_id` (`session_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_approved` (`approved`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 25, 2025 at 07:13 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yuvraj`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int NOT NULL,
  `gym_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `gym_name`, `email`, `phone`, `address`, `open_time`, `close_time`, `password`) VALUES
(1, 'Gym and Fitnees', 'krishhalai@gmail.com', '9712102682', 'rajkot gujrat', '09:00:00', '22:00:00', '$2y$10$jwEs5/HLyQVct1xGSEQ7NOizDerqpKV9mN/sGRrCKp4m2dkFqPBTO');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(36, 1, 8, 2, '2025-04-25 19:10:54');

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `inquiry_type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_inquiries`
--

INSERT INTO `contact_inquiries` (`id`, `first_name`, `last_name`, `email`, `phone`, `inquiry_type`, `message`, `created_at`) VALUES
(6, 'halai', 'krish', 'krishhalai83@gmail.com', '9712102682', 'classes', 'wesdrcfgtvnhjkm,l;', '2025-04-06 20:47:32'),
(7, 'halai', 'krish', 'krishhalai83@gmail.com', '9712102682', 'membership', 'its not good', '2025-04-07 02:46:14'),
(8, 'krutam', 'desai', 'khalai937@rku.ac.in', '1254789635', 'membership', 'dgcbsfyul', '2025-04-09 02:30:30');

-- --------------------------------------------------------

--
-- Table structure for table 
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_general_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_cart_value` decimal(10,2) DEFAULT '0.00',
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `valid_from` date NOT NULL,
  `valid_until` date NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `user_usage_limit` int DEFAULT '1',
  `times_used` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `description`, `discount_type`, `discount_value`, `min_cart_value`, `max_discount_amount`, `valid_from`, `valid_until`, `usage_limit`, `user_usage_limit`, `times_used`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME15', '15% off for new customers', 'percentage', 15.00, 1000.00, 200.00, '2025-04-26', '2025-05-26', 100, 1, 0, 1, '2025-04-25 18:40:41', '2025-04-25 18:40:41'),
(2, 'YUVRAJ', '₹100 off on any order', 'fixed', 100.00, 0.00, NULL, '2025-04-26', '2025-06-25', NULL, 1, 2, 1, '2025-04-25 18:40:41', '2025-04-25 18:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `coupon_id` int NOT NULL,
  `coupon_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'hello',
  `discount_amount` decimal(10,2) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `coupon_usages`
--

INSERT INTO `coupon_usages` (`id`, `order_id`, `coupon_id`, `coupon_code`, `discount_amount`, `discount_type`, `applied_at`) VALUES
(1, 25, 2, 'hello', 100.00, 'percentage', '2025-04-25 18:49:46'),
(2, 26, 2, 'hello', 100.00, 'percentage', '2025-04-25 18:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('Available','In Use','Under Maintenance','Out of Order') DEFAULT 'Available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `name`, `category`, `price`, `status`, `image`) VALUES
(3, 'doumbale', 'Strength', 700.00, 'Available', '../image/products/equipment4.jpg'),
(4, 'primium', 'Strength', 4586212.00, 'Available', '../image/products/equipment5.jpg'),
(6, 'sfsfdf', 'Cardio', 12299.00, 'Available', '../image/products/pexels-nicole-avagliano-1132392-2749481.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

CREATE TABLE `membership_plans` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `plan_type` varchar(50) NOT NULL,
  `features` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`id`, `name`, `duration`, `price`, `status`, `plan_type`, `features`, `description`, `created_at`) VALUES
(5, 'silver', '3 Months', 2000.00, 'Active', 'basic', 'Personal Trainer', 'wesdrtfgyhjklfghbjn', '2025-04-07 02:53:23'),
(6, 'gold', '3 Months', 4500.00, 'Active', 'premium', 'Group Classes', 'dtrdgyuijok', '2025-04-07 17:47:26'),
(7, 'vip', '3 mounth', 6000.00, 'active', 'vip', 'personal trainer', 'rdrrdghni8uer', '2025-04-09 02:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_amount`, `discount_amount`, `status`, `coupon_code`) VALUES
(1, 1, '2025-04-25 22:30:54', 0.00, 0.00, 'Delivered', ''),
(4, 1, '2025-04-25 22:40:58', 0.00, 0.00, 'Pending', ''),
(5, 1, '2025-04-25 22:49:21', 0.00, 0.00, 'Pending', ''),
(6, 1, '2025-04-25 22:52:03', 0.00, 0.00, 'Pending', ''),
(7, 1, '2025-04-25 22:57:51', 120.00, NULL, 'Pending', NULL),
(8, 1, '2025-04-25 22:59:53', 9120.00, NULL, 'Pending', NULL),
(9, 1, '2025-04-25 23:00:56', 120.00, NULL, 'Pending', NULL),
(10, 1, '2025-04-25 23:03:39', 120.00, NULL, 'Pending', NULL),
(11, 1, '2025-04-25 23:04:27', 120.00, NULL, 'Pending', NULL),
(13, 1, '2025-04-25 23:12:24', 4620.00, NULL, 'Pending', NULL),
(25, 1, '2025-04-26 00:19:46', 4500.00, 100.00, 'Pending', 'FLAT100'),
(26, 1, '2025-04-26 00:23:22', 4620.00, 100.00, 'Pending', 'YUVRAJ');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 8, 1, 120.00),
(4, 4, 8, 1, 120.00),
(5, 5, 8, 1, 120.00),
(6, 6, 8, 1, 120.00),
(7, 7, 8, 1, 120.00),
(8, 8, 8, 1, 120.00),
(9, 8, 7, 2, 4500.00),
(10, 9, 8, 1, 120.00),
(11, 10, 8, 1, 120.00),
(12, 11, 8, 1, 120.00),
(14, 13, 7, 1, 4500.00),
(15, 13, 8, 1, 120.00),
(18, 25, 7, 1, 4500.00),
(19, 26, 7, 1, 4500.00),
(20, 26, 8, 1, 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `member_name` varchar(255) NOT NULL,
  `membership_plan` varchar(50) NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Completed','Pending') DEFAULT 'Completed',
  `payment_method` varchar(50) NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `member_name`, `membership_plan`, `payment_date`, `amount`, `status`, `payment_method`, `notes`, `created_at`) VALUES
(13, 'krish', 'premium', '2025-04-06', 4000.00, 'Completed', 'Credit Card', 'sdsdff', '2025-04-06 08:30:17'),
(14, 'krish', 'premium', '2025-04-06', 4000.00, 'Completed', 'Credit Card', 'sdsdff', '2025-04-06 08:30:40'),
(18, 'krish', 'basic', '2025-04-03', 12000.00, 'Completed', 'Bank Transfer', 'dsffsdf', '2025-04-06 08:33:30'),
(19, 'cgvhbjnk', 'basic', '2025-03-31', 56.00, 'Completed', 'Bank Transfer', 'esrdctfgvj', '2025-04-07 17:48:15');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('in-stock','out-of-stock') DEFAULT 'in-stock',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `brand`, `category`, `price`, `stock`, `description`, `image`, `status`, `created_at`) VALUES
(7, 'protien', 'power', 'protien', 4500.00, 0, 'ghjk', 'protein5.jpg', 'in-stock', '2025-04-06 18:48:57'),
(8, 'protien house', 'power', 'Mass Gainer', 120.00, -8, 'ewrtfhyuijo', 'protein6.jpg', 'in-stock', '2025-04-07 02:48:20');

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `id` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `experience` int NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('active','on-leave','terminated') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`id`, `first_name`, `last_name`, `email`, `specialization`, `experience`, `photo`, `status`, `created_at`) VALUES
(5, 'krish', 'halai', 'khalai937@rku.ac.in', 'Cardio', 5, 't22.jpg', 'active', '2025-04-08 02:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `verification_token` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `membership_plan` enum('no plan','Basic','Premium','VIP') NOT NULL DEFAULT 'no plan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `gender`, `password`, `created_at`, `status`, `verification_token`, `otp`, `otp_expiry`, `membership_plan`) VALUES
(123, 'prince viradiya', 'krishhalai83@gmail.com', 'female', '$2y$10$X6m4QTRdXFLx9ec2NU6/ZOMrByeVvruDz10O4GA70Q0zIaGRhDIiu', '2025-04-08 10:48:17', 'active', '', NULL, NULL, 'VIP'),
(124, 'janki', 'janki.kansagra@rku.ac.in', 'female', '$2y$10$Tc0raoBiWZqcDKzmrolacO39GRCfGeqrfcBmhTojVoMVj44C7D6XC', '2025-04-09 10:41:32', 'inactive', '37c50f57ea4c76973b44fc92adcffb30', NULL, NULL, 'no plan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_plans`
--
ALTER TABLE `membership_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD CONSTRAINT `coupon_usages_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `coupon_usages_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

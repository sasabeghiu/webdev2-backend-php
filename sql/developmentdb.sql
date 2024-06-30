-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jun 30, 2024 at 12:38 PM
-- Server version: 11.3.2-MariaDB-1:11.3.2+maria~ubu2204
-- PHP Version: 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`id`, `cart_id`, `product_id`, `quantity`) VALUES
(302, 144, 19, 1),
(305, 146, 22, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(12, 'Locks'),
(13, 'Cameras'),
(14, 'Safes');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `total`, `status`, `created_at`, `updated_at`) VALUES
(122, 43, 710.00, 'ordered', '2024-06-30 12:31:55', '2024-06-30 12:31:55'),
(123, 44, 1140.00, 'ordered', '2024-06-30 12:34:53', '2024-06-30 12:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(215, 122, 17, 3, 90.00),
(216, 122, 18, 2, 220.00),
(217, 123, 23, 3, 240.00),
(218, 123, 20, 1, 420.00);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity_available` int(11) NOT NULL,
  `description` varchar(8000) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `quantity_available`, `description`, `image`, `category_id`) VALUES
(17, 'Wireless Audio-Recording Night WebCam', 90.00, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed bibendum enim, vitae porttitor lectus. Nulla vitae nunc feugiat, commodo lectus sit amet, venenatis lectus. Nam sit amet congue metus. Nullam ut blandit mi. Integer lobortis mi vitae pretium pellentesque. Praesent sed laoreet leo. Mauris eget sapien at massa placerat tincidunt vitae nec arcu. In iaculis, orci vitae pretium feugiat, orci lacus pretium augue, eu sollicitudin urna eros id tellus. Morbi orci dolor, tincidunt a dolor sit amet, venenatis porttitor velit. In sit amet blandit nisl. Etiam metus lorem, viverra sed eros a, facilisis vestibulum nulla.', 'https://i.imgur.com/iImPw02.jpeg', 13),
(18, 'Smart Security Camera', 220.00, 52, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed bibendum enim, vitae porttitor lectus. Nulla vitae nunc feugiat, commodo lectus sit amet, venenatis lectus. Nam sit amet congue metus. Nullam ut blandit mi. Integer lobortis mi vitae pretium pellentesque. Praesent sed laoreet leo. Mauris eget sapien at massa placerat tincidunt vitae nec arcu. In iaculis, orci vitae pretium feugiat, orci lacus pretium augue, eu sollicitudin urna eros id tellus. Morbi orci dolor, tincidunt a dolor sit amet, venenatis porttitor velit. In sit amet blandit nisl. Etiam metus lorem, viverra sed eros a, facilisis vestibulum nulla.', 'https://i.imgur.com/5kDd9WP.jpeg', 13),
(19, 'Keyless Smart Door lock', 150.00, 59, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed bibendum enim, vitae porttitor lectus. Nulla vitae nunc feugiat, commodo lectus sit amet, venenatis lectus. Nam sit amet congue metus. Nullam ut blandit mi. Integer lobortis mi vitae pretium pellentesque. Praesent sed laoreet leo. Mauris eget sapien at massa placerat tincidunt vitae nec arcu. In iaculis, orci vitae pretium feugiat, orci lacus pretium augue, eu sollicitudin urna eros id tellus. Morbi orci dolor, tincidunt a dolor sit amet, venenatis porttitor velit. In sit amet blandit nisl. Etiam metus lorem, viverra sed eros a, facilisis vestibulum nulla.', 'https://hotlock.axiomthemes.com/wp-content/uploads/2017/01/product-5.jpg', 12),
(20, 'FreeTech Security Camera', 420.00, 82, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed bibendum enim, vitae porttitor lectus. Nulla vitae nunc feugiat, commodo lectus sit amet, venenatis lectus. Nam sit amet congue metus. Nullam ut blandit mi. Integer lobortis mi vitae pretium pellentesque. Praesent sed laoreet leo. Mauris eget sapien at massa placerat tincidunt vitae nec arcu. In iaculis, orci vitae pretium feugiat, orci lacus pretium augue, eu sollicitudin urna eros id tellus. Morbi orci dolor, tincidunt a dolor sit amet, venenatis porttitor velit. In sit amet blandit nisl. Etiam metus lorem, viverra sed eros a, facilisis vestibulum nulla.', 'https://i.imgur.com/53CPMpN.jpeg', 13),
(21, 'Digital Safety Door Lock', 80.00, 99, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed bibendum enim, vitae porttitor lectus. Nulla vitae nunc feugiat, commodo lectus sit amet, venenatis lectus. Nam sit amet congue metus. Nullam ut blandit mi. Integer lobortis mi vitae pretium pellentesque. Praesent sed laoreet leo. Mauris eget sapien at massa placerat tincidunt vitae nec arcu. In iaculis, orci vitae pretium feugiat, orci lacus pretium augue, eu sollicitudin urna eros id tellus. Morbi orci dolor, tincidunt a dolor sit amet, venenatis porttitor velit. In sit amet blandit nisl. Etiam metus lorem, viverra sed eros a, facilisis vestibulum nulla.', 'https://hotlock.axiomthemes.com/wp-content/uploads/2017/01/product-2.jpg', 12),
(22, 'Aluminum Housing Home Security Camera', 100.00, 85, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed bibendum enim, vitae porttitor lectus. Nulla vitae nunc feugiat, commodo lectus sit amet, venenatis lectus. Nam sit amet congue metus. Nullam ut blandit mi. Integer lobortis mi vitae pretium pellentesque. Praesent sed laoreet leo. Mauris eget sapien at massa placerat tincidunt vitae nec arcu. In iaculis, orci vitae pretium feugiat, orci lacus pretium augue, eu sollicitudin urna eros id tellus. Morbi orci dolor, tincidunt a dolor sit amet, venenatis porttitor velit. In sit amet blandit nisl. Etiam metus lorem, viverra sed eros a, facilisis vestibulum nulla.', 'https://i.imgur.com/ftVLccD.jpeg', 13),
(23, 'Invasion Proof Digital Safe', 240.00, 89, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed bibendum enim, vitae porttitor lectus. Nulla vitae nunc feugiat, commodo lectus sit amet, venenatis lectus. Nam sit amet congue metus. Nullam ut blandit mi. Integer lobortis mi vitae pretium pellentesque. Praesent sed laoreet leo. Mauris eget sapien at massa placerat tincidunt vitae nec arcu. In iaculis, orci vitae pretium feugiat, orci lacus pretium augue, eu sollicitudin urna eros id tellus. Morbi orci dolor, tincidunt a dolor sit amet, venenatis porttitor velit. In sit amet blandit nisl. Etiam metus lorem, viverra sed eros a, facilisis vestibulum nulla.', 'https://hotlock.axiomthemes.com/wp-content/uploads/2017/01/product-4.jpg', 14),
(24, 'Door Jaw Lock Security Device', 110.00, 93, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed bibendum enim, vitae porttitor lectus. Nulla vitae nunc feugiat, commodo lectus sit amet, venenatis lectus. Nam sit amet congue metus. Nullam ut blandit mi. Integer lobortis mi vitae pretium pellentesque. Praesent sed laoreet leo. Mauris eget sapien at massa placerat tincidunt vitae nec arcu. In iaculis, orci vitae pretium feugiat, orci lacus pretium augue, eu sollicitudin urna eros id tellus. Morbi orci dolor, tincidunt a dolor sit amet, venenatis porttitor velit. In sit amet blandit nisl. Etiam metus lorem, viverra sed eros a, facilisis vestibulum nulla.', 'https://hotlock.axiomthemes.com/wp-content/uploads/2017/01/product-6.jpg', 14);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Customer');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(8000) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `name`, `description`, `image`) VALUES
(1, 'Electronic Security', 'Electronic security services, provided by specialized security companies, play a crucial role in safeguarding assets, information, and people in today\'s digitally connected world. These services encompass a wide range of solutions, including surveillance systems, access control, intrusion detection, and cybersecurity measures. By leveraging advanced technologies such as AI, IoT, and cloud computing, these companies offer customized, scalable security setups that monitor and protect against unauthorized access, theft, and cyber attacks. With a focus on real-time threat detection and rapid response, electronic security services ensure a proactive defense mechanism for businesses and individuals alike, offering peace of mind in an increasingly complex and vulnerable digital landscape.', 'https://hotlock.axiomthemes.com/wp-content/uploads/2017/01/post-6-1170x658.jpg'),
(2, 'Residential Locksmith', 'Residential locksmith services cater to the security needs of homeowners, offering a wide array of solutions designed to ensure the safety and security of homes and personal property. These services range from traditional lock and key assistance, such as lock installation, repair, and rekeying, to more advanced offerings including smart lock installations and keyless entry systems. Residential locksmiths are also pivotal in emergency lockout situations, providing fast and reliable access to homes without compromising security. By staying abreast of the latest security technologies and practices, residential locksmiths enable homeowners to safeguard their families and assets against unauthorized access, offering tailored advice and solutions that enhance home security. Their expertise ensures that homeowners can enjoy peace of mind, knowing their residences are protected by high-quality, reliable locking mechanisms and security strategies.', 'https://hotlock.axiomthemes.com/wp-content/uploads/2017/01/post-15-1170x658.jpg'),
(3, 'Car Locksmith', 'Car locksmith services specialize in addressing the security and access needs of vehicle owners, offering a comprehensive range of solutions for various automotive lock-related issues. These services include emergency lockout assistance, where locksmiths provide rapid responses to help drivers regain access to their vehicles without damaging the lock or car. Beyond emergency services, car locksmiths offer key duplication, transponder key programming, ignition repair, and replacement services. They are also adept at dealing with broken key extractions and can rekey vehicle locks to enhance security following a theft or loss of keys. With expertise in the latest automotive lock technology, including keyless entry systems and advanced security features, car locksmiths ensure that vehicle owners have access to efficient, reliable services that keep their cars secure and accessible. Their specialized knowledge and tools enable them to address a wide array of automotive lock and key issues, offering peace of mind to drivers.', 'https://hotlock.axiomthemes.com/wp-content/uploads/2017/01/post-1-1170x658.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_info`
--

CREATE TABLE `shipping_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `phone_number` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_info`
--

INSERT INTO `shipping_info` (`id`, `user_id`, `full_name`, `address`, `city`, `postal_code`, `country`, `email`, `phone_number`) VALUES
(14, 43, 'John Dolphin', 'Bijdoorplan 15', 'Haarlem', '2015 CE', 'Netherlands', 'customer@innova.com', '0674915025'),
(15, 44, 'Hall Guy', 'Randomstraat 23', 'Amsterdam', '4142 AV', 'Netherlands', 'admin@innova.com', '0674012456');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_cart`
--

INSERT INTO `shopping_cart` (`id`, `user_id`, `created_at`, `updated_at`, `total_price`) VALUES
(144, 43, '2024-06-30 12:32:32', '2024-06-30 12:32:32', 150.00),
(146, 44, '2024-06-30 12:35:02', '2024-06-30 12:35:02', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `role_id`) VALUES
(43, 'customer', '$2y$10$jhiWKnMl3OOBeFmkyv8HrOoTJJ8CvihDziVS2T8GdlFV/6HNACGQO', 'customer@innova.com', 2),
(44, 'admin', '$2y$10$Nw8zdbm2JHYRySF0QpyfQu.8sxJMQDnDSgok/x44wLbtu4uZBSdz2', 'admin@innova.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_item_shopping_cart` (`cart_id`),
  ADD KEY `cart_item_product` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_user_id` (`user_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_order_id` (`order_id`),
  ADD KEY `order_item_product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_category` (`category_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_info`
--
ALTER TABLE `shipping_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `shipping_info`
--
ALTER TABLE `shipping_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `cart_item_shopping_cart` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `order_item_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `shipping_info`
--
ALTER TABLE `shipping_info`
  ADD CONSTRAINT `fk_shipping_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

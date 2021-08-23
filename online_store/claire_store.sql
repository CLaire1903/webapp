-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 21, 2021 at 01:30 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `claire_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `profile_pic` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `cus_username` varchar(15) CHARACTER SET utf8 NOT NULL,
  `password` varchar(15) CHARACTER SET utf8 NOT NULL,
  `confirmPassword` varchar(15) CHARACTER SET utf8 NOT NULL,
  `firstName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `lastName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `gender` enum('male','female') CHARACTER SET utf8 NOT NULL DEFAULT 'male',
  `dateOfBirth` date NOT NULL,
  `regdDateNTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `accountStatus` enum('active','inactive') CHARACTER SET utf8 NOT NULL DEFAULT 'active',
  UNIQUE KEY `username` (`cus_username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`profile_pic`, `cus_username`, `password`, `confirmPassword`, `firstName`, `lastName`, `gender`, `dateOfBirth`, `regdDateNTime`, `accountStatus`) VALUES
('image/customer_pic/defaultprofile.png', 'ahhuey19', 'Tang_1234567', 'Tang_1234567', 'Ah', 'Huey', 'female', '2001-03-19', '2021-08-21 13:28:40', 'active'),
('image/customer_pic/defaultprofile.png', 'ahyuan11', 'Yuan_759130', 'Yuan_759130', 'Tang', 'Jia Yuan', 'male', '2001-08-11', '2021-08-20 07:32:15', 'active'),
('image/customer_pic/Amanda11_1629444813.jpg', 'amanda11', 'Amanda_11', 'Amanda_11', 'Amanda', 'Charles', 'female', '1994-03-24', '2021-08-21 13:25:56', 'active'),
('image/customer_pic/Apple123_1629552203.jpg', 'apple123', 'Abc_123456', 'Abc_123456', 'Apple', 'Chew', 'female', '2000-02-14', '2021-08-21 13:26:14', 'active'),
('image/customer_pic/defaultprofile.png', 'ben1234567', 'Ben@1234567', 'Ben@1234567', 'Ben', 'Jackson', 'male', '2001-05-20', '2021-08-21 13:26:20', 'active'),
('image/customer_pic/defaultprofile.png', 'claire031999', 'Hanai@1903', 'Hanai@1903', 'Tang', 'Jia Huey', 'female', '1999-03-19', '2021-08-20 07:32:45', 'active'),
('image/customer_pic/defaultprofile.png', 'claire1111', 'Tang_1234567', 'Tang_1234567', 'Tang', 'JIa Huey', 'female', '2002-11-11', '2021-08-20 07:32:49', 'active'),
('image/customer_pic/defaultprofile.png', 'claireabc33', 'Huey_1234', 'Huey_1234', 'wrghy', 'egth5j', 'female', '1999-01-31', '2021-08-20 07:32:52', 'active'),
('image/customer_pic/defaultprofile.png', 'jiahao1410', 'Tang_759130', 'Tang_759130', 'Tang', 'Jia Hao', 'male', '2002-10-14', '2021-08-21 13:28:49', 'active'),
('image/customer_pic/defaultprofile.png', 'jiahong03', 'Tang759130@', 'Tang759130@', 'Tang', 'Jia Hong', 'male', '2003-05-04', '2021-08-21 13:29:16', 'active'),
('image/customer_pic/defaultprofile.png', 'joclyn0210', 'Joclyn@0210', 'Joclyn@0210', 'Joclyn', 'Lee', 'female', '1999-02-10', '2021-08-20 07:33:05', 'active'),
('image/customer_pic/defaultprofile.png', 'john11', 'Abc_1234567', 'Abc_1234567', 'Philip', 'John', 'male', '1991-05-22', '2021-08-21 13:29:07', 'active'),
('image/customer_pic/defaultprofile.png', 'kumchoon67', 'Tang_759130', 'Tang_759130', 'Kum', 'Choon', 'male', '1967-04-21', '2021-08-21 13:28:55', 'active'),
('image/customer_pic/defaultprofile.png', 'yewlan10', 'Tay_2243', 'Tay_2243', 'Tay', 'Yew Lan', 'female', '1975-10-05', '2021-08-21 13:29:02', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int(5) NOT NULL AUTO_INCREMENT,
  `orderDateNTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cus_username` varchar(15) CHARACTER SET utf8 NOT NULL,
  `total_amount` double NOT NULL,
  PRIMARY KEY (`orderID`),
  KEY `cus_username` (`cus_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `orderDateNTime`, `cus_username`, `total_amount`) VALUES
(1, '2021-08-21 13:27:50', 'ahyuan11', 209.09),
(2, '2021-08-06 14:44:01', 'ahyuan11', 495),
(3, '2021-08-06 14:52:59', 'claire031999', 215.98),
(4, '2021-08-21 13:28:30', 'apple123', 165),
(6, '2021-08-21 13:27:55', 'joclyn0210', 120),
(7, '2021-08-21 13:28:01', 'apple123', 145.1),
(8, '2021-08-21 13:28:09', 'claireabc33', 176.34),
(11, '2021-08-06 14:51:43', 'joclyn0210', 181.6),
(13, '2021-08-05 14:18:22', 'ahyuan11', 105.36),
(14, '2021-08-21 13:28:21', 'amanda11', 175.36),
(16, '2021-08-21 13:28:15', 'ahyuan11', 63),
(18, '2021-08-18 08:28:13', 'ahyuan11', 501.26);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE IF NOT EXISTS `order_detail` (
  `orderID` int(5) NOT NULL,
  `productID` int(5) NOT NULL,
  `quantity` int(5) NOT NULL,
  `product_TA` double NOT NULL,
  PRIMARY KEY (`orderID`,`productID`),
  KEY `productID` (`productID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`orderID`, `productID`, `quantity`, `product_TA`) VALUES
(1, 2, 2, 100.1),
(1, 8, 1, 8.99),
(1, 78, 5, 100),
(2, 7, 9, 270),
(2, 11, 15, 225),
(3, 1, 1, 60),
(3, 4, 3, 18),
(3, 8, 2, 17.98),
(4, 11, 11, 165),
(6, 4, 10, 60),
(7, 2, 2, 100.1),
(8, 10, 3, 26.34),
(11, 6, 16, 181.6),
(13, 10, 12, 105.36),
(14, 10, 12, 105.36),
(14, 83, 14, 70),
(16, 3, 1, 3),
(16, 4, 5, 30),
(16, 7, 1, 30),
(18, 1, 1, 60),
(18, 2, 6, 300.3),
(18, 3, 1, 3),
(18, 4, 2, 12),
(18, 7, 3, 90),
(18, 8, 4, 35.96);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `productID` int(5) NOT NULL AUTO_INCREMENT,
  `product_pic` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `name_malay` varchar(30) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `price` double NOT NULL,
  `promotion_price` double NOT NULL,
  `manufacture_date` date NOT NULL,
  `expired_date` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `product_pic`, `name`, `name_malay`, `description`, `price`, `promotion_price`, `manufacture_date`, `expired_date`, `created`, `modified`) VALUES
(1, 'image/product_pic/1.png', 'Basketball', 'Bola Keranjang', 'A ball used in the NBA.', 60, 35, '2019-03-06', '2023-06-02', '2015-08-02 12:04:03', '2021-08-10 10:42:49'),
(2, 'image/product_pic/default.jpg', 'Netball', 'Bola Jaring', 'A ball used in the NBA.', 50.05, 30, '2021-08-01', '2023-07-12', '2015-08-02 12:04:03', '2021-08-03 01:56:19'),
(3, 'image/product_pic/default.jpg', 'Gatorade', 'Gatorade', 'This is a very good drink for athletes.', 3, 1, '2021-08-01', '2021-08-18', '2015-08-02 12:14:29', '2021-08-03 01:56:44'),
(4, 'image/product_pic/default.jpg\n', 'Eye Glasses', 'cermin mata', 'It will make you read better.', 6, 1, '2021-08-02', '2025-08-02', '2015-08-02 12:15:04', '2021-08-03 01:56:52'),
(5, 'image/product_pic/ID5_1628670931.jpeg', 'Trash Can', 'Tong Sampah', 'It will help you maintain cleanliness.', 3.95, 2, '2021-08-01', '2024-07-02', '2015-08-02 12:16:08', '2021-08-11 08:35:30'),
(6, 'image/product_pic/6.jpg', 'Mouse', 'Tetikus', 'Very useful if you love your computer.', 11.35, 10, '2021-08-01', '2025-12-04', '2015-08-02 12:17:58', '2021-08-10 13:56:24'),
(7, 'image/product_pic/default.jpg', 'Earphone', 'fon telinga', 'You need this one if you love music.', 30, 15, '2021-08-01', '2026-11-02', '2015-08-02 12:18:21', '2021-08-03 01:57:11'),
(8, 'image/product_pic/default.jpg', 'Pillow', 'bantal', 'Sleeping well is important.', 8.99, 5, '2021-08-01', '2025-11-25', '2015-08-02 12:18:56', '2021-08-03 01:57:18'),
(10, 'image/product_pic/default.jpg', 'bottle', 'perkakas air', 'container to fill with water', 8.78, 5.5, '2020-08-01', '2023-12-31', '2021-06-01 02:59:26', '2021-08-06 13:09:42'),
(11, 'image/product_pic/ID11_1628672691.jpg', 'lotion', 'losyen', ' A product that can apply on the skin and keep the skin moisture. ', 15, 10, '2021-06-01', '2022-06-29', '2021-06-30 00:00:00', '2021-08-11 09:04:50'),
(75, 'image/product_pic/75.png', 'eye shadow', 'eye shadow', 'cosmetic', 123, 80, '2021-08-01', '2021-08-31', '2021-08-01 15:15:47', '2021-08-07 09:33:46'),
(78, 'image/product_pic/78.png', 'book', 'buku', 'store knowledge', 20, 15, '2021-08-01', '2021-08-31', '2021-08-02 13:03:47', '2021-08-05 13:26:59'),
(83, 'image/product_pic/ID83_1629551424.jpeg', 'Plaster', 'Plaster', 'Small medical dressing', 5, 3, '2021-08-01', '2024-07-05', '2021-08-05 14:12:19', '2021-08-21 13:10:24'),
(96, 'image/product_pic/default.jpg', 'wallpaper', 'kertas dinding', 'wall decoration', 100, 50, '2021-08-01', '2025-11-10', '2021-08-09 16:54:25', '2021-08-20 07:31:10'),
(97, 'image/product_pic/ID97_1629552238.jpg', 'Culculator', 'Calculator', 'Solve maths problem', 100, 50, '2020-12-27', '2021-08-25', '2021-08-10 14:08:06', '2021-08-21 13:23:58'),
(98, 'image/product_pic/ID98_1629551479.png', 'Vase', 'Pasu bunga', 'To put flower', 50, 25, '2021-08-01', '2025-12-31', '2021-08-10 14:10:38', '2021-08-21 13:11:19');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cus_username`) REFERENCES `customers` (`cus_username`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  ADD CONSTRAINT `order_detail_ibfk_3` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

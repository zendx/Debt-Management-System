-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2024 at 11:34 AM
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
-- Database: `dms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$77vD97rt.p9LvW/W4PECQ.NREU3o6pzQiCUEtFRXp4l32Qt1cRRnm');

-- --------------------------------------------------------

--
-- Table structure for table `credits`
--

CREATE TABLE `credits` (
  `id` int(11) NOT NULL,
  `debtor_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `credit_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL,
  `deducted_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `credits`
--

INSERT INTO `credits` (`id`, `debtor_id`, `amount`, `credit_date`, `remarks`, `deducted_date`) VALUES
(1, 2, 2000000.00, '2024-07-17 12:14:34', 'Excess payment credited', '2024-07-17 12:32:57'),
(2, 3, 99999999.99, '2024-07-17 12:24:01', 'Excess payment credited', '2024-07-17 12:32:57'),
(3, 3, 99999999.99, '2024-07-17 12:24:44', 'Excess payment credited', '2024-07-17 12:32:57'),
(4, 3, 1000000.00, '2024-07-17 12:27:17', 'Excess payment credited', '2024-07-17 12:32:57'),
(5, 3, -99999999.99, '2024-07-17 12:34:51', 'For Purchase', '2024-07-17 12:34:51'),
(6, 3, -99999999.99, '2024-07-17 12:35:19', 'For Purchase', '2024-07-17 12:35:19'),
(7, 3, -500000.00, '2024-07-17 12:36:02', 'For Purchase', '2024-07-17 12:36:02'),
(8, 4, 3000000.00, '2024-07-18 09:43:49', 'Excess payment credited', '2024-07-18 09:43:49'),
(9, 4, -2000000.00, '2024-07-18 09:45:23', '', '2024-07-18 09:45:23');

-- --------------------------------------------------------

--
-- Table structure for table `debtors`
--

CREATE TABLE `debtors` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `amount_owed` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `closed_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `debtors`
--

INSERT INTO `debtors` (`id`, `full_name`, `address`, `email`, `phone`, `amount_owed`, `created_at`, `closed_at`, `created_by`, `closed_by`) VALUES
(2, 'Henry Shedrach', 'wed', 'demo@example.com', '08031975415', 0.00, '2024-07-17 10:41:29', NULL, 1, NULL),
(3, 'Emeka ', 'Iyana Ipaja', 'demo@example.com', '08031975415', 0.00, '2024-07-17 11:24:56', NULL, 1, NULL),
(4, 'Ifeanyi', 'Iyana Ipaja', 'demo@example', '08031975415', 0.00, '2024-07-18 09:40:07', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `debtor_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `debtor_id`, `amount`, `payment_date`, `remarks`) VALUES
(1, 2, 500000.00, '2024-07-17 10:41:29', 'Initial amount owed'),
(2, 2, 20000.00, '2024-07-17 10:42:02', 'Good'),
(3, 2, 10000.00, '2024-07-17 10:47:54', 'good'),
(4, 2, 490000.00, '2024-07-17 10:48:33', 'Great'),
(5, 3, 5000000.00, '2024-07-17 11:24:56', 'Initial amount owed'),
(6, 3, 2000000.00, '2024-07-17 11:25:41', 'Good'),
(7, 3, 1000000.00, '2024-07-17 11:27:14', 'Good'),
(8, 3, 2000000.00, '2024-07-17 11:29:16', 'Great'),
(9, 2, 2000000.00, '2024-07-17 12:00:49', 'Credit'),
(10, 2, 1000000.00, '2024-07-17 12:02:42', 'Credits'),
(11, 2, 20000000.00, '2024-07-17 12:04:30', 'Credit'),
(12, 2, 12000000.00, '2024-07-17 12:09:01', ''),
(13, 2, 2000000.00, '2024-07-17 12:14:34', ''),
(14, 3, 99999999.99, '2024-07-17 12:24:01', ''),
(15, 3, 99999999.99, '2024-07-17 12:24:43', ''),
(16, 3, 1000000.00, '2024-07-17 12:27:17', ''),
(17, 4, 5000000.00, '2024-07-18 09:40:07', 'Initial amount owed'),
(18, 4, 2000000.00, '2024-07-18 09:41:53', 'Part Payment'),
(19, 4, 1000000.00, '2024-07-18 09:42:35', 'Part Payment'),
(20, 4, 5000000.00, '2024-07-18 09:43:49', 'Complete Payment');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credits`
--
ALTER TABLE `credits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `debtor_id` (`debtor_id`);

--
-- Indexes for table `debtors`
--
ALTER TABLE `debtors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `closed_by` (`closed_by`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `debtor_id` (`debtor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `credits`
--
ALTER TABLE `credits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `debtors`
--
ALTER TABLE `debtors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `credits`
--
ALTER TABLE `credits`
  ADD CONSTRAINT `credits_ibfk_1` FOREIGN KEY (`debtor_id`) REFERENCES `debtors` (`id`);

--
-- Constraints for table `debtors`
--
ALTER TABLE `debtors`
  ADD CONSTRAINT `debtors_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `debtors_ibfk_2` FOREIGN KEY (`closed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`debtor_id`) REFERENCES `debtors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

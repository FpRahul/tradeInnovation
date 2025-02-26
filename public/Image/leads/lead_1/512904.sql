-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 11, 2025 at 06:06 AM
-- Server version: 8.0.41-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tradeinnovation`
--

-- --------------------------------------------------------

--
-- Table structure for table `service_stages`
--

CREATE TABLE `service_stages` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` int DEFAULT '0',
  `stage_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_stage_id` int DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stage` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_stages`
--

INSERT INTO `service_stages` (`id`, `service_id`, `stage_id`, `title`, `sub_stage_id`, `description`, `stage`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Search trademark', NULL, 'Search for the trademark on the online portal', 0, NULL, NULL),
(2, 1, 2, 'Send quotation and payment verification', NULL, 'N/A', 0, NULL, NULL),
(3, 1, 3, 'Document verification and client approval', NULL, 'Search trademark on online portal to ensure the uniqueness', 0, NULL, NULL),
(4, 1, 4, 'Draft application on the portal', NULL, 'N/A', 0, NULL, NULL),
(5, 1, 5, 'Initial Examination process', NULL, 'N/A', 0, NULL, NULL),
(6, 2, 6, 'Payment confirmation', NULL, 'N/A', 0, NULL, NULL),
(7, 2, 7, 'Prior Art', NULL, 'N/A', 0, NULL, NULL),
(8, 2, 8, 'Document verification and client approval', NULL, 'N/A', 0, NULL, NULL),
(9, 2, 9, 'Drafting application', NULL, 'N/A', 0, NULL, NULL),
(10, 1, 10, 'Payment status', 2, 'check for payment', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `service_stages`
--
ALTER TABLE `service_stages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `service_stages`
--
ALTER TABLE `service_stages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

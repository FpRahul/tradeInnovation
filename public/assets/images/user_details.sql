-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 09, 2025 at 05:13 AM
-- Server version: 8.0.40-0ubuntu0.24.04.1
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
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` bigint UNSIGNED NOT NULL,
  `userId` int NOT NULL,
  `fatherHusbandName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keyResponsibilityArea` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keyPerformanceIndicator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergencyContactDetails` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currentAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanentAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploadPhotograph` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploadPan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploadAadhar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploadDrivingLicence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incorporationType` int NOT NULL DEFAULT '0',
  `registered` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referralPartner` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `userId`, `fatherHusbandName`, `qualification`, `skills`, `keyResponsibilityArea`, `keyPerformanceIndicator`, `emergencyContactDetails`, `currentAddress`, `permanentAddress`, `uploadPhotograph`, `uploadPan`, `uploadAadhar`, `uploadDrivingLicence`, `incorporationType`, `registered`, `referralPartner`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '1', 1, '2024-12-31 07:01:40', '2024-12-31 07:01:40'),
(2, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1', 2, '2025-01-01 04:25:25', '2025-01-01 05:00:02'),
(3, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '1', 2, '2025-01-01 05:05:22', '2025-01-01 05:05:22'),
(4, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1', 2, '2025-01-02 05:49:42', '2025-01-02 05:49:42'),
(5, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '1', 1, '2025-01-06 05:17:06', '2025-01-06 05:17:06'),
(6, 16, 'suresh', 'b.tech', 'html,css,javascript', 'key responsibility test', 'key indicator test', '0123456789', 'current', 'permanent', '562025.webp', '746709.webp', '719656.webp', '983944.webp', 0, NULL, 0, '2025-01-07 06:57:58', '2025-01-08 06:21:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

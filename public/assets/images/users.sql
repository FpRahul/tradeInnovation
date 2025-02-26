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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `altEmail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `altNumber` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `companyName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Profession` int NOT NULL DEFAULT '0',
  `communicationAdress` text COLLATE utf8mb4_unicode_ci,
  `referralPartner` int NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `archive` tinyint NOT NULL DEFAULT '1' COMMENT '0 for archive & 1 for active',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 for inactive & 1 for active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `altEmail`, `mobile`, `altNumber`, `companyName`, `address`, `Profession`, `communicationAdress`, `referralPartner`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `archive`, `status`) VALUES
(1, 'admin', 1, 'admin@gmail.com', NULL, '', NULL, NULL, NULL, 0, NULL, 0, NULL, '$2y$12$XB45zVTx4iCEyVocftkfeOxSFct7AX/VOZ4/77KbjXRMKPdOs4x2q', NULL, NULL, '2024-12-31 06:51:58', 1, 1),
(2, 'piyush', 2, 'mohit@fpdemo.com', NULL, '9057537243', NULL, 'company name', 'test', 0, NULL, 0, NULL, '$2y$12$WHFTCTzoB59fdSEO1C8qmOthVKbEXRe9eKV2A2GzFfuPFG6HaJ1Zy', NULL, '2024-12-31 07:01:40', '2025-01-06 04:37:15', 1, 1),
(4, 'piyush', 3, 'piyushvijay@gmail.com', 'piyush.vijay@futureprofilez.com', '9057537243', '9874563215', 'company name', 'bani park', 0, 'raja park', 0, NULL, '$2y$12$Z2bTzDytaYrcvuCxGJzD6ODVXcHmbbxozWV4/PHH/2qaMnb//ucsW', NULL, '2025-01-01 04:25:25', '2025-01-02 05:40:36', 1, 1),
(6, 'test 1', 3, 'admin2@gmail.com', 'admin1@futureprofilez.com', '1234567898', '9874563215', 'test company', 'test 1 address', 0, 'test 2 address', 0, NULL, '$2y$12$nSuYqQFIkxBA/YMBeRKfg.t.omQY2CsZznSACYOpFYWQ2SjwXQq6S', NULL, '2025-01-01 05:05:22', '2025-01-06 03:48:24', 1, 1),
(7, 'adarsh patel', 4, 'Ap.Patel@futureprofilez.com', 'aadarsh11@gmail.com', '5555555554', '6666666665', 'patel coachingg', 'MP one', 2, NULL, 0, NULL, '$2y$12$TW2tbtKRoOHQLOzptvOaRef3MTlprZF1Pafkgz3h6yyFIu5q1wCi2', NULL, '2025-01-01 06:32:33', '2025-01-06 03:48:41', 1, 1),
(8, 'prem prem', 2, 'prem@gmail.com', NULL, '7014613031', NULL, 'future proflez', 'bani park jaipur', 0, NULL, 0, NULL, '$2y$12$HQdiTojlTuekiX4DhkEXceKhAT4oi38sIYRO6piIIi5dbrM5Rmv76', NULL, '2025-01-02 05:49:42', '2025-01-06 05:13:55', 1, 0),
(9, 'prem one', 2, 'prem11@gmail.com', NULL, '0123456789', NULL, 'future proflez', 'prem address one', 0, NULL, 0, NULL, '$2y$12$g9jODcLmKGd4fBDbNGtCwOscGON.OUNXL7uB25IiXFJ2JYR/cYTOG', NULL, '2025-01-06 05:17:06', '2025-01-06 05:17:06', 1, 1),
(10, 'patel one', 4, 'piyush.vijay@futureprofilez.com', 'adarsh11@gmail.com', '25589632587', '6666666665', 'future profilez', 'bani park', 1, NULL, 0, NULL, '$2y$12$flG49sCjKrUPgBOWcL0iB./LDky4wgm7yWi1Oo2pEusnliO0pu1jK', NULL, '2025-01-06 06:54:28', '2025-01-06 06:54:28', 1, 1),
(11, 'adarsh', 4, 'A.Patel@futureprofilez.com', 'adarsh11@gmail.com', '905753724322', '6666666666', 'patel coaching', 'bani park', 1, NULL, 0, NULL, '$2y$12$k27VjvFa/o3jxZEkxtVKNeQqUPfD2mxIO5Qvba191A/S3f9S0cE.m', NULL, '2025-01-06 06:55:47', '2025-01-06 06:55:47', 1, 1),
(16, 'abc bca', 2, 'maam@gmail.com', NULL, '1234567889', '0123456789', NULL, NULL, 0, NULL, 0, NULL, '$2y$12$qMCYqAlYKzT6S/Tg0Kqm4e54xGM89oCOn3e4bUId0W8nKbx7AUYyG', NULL, '2025-01-07 06:57:58', '2025-01-08 05:26:28', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `name` (`name`),
  ADD KEY `name_2` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 21, 2025 at 05:32 AM
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
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_options`
--

CREATE TABLE `category_options` (
  `id` bigint UNSIGNED NOT NULL,
  `authId` int NOT NULL DEFAULT '0',
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '1 for professions , 2 for incorporation and 3 for referral',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 for inactive & 1 for active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_options`
--

INSERT INTO `category_options` (`id`, `authId`, `type`, `name`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 'CA', 1, '2025-01-10 04:59:29', '2025-01-17 00:37:54'),
(3, 1, 1, 'CEO', 1, '2025-01-10 05:13:19', '2025-01-17 00:54:09'),
(4, 1, 2, 'Partnership', 1, '2025-01-10 06:07:11', '2025-01-10 06:33:43'),
(12, 1, 2, 'Incorporation', 1, '2025-01-17 00:54:37', '2025-01-17 00:54:57'),
(14, 1, 3, 'Associate', 1, '2025-01-17 04:07:27', '2025-01-17 04:07:27'),
(15, 1, 3, 'Employee', 1, '2025-01-17 04:07:38', '2025-01-17 04:07:38'),
(16, 1, 3, 'Google', 1, '2025-01-17 04:07:48', '2025-01-17 04:07:48'),
(17, 1, 3, 'Facebook', 1, '2025-01-17 04:07:57', '2025-01-17 04:07:57'),
(18, 1, 3, 'Newspaper', 1, '2025-01-17 04:08:08', '2025-01-17 04:08:08'),
(19, 1, 3, 'Client', 1, '2025-01-17 04:08:19', '2025-01-17 04:08:19'),
(20, 1, 1, 'advocate', 1, '2025-01-20 00:44:57', '2025-01-20 00:44:57');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `source` int NOT NULL DEFAULT '0',
  `source_id` int NOT NULL DEFAULT '0',
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assign_to` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '0',
  `completed_date` datetime DEFAULT NULL,
  `quotation_sent` tinyint NOT NULL DEFAULT '0',
  `quotation_sent_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `user_id`, `source`, `source_id`, `client_name`, `company_name`, `mobile_number`, `email`, `assign_to`, `description`, `status`, `completed_date`, `quotation_sent`, `quotation_sent_date`, `created_at`, `updated_at`) VALUES
(4, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-18 05:00:37', '2025-01-18 05:00:37'),
(5, 1, 14, 6, 'piyush', 'PIY', '9057537243', 'piyushvijay@gmail.com', 11, 'TEST DESCRIPTION', 0, NULL, 0, NULL, '2025-01-20 04:08:19', '2025-01-20 04:08:19'),
(6, 1, 15, 10, 'client name', 'company name', '9057537243', 'mohit@fpdemo.com', 10, 'test', 0, NULL, 0, NULL, '2025-01-20 04:48:03', '2025-01-20 04:48:03'),
(7, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:19:21', '2025-01-20 07:19:21'),
(8, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:19:45', '2025-01-20 07:19:45'),
(9, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:22:47', '2025-01-20 07:22:47'),
(10, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:24:14', '2025-01-20 07:24:14'),
(11, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:27:37', '2025-01-20 07:27:37'),
(12, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:29:37', '2025-01-20 07:29:37'),
(13, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:30:02', '2025-01-20 07:30:02'),
(14, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:31:39', '2025-01-20 07:31:39'),
(15, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:32:13', '2025-01-20 07:32:13'),
(16, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:32:52', '2025-01-20 07:32:52'),
(17, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:33:32', '2025-01-20 07:33:32'),
(18, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:34:50', '2025-01-20 07:34:50'),
(19, 1, 15, 10, 'client name', 'company name', '9057537243', 'piyush.vijay@futureprofilez.com', 10, 'test description', 0, NULL, 0, NULL, '2025-01-20 07:37:02', '2025-01-20 07:37:02');

-- --------------------------------------------------------

--
-- Table structure for table `lead_logs`
--

CREATE TABLE `lead_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `lead_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `is_follow_up` tinyint NOT NULL DEFAULT '0',
  `follow_up_date` datetime DEFAULT NULL,
  `verfied_on` datetime DEFAULT NULL,
  `dead_line` datetime DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `action_date` datetime DEFAULT NULL,
  `object_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hearing_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_log_attachments`
--

CREATE TABLE `lead_log_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `lead_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_services`
--

CREATE TABLE `lead_services` (
  `id` bigint UNSIGNED NOT NULL,
  `lead_id` bigint UNSIGNED NOT NULL,
  `service_id` int NOT NULL DEFAULT '0',
  `subservice_id` int NOT NULL DEFAULT '0',
  `quoted_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lead_services`
--

INSERT INTO `lead_services` (`id`, `lead_id`, `service_id`, `subservice_id`, `quoted_price`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 1, NULL, '2025-01-18 05:00:37', '2025-01-18 05:00:37'),
(2, 4, 2, 5, NULL, '2025-01-18 05:00:37', '2025-01-18 05:00:37'),
(3, 4, 11, 7, NULL, '2025-01-18 05:00:37', '2025-01-18 05:00:37'),
(4, 19, 11, 7, NULL, '2025-01-20 04:08:19', '2025-01-20 07:37:02'),
(5, 6, 2, 5, NULL, '2025-01-20 04:48:03', '2025-01-20 04:48:03');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Login', 'rahul logged into portal', NULL, NULL),
(2, 1, 'Login', 'rahul logged into portal', '2025-01-18 02:22:04', NULL),
(3, 1, 'Login', 'rahul logged into portal', '2025-01-19 23:04:05', NULL),
(4, 1, 'Login', 'rahul logged into portal', '2025-01-20 00:03:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `parentId` int NOT NULL,
  `menuName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actionRoutes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `parentId`, `menuName`, `icon`, `url`, `actionRoutes`, `created_at`, `updated_at`) VALUES
(1, 0, 'Dashboard', 'dashboard-icon', 'dashboard', 'dashboard', NULL, NULL),
(2, 0, 'Users', 'user-icon', 'javascript:void(0);', 'users.listing,users.adduser,client.listing,users.addclient,associate.listing,users.addassociate', NULL, NULL),
(3, 2, 'Internal Users', NULL, 'users.listing', 'users.listing,users.adduser', NULL, NULL),
(4, 2, 'Clients', NULL, 'client.listing', 'client.listing,users.addclient', NULL, NULL),
(5, 2, 'Associates', NULL, 'associate.listing', 'associate.listing,users.addassociate', NULL, NULL),
(6, 0, 'Leads', 'leads-icon', 'leads.index', 'leads.index,leads.add,leads.assign,leads.archive,leads.quote,leads.logs,leads.delete', NULL, NULL),
(7, 0, 'Services', 'setting-icon', 'services.index', 'services.index,service.add,service.status', NULL, NULL),
(8, 0, 'Panel Settings', 'setting-icon', 'javascript:void(0);', 'settings.roles,settings.addrole', NULL, NULL),
(9, 8, 'Roles', NULL, 'settings.roles', 'settings.roles,settings.addrole', NULL, NULL),
(10, 2, 'Settings', NULL, 'javascript:void(0);', 'professions.index,professions.add,professions.status,professions.delete,incorporation.index,incorporations.add,incorporations.status,incorporations.delete,referral.index,referral.add,referral.status,referral.delete', NULL, NULL),
(11, 10, 'Professions', NULL, 'professions.index', 'professions.index,professions.add,professions.status,professions.delete', NULL, NULL),
(12, 10, 'Incorporation type', NULL, 'incorporation.index', 'incorporation.index,incorporations.add,incorporations.status,incorporations.delete', NULL, NULL),
(13, 10, 'Referral partner', NULL, 'referral.index', 'referral.index,referral.add,referral.status,referral.delete', NULL, NULL),
(14, 8, 'Logs', NULL, 'logs.index', 'logs.index,logs.view', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_actions`
--

CREATE TABLE `menu_actions` (
  `id` bigint UNSIGNED NOT NULL,
  `menuId` int DEFAULT NULL,
  `actionName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_actions`
--

INSERT INTO `menu_actions` (`id`, `menuId`, `actionName`, `route`, `created_at`, `updated_at`) VALUES
(1, 3, 'Add / Edit', 'users.adduser', NULL, NULL),
(2, 3, 'Update Status', 'users.status', NULL, NULL),
(3, 3, 'Archive', 'users.delete', NULL, NULL),
(4, 4, 'Add / Edit', 'users.addclient', NULL, NULL),
(5, 4, 'Update Status', 'users.status', NULL, NULL),
(6, 4, 'Archive', 'users.delete', NULL, NULL),
(7, 5, 'Add / Edit', 'users.addassociate', NULL, NULL),
(8, 5, 'Update Status', 'users.status', NULL, NULL),
(9, 5, 'Archive', 'users.delete', NULL, NULL),
(10, 6, 'Add / Edit', 'leads.add', NULL, NULL),
(11, 6, 'Assign', 'leads.assign', NULL, NULL),
(12, 6, 'Archive', 'leads.archive', NULL, NULL),
(13, 6, 'Send Quote', 'leads.quote', NULL, NULL),
(14, 6, 'Logs', 'leads.logs', NULL, NULL),
(15, 6, 'Delete', 'leads.delete', NULL, NULL),
(16, 7, 'Add / Edit', 'service.add', NULL, NULL),
(17, 7, 'Update Status', 'service.status', NULL, NULL),
(18, 7, 'Delete', NULL, NULL, NULL),
(19, 11, 'Add / Edit', 'professions.add', NULL, NULL),
(20, 11, 'Update Status', 'professions.status', NULL, NULL),
(22, 12, 'Add / Edit', 'incorporations.add', NULL, NULL),
(23, 12, 'Update Status', 'incorporations.status', NULL, NULL),
(25, 13, 'Add / Edit', 'referral.add', NULL, NULL),
(26, 13, 'Update Status', 'referral.status', NULL, NULL),
(28, 14, 'View', 'referral.delete', NULL, NULL),
(29, 14, 'View', 'logs.view', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_12_26_120114_add_column_to_users_table', 1),
(6, '2024_12_27_094240_create_role_menu', 1),
(7, '2024_12_27_094557_create_roles', 1),
(8, '2024_12_27_105648_add_to_users_table', 1),
(9, '2024_12_27_111149_create_user_details_table', 1),
(11, '2024_12_30_101711_change_column_type_in_users', 2),
(12, '2024_12_26_122626_create_menu_table', 3),
(13, '2024_12_30_073231_add_column_to_menu', 3),
(14, '2024_12_31_051955_add_column_to_menus', 3),
(15, '2024_12_31_112426_add_to_users_table', 4),
(16, '2024_12_31_121638_change_column_type_in_user_details', 5),
(17, '2024_12_31_103242_create_menu_actions_table', 6),
(18, '2025_01_01_091214_add_to_users_table', 6),
(19, '2025_01_01_110515_add_to_users_table', 7),
(20, '2025_01_01_113518_add_to_users_table', 8),
(21, '2025_01_07_070022_add_to_user_details_table', 9),
(22, '2025_01_07_072627_create_user_experiences_table', 10),
(23, '2025_01_07_122458_change_column_type_in_user_details', 11),
(25, '2025_01_10_065835_create_category_options_table', 12),
(26, '2025_01_15_064739_create_services_table', 13),
(27, '2025_01_15_065220_create_sub_services_table', 13),
(28, '2025_01_16_091650_add_column_to_menu_actions', 14),
(37, '2025_01_17_065809_create_leads_table', 15),
(38, '2025_01_17_071721_create_lead_services_table', 15),
(39, '2025_01_17_072242_create_lead_logs_table', 15),
(40, '2025_01_17_073111_create_lead_log_attachments_table', 15),
(41, '2025_01_17_104058_create_logs_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2025-01-16 11:04:14', '2025-01-16 11:04:14'),
(2, ' Client', '2025-01-16 11:04:14', '2025-01-16 11:04:14'),
(3, 'Associate', '2025-01-16 11:05:27', '2025-01-16 11:05:27'),
(4, 'Employee', '2025-01-16 11:04:14', '2025-01-16 11:04:14'),
(5, 'Project Manager', '2025-01-16 11:04:14', '2025-01-16 11:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `role_menus`
--

CREATE TABLE `role_menus` (
  `id` bigint UNSIGNED NOT NULL,
  `roleId` int NOT NULL,
  `menuId` int NOT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `serviceName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 for inactive & 1 for active',
  `serviceDescription` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `serviceName`, `status`, `serviceDescription`, `created_at`, `updated_at`) VALUES
(1, 'service name', 1, 'service description', '2025-01-15 01:44:08', '2025-01-16 07:30:24'),
(2, 'service name 2', 1, 'service description 2', '2025-01-15 01:47:50', '2025-01-15 01:47:50'),
(10, 'piyush', 0, 'asdfdsa', '2025-01-16 04:43:02', '2025-01-16 04:43:02'),
(11, 'services name 3', 1, 'services des 3', '2025-01-17 01:19:09', '2025-01-17 01:20:27');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('xq6NK7ciHF0zuRN594fximUxsuQlatFxT5UeVi4j', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQzdNUmJrTE9GVUNOYVIzdmZYc0hUZ2E0aFVBb3FyQjBTZVNmRzN5TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sZWFkcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1737378422);

-- --------------------------------------------------------

--
-- Table structure for table `sub_services`
--

CREATE TABLE `sub_services` (
  `id` bigint UNSIGNED NOT NULL,
  `serviceId` int NOT NULL DEFAULT '0',
  `subServiceName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subServiceDescription` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_services`
--

INSERT INTO `sub_services` (`id`, `serviceId`, `subServiceName`, `subServiceDescription`, `created_at`, `updated_at`) VALUES
(1, 1, 'sub service 1', NULL, '2025-01-15 05:54:33', '2025-01-15 05:54:33'),
(3, 1, 'sub service 3', NULL, '2025-01-15 05:54:33', '2025-01-15 05:54:33'),
(4, 1, 'sub service 4', NULL, '2025-01-15 05:55:11', '2025-01-15 05:55:11'),
(5, 2, 'sub 2', NULL, '2025-01-15 05:56:28', '2025-01-15 05:56:28'),
(6, 2, 'sub 23', NULL, '2025-01-15 05:56:39', '2025-01-15 05:56:39'),
(7, 11, 'sub service 3', NULL, '2025-01-17 01:21:14', '2025-01-17 01:21:14'),
(9, 11, 'sub service 312', NULL, '2025-01-17 01:22:01', '2025-01-17 01:22:01');

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
(1, 'rahul', 1, 'admin@gmail.com', NULL, '', NULL, NULL, NULL, 0, NULL, 0, NULL, '$2y$12$KOg/YzzGqCF10skJdLpw9.db8/GFg1.KFbffYyq33AwBTA/02rKtK', NULL, NULL, '2025-01-16 07:11:52', 1, 1),
(2, 'piyush', 2, 'mohit@fpdemo.com', NULL, '9057537243', NULL, 'company name', 'test', 0, NULL, 0, NULL, '$2y$12$WHFTCTzoB59fdSEO1C8qmOthVKbEXRe9eKV2A2GzFfuPFG6HaJ1Zy', NULL, '2024-12-31 07:01:40', '2025-01-16 05:32:05', 1, 1),
(4, 'piyush', 3, 'piyushvijay@gmail.com', 'piyush.vijay@futureprofilez.com', '9057537243', '9874563215', 'company name', 'bani park', 0, 'raja park', 0, NULL, '$2y$12$Z2bTzDytaYrcvuCxGJzD6ODVXcHmbbxozWV4/PHH/2qaMnb//ucsW', NULL, '2025-01-01 04:25:25', '2025-01-09 01:54:41', 0, 1),
(6, 'test 1', 3, 'admin2@gmail.com', 'admin1@futureprofilez.com', '1234567898', '9874563215', 'test company', 'test 1 address', 0, 'test 2 address', 0, NULL, '$2y$12$nSuYqQFIkxBA/YMBeRKfg.t.omQY2CsZznSACYOpFYWQ2SjwXQq6S', NULL, '2025-01-01 05:05:22', '2025-01-06 03:48:24', 1, 1),
(7, 'employee one', 4, 'Ap.Patel@futureprofilez.com', 'aadarsh11@gmail.com', '5555555554', '6666666665', 'patel coachingg', 'MP one', 2, NULL, 0, NULL, '$2y$12$TW2tbtKRoOHQLOzptvOaRef3MTlprZF1Pafkgz3h6yyFIu5q1wCi2', NULL, '2025-01-01 06:32:33', '2025-01-09 01:56:33', 1, 1),
(8, 'prem prem', 2, 'prem@gmail.com', NULL, '7014613031', NULL, 'future proflez', 'bani park jaipur', 0, NULL, 0, NULL, '$2y$12$HQdiTojlTuekiX4DhkEXceKhAT4oi38sIYRO6piIIi5dbrM5Rmv76', NULL, '2025-01-02 05:49:42', '2025-01-09 04:08:36', 0, 0),
(9, 'prem one', 2, 'prem11@gmail.com', NULL, '0123456789', NULL, 'future proflez', 'prem address one', 0, NULL, 0, NULL, '$2y$12$g9jODcLmKGd4fBDbNGtCwOscGON.OUNXL7uB25IiXFJ2JYR/cYTOG', NULL, '2025-01-06 05:17:06', '2025-01-09 06:01:59', 0, 1),
(10, 'patel one', 4, 'piyush.vijay@futureprofilez.com', 'adarsh11@gmail.com', '25589632587', '6666666665', 'future profilez', 'bani park', 1, NULL, 0, NULL, '$2y$12$flG49sCjKrUPgBOWcL0iB./LDky4wgm7yWi1Oo2pEusnliO0pu1jK', NULL, '2025-01-06 06:54:28', '2025-01-06 06:54:28', 1, 1),
(11, 'adarsh', 5, 'A.Patel@futureprofilez.com', 'adarsh11@gmail.com', '905753724322', '6666666666', 'patel coaching', 'bani park', 1, NULL, 0, NULL, '$2y$12$k27VjvFa/o3jxZEkxtVKNeQqUPfD2mxIO5Qvba191A/S3f9S0cE.m', NULL, '2025-01-06 06:55:47', '2025-01-09 03:49:02', 1, 1),
(16, 'abc bca', 2, 'maam@gmail.com', NULL, '1234567889', '0123456789', NULL, NULL, 0, NULL, 0, NULL, '$2y$12$qMCYqAlYKzT6S/Tg0Kqm4e54xGM89oCOn3e4bUId0W8nKbx7AUYyG', NULL, '2025-01-07 06:57:58', '2025-01-09 06:02:11', 1, 1),
(18, 'test 1', 3, 'diamond@gmail.com', 'adarsh11@gmail.com', '5555555555', '6666666665', 'abc group', 'MP katni', 0, 'test 2 address', 0, NULL, '$2y$12$f6G.tE0Tbut2hIB49f8jBeSsbPAToItjtJuCPOLkch5BxVG6PsdsO', NULL, '2025-01-09 01:10:36', '2025-01-09 01:19:08', 1, 1),
(19, 'manager', 5, 'manager@gmail.com', NULL, '9057537243', NULL, 'abc group', 'mp', 2, NULL, 0, NULL, '$2y$12$/RI9IPS.O8mDCp6Tc4Z2qeuhAx81AhrtpuJlek3F2YXX.So6K41gm', NULL, '2025-01-09 02:26:39', '2025-01-09 03:48:33', 1, 1);

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
(1, 2, 'adfaadf', 'b.tech', 'html,css,javascript', 'key responsibility test', 'key indicator test', '0123456789', 'test', 'test', '591206.png', '921555.png', '996545.jpg', '807983.jpg', 2, '1', 1, '2024-12-31 07:01:40', '2025-01-16 05:32:05'),
(2, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1', 2, '2025-01-01 04:25:25', '2025-01-01 05:00:02'),
(3, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '1', 2, '2025-01-01 05:05:22', '2025-01-01 05:05:22'),
(4, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1', 2, '2025-01-02 05:49:42', '2025-01-02 05:49:42'),
(5, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '1', 1, '2025-01-06 05:17:06', '2025-01-06 05:17:06'),
(6, 16, 'suresh', 'b.tech', 'html,css,javascript', 'key responsibility test', 'key indicator test', '0123456789', 'current', 'permanent', '562684.webp', '746709.webp', '719656.webp', '983944.webp', 0, NULL, 0, '2025-01-07 06:57:58', '2025-01-09 04:34:49'),
(7, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '1', 1, '2025-01-09 01:10:36', '2025-01-09 01:16:40'),
(8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '361085.jpg', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-01-18 00:24:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_experiences`
--

CREATE TABLE `user_experiences` (
  `id` bigint UNSIGNED NOT NULL,
  `userId` int NOT NULL DEFAULT '0',
  `employerName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_experiences`
--

INSERT INTO `user_experiences` (`id`, `userId`, `employerName`, `startDate`, `endDate`, `created_at`, `updated_at`) VALUES
(3, 16, 'future profilez1', '2025-01-09', '2025-01-12', '2025-01-07 06:57:58', '2025-01-08 05:40:57'),
(9, 16, 'rajapark', '2025-01-19', '2025-01-21', '2025-01-09 06:00:18', '2025-01-09 06:00:18'),
(12, 2, 'future profilez', '2025-01-17', '2025-02-07', '2025-01-16 05:32:05', '2025-01-16 05:32:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `category_options`
--
ALTER TABLE `category_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_user_id_foreign` (`user_id`);

--
-- Indexes for table `lead_logs`
--
ALTER TABLE `lead_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_logs_lead_id_foreign` (`lead_id`),
  ADD KEY `lead_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `lead_log_attachments`
--
ALTER TABLE `lead_log_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_log_attachments_lead_id_foreign` (`lead_id`),
  ADD KEY `lead_log_attachments_user_id_foreign` (`user_id`);

--
-- Indexes for table `lead_services`
--
ALTER TABLE `lead_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_services_lead_id_foreign` (`lead_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_actions`
--
ALTER TABLE `menu_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_menus`
--
ALTER TABLE `role_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_services`
--
ALTER TABLE `sub_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `name` (`name`),
  ADD KEY `name_2` (`name`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_experiences`
--
ALTER TABLE `user_experiences`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_options`
--
ALTER TABLE `category_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `lead_logs`
--
ALTER TABLE `lead_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_log_attachments`
--
ALTER TABLE `lead_log_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_services`
--
ALTER TABLE `lead_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `menu_actions`
--
ALTER TABLE `menu_actions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role_menus`
--
ALTER TABLE `role_menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sub_services`
--
ALTER TABLE `sub_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_experiences`
--
ALTER TABLE `user_experiences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lead_logs`
--
ALTER TABLE `lead_logs`
  ADD CONSTRAINT `lead_logs_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lead_log_attachments`
--
ALTER TABLE `lead_log_attachments`
  ADD CONSTRAINT `lead_log_attachments_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_log_attachments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lead_services`
--
ALTER TABLE `lead_services`
  ADD CONSTRAINT `lead_services_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

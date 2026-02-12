-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 11, 2026 at 04:36 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `happylife`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `from_user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `from_package_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `user_id`, `from_user_id`, `type`, `amount`, `description`, `from_package_id`, `status`, `updated_at`, `created_at`) VALUES
(4, 2, 28, 'direct', 1250.00, 'Direct sponsor bonus for Yahaya Ibrahim (SAPPHIRE)', 1, 'paid', '2026-02-11 07:51:26', '2026-02-11 06:51:26');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'Arewa', 'AR', '2026-02-10 10:13:53', '2026-02-10 10:13:53', 1),
(2, 'Biafra', 'BI', '2026-02-10 10:13:53', '2026-02-10 10:13:53', 1),
(3, 'Ododuwa', 'OD', '2026-02-10 10:13:53', '2026-02-10 10:13:53', 1),
(4, 'Others', 'OT', '2026-02-10 10:13:53', '2026-02-10 10:13:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(6) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `funding_requests`
--

CREATE TABLE `funding_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `proof` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc`
--

CREATE TABLE `kyc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `front_image` varchar(255) DEFAULT NULL,
  `back_image` varchar(255) DEFAULT NULL,
  `selfie_image` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `place_of_issue` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_comment` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landing_products`
--

CREATE TABLE `landing_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `display_price` decimal(12,2) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `landing_products`
--

INSERT INTO `landing_products` (`id`, `name`, `image`, `description`, `display_price`, `category`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Smartphone X', NULL, 'Latest smartphone with all features', 150000.00, 'Electronics', 1, 1, '2026-02-10 11:24:01', '2026-02-10 11:24:01'),
(2, 'Headphones Y', NULL, 'Noise-cancelling over-ear headphones', 35000.00, 'Electronics', 2, 1, '2026-02-10 11:24:01', '2026-02-10 11:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_10_090945_create_landing_products_table', 1),
(5, '2026_02_10_090945_create_packages_table', 1),
(6, '2026_02_10_104637_create_countries_table', 1),
(7, '2026_02_10_104637_create_pickup_centers_table', 1),
(8, '2026_02_10_104637_create_ranks_table', 1),
(9, '2026_02_10_104637_create_repurchase_products_table', 1),
(10, '2026_02_10_104637_create_vtu_plans_table', 1),
(11, '2026_02_10_104637_create_vtu_providers_table', 1),
(12, '2026_02_10_110100_create_states_table', 1),
(13, '2026_02_10_111323_add_is_active_to_countries_table', 2),
(14, '2026_02_10_111819_create_sessions_table', 3),
(15, '2026_02_10_113108_create_email_verifications_table', 4),
(16, '2026_02_10_114140_add_email_verified_at_to_users_table', 5),
(17, '2026_02_10_115703_create_products_table', 6),
(18, '2026_02_10_123659_add_referral_code_to_users_table', 7),
(19, '2026_02_10_144813_create_payments_table', 8),
(20, '2026_02_10_162331_add_payment_status_and_activated_at_to_users_table', 9),
(21, '2026_02_10_180420_add_binary_pv_to_users_table', 10),
(22, '2026_02_11_094151_create_kyc_table', 11),
(23, '2026_02_11_103535_create_wallets_table', 12),
(24, '2026_02_11_103705_create_wallet_transactions_table', 13),
(25, '2026_02_11_114721_create_funding_requests_table', 14),
(26, '2026_02_11_123834_create_product_categories_table', 15),
(27, '2026_02_11_141244_create_withdrawals_table', 16),
(28, '2026_02_11_142944_create_product_claims_table', 17),
(29, '2026_02_11_150739_create_upgrades_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `pv_total` int(11) NOT NULL DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(50) NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(50) DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `pv` int(11) NOT NULL,
  `product_entitlement` varchar(255) NOT NULL,
  `direct_bonus_amount` decimal(12,2) NOT NULL,
  `pairing_cap` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `price`, `pv`, `product_entitlement`, `direct_bonus_amount`, `pairing_cap`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'SAPPHIRE', 6500.00, 10, 'Basic Products', 1250.00, 5000.00, 'Starter package for new members', 1, 1, '2026-02-10 11:22:24', '2026-02-10 11:22:24'),
(2, 'OHEKEM', 10500.00, 16, 'Intermediate Products', 2000.00, 8000.00, 'Intermediate membership package', 1, 2, '2026-02-10 11:22:24', '2026-02-10 11:22:24'),
(3, 'EMERALD', 54500.00, 100, 'Premium Products', 12500.00, 50000.00, 'Premium membership package', 1, 3, '2026-02-10 11:22:24', '2026-02-10 11:22:24'),
(4, 'ACHIEVERS', 132500.00, 244, 'Elite Products', 30500.00, 100000.00, 'Elite membership package', 1, 4, '2026-02-10 11:22:24', '2026-02-10 11:22:24'),
(5, 'LIFESTYLE', 265500.00, 500, 'Luxury Products', 62500.00, 200000.00, 'Top-tier lifestyle package', 1, 5, '2026-02-10 11:22:24', '2026-02-10 11:22:24');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `gateway_reference` varchar(100) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `proof_url` varchar(255) DEFAULT NULL,
  `authorization_url` text DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `package_id`, `amount`, `payment_method`, `reference`, `gateway_reference`, `status`, `proof_url`, `authorization_url`, `gateway_response`, `description`, `created_at`, `updated_at`) VALUES
(8, 11, 1, 6500.00, 'paystack', 'PS177074962111', 'HL177074960369411', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-10 17:53:41', '2026-02-10 17:53:41'),
(9, 12, 1, 6500.00, 'paystack', 'PS177075272712', 'HL177075271663912', 'completed', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-10 18:45:27', '2026-02-10 18:45:27'),
(13, 13, 1, 6500.00, 'paystack', 'PS177075464213', 'HL177075462981313', 'completed', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-10 19:17:22', '2026-02-10 19:17:22'),
(14, 14, 1, 6500.00, 'paystack', 'PS177075536114', 'HL177075535216514', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-10 19:29:21', '2026-02-10 19:29:21'),
(15, 15, 1, 6500.00, 'paystack', 'PS177079087515', 'HL177079086835115', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 05:21:15', '2026-02-11 05:21:15'),
(16, 16, 1, 6500.00, 'paystack', 'PS177079137516', 'HL177079136807016', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 05:29:35', '2026-02-11 05:29:35'),
(17, 17, 1, 6500.00, 'paystack', 'PS177079198117', 'HL177079197474717', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 05:39:41', '2026-02-11 05:39:41'),
(18, 18, 1, 6500.00, 'paystack', 'PS177079234518', 'HL177079233989818', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 05:45:45', '2026-02-11 05:45:45'),
(19, 19, 1, 6500.00, 'paystack', 'PS177079262119', 'HL177079261394419', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 05:50:21', '2026-02-11 05:50:21'),
(20, 20, 1, 6500.00, 'paystack', 'PS177079323220', 'HL177079322596820', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 06:00:32', '2026-02-11 06:00:32'),
(21, 21, 1, 6500.00, 'paystack', 'PS177079358821', 'HL177079358214821', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 06:06:28', '2026-02-11 06:06:28'),
(22, 22, 1, 6500.00, 'paystack', 'PS177079435322', 'HL177079434637122', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 06:19:13', '2026-02-11 06:19:13'),
(23, 23, 1, 6500.00, 'paystack', 'PS177079535423', 'HL177079534712423', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 06:35:54', '2026-02-11 06:35:54'),
(24, 25, 1, 6500.00, 'paystack', 'PS177079564525', 'HL177079563976225', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 06:40:45', '2026-02-11 06:40:45'),
(25, 26, 1, 6500.00, 'paystack', 'PS177079584726', 'HL177079584230326', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 06:44:07', '2026-02-11 06:44:07'),
(26, 27, 1, 6500.00, 'paystack', 'PS177079603027', 'HL177079601807227', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 06:47:10', '2026-02-11 06:47:10'),
(27, 28, 1, 6500.00, 'paystack', 'PS177079628628', 'HL177079628042228', 'paid', NULL, NULL, NULL, 'Paystack payment for SAPPHIRE package', '2026-02-11 06:51:26', '2026-02-11 06:51:26');

-- --------------------------------------------------------

--
-- Table structure for table `pickup_centers`
--

CREATE TABLE `pickup_centers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `operating_hours` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pickup_centers`
--

INSERT INTO `pickup_centers` (`id`, `state_id`, `name`, `address`, `contact_phone`, `contact_person`, `operating_hours`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 22, 'Ikeja Hub', '123 Allen Avenue, Ikeja, Lagos', '08030000001', 'John Doe', 'Mon-Sat 9am-5pm', 1, '2026-02-10 11:23:50', '2026-02-10 11:23:50'),
(2, 16, 'Abuja Central', '456 CBD, Abuja', '08030000002', 'Jane Smith', 'Mon-Sat 9am-5pm', 1, '2026-02-10 11:23:50', '2026-02-10 11:23:50');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pv` int(11) NOT NULL DEFAULT 0,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `pv`, `package_id`, `created_at`, `updated_at`) VALUES
(1, 'Starter Kit', 6500.00, 10, 1, '2026-02-10 13:09:51', '2026-02-10 13:09:51'),
(2, 'Ohekem Pack', 10500.00, 16, 2, '2026-02-10 13:09:51', '2026-02-10 13:09:51'),
(3, 'Emerald Pack', 54500.00, 100, 3, '2026-02-10 13:09:51', '2026-02-10 13:09:51'),
(4, 'Achievers Pack', 132500.00, 244, 4, '2026-02-10 13:09:51', '2026-02-10 13:09:51'),
(5, 'Lifestyle Pack', 265500.00, 500, 5, '2026-02-10 13:09:51', '2026-02-10 13:09:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Health', 'health', NULL, NULL, 1, 0, '2026-02-11 12:46:08', '2026-02-11 12:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `product_claims`
--

CREATE TABLE `product_claims` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `pickup_center_id` bigint(20) UNSIGNED NOT NULL,
  `claim_number` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected','collected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `collected_at` timestamp NULL DEFAULT NULL,
  `receipt_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`receipt_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_claims`
--

INSERT INTO `product_claims` (`id`, `user_id`, `product_id`, `pickup_center_id`, `claim_number`, `status`, `admin_notes`, `claimed_at`, `approved_at`, `collected_at`, `receipt_data`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 2, 'CLM-20260211-0001', 'pending', NULL, '2026-02-11 13:41:57', NULL, NULL, '{\"product\":{\"id\":1,\"name\":\"Starter Kit\",\"price\":\"6500.00\",\"pv\":10},\"pickup_center\":{\"id\":2,\"name\":\"Abuja Central\",\"address\":\"456 CBD, Abuja\",\"contact_person\":\"Jane Smith\",\"contact_phone\":\"08030000002\",\"operating_hours\":\"Mon-Sat 9am-5pm\",\"state\":\"Abia\"},\"user\":{\"id\":2,\"name\":\"John Doe\",\"email\":\"john@example.com\",\"phone\":\"08030000003\",\"username\":\"johndoe\"}}', '2026-02-11 13:41:57', '2026-02-11 13:41:57');

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `required_pv` decimal(12,2) NOT NULL,
  `cash_reward` decimal(12,2) NOT NULL,
  `other_reward` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`id`, `name`, `level`, `required_pv`, `cash_reward`, `other_reward`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SAPPHIRE', 1, 10.00, 1000.00, NULL, 'Rank SAPPHIRE reward', 1, '2026-02-10 11:23:24', '2026-02-10 11:23:24'),
(2, 'OHEKEM', 2, 50.00, 5000.00, NULL, 'Rank OHEKEM reward', 1, '2026-02-10 11:23:24', '2026-02-10 11:23:24'),
(3, 'EMERALD', 3, 200.00, 12500.00, NULL, 'Rank EMERALD reward', 1, '2026-02-10 11:23:24', '2026-02-10 11:23:24'),
(4, 'ACHIEVER', 4, 500.00, 30500.00, NULL, 'Rank ACHIEVER reward', 1, '2026-02-10 11:23:24', '2026-02-10 11:23:24'),
(5, 'LIFESTYLE', 5, 1000.00, 62500.00, NULL, 'Rank LIFESTYLE reward', 1, '2026-02-10 11:23:24', '2026-02-10 11:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `repurchase_products`
--

CREATE TABLE `repurchase_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `pv_value` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sku` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repurchase_products`
--

INSERT INTO `repurchase_products` (`id`, `category_id`, `name`, `price`, `pv_value`, `stock`, `image`, `description`, `is_active`, `sku`, `created_at`, `updated_at`) VALUES
(1, 1, 'Vitamin C Pack', 5000.00, 5, 100, NULL, 'Pack of 30 vitamin C tablets', 1, 'VC001', '2026-02-10 11:24:40', '2026-02-10 11:24:40'),
(2, 1, 'Energy Drink', 2000.00, 2, 200, NULL, 'Energy boosting drink', 1, 'ED001', '2026-02-10 11:24:40', '2026-02-10 11:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('q4vo0zGiX6Nymi1FdtEPZXxFTeTZn81jL6KASn6Z', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoialh0YkFuOUJnNzg1b0NBY3ZRcnFmS09sblU0ZVlEMEt6ckpLSVNkMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1770823513);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `code`, `is_active`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 'Adamawa', 'AD', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(2, 'Bauchi', 'BA', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(3, 'Borno', 'BO', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(4, 'Gombe', 'GO', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(5, 'Jigawa', 'JI', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(6, 'Kaduna', 'KD', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(7, 'Kano', 'KN', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(8, 'Katsina', 'KT', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(9, 'Kebbi', 'KE', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(10, 'Kwara', 'KW', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(11, 'Niger', 'NI', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(12, 'Sokoto', 'SO', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(13, 'Taraba', 'TA', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(14, 'Yobe', 'YO', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(15, 'Zamfara', 'ZA', 1, 1, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(16, 'Abia', 'AB', 1, 2, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(17, 'Anambra', 'AN', 1, 2, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(18, 'Ebonyi', 'EB', 1, 2, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(19, 'Enugu', 'EN', 1, 2, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(20, 'Imo', 'IM', 1, 2, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(21, 'Ekiti', 'EK', 1, 3, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(22, 'Lagos', 'LG', 1, 3, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(23, 'Ogun', 'OG', 1, 3, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(24, 'Ondo', 'OD', 1, 3, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(25, 'Osun', 'OS', 1, 3, '2026-02-10 10:13:53', '2026-02-10 10:13:53'),
(26, 'Oyo', 'OY', 1, 3, '2026-02-10 10:13:53', '2026-02-10 10:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `upgrades`
--

CREATE TABLE `upgrades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `old_package_id` bigint(20) UNSIGNED NOT NULL,
  `new_package_id` bigint(20) UNSIGNED NOT NULL,
  `difference_amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'commission_wallet',
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `sponsor_id` varchar(255) DEFAULT NULL,
  `placement_id` varchar(255) DEFAULT NULL,
  `placement_position` varchar(255) DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pickup_center_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `left_count` int(11) NOT NULL DEFAULT 0,
  `right_count` int(11) NOT NULL DEFAULT 0,
  `total_pv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `current_pv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `registration_date` timestamp NULL DEFAULT NULL,
  `role` enum('admin','company','member') NOT NULL DEFAULT 'member',
  `remember_token` varchar(100) DEFAULT NULL,
  `verification_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referral_code` varchar(255) DEFAULT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `activated_at` timestamp NULL DEFAULT NULL,
  `commission_wallet_balance` decimal(12,2) DEFAULT 0.00,
  `registration_wallet_balance` decimal(12,2) DEFAULT 0.00,
  `rank_wallet_balance` decimal(12,2) DEFAULT 0.00,
  `shopping_wallet_balance` decimal(12,2) DEFAULT 0.00,
  `left_pv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `right_pv` decimal(12,2) NOT NULL DEFAULT 0.00,
  `direct_bonus_total` decimal(12,2) DEFAULT 0.00,
  `indirect_level_2_bonus_total` decimal(12,2) DEFAULT 0.00,
  `indirect_level_3_bonus_total` decimal(12,2) DEFAULT 0.00,
  `matching_pv_bonus_total` decimal(12,2) DEFAULT 0.00,
  `rank_bonus_total` decimal(12,2) DEFAULT 0.00,
  `repurchase_bonus_total` decimal(12,2) DEFAULT 0.00,
  `lifestyle_bonus_total` decimal(12,2) DEFAULT 0.00,
  `direct_sponsors_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `username`, `phone`, `password`, `sponsor_id`, `placement_id`, `placement_position`, `package_id`, `country`, `state`, `product_id`, `pickup_center_id`, `rank_id`, `left_count`, `right_count`, `total_pv`, `current_pv`, `status`, `registration_date`, `role`, `remember_token`, `verification_code`, `created_at`, `updated_at`, `referral_code`, `payment_status`, `activated_at`, `commission_wallet_balance`, `registration_wallet_balance`, `rank_wallet_balance`, `shopping_wallet_balance`, `left_pv`, `right_pv`, `direct_bonus_total`, `indirect_level_2_bonus_total`, `indirect_level_3_bonus_total`, `matching_pv_bonus_total`, `rank_bonus_total`, `repurchase_bonus_total`, `lifestyle_bonus_total`, `direct_sponsors_count`) VALUES
(2, 'John Doe', 'john@example.com', '2026-02-10 13:18:14', 'johndoe', '08030000003', '$2y$12$54luF8FjQiMF5Z9/x7FbvO0RRZLtmGML4HrhpsoY4o/vycr9W9WRi', '1', '1', NULL, 2, NULL, NULL, 1, 2, 2, 1, 0, 50.00, 50.00, 'active', '2026-02-10 11:51:19', 'member', NULL, NULL, '2026-02-10 11:51:19', '2026-02-11 09:37:31', 'SP12345', 'paid', NULL, 11250.00, 0.00, 0.00, 0.00, 50.00, 0.00, 6250.00, 0.00, 0.00, 0.00, 5000.00, 0.00, 0.00, 1),
(28, 'Yahaya Ibrahim', 'yahayaibraheem808@gmail.com', '2026-02-11 06:51:13', 'yahayaibrahim', '+234 7084343765', '$2y$12$pVcGOmKwa1g4jwg1mJSNb..QXAlbdH5YwGuIldNA98tI5CnL8vEfa', '2', '2', 'left', 1, 'AREWA', 'Kano Central', 1, NULL, NULL, 0, 0, 10.00, 10.00, 'active', '2026-02-11 06:50:48', 'member', NULL, NULL, '2026-02-11 06:50:48', '2026-02-11 06:51:26', 'HLOZ8L9N', 'paid', '2026-02-11 06:51:26', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vtu_plans`
--

CREATE TABLE `vtu_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `validity` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vtu_plans`
--

INSERT INTO `vtu_plans` (`id`, `provider_id`, `name`, `amount`, `validity`, `size`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 5, 'Daily 1GB', 300.00, '1 day', '1GB', 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(2, 5, 'Weekly 2GB', 1000.00, '7 days', '2GB', 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(3, 5, 'Monthly 10GB', 3000.00, '30 days', '10GB', 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(4, 6, 'Daily 1GB', 250.00, '1 day', '1GB', 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(5, 6, 'Weekly 2GB', 900.00, '7 days', '2GB', 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(6, 6, 'Monthly 10GB', 2800.00, '30 days', '10GB', 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(7, 9, 'DSTV Premium', 24500.00, '30 days', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(8, 9, 'DSTV Compact', 12600.00, '30 days', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(9, 10, 'GOTV Max', 5750.00, '30 days', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(10, 10, 'GOTV Jolli', 3650.00, '30 days', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29');

-- --------------------------------------------------------

--
-- Table structure for table `vtu_providers`
--

CREATE TABLE `vtu_providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL COMMENT 'airtime, data, cable, electricity',
  `code` varchar(50) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vtu_providers`
--

INSERT INTO `vtu_providers` (`id`, `name`, `category`, `code`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'MTN Nigeria', 'airtime', 'MTN', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(2, 'Glo', 'airtime', 'GLO', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(3, 'Airtel', 'airtime', 'AIR', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(4, '9mobile', 'airtime', 'ETI', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(5, 'MTN Data', 'data', 'MTN-DATA', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(6, 'Glo Data', 'data', 'GLO-DATA', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(7, 'Airtel Data', 'data', 'AIR-DATA', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(8, '9mobile Data', 'data', 'ETI-DATA', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(9, 'DSTV', 'cable', 'DSTV', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(10, 'GOTV', 'cable', 'GOTV', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(11, 'Startimes', 'cable', 'STR', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(12, 'Ikeja Electric', 'electricity', 'IKEDC', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(13, 'Eko Electric', 'electricity', 'EKEDC', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(14, 'Abuja Electric', 'electricity', 'AEDC', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(15, 'Kano Electric', 'electricity', 'KEDCO', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(16, 'Port Harcourt Electric', 'electricity', 'PHED', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(17, 'Benin Electric', 'electricity', 'BEDC', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(18, 'Enugu Electric', 'electricity', 'EEDC', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(19, 'Ibadan Electric', 'electricity', 'IBEDC', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(20, 'Jos Electric', 'electricity', 'JED', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29'),
(21, 'Kaduna Electric', 'electricity', 'KAEDCO', NULL, 1, '2026-02-11 14:01:29', '2026-02-11 14:01:29');

-- --------------------------------------------------------

--
-- Table structure for table `vtu_transactions`
--

CREATE TABLE `vtu_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `provider_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `smart_card` varchar(50) DEFAULT NULL,
  `meter_number` varchar(50) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `reference` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `locked_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `type`, `balance`, `locked_balance`, `created_at`, `updated_at`) VALUES
(2, 2, 'commission', 5000.00, 0.00, '2026-02-11 09:37:31', '2026-02-11 09:37:31'),
(3, 2, 'registration', 0.00, 0.00, '2026-02-11 11:00:38', '2026-02-11 11:00:38'),
(4, 2, 'rank', 0.00, 0.00, '2026-02-11 11:00:38', '2026-02-11 11:00:38'),
(5, 2, 'shopping', 0.00, 0.00, '2026-02-11 11:00:38', '2026-02-11 11:00:38'),
(6, 28, 'commission', 0.00, 0.00, '2026-02-11 14:24:32', '2026-02-11 14:24:32'),
(7, 28, 'registration', 0.00, 0.00, '2026-02-11 14:24:32', '2026-02-11 14:24:32'),
(8, 28, 'rank', 0.00, 0.00, '2026-02-11 14:24:32', '2026-02-11 14:24:32'),
(9, 28, 'shopping', 0.00, 0.00, '2026-02-11 14:24:32', '2026-02-11 14:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reference` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `wallet_id`, `user_id`, `type`, `amount`, `description`, `reference`, `status`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'credit', 5000.00, 'Rank achievement bonus: OHEKEM', 'RANK-2-1770806251', 'completed', '{\"rank_id\":2,\"rank_name\":\"OHEKEM\"}', '2026-02-11 09:37:31', '2026-02-11 09:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `bank_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bank_details`)),
  `admin_notes` text DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `user_id`, `amount`, `fee`, `net_amount`, `status`, `bank_details`, `admin_notes`, `processed_at`, `reference`, `created_at`, `updated_at`) VALUES
(1, 2, 2000.00, 40.00, 1960.00, 'pending', '{\"bank_name\":\"United back for africa\",\"account_name\":\"Ibrahim Yahaya\",\"account_number\":\"2140692309\"}', NULL, NULL, 'WDR-BCOTMSNLS4WRQVMAFBMW', '2026-02-11 13:16:45', '2026-02-11 13:16:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_verifications_user_id_index` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `funding_requests`
--
ALTER TABLE `funding_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `funding_requests_transaction_id_unique` (`transaction_id`),
  ADD KEY `funding_requests_user_id_foreign` (`user_id`);

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
-- Indexes for table `kyc`
--
ALTER TABLE `kyc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kyc_user_id_foreign` (`user_id`),
  ADD KEY `kyc_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `landing_products`
--
ALTER TABLE `landing_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_reference_unique` (`reference`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_package_id_foreign` (`package_id`);

--
-- Indexes for table `pickup_centers`
--
ALTER TABLE `pickup_centers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pickup_centers_state_id_foreign` (`state_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_categories_slug_unique` (`slug`);

--
-- Indexes for table `product_claims`
--
ALTER TABLE `product_claims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_claims_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD UNIQUE KEY `product_claims_claim_number_unique` (`claim_number`),
  ADD KEY `product_claims_product_id_foreign` (`product_id`),
  ADD KEY `product_claims_pickup_center_id_foreign` (`pickup_center_id`),
  ADD KEY `product_claims_user_id_status_index` (`user_id`,`status`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repurchase_products`
--
ALTER TABLE `repurchase_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `repurchase_products_sku_unique` (`sku`),
  ADD KEY `repurchase_products_category_id_foreign` (`category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `states_country_id_foreign` (`country_id`);

--
-- Indexes for table `upgrades`
--
ALTER TABLE `upgrades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `upgrades_reference_unique` (`reference`),
  ADD KEY `upgrades_old_package_id_foreign` (`old_package_id`),
  ADD KEY `upgrades_new_package_id_foreign` (`new_package_id`),
  ADD KEY `upgrades_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  ADD KEY `users_package_id_foreign` (`package_id`),
  ADD KEY `users_pickup_center_id_foreign` (`pickup_center_id`),
  ADD KEY `users_rank_id_foreign` (`rank_id`),
  ADD KEY `users_product_id_foreign` (`product_id`);

--
-- Indexes for table `vtu_plans`
--
ALTER TABLE `vtu_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_index` (`provider_id`);

--
-- Indexes for table `vtu_providers`
--
ALTER TABLE `vtu_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_code` (`code`),
  ADD KEY `category_index` (`category`);

--
-- Indexes for table `vtu_transactions`
--
ALTER TABLE `vtu_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vtu_transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallets_user_id_type_unique` (`user_id`,`type`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallet_transactions_reference_unique` (`reference`),
  ADD KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  ADD KEY `wallet_transactions_user_id_wallet_id_created_at_index` (`user_id`,`wallet_id`,`created_at`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `withdrawals_reference_unique` (`reference`),
  ADD KEY `withdrawals_user_id_status_index` (`user_id`,`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `funding_requests`
--
ALTER TABLE `funding_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc`
--
ALTER TABLE `kyc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landing_products`
--
ALTER TABLE `landing_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pickup_centers`
--
ALTER TABLE `pickup_centers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_claims`
--
ALTER TABLE `product_claims`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `repurchase_products`
--
ALTER TABLE `repurchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `upgrades`
--
ALTER TABLE `upgrades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `vtu_plans`
--
ALTER TABLE `vtu_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vtu_providers`
--
ALTER TABLE `vtu_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `vtu_transactions`
--
ALTER TABLE `vtu_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD CONSTRAINT `email_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `funding_requests`
--
ALTER TABLE `funding_requests`
  ADD CONSTRAINT `funding_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kyc`
--
ALTER TABLE `kyc`
  ADD CONSTRAINT `kyc_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kyc_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pickup_centers`
--
ALTER TABLE `pickup_centers`
  ADD CONSTRAINT `pickup_centers_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_claims`
--
ALTER TABLE `product_claims`
  ADD CONSTRAINT `product_claims_pickup_center_id_foreign` FOREIGN KEY (`pickup_center_id`) REFERENCES `pickup_centers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_claims_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_claims_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `repurchase_products`
--
ALTER TABLE `repurchase_products`
  ADD CONSTRAINT `repurchase_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `upgrades`
--
ALTER TABLE `upgrades`
  ADD CONSTRAINT `upgrades_new_package_id_foreign` FOREIGN KEY (`new_package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `upgrades_old_package_id_foreign` FOREIGN KEY (`old_package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `upgrades_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `users_pickup_center_id_foreign` FOREIGN KEY (`pickup_center_id`) REFERENCES `pickup_centers` (`id`),
  ADD CONSTRAINT `users_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `repurchase_products` (`id`),
  ADD CONSTRAINT `users_rank_id_foreign` FOREIGN KEY (`rank_id`) REFERENCES `ranks` (`id`);

--
-- Constraints for table `vtu_plans`
--
ALTER TABLE `vtu_plans`
  ADD CONSTRAINT `fk_provider` FOREIGN KEY (`provider_id`) REFERENCES `vtu_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 10, 2025 at 10:40 PM
-- Server version: 10.5.25-MariaDB-cll-lve
-- PHP Version: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dakoiim1_amis_two_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `adx_country`
--

CREATE TABLE `adx_country` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agreements`
--

CREATE TABLE `agreements` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `agreement_type` varchar(100) DEFAULT NULL,
  `parties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parties`)),
  `effective_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` enum('draft','active','expired','terminated','archived') DEFAULT 'draft',
  `terms` text DEFAULT NULL,
  `conditions` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `remarks` text DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agreements`
--

INSERT INTO `agreements` (`id`, `branch_id`, `title`, `description`, `agreement_type`, `parties`, `effective_date`, `expiry_date`, `status`, `terms`, `conditions`, `attachments`, `remarks`, `is_deleted`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 116, 'Shopping Agreements', 'This is about shopping', 'MOU', '[\"ESPA\",\"ITU\",\"STREIT\"]', '2025-04-16', '2025-04-24', 'active', 'This is the terms sees', 'These are the conditions', '[{\"original_name\":\"1745631051_6e5d90dc29e5d3a7b459.pdf\",\"stored_name\":\"1745631051_6e5d90dc29e5d3a7b459.pdf\",\"path\":\".\\/public\\/uploads\\/agreements_attachments\\/1745631051_6e5d90dc29e5d3a7b459.pdf\",\"size\":3063549,\"type\":\"application\\/pdf\"},{\"original_name\":\"1745631051_79e18055f361a2cf9b29.pdf\",\"stored_name\":\"1745631051_79e18055f361a2cf9b29.pdf\",\"path\":\".\\/public\\/uploads\\/agreements_attachments\\/1745631051_79e18055f361a2cf9b29.pdf\",\"size\":5927885,\"type\":\"application\\/pdf\"},{\"original_name\":\"1745631085_312fc11be2cb1f7b49a7.jpg\",\"stored_name\":\"1745631085_312fc11be2cb1f7b49a7.jpg\",\"path\":\".\\/public\\/uploads\\/agreements_attachments\\/1745631085_312fc11be2cb1f7b49a7.jpg\",\"size\":195920,\"type\":\"image\\/jpeg\"},{\"original_name\":\"1745631085_607f1a6f03c0a8e7a3b6.xml\",\"stored_name\":\"1745631085_607f1a6f03c0a8e7a3b6.xml\",\"path\":\".\\/public\\/uploads\\/agreements_attachments\\/1745631085_607f1a6f03c0a8e7a3b6.xml\",\"size\":2709856,\"type\":\"text\\/xml\"}]', 'Remarkesre', 0, '2025-04-26 01:30:51', 1, '2025-04-26 02:01:43', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `abbrev` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `branch_status` tinyint(4) NOT NULL DEFAULT 1,
  `branch_status_by` int(11) NOT NULL,
  `branch_status_at` datetime DEFAULT NULL,
  `branch_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `parent_id`, `abbrev`, `name`, `remarks`, `branch_status`, `branch_status_by`, `branch_status_at`, `branch_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(107, 0, 'BREC', 'East Sepik Province', '6', 1, 1, '2025-04-07 01:32:42', 'activea', NULL, 'fkenny', '2025-04-07 01:36:53', 'Aitapeti ITUs', NULL, NULL),
(108, 1, '15', 'Sandaun Province', '5', 0, 0, NULL, 'branch', NULL, 'fkenny', '2024-08-31 03:14:12', '19', NULL, NULL),
(110, 1, '13', 'Madang Province', '4', 1, 1, '2025-07-25 12:57:33', 'Update this branch', '2024-08-31 02:07:03', 'ngande@gmail.com', '2025-07-25 12:57:33', '19', NULL, NULL),
(112, 0, '19', 'Eastern Highlands Province', 'Thifsdi', 0, 0, '2025-04-04 09:48:35', 'branchdasdsa', '2024-11-25 06:08:12', 'ngande@gmail.com', '2025-04-09 06:19:46', 'Aitapeti ITUs', NULL, NULL),
(113, 1, '05', 'National Capital District', '', 0, 0, NULL, 'branch', '2024-11-25 08:20:35', 'ngande@gmail.com', '2024-11-25 08:20:35', '', NULL, NULL),
(114, 110, 'MOR', 'Morobe Province', 'Tjifhsi', 0, 0, '2025-04-04 09:35:09', 'branchCAcacn', '2024-11-25 08:21:39', 'ngande@gmail.com', '2025-04-04 09:35:09', 'Aitapeti ITUs', NULL, NULL),
(115, 112, 'KBXes', 'Kubex Branches', 'This is kubesee', 0, 0, '2025-04-04 09:48:26', 'branchdasda', '2025-04-04 09:20:03', 'Aitapeti ITUs', '2025-04-04 09:48:26', 'Aitapeti ITUs', NULL, NULL),
(116, 0, 'INFOB', 'Information Branch', 'dasdas', 1, 1, '2025-04-07 01:36:38', 'Initial activation', '2025-04-07 01:36:38', 'Aitapeti ITUs', '2025-04-07 01:36:38', 'Aitapeti ITUs', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `commodities`
--

CREATE TABLE `commodities` (
  `id` int(11) NOT NULL,
  `commodity_code` varchar(50) NOT NULL,
  `commodity_name` varchar(255) NOT NULL,
  `commodity_icon` text DEFAULT NULL,
  `commodity_color_code` varchar(10) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodities`
--

INSERT INTO `commodities` (`id`, `commodity_code`, `commodity_name`, `commodity_icon`, `commodity_color_code`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `is_deleted`) VALUES
(1, 'RCE012', 'RICE', 'public/uploads/commodities/icons/commodity_1748305715_1748305715_523d304b808273b317da.jpg', '#b1e68e', '2025-05-27 10:13:36', '5', '2025-05-27 10:28:35', '5', NULL, NULL, 0),
(2, 'K002', 'KUMU', 'public/uploads/commodities/icons/commodity_1748305391_1748305391_0b09f90be3e33ba06bb5.png', '#0c8837', '2025-05-27 10:17:23', '5', '2025-05-27 10:23:11', '5', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `commodity_prices`
--

CREATE TABLE `commodity_prices` (
  `id` int(11) UNSIGNED NOT NULL,
  `commodity_id` int(11) UNSIGNED NOT NULL,
  `price_date` date NOT NULL,
  `market_type` enum('local','export','wholesale','retail') DEFAULT 'local',
  `price_per_unit` decimal(15,2) NOT NULL,
  `unit_of_measurement` varchar(50) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'PGK',
  `location` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `commodity_prices`
--

INSERT INTO `commodity_prices` (`id`, `commodity_id`, `price_date`, `market_type`, `price_per_unit`, `unit_of_measurement`, `currency`, `location`, `source`, `notes`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `is_deleted`) VALUES
(1, 1, '2024-08-06', 'local', 421.12, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(2, 1, '2024-09-06', 'local', 421.12, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(3, 1, '2024-10-06', 'local', 519.68, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(4, 1, '2024-11-06', 'local', 416.64, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(5, 1, '2024-12-06', 'local', 430.08, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(6, 1, '2025-01-06', 'local', 430.08, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(7, 1, '2025-02-06', 'local', 510.72, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(8, 1, '2025-03-06', 'local', 362.88, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(9, 1, '2025-04-06', 'local', 407.68, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(10, 1, '2025-05-06', 'local', 421.12, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(11, 1, '2025-06-06', 'local', 519.68, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(12, 1, '2025-07-06', 'local', 492.80, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(13, 1, '2024-08-06', 'export', 541.63, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(14, 1, '2024-09-06', 'export', 512.51, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(15, 1, '2024-10-06', 'export', 611.52, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(16, 1, '2024-11-06', 'export', 547.46, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(17, 1, '2024-12-06', 'export', 663.94, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(18, 1, '2025-01-06', 'export', 628.99, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(19, 1, '2025-02-06', 'export', 669.76, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(20, 1, '2025-03-06', 'export', 535.81, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(21, 1, '2025-04-06', 'export', 698.88, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(22, 1, '2025-05-06', 'export', 594.05, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(23, 1, '2025-06-06', 'export', 506.69, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(24, 1, '2025-07-06', 'export', 465.92, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(25, 1, '2024-08-06', 'wholesale', 344.06, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(26, 1, '2024-09-06', 'wholesale', 430.08, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(27, 1, '2024-10-06', 'wholesale', 329.73, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(28, 1, '2024-11-06', 'wholesale', 351.23, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(29, 1, '2024-12-06', 'wholesale', 408.58, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(30, 1, '2025-01-06', 'wholesale', 351.23, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(31, 1, '2025-02-06', 'wholesale', 394.24, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(32, 1, '2025-03-06', 'wholesale', 358.40, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(33, 1, '2025-04-06', 'wholesale', 358.40, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(34, 1, '2025-05-06', 'wholesale', 430.08, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(35, 1, '2025-06-06', 'wholesale', 340.48, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(36, 1, '2025-07-06', 'wholesale', 390.66, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(37, 1, '2024-08-06', 'retail', 462.34, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(38, 1, '2024-09-06', 'retail', 505.34, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(39, 1, '2024-10-06', 'retail', 505.34, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(40, 1, '2024-11-06', 'retail', 462.34, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(41, 1, '2024-12-06', 'retail', 430.08, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(42, 1, '2025-01-06', 'retail', 569.86, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(43, 1, '2025-02-06', 'retail', 618.24, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(44, 1, '2025-03-06', 'retail', 446.21, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(45, 1, '2025-04-06', 'retail', 585.98, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(46, 1, '2025-05-06', 'retail', 494.59, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(47, 1, '2025-06-06', 'retail', 483.84, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(48, 1, '2025-07-06', 'retail', 564.48, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(49, 2, '2024-08-06', 'local', 474.88, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(50, 2, '2024-09-06', 'local', 434.56, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(51, 2, '2024-10-06', 'local', 430.08, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(52, 2, '2024-11-06', 'local', 470.40, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(53, 2, '2024-12-06', 'local', 461.44, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(54, 2, '2025-01-06', 'local', 461.44, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(55, 2, '2025-02-06', 'local', 528.64, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(56, 2, '2025-03-06', 'local', 528.64, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(57, 2, '2025-04-06', 'local', 474.88, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(58, 2, '2025-05-06', 'local', 488.32, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(59, 2, '2025-06-06', 'local', 452.48, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(60, 2, '2025-07-06', 'local', 501.76, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(61, 2, '2024-08-06', 'export', 588.22, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(62, 2, '2024-09-06', 'export', 524.16, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(63, 2, '2024-10-06', 'export', 605.70, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(64, 2, '2024-11-06', 'export', 605.70, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(65, 2, '2024-12-06', 'export', 634.82, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(66, 2, '2025-01-06', 'export', 477.57, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(67, 2, '2025-02-06', 'export', 483.39, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(68, 2, '2025-03-06', 'export', 524.16, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(69, 2, '2025-04-06', 'export', 605.70, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(70, 2, '2025-05-06', 'export', 547.46, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(71, 2, '2025-06-06', 'export', 652.29, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(72, 2, '2025-07-06', 'export', 483.39, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(73, 2, '2024-08-06', 'wholesale', 415.74, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(74, 2, '2024-09-06', 'wholesale', 426.50, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(75, 2, '2024-10-06', 'wholesale', 304.64, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(76, 2, '2024-11-06', 'wholesale', 333.31, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(77, 2, '2024-12-06', 'wholesale', 301.06, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(78, 2, '2025-01-06', 'wholesale', 397.82, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(79, 2, '2025-02-06', 'wholesale', 311.81, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(80, 2, '2025-03-06', 'wholesale', 315.39, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(81, 2, '2025-04-06', 'wholesale', 297.47, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(82, 2, '2025-05-06', 'wholesale', 369.15, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(83, 2, '2025-06-06', 'wholesale', 372.74, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(84, 2, '2025-07-06', 'wholesale', 369.15, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(85, 2, '2024-08-06', 'retail', 628.99, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(86, 2, '2024-09-06', 'retail', 639.74, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(87, 2, '2024-10-06', 'retail', 494.59, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(88, 2, '2024-11-06', 'retail', 456.96, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(89, 2, '2024-12-06', 'retail', 478.46, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(90, 2, '2025-01-06', 'retail', 440.83, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(91, 2, '2025-02-06', 'retail', 440.83, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(92, 2, '2025-03-06', 'retail', 505.34, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(93, 2, '2025-04-06', 'retail', 521.47, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(94, 2, '2025-05-06', 'retail', 430.08, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(95, 2, '2025-06-06', 'retail', 580.61, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0),
(96, 2, '2025-07-06', 'retail', 516.10, 'kg', 'PGK', 'Port Moresby', 'Market Survey', 'Sample data for testing', '2025-07-06 19:58:42', '1', '2025-07-06 19:58:42', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `commodity_production`
--

CREATE TABLE `commodity_production` (
  `id` int(11) NOT NULL,
  `commodity_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `item` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit_of_measurement` varchar(50) DEFAULT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `is_exported` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodity_production`
--

INSERT INTO `commodity_production` (`id`, `commodity_id`, `date_from`, `date_to`, `item`, `description`, `unit_of_measurement`, `quantity`, `is_exported`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `is_deleted`) VALUES
(1, 1, '2025-05-05', '2025-05-23', 'Dry Rice', 'This is the Dry ric', 'kg', 200.000, 0, '2025-05-27 11:13:08', '5', '2025-05-27 11:13:08', NULL, NULL, NULL, 0),
(2, 1, '2025-03-11', '2025-04-10', 'Wetland Rice', 'This is for export', 'tons', 500.000, 1, '2025-05-27 11:14:18', '5', '2025-05-27 11:14:18', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dakoii_users`
--

CREATE TABLE `dakoii_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `orgcode` varchar(500) NOT NULL,
  `role` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dakoii_users`
--

INSERT INTO `dakoii_users` (`id`, `name`, `username`, `password`, `orgcode`, `role`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(2, 'Free Kenny', 'fkenny', '$2y$10$A.8jXDJcv/wbzVi3l8bt/OPY6B0FpExgbUg.HOk6Khq9CYvKNQCyK', '', 'admin', 1, '2023-03-16 06:49:23', '2025-04-01 23:50:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `classification` enum('private','internal','public') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `doc_date` datetime DEFAULT NULL,
  `authors` text DEFAULT NULL,
  `file_path` varchar(520) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `branch_id`, `folder_id`, `classification`, `title`, `description`, `doc_date`, `authors`, `file_path`, `file_type`, `file_size`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 116, 0, 'internal', 'National Agriculture Sector Plan', 'Thisi si the file about', '2025-04-16 00:00:00', 'Fill, Coko, Tigim', '', '', '', '2025-04-25 00:17:27', 1, '2025-04-25 00:17:27', NULL, NULL, NULL),
(2, 116, 0, 'private', 'East Sepik Province Integrated Development Plan 2018 - 2028', 'This is nice', '2025-04-14 00:00:00', 'Roko', '', '', '', '2025-04-25 00:20:39', 1, '2025-04-25 00:20:39', NULL, NULL, NULL),
(3, 116, 0, 'private', 'Windful Docx', 'Tis setep', '2025-04-15 00:00:00', 'Flo', '', '', '', '2025-04-25 00:21:41', 1, '2025-04-25 00:21:41', NULL, NULL, NULL),
(4, 116, 0, 'public', 'This is Docs', 'Exerrcise', '2025-04-08 00:00:00', 'Cool, Man, Gol', '', '', '', '2025-05-02 18:13:58', 1, '2025-05-02 18:13:58', NULL, NULL, NULL),
(5, 116, 0, 'internal', 'National Agriculture Sector Plan', '', '2024-04-28 00:00:00', 'NDAL Staff', '', '', '', '2025-05-08 13:18:28', 5, '2025-05-08 13:18:28', NULL, NULL, NULL),
(6, 116, 0, 'internal', 'National Agriculture Sector Plan', 'This is the plan', '2025-05-06 00:00:00', '', '', '', '', '2025-05-09 09:39:46', 6, '2025-05-09 09:39:46', NULL, NULL, NULL),
(7, 116, 1, 'public', 'This is NASP', 'DAS', '2025-05-07 00:00:00', 'Book NASP', '', '', '', '2025-05-09 13:30:29', 6, '2025-05-09 13:30:29', NULL, NULL, NULL),
(8, 116, 1, 'internal', 'This Docu', 'This isi the doc', '2025-05-14 00:00:00', 'Cool, Man, Gol', 'public/public/uploads/documents/1/1748496674_e1cfb762f63e9ff8d83d.pdf', 'application/pdf', '638328', '2025-05-29 15:31:14', 1, '2025-05-29 15:31:14', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `parent_folder_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `access` enum('private','internal','public') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `branch_id`, `parent_folder_id`, `name`, `description`, `access`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 116, NULL, 'Cool Mix Folder', 'This is informatio folder', 'private', '2025-04-25 00:00:37', 1, '2025-04-25 00:23:19', 1, NULL, NULL),
(2, 116, 1, 'Child Folder', 'This is the meee', 'internal', '2025-04-25 00:19:43', 1, '2025-04-25 00:19:43', NULL, NULL, NULL),
(3, 116, NULL, 'Another Folder', 'This is another folder', 'internal', '2025-07-18 12:36:00', 1, '2025-07-18 12:36:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gov_structure`
--

CREATE TABLE `gov_structure` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `json_id` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL COMMENT 'province, district, llg, ward',
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `flag_filepath` varchar(255) NOT NULL,
  `map_center` varchar(100) NOT NULL,
  `map_zoom` varchar(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gov_structure`
--

INSERT INTO `gov_structure` (`id`, `parent_id`, `json_id`, `level`, `code`, `name`, `flag_filepath`, `map_center`, `map_zoom`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(107, 1, '', '668a28186d45c1720330264', '14', 'East Sepik Province', '/public/uploads/gov/1720334792_c60991ee1d4dadb08b19.png', '-4.226649, 143.556513', '6', NULL, 'fkenny', '2024-08-31 03:13:54', '19', NULL, NULL),
(108, 1, '', '668a283bb2aae1720330299', '15', 'Sandaun Province', '', '', '5', NULL, 'fkenny', '2024-08-31 03:14:12', '19', NULL, NULL),
(110, 1, '', '3453565465', '13', 'Madang Province', '', '-5.191644, 145.720761', '4', '2024-08-31 02:07:03', 'ngande@gmail.com', '2024-11-25 08:21:58', '19', NULL, NULL),
(112, 1, '', '674387acac8941732478892', '19', 'Eastern Highlands Province', '', '', '', '2024-11-25 06:08:12', 'ngande@gmail.com', '2024-11-25 06:08:12', '', NULL, NULL),
(113, 1, '', '6743a6b3ef4061732486835', '05', 'National Capital District', '', '', '', '2024-11-25 08:20:35', 'ngande@gmail.com', '2024-11-25 08:20:35', '', NULL, NULL),
(114, 1, '', '6743a6f376e911732486899', '18', 'Morobe Province', '', '', '', '2024-11-25 08:21:39', 'ngande@gmail.com', '2024-11-25 08:21:39', '', NULL, NULL),
(156, 0, 'PG02', 'province', 'PG02', 'Gulf', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(157, 0, 'PG03', 'province', 'PG03', 'Central', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(158, 0, 'PG04', 'province', 'PG04', 'National Capital District', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(159, 0, 'PG05', 'province', 'PG05', 'Milne Bay', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(160, 0, 'PG06', 'province', 'PG06', 'Northern (Oro)', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(161, 0, 'PG07', 'province', 'PG07', 'Southern Highlands', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(162, 0, 'PG08', 'province', 'PG08', 'Enga', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(163, 0, 'PG09', 'province', 'PG09', 'Western Highlands', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(164, 0, 'PG10', 'province', 'PG10', 'Chimbu (Simbu)', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(165, 0, 'PG11', 'province', 'PG11', 'Eastern Highlands', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(166, 0, 'PG12', 'province', 'PG12', 'Morobe', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(167, 0, 'PG13', 'province', 'PG13', 'Madang', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(168, 0, 'PG14', 'province', 'PG14', 'East Sepik', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(170, 0, 'PG16', 'province', 'PG16', 'Manus', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(171, 0, 'PG17', 'province', 'PG17', 'New Ireland', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(172, 0, 'PG18', 'province', 'PG18', 'East New Britain', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(173, 0, 'PG19', 'province', 'PG19', 'West New Britain', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(174, 0, 'PG20', 'province', 'PG20', 'Autonomous Region of Bougainville', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(175, 0, 'PG21', 'province', 'PG21', 'Hela', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(176, 0, 'PG22', 'province', 'PG22', 'Jiwaka', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(177, 0, 'PG01', 'province', 'PG01', 'Western', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(191, 0, 'PG15', 'province', 'PG15', 'West Sepik (Sandaun)', '', '', '', '2025-05-27 18:18:37', '1', '2025-05-27 18:18:37', '1', NULL, NULL),
(205, 174, '2001', 'district', '2001', 'North Bougainville', '', '', '', '2025-07-06 10:31:33', '1', '2025-07-06 10:31:33', '1', NULL, NULL),
(206, 174, '2002', 'district', '2002', 'Central Bougainville', '', '', '', '2025-07-06 10:31:33', '1', '2025-07-06 10:31:33', '1', NULL, NULL),
(207, 174, '2003', 'district', '2003', 'South Bougainville', '', '', '', '2025-07-06 10:31:33', '1', '2025-07-06 10:31:33', '1', NULL, NULL),
(208, 206, '200207', 'llg', '200207', 'Wakunai', '', '', '', '2025-07-06 10:32:00', '1', '2025-07-06 10:32:00', '1', NULL, NULL),
(209, 206, '200208', 'llg', '200208', 'Arawa', '', '', '', '2025-07-06 10:32:00', '1', '2025-07-06 10:32:00', '1', NULL, NULL),
(210, 209, '20020801', 'ward', '20020801', 'Kokoda', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(211, 209, '20020802', 'ward', '20020802', 'Torau', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(212, 209, '20020803', 'ward', '20020803', 'Kongara No. 1', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(213, 209, '20020804', 'ward', '20020804', 'Kongara No. 2', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(214, 209, '20020805', 'ward', '20020805', 'Eivo 1', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(215, 209, '20020806', 'ward', '20020806', 'Avaipa', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(216, 209, '20020807', 'ward', '20020807', 'Oune', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(217, 209, '20020808', 'ward', '20020808', 'Bava Pirung', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(218, 209, '20020809', 'ward', '20020809', 'North Nasioi', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(219, 209, '20020810', 'ward', '20020810', 'Apiatei', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(220, 209, '20020811', 'ward', '20020811', 'South Nasioi', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(221, 209, '20020812', 'ward', '20020812', 'loro 1', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(222, 209, '20020813', 'ward', '20020813', 'loro 2/Domana', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(223, 209, '20020814', 'ward', '20020814', 'Pinei-Nari', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(224, 209, '20020815', 'ward', '20020815', 'loro 3', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(225, 209, '20020882', 'ward', '20020882', 'Arawa Urban', '', '', '', '2025-07-06 11:46:35', '1', '2025-07-06 11:46:35', '1', NULL, NULL),
(226, 168, '1401', 'district', '1401', 'Ambunti/Drekikier', '', '', '', '2025-07-06 11:48:55', '1', '2025-07-06 11:48:55', '1', NULL, NULL),
(227, 168, '1402', 'district', '1402', 'Angoram', '', '', '', '2025-07-06 11:48:55', '1', '2025-07-06 11:48:55', '1', NULL, NULL),
(228, 168, '1403', 'district', '1403', 'Maprik', '', '', '', '2025-07-06 11:48:55', '1', '2025-07-06 11:48:55', '1', NULL, NULL),
(229, 168, '1404', 'district', '1404', 'Wewak', '', '', '', '2025-07-06 11:48:55', '1', '2025-07-06 11:48:55', '1', NULL, NULL),
(230, 168, '1405', 'district', '1405', 'Wosera Gawi', '', '', '', '2025-07-06 11:48:55', '1', '2025-07-06 11:48:55', '1', NULL, NULL),
(231, 168, '1406', 'district', '1406', 'Yangoru Saussia', '', '', '', '2025-07-06 11:48:55', '1', '2025-07-06 11:48:55', '1', NULL, NULL),
(232, 226, '140101', 'llg', '140101', 'Ambunti Rural', '', '', '', '2025-07-06 11:49:10', '1', '2025-07-06 11:49:10', '1', NULL, NULL),
(233, 226, '140102', 'llg', '140102', 'Dreikikier Rural', '', '', '', '2025-07-06 11:49:10', '1', '2025-07-06 11:49:10', '1', NULL, NULL),
(234, 226, '140103', 'llg', '140103', 'Gawanga Rural', '', '', '', '2025-07-06 11:49:10', '1', '2025-07-06 11:49:10', '1', NULL, NULL),
(235, 226, '140104', 'llg', '140104', 'Tunap/Hustein', '', '', '', '2025-07-06 11:49:11', '1', '2025-07-06 11:49:11', '1', NULL, NULL),
(236, 232, '14010101', 'ward', '14010101', 'Ambunti', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(237, 232, '14010102', 'ward', '14010102', 'Bangus', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(238, 232, '14010103', 'ward', '14010103', 'Waskuk', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(239, 232, '14010104', 'ward', '14010104', 'Beglam', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(240, 232, '14010105', 'ward', '14010105', 'Tangujamb', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(241, 232, '14010106', 'ward', '14010106', 'Singiok', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(242, 232, '14010107', 'ward', '14010107', 'Amaki 1', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(243, 232, '14010108', 'ward', '14010108', 'Ablatak', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(244, 232, '14010109', 'ward', '14010109', 'Waiwos', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(245, 232, '14010110', 'ward', '14010110', 'Bu-Ur', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(246, 232, '14010111', 'ward', '14010111', 'Warsei', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(247, 232, '14010112', 'ward', '14010112', 'Ambuken', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(248, 232, '14010113', 'ward', '14010113', 'Tauri', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(249, 232, '14010114', 'ward', '14010114', 'Oum 1', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(250, 232, '14010115', 'ward', '14010115', 'Oum 2', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(251, 232, '14010116', 'ward', '14010116', 'Sanapian', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(252, 232, '14010117', 'ward', '14010117', 'Hauna', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(253, 232, '14010118', 'ward', '14010118', 'Waskuk', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(254, 232, '14010119', 'ward', '14010119', 'Kupkain', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(255, 232, '14010120', 'ward', '14010120', 'Swagap 1', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(256, 232, '14010121', 'ward', '14010121', 'Baku', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(257, 232, '14010122', 'ward', '14010122', 'Yessan', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(258, 232, '14010123', 'ward', '14010123', 'Prukunawi', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(259, 232, '14010124', 'ward', '14010124', 'Yambun', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(260, 232, '14010125', 'ward', '14010125', 'Malu', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(261, 232, '14010126', 'ward', '14010126', 'Yerakai', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(262, 232, '14010127', 'ward', '14010127', 'Garamambu', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(263, 232, '14010128', 'ward', '14010128', 'Yauambak', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(264, 232, '14010129', 'ward', '14010129', 'Avatip', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(265, 232, '14010180', 'ward', '14010180', 'Ambunti Urban', '', '', '', '2025-07-06 12:13:33', '1', '2025-07-06 12:13:33', '1', NULL, NULL),
(266, 233, '14010201', 'ward', '14010201', 'Tumam', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(267, 233, '14010202', 'ward', '14010202', 'Moihwak', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(268, 233, '14010203', 'ward', '14010203', 'Musungua', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(269, 233, '14010204', 'ward', '14010204', 'Taihunge', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(270, 233, '14010205', 'ward', '14010205', 'Mosinau', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(271, 233, '14010206', 'ward', '14010206', 'Prombil', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(272, 233, '14010207', 'ward', '14010207', 'Missim', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(273, 233, '14010208', 'ward', '14010208', 'Pelnandu', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(274, 233, '14010209', 'ward', '14010209', 'Musindai', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(275, 233, '14010210', 'ward', '14010210', 'Bana', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(276, 233, '14010211', 'ward', '14010211', 'Hambini', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(277, 233, '14010212', 'ward', '14010212', 'Waringame', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(278, 233, '14010213', 'ward', '14010213', 'SelnI', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(279, 233, '14010214', 'ward', '14010214', 'Aresili', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(280, 233, '14010215', 'ward', '14010215', 'Whaleng', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(281, 233, '14010216', 'ward', '14010216', 'Yawatong', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(282, 233, '14010217', 'ward', '14010217', 'Lainimguap', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(283, 233, '14010218', 'ward', '14010218', 'Krunguanam', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(284, 233, '14010219', 'ward', '14010219', 'Yakrumbok', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(285, 233, '14010220', 'ward', '14010220', 'King', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(286, 233, '14010221', 'ward', '14010221', 'Kofem', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(287, 233, '14010222', 'ward', '14010222', 'Sakap', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(288, 233, '14010223', 'ward', '14010223', 'Makumauip', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(289, 233, '14010224', 'ward', '14010224', 'Tong', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(290, 233, '14010225', 'ward', '14010225', 'Kumbun', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(291, 233, '14010226', 'ward', '14010226', 'Miringe', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(292, 233, '14010227', 'ward', '14010227', 'Yawerng', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(293, 233, '14010228', 'ward', '14010228', 'Yambes', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(294, 233, '14010229', 'ward', '14010229', 'Waim/Saiweep', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(295, 233, '14010230', 'ward', '14010230', 'Moseng', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(296, 233, '14010231', 'ward', '14010231', 'Pagilo', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(297, 233, '14010232', 'ward', '14010232', 'Luwaite', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(298, 233, '14010233', 'ward', '14010233', 'Selnau', '', '', '', '2025-07-06 12:23:58', '1', '2025-07-06 12:23:58', '1', NULL, NULL),
(299, 234, '14010301', 'ward', '14010301', 'Apangai', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(300, 234, '14010302', 'ward', '14010302', 'Yambanakor 1', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(301, 234, '14010303', 'ward', '14010303', 'Yambinakor 2', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(302, 234, '14010304', 'ward', '14010304', 'Asanakor', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(303, 234, '14010305', 'ward', '14010305', 'Inakor', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(304, 234, '14010306', 'ward', '14010306', 'Apos', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(305, 234, '14010307', 'ward', '14010307', 'Daina', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(306, 234, '14010308', 'ward', '14010308', 'Masalagar', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(307, 234, '14010309', 'ward', '14010309', 'Wasambu', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(308, 234, '14010310', 'ward', '14010310', 'Bongomasi', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(309, 234, '14010311', 'ward', '14010311', 'Wahaukia', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(310, 234, '14010312', 'ward', '14010312', 'Bongos', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(311, 234, '14010313', 'ward', '14010313', 'Kuyor', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(312, 234, '14010314', 'ward', '14010314', 'Kuatengisi', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(313, 234, '14010315', 'ward', '14010315', 'Mamsi', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(314, 234, '14010316', 'ward', '14010316', 'Kubriwat 1', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(315, 234, '14010317', 'ward', '14010317', 'Kubriwat 2', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(316, 234, '14010318', 'ward', '14010318', 'Tau 1', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(317, 234, '14010319', 'ward', '14010319', 'Tau 2', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(318, 234, '14010320', 'ward', '14010320', 'Wamenokor', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(319, 234, '14010321', 'ward', '14010321', 'Sauke Aucheli', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(320, 234, '14010322', 'ward', '14010322', 'Surumburombo', '', '', '', '2025-07-06 12:24:31', '1', '2025-07-06 12:24:31', '1', NULL, NULL),
(321, 235, '14010401', 'ward', '14010401', 'Hotmin', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(322, 235, '14010402', 'ward', '14010402', 'Burmai', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(323, 235, '14010403', 'ward', '14010403', 'Arai', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(324, 235, '14010404', 'ward', '14010404', 'Nino', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(325, 235, '14010405', 'ward', '14010405', 'Itelinu', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(326, 235, '14010406', 'ward', '14010406', 'Samo', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(327, 235, '14010407', 'ward', '14010407', 'Painum', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(328, 235, '14010408', 'ward', '14010408', 'Wanium', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(329, 235, '14010409', 'ward', '14010409', 'Aumi', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(330, 235, '14010410', 'ward', '14010410', 'Pekwei', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(331, 235, '14010411', 'ward', '14010411', 'Wanamoi', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(332, 235, '14010412', 'ward', '14010412', 'Waniap', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(333, 235, '14010413', 'ward', '14010413', 'Kavia', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(334, 235, '14010414', 'ward', '14010414', 'Ama', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(335, 235, '14010415', 'ward', '14010415', 'Yenuai', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(336, 235, '14010416', 'ward', '14010416', 'Panawai', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(337, 235, '14010417', 'ward', '14010417', 'Imombi', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(338, 235, '14010418', 'ward', '14010418', 'Mowi', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(339, 235, '14010419', 'ward', '14010419', 'Iniok', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(340, 235, '14010420', 'ward', '14010420', 'Paupe', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(341, 235, '14010421', 'ward', '14010421', 'Oum 3', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(342, 235, '14010422', 'ward', '14010422', 'Walio', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(343, 235, '14010423', 'ward', '14010423', 'Nein', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(344, 235, '14010424', 'ward', '14010424', 'Nekiei/Wusol', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(345, 235, '14010425', 'ward', '14010425', 'Masuwari', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(346, 235, '14010426', 'ward', '14010426', 'Sio', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(347, 235, '14010427', 'ward', '14010427', 'Hanasi', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(348, 235, '14010428', 'ward', '14010428', 'Moropote', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(349, 235, '14010429', 'ward', '14010429', 'Maposi', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(350, 235, '14010430', 'ward', '14010430', 'Lariaso', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(351, 235, '14010431', 'ward', '14010431', 'Yabatawe', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(352, 235, '14010432', 'ward', '14010432', 'Sowano', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(353, 235, '14010433', 'ward', '14010433', 'Bitara', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(354, 235, '14010434', 'ward', '14010434', 'Kagiru', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(355, 235, '14010435', 'ward', '14010435', 'Begapuki', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(356, 235, '14010436', 'ward', '14010436', 'Wagu', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(357, 235, '14010437', 'ward', '14010437', 'Niksek/Paka', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL),
(358, 235, '14010438', 'ward', '14010438', 'Gahom', '', '', '', '2025-07-06 12:24:55', '1', '2025-07-06 12:24:55', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `agenda` text DEFAULT NULL,
  `meeting_date` datetime NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `participants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`participants`)),
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
  `minutes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`minutes`)),
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `recurrence_rule` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `access_type` enum('private','internal','public') DEFAULT 'private',
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `branch_id`, `title`, `agenda`, `meeting_date`, `start_time`, `end_time`, `location`, `participants`, `status`, `minutes`, `attachments`, `recurrence_rule`, `remarks`, `access_type`, `is_deleted`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 116, '1st Meeting', 'Everything', '2025-04-25 00:00:00', '2025-04-25 15:00:00', '2025-04-25 17:04:00', 'Wewak HQ', '[{\"name\":\"Rooki - Manager Tomorrow\",\"position\":\"Manager\",\"contacts\":\"75556\",\"remarks\":\"Vvog\"},{\"name\":\"Mooki - Inspector\",\"position\":\"Inds\",\"contacts\":\"5566\",\"remarks\":\"fsdf\"},{\"name\":\"Tooki - Runner\",\"position\":\"TOJJS\",\"contacts\":\"56476\",\"remarks\":\"fffffffff\"}]', 'completed', NULL, '[{\"filename\":\"security-guard-security-company-allied-universal-police-officer-png-favpng-W4bD52X5ptVTQ74wkKH6T3KDJ.jpg\",\"path\":\"public\\/uploads\\/meeting_attachments\\/1745566640_a9c2f6fe2afee3bd651f.jpg\"}]', NULL, 'It went well', 'internal', 0, '2025-04-25 07:37:20', 1, '2025-05-02 18:16:14', 1, NULL, NULL),
(2, 116, 'Tookin', 'Weaas', '2025-04-25 00:00:00', '2025-04-25 16:49:00', '2025-04-25 17:49:00', 'Ples blo wok', '\"Refef, Teref\"', 'scheduled', NULL, NULL, NULL, '', 'internal', 0, '2025-04-25 07:50:34', 1, '2025-04-25 07:50:34', NULL, NULL, NULL),
(3, 115, 'National Agriculture Boom', '1. cooking\r\n2. Rooling', '2025-04-25 00:00:00', '2025-04-25 16:03:00', '2025-04-25 18:03:00', 'Location of Ev Two', '[{\"name\":\"Koo\",\"position\":\"Glolo\",\"contacts\":\"445553434\",\"remarks\":\"Wonderfull\"},{\"name\":\"Revelksi\",\"position\":\"Feoreko\",\"contacts\":\"fsdfdk\",\"remarks\":\"tjtor\"},{\"name\":\"Tefsjdif\",\"position\":\"tjirjtie\",\"contacts\":\"tjritjir\",\"remarks\":\"jijfig\"},{\"name\":\"Kululu\",\"position\":\"Tetet\",\"contacts\":\"444445435\",\"remarks\":\"Fffff\"}]', 'cancelled', NULL, '[{\"filename\":\"Wawun Trading.pdf\",\"path\":\"public\\/uploads\\/meeting_attachments\\/1745568312_c8c932e361e13e871697.pdf\"}]', NULL, 'This meeting ended in chaos', 'private', 0, '2025-04-25 08:05:12', 1, '2025-04-25 08:06:12', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `org_settings`
--

CREATE TABLE `org_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `settings_code` varchar(100) NOT NULL,
  `settings_name` varchar(255) NOT NULL,
  `settings` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `org_settings`
--

INSERT INTO `org_settings` (`id`, `settings_code`, `settings_name`, `settings`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'email_setting', 'Email Setting', '{\r\n  \"email_settings\": {\r\n    \"username\": \"test-email@dakoiims.com\",\r\n    \"password\": \"test-email\",\r\n    \"incoming_server\": \"mail.dakoiims.com\",\r\n    \"imap_port\": 993,\r\n    \"pop3_port\": 995,\r\n    \"outgoing_server\": \"mail.dakoiims.com\",\r\n    \"smtp_port\": 465\r\n  }\r\n}\r\n', 7, 7, NULL, '2025-05-13 01:39:25', '2025-05-13 01:39:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_corporate_plan`
--

CREATE TABLE `plans_corporate_plan` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `title` text NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `remarks` text NOT NULL,
  `corp_plan_status` tinyint(4) NOT NULL DEFAULT 1,
  `corp_plan_status_by` int(11) NOT NULL,
  `corp_plan_status_at` datetime DEFAULT NULL,
  `corp_plan_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_corporate_plan`
--

INSERT INTO `plans_corporate_plan` (`id`, `parent_id`, `type`, `code`, `title`, `date_from`, `date_to`, `remarks`, `corp_plan_status`, `corp_plan_status_by`, `corp_plan_status_at`, `corp_plan_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(116, 0, 'plans', 'CP2021', 'Corporate Plan 204', '2025-01-01', '2025-02-01', 'Tifsdfs', 1, 1, '2025-04-08 10:01:07', '', '2025-04-04 15:23:19', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(117, 116, 'overarching_objective', 'OvBj45', 'OverAching Objective', NULL, NULL, 'Reamskrjdj', 1, 1, '2025-04-08 09:59:13', '', '2025-04-07 05:43:36', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(118, 117, 'objective', 'Objj', 'Obectttt', NULL, NULL, 'fsdjfsodj', 1, 0, NULL, '', '2025-04-07 06:00:44', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(119, 118, 'kra', 'WW01', 'Thisi and treee', NULL, NULL, 'Remarjssdf', 1, 1, '2025-04-08 07:56:53', '', '2025-04-07 06:10:21', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(120, 119, 'strategy', 'ST022', 'Stratenre', NULL, NULL, 'Tasjfdosh', 0, 1, '2025-04-08 07:53:27', '', '2025-04-07 06:10:42', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(121, 119, 'strategy', 'ST0212', 'Stretagy3ee', NULL, NULL, 'Remostatrr', 1, 0, NULL, '', '2025-04-08 07:44:52', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(122, 118, 'kra', 'KR2', 'Key Result', NULL, NULL, 'Remarkk', 1, 0, NULL, '', '2025-04-08 07:58:08', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(123, 117, 'objective', 'Obj44', 'Objective 44', NULL, NULL, 'Remarkssees', 1, 0, NULL, '', '2025-04-08 07:58:30', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(125, 116, 'overarching_objective', 'OvBj01', 'Over-Bjective Tree', NULL, NULL, 'faffe', 1, 0, NULL, '', '2025-04-08 09:58:49', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(126, 117, 'objective', 'Ojb66', 'Obj66oy', NULL, NULL, 'Remarkses', 1, 0, NULL, '', '2025-04-08 09:59:36', '1', '2025-05-08 23:01:16', '', '2025-05-08 23:01:16', NULL),
(127, 0, 'plans', 'Copro2022', 'Copplapal', '2022-01-01', '2023-12-01', 'Tojkojfos', 0, 1, '2025-04-08 10:02:00', '', '2025-04-08 10:01:47', '1', '2025-05-08 23:01:01', '1', '2025-05-08 23:01:01', NULL),
(128, 123, 'kra', 'KRRW', 'FKDFOSq', NULL, NULL, 'fmnksdfn', 1, 0, NULL, '', '2025-04-24 03:40:36', '1', '2025-05-08 23:01:16', '', '2025-05-08 23:01:16', NULL),
(129, 128, 'strategy', 'STRWTER', 'FSDFS', NULL, NULL, 'fsdflsn', 1, 0, NULL, '', '2025-04-24 03:40:53', '1', '2025-05-08 23:01:16', '1', '2025-05-08 23:01:16', NULL),
(130, 122, 'strategy', 'ST03', 'This isi Strategy', NULL, NULL, 'Temafdjn', 1, 0, NULL, '', '2025-04-24 06:29:09', '1', '2025-05-08 23:01:16', '', '2025-05-08 23:01:16', NULL),
(131, 0, 'plans', 'CP2025', 'National Agriculture Corporate Plan 2024 - 2028', '2024-01-01', '2028-12-31', '', 1, 0, NULL, '', '2025-05-08 23:02:05', '6', '2025-05-08 23:04:40', '6', NULL, NULL),
(132, 131, 'overarching_objective', 'OAO1', 'Increase productivity through coordination, policy, strategy and planning of sector research,  regulatory standards, agribusiness enterprises, primary producer and industry organizations,  downstream processing, trade and marketing and cross-cutting functions ', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:11:16', '6', '2025-05-08 23:11:16', '6', NULL, NULL),
(133, 131, 'overarching_objective', 'OAO2', 'Improve quality of rural life by transforming communities through coordination and technical advice  on land use, production systems, product development, enterprise management and  commercialized value chain participation ', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:11:44', '6', '2025-05-08 23:11:44', '6', NULL, NULL),
(134, 131, 'overarching_objective', 'OAO3', 'Improve performance by coordinating natural disaster response and recovery in the four regions.', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:11:59', '6', '2025-05-08 23:11:59', '6', NULL, NULL),
(135, 132, 'objective', 'OBJ1', ' Increase productivity, improve scale of production, and enhance competitiveness of agricultural  products in the market.  ', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:12:35', '6', '2025-05-08 23:12:35', '', NULL, NULL),
(136, 132, 'objective', 'OBJ2', 'Improve infrastructure network including roads, bridges, airstrips, transport networks…etc. for better  access to markets.  ', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:13:00', '6', '2025-05-08 23:13:00', '', NULL, NULL),
(137, 133, 'objective', 'OBJ2.2', 'Encourage agriculture commodity investments and exports. ', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:13:50', '6', '2025-05-08 23:13:50', '', NULL, NULL),
(138, 135, 'kra', 'KRA1.1', 'Relevant policies to increase the  efficiency and scale of production,  productivity, and markets for  commercial agriculture  downstream processing, Value  adding and value chains  developed.', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:14:48', '6', '2025-05-08 23:14:48', '', NULL, NULL),
(139, 135, 'kra', 'KRA1.2', 'Appropriate policies for  rehabilitation and revival of large – scale run down plantations and  estates developed.', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:15:09', '6', '2025-05-08 23:15:09', '', NULL, NULL),
(140, 138, 'strategy', 'ST1.1.1', 'To gather information  through surveys and  consultation with  relevant stakeholders.  ', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:15:29', '6', '2025-05-08 23:15:29', '', NULL, NULL),
(141, 138, 'strategy', 'ST1.1.2', 'Compile and analyses  information and data  gathered from  consultations.', NULL, NULL, '', 1, 0, NULL, '', '2025-05-08 23:15:48', '6', '2025-05-08 23:15:48', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_mtdp`
--

CREATE TABLE `plans_mtdp` (
  `id` int(11) NOT NULL,
  `abbrev` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `remarks` text NOT NULL,
  `mtdp_status` tinyint(4) NOT NULL DEFAULT 1,
  `mtdp_status_by` int(11) NOT NULL,
  `mtdp_status_at` datetime DEFAULT NULL,
  `mtdp_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp`
--

INSERT INTO `plans_mtdp` (`id`, `abbrev`, `title`, `date_from`, `date_to`, `remarks`, `mtdp_status`, `mtdp_status_by`, `mtdp_status_at`, `mtdp_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'MTDEE', 'MDASEE', '2025-04-01', '2025-04-18', 'DAfafji', 0, 4, '2025-05-08 11:20:23', 'dfsdf', '2025-04-07 01:39:53', '1', '2025-05-08 12:31:50', '1', '2025-05-08 12:31:50', NULL),
(2, 'GGDODee', 'GKOLO', '2001-01-01', '2003-12-12', 'dasdjaspgggh', 0, 4, '2025-05-08 11:20:32', 'daasd', '2025-04-08 19:17:16', '1', '2025-05-08 12:31:45', '1', '2025-05-08 12:31:45', NULL),
(3, 'MTDPIV', 'Medium Term Development Plan Four', '2022-01-01', '2027-12-31', 'MTDPIV', 1, 4, '2025-05-08 11:04:39', '', '2025-05-08 11:04:39', '4', '2025-05-08 11:04:39', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_mtdp_dip`
--

CREATE TABLE `plans_mtdp_dip` (
  `id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_code` varchar(20) NOT NULL,
  `dip_title` varchar(255) NOT NULL,
  `dip_remarks` text NOT NULL,
  `investments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `kras` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `strategies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `indicators` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `dip_status` tinyint(4) NOT NULL DEFAULT 1,
  `dip_status_by` int(11) NOT NULL,
  `dip_status_at` datetime DEFAULT NULL,
  `dip_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_dip`
--

INSERT INTO `plans_mtdp_dip` (`id`, `mtdp_id`, `spa_id`, `dip_code`, `dip_title`, `dip_remarks`, `investments`, `kras`, `strategies`, `indicators`, `dip_status`, `dip_status_by`, `dip_status_at`, `dip_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 'DIP1.2', 'Dip One two', '', '[]', '[]', '[]', '[]', 1, 1, '2025-04-07 03:00:04', '', '2025-04-07 03:00:04', '1', '2025-05-08 12:31:50', '', '2025-05-08 12:31:50', NULL),
(2, 1, 1, 'DIP1.2', 'Dip One two', 'REmeme', '[{\"item\":\"Iventisdfm\",\"amount\":20,\"year\":2020,\"funding\":\"FFF\"}]', '[{\"description\":\"KRAone\",\"period\":\"2020-2023\"},{\"description\":\"KRAtwo\",\"period\":\"2020-2021\"}]', '[{\"description\":\"Snake ron\"}]', '[{\"name\":\"MarkMAkr\",\"target\":\"30\",\"year\":2024}]', 1, 1, '2025-04-07 03:31:41', '', '2025-04-07 03:31:41', '1', '2025-05-08 12:31:50', '1', '2025-05-08 12:31:50', NULL),
(3, 1, 1, 'DIP3', 'DIP-Cooking', 'Remekkka', '[{\"item\":\"Invet\",\"amount\":20,\"year\":2024,\"funding\":\"Gogo\"},{\"item\":\"Invettwo\",\"amount\":30,\"year\":2021,\"funding\":\"Fundso\"}]', '[{\"description\":\"KRA-One\",\"period\":\"2021-2023\"},{\"description\":\"KRA-Two\",\"period\":\"2020-2021\"}]', '[{\"description\":\"Stratex\"},{\"description\":\"StackStack\"}]', '[{\"name\":\"IndexOne\",\"target\":\"40\",\"year\":2024},{\"name\":\"Indimex\",\"target\":\"20\",\"year\":2025}]', 1, 1, '2025-04-07 04:19:47', '', '2025-04-07 04:18:00', '1', '2025-05-08 12:31:50', '1', '2025-05-08 12:31:50', NULL),
(4, 2, 4, 'DIP101', 'DIPDIP', '', '[]', '[]', '[]', '[]', 1, 1, '2025-04-08 19:32:14', '', '2025-04-08 19:32:14', '1', '2025-05-08 12:31:45', '', '2025-05-08 12:31:45', NULL),
(5, 2, 4, 'DIP101', 'DIPDIP', '', '[]', '[]', '[]', '[]', 1, 1, '2025-04-08 19:59:57', '', '2025-04-08 19:59:57', '1', '2025-05-08 12:31:45', '', '2025-05-08 12:31:45', NULL),
(6, 2, 3, 'DIP1.3', 'Dip One three', 'dadasda', '[{\"item\":\"Investim\",\"amount\":40,\"year\":2024,\"funding\":\"Gogo\"}]', '[{\"description\":\"KRA22\",\"period\":\"2024-2023\"}]', '[]', '[]', 0, 1, '2025-04-24 06:02:22', 'dasdsa', '2025-04-22 06:09:20', '1', '2025-05-08 12:31:45', '1', '2025-05-08 12:31:45', NULL),
(7, 2, 3, 'DIP1.3', 'Dip One three', 'czxcaf', '[]', '[]', '[]', '[]', 1, 1, '2025-04-24 00:34:11', '', '2025-04-24 00:34:11', '1', '2025-05-08 12:31:45', '', '2025-05-08 12:31:45', NULL),
(8, 2, 3, 'DIP1.34', 'Dip One three four', '', '[{\"item\":\"asda\",\"amount\":233,\"year\":2024,\"funding\":\"FOFO\"}]', '[{\"description\":\"KRA Frefenf\",\"period\":\"2024-2025\"}]', '[{\"description\":\"Strategies Tragis\"}]', '[{\"name\":\"Insi\",\"target\":\"30\",\"year\":2025}]', 1, 1, '2025-04-24 00:40:10', '', '2025-04-24 00:40:10', '1', '2025-05-08 12:31:45', '', '2025-05-08 12:31:45', NULL),
(9, 2, 3, 'DIP1.35', 'Dip One three five', 'Fronkoko', '[{\"item\":\"asda\",\"amount\":233,\"year\":2024,\"funding\":\"FOFO\"},{\"item\":\"Dafa\",\"amount\":244,\"year\":2024,\"funding\":\"GGO\"},{\"item\":\"Fogko\",\"amount\":29,\"year\":2023,\"funding\":\"ROko\"}]', '[{\"description\":\"KRA Frefenf\",\"period\":\"2024-2025\"},{\"description\":\"KRF\",\"period\":\"2024-2026\"},{\"description\":\"Gomodi\",\"period\":\"2024-2027\"}]', '[{\"description\":\"Strategies Tragis\"},{\"description\":\"Style Mangi\"},{\"description\":\"MoreMex\"},{\"description\":\"Steampot\"}]', '[{\"name\":\"Insi\",\"target\":\"30\",\"year\":2025},{\"name\":\"Indexfo\",\"target\":\"20\",\"year\":2026},{\"name\":\"Inforex\",\"target\":\"10\",\"year\":2023},{\"name\":\"Infrefre\",\"target\":\"14\",\"year\":2022}]', 1, 1, '2025-04-24 00:41:39', '', '2025-04-24 00:41:39', '1', '2025-05-08 12:31:45', '1', '2025-05-08 12:31:45', NULL),
(10, 3, 5, 'DIP1.1', 'Commercial Agriculture and Livestock Developement', '', '[]', '[]', '[]', '[]', 1, 4, '2025-05-08 11:07:28', '', '2025-05-08 11:07:28', '4', '2025-05-08 11:07:28', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_mtdp_indicators`
--

CREATE TABLE `plans_mtdp_indicators` (
  `id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_id` int(11) NOT NULL,
  `investment_id` int(11) NOT NULL,
  `kra_id` int(11) NOT NULL,
  `strategies_id` int(11) NOT NULL,
  `indicator` text NOT NULL,
  `source` varchar(255) NOT NULL,
  `baseline` varchar(255) NOT NULL,
  `year_one` varchar(255) NOT NULL,
  `year_two` varchar(255) NOT NULL,
  `year_three` varchar(255) NOT NULL,
  `year_four` varchar(255) NOT NULL,
  `year_five` varchar(255) NOT NULL,
  `indicators_status` tinyint(4) NOT NULL DEFAULT 1,
  `indicators_status_by` int(11) NOT NULL,
  `indicators_status_at` datetime DEFAULT NULL,
  `indicators_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_indicators`
--

INSERT INTO `plans_mtdp_indicators` (`id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `investment_id`, `kra_id`, `strategies_id`, `indicator`, `source`, `baseline`, `year_one`, `year_two`, `year_three`, `year_four`, `year_five`, `indicators_status`, `indicators_status_by`, `indicators_status_at`, `indicators_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 3, 7, 10, 10, 1, 1, 'Indiccations', 'Fodo', 'Bassssed', 'FAFofsd', 'kfnksdfn', 'lnfksdn', 'mfksdk', 'fsdkfn', 1, 1, '2025-05-05 12:53:49', '', '2025-05-05 12:53:49', '1', '2025-05-08 12:31:45', '1', '2025-05-08 12:31:45', NULL),
(2, 2, 3, 7, 10, 10, 1, 1, 'Indicacmdsk', 'Sourcess', '', 'Yeafo 1', 'Yodans 2', 'ofjosfndo 3', 'Yfsdfn 4', 'Yaiafb 5', 1, 1, '2025-05-05 12:57:19', '', '2025-05-05 12:57:19', '1', '2025-05-08 12:31:45', '', '2025-05-08 12:31:45', NULL),
(3, 2, 3, 7, 10, 10, 1, 1, 'Incaionsfdsfn', 'Sososo', 'Baselinerrrr', 'Yesf 1', 'Y2 ', 'Y3 ', 'Y 4', 'Y 5', 1, 1, '2025-05-05 13:04:57', '', '2025-05-05 13:04:57', '1', '2025-05-08 12:31:45', '', '2025-05-08 12:31:45', NULL),
(4, 3, 5, 10, 12, 12, 2, 4, 'Total Coca Production (000 tonnes)', 'Cocoa Board', '37.4', '42.9', '45.9', '50.5', '56.2', '63.0', 1, 4, '2025-05-08 11:19:25', '', '2025-05-08 11:19:25', '4', '2025-05-08 11:19:25', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_mtdp_investments`
--

CREATE TABLE `plans_mtdp_investments` (
  `id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `sa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `dip_link_dip_id` int(11) NOT NULL,
  `investment` text NOT NULL,
  `year_one` decimal(18,2) NOT NULL DEFAULT 0.00,
  `year_two` decimal(18,2) NOT NULL DEFAULT 0.00,
  `year_three` decimal(18,2) NOT NULL DEFAULT 0.00,
  `year_four` decimal(18,2) NOT NULL DEFAULT 0.00,
  `year_five` decimal(18,2) NOT NULL DEFAULT 0.00,
  `funding_sources` text NOT NULL,
  `investment_status` tinyint(4) NOT NULL DEFAULT 1,
  `investment_status_by` int(11) NOT NULL,
  `investment_status_at` datetime DEFAULT NULL,
  `investment_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_investments`
--

INSERT INTO `plans_mtdp_investments` (`id`, `mtdp_id`, `spa_id`, `sa_id`, `dip_id`, `dip_link_dip_id`, `investment`, `year_one`, `year_two`, `year_three`, `year_four`, `year_five`, `funding_sources`, `investment_status`, `investment_status_by`, `investment_status_at`, `investment_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(10, 2, 3, 10, 7, 8, 'Good Invest', 200.00, 300.00, 400.00, 500.00, 600.00, 'Fund Source', 1, 1, '2025-05-05 02:14:04', '', '2025-05-05 02:14:04', '1', '2025-05-08 12:31:45', '1', '2025-05-08 12:31:45', NULL),
(11, 3, 5, 11, 10, 10, 'Oil Palm Development Program', 30000000.00, 30000000.00, 30000000.00, 30000000.00, 30000000.00, 'GoPNG', 1, 4, '2025-05-08 11:11:43', '', '2025-05-08 11:11:43', '4', '2025-05-08 11:11:43', '', NULL, NULL),
(12, 3, 5, 12, 10, 10, 'National Cocoa Development Program', 2000000.00, 15000000.00, 15000000.00, 15000000.00, 15000000.00, 'GoPNG/PPP', 1, 4, '2025-05-08 11:13:08', '', '2025-05-08 11:13:08', '4', '2025-05-08 11:13:08', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_mtdp_kra`
--

CREATE TABLE `plans_mtdp_kra` (
  `id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_id` int(11) NOT NULL,
  `investment_id` int(11) NOT NULL,
  `kpi` text NOT NULL,
  `year_one` varchar(255) NOT NULL,
  `year_two` varchar(255) NOT NULL,
  `year_three` varchar(255) NOT NULL,
  `year_four` varchar(255) NOT NULL,
  `year_five` varchar(255) NOT NULL,
  `responsible_agencies` varchar(255) NOT NULL,
  `kra_status` tinyint(4) NOT NULL DEFAULT 1,
  `kra_status_by` int(11) NOT NULL,
  `kra_status_at` datetime DEFAULT NULL,
  `kra_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_kra`
--

INSERT INTO `plans_mtdp_kra` (`id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `investment_id`, `kpi`, `year_one`, `year_two`, `year_three`, `year_four`, `year_five`, `responsible_agencies`, `kra_status`, `kra_status_by`, `kra_status_at`, `kra_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 3, 7, 10, 10, 'KPI Number Wan', 'Enforce legistation', '12', 'Enforce legistation', 'Enforce legistation', 'Enforce legistation', 'DAL, OPIC', 1, 1, '2025-05-05 02:31:51', 'Re-Activiates', '2025-05-05 02:30:46', '1', '2025-05-08 12:31:45', '1', '2025-05-08 12:31:45', NULL),
(2, 3, 5, 10, 12, 12, 'Number of Cocoa Plantations rehabilitated and/or planted', '5', '20', '20', '20', '20', 'PNGCB', 1, 4, '2025-05-08 11:14:18', '', '2025-05-08 11:14:18', '4', '2025-05-08 11:14:18', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_mtdp_spa`
--

CREATE TABLE `plans_mtdp_spa` (
  `id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `spa_status` tinyint(4) NOT NULL DEFAULT 1,
  `spa_status_by` int(11) NOT NULL,
  `spa_status_at` datetime DEFAULT NULL,
  `spa_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_spa`
--

INSERT INTO `plans_mtdp_spa` (`id`, `mtdp_id`, `code`, `title`, `remarks`, `spa_status`, `spa_status_by`, `spa_status_at`, `spa_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(5, 3, 'SPA1', 'Strategic Economic Investment', '', 1, 4, '2025-05-08 11:06:46', '', '2025-05-08 11:06:46', '4', '2025-05-08 11:06:46', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_mtdp_specific_area`
--

CREATE TABLE `plans_mtdp_specific_area` (
  `id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_code` varchar(20) NOT NULL,
  `sa_title` varchar(255) NOT NULL,
  `sa_remarks` text NOT NULL,
  `sa_status` tinyint(4) NOT NULL DEFAULT 1,
  `sa_status_by` int(11) NOT NULL,
  `sa_status_at` datetime DEFAULT NULL,
  `sa_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_specific_area`
--

INSERT INTO `plans_mtdp_specific_area` (`id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_code`, `sa_title`, `sa_remarks`, `sa_status`, `sa_status_by`, `sa_status_at`, `sa_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(11, 3, 5, 10, 'SA1.1.1', 'Oil Palm', '', 1, 4, '2025-05-08 11:09:52', '', '2025-05-08 11:09:52', '4', '2025-05-08 11:09:52', '', NULL, NULL),
(12, 3, 5, 10, 'SA1.1.3', 'Cocoa', '', 1, 4, '2025-05-08 11:10:08', '', '2025-05-08 11:10:08', '4', '2025-05-08 11:10:08', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_mtdp_strategies`
--

CREATE TABLE `plans_mtdp_strategies` (
  `id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_id` int(11) NOT NULL,
  `investment_id` int(11) NOT NULL,
  `kra_id` int(11) NOT NULL,
  `strategy` text NOT NULL,
  `policy_reference` varchar(255) NOT NULL,
  `strategies_status` tinyint(4) NOT NULL DEFAULT 1,
  `strategies_status_by` int(11) NOT NULL,
  `strategies_status_at` datetime DEFAULT NULL,
  `strategies_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_strategies`
--

INSERT INTO `plans_mtdp_strategies` (`id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `investment_id`, `kra_id`, `strategy`, `policy_reference`, `strategies_status`, `strategies_status_by`, `strategies_status_at`, `strategies_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 3, 7, 10, 10, 1, 'This is the Strategyicic', 'POL Refff', 1, 1, '2025-05-05 12:41:37', 'ogjojgi', '2025-05-05 12:41:08', '1', '2025-05-08 12:31:45', '1', '2025-05-08 12:31:45', NULL),
(2, 3, 5, 10, 12, 12, 2, 'Establish regional budwood gardens, seed garden and nurseries', 'Cocoa Industry Strategic Plan 2016 - 2025', 1, 4, '2025-05-08 11:15:50', '', '2025-05-08 11:15:50', '4', '2025-05-08 11:15:50', '', NULL, NULL),
(3, 3, 5, 10, 12, 12, 2, 'Cocoa Quality Improvement and Assurance', 'Cocoa Industry Strategic Plan 2016 - 2025', 1, 4, '2025-05-08 11:16:47', '', '2025-05-08 11:16:47', '4', '2025-05-08 11:16:47', '', NULL, NULL),
(4, 3, 5, 10, 12, 12, 2, 'Distribute Cocoa Seedlings to Cocoa Farmers', 'Cocoa Industry Strategic Plan 2016 - 2025', 1, 4, '2025-05-08 11:17:19', '', '2025-05-08 11:17:19', '4', '2025-05-08 11:17:19', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans_nasp`
--

CREATE TABLE `plans_nasp` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` varchar(20) NOT NULL COMMENT 'type = plans, apas,  dips, specific_areas, objectives, outputs, indicators',
  `code` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `remarks` text NOT NULL,
  `nasp_status` tinyint(4) NOT NULL DEFAULT 1,
  `nasp_status_by` int(11) NOT NULL,
  `nasp_status_at` datetime DEFAULT NULL,
  `nasp_status_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_nasp`
--

INSERT INTO `plans_nasp` (`id`, `parent_id`, `type`, `code`, `title`, `date_from`, `date_to`, `remarks`, `nasp_status`, `nasp_status_by`, `nasp_status_at`, `nasp_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 0, 'plans', 'NASP2023-2033', 'NASP 2023 to 2033', '2023-01-01', '2033-01-01', 'This is the NASP Plan', 1, 1, NULL, '', '2025-07-08 12:46:29', '1', '2025-07-08 12:46:29', '', NULL, NULL),
(2, 1, 'apas', 'APA01', 'APA One', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:46:46', '1', '2025-07-08 12:46:46', '', NULL, NULL),
(3, 1, 'apas', 'APA02', 'APA TWO', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:47:02', '1', '2025-07-08 12:47:02', '', NULL, NULL),
(4, 2, 'dips', 'DIP01', 'DIP ONE', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:47:15', '1', '2025-07-08 12:47:15', '', NULL, NULL),
(5, 2, 'dips', 'DIP02', 'DIP TWO', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:47:29', '1', '2025-07-08 12:47:29', '', NULL, NULL),
(6, 4, 'specific_areas', 'SPArea01', 'SP Area ONE', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:47:46', '1', '2025-07-08 12:47:46', '', NULL, NULL),
(7, 4, 'specific_areas', 'SPArea02', 'SP Area TWO', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:48:02', '1', '2025-07-08 12:48:02', '', NULL, NULL),
(8, 6, 'objectives', 'OBJ01', 'Objective One', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:48:23', '1', '2025-07-08 12:48:23', '', NULL, NULL),
(9, 6, 'objectives', 'OBJ02', 'Objectov two', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:49:34', '1', '2025-07-08 12:49:34', '', NULL, NULL),
(10, 8, 'outputs', 'OP01', 'Outut ONE', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:49:53', '1', '2025-07-08 12:49:53', '', NULL, NULL),
(11, 8, 'outputs', 'OP02', 'OutPut Two', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:50:04', '1', '2025-07-08 12:50:04', '', NULL, NULL),
(12, 10, 'indicators', 'In01', 'Indicators One', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:50:23', '1', '2025-07-08 12:50:23', '', NULL, NULL),
(13, 10, 'indicators', 'In02', 'Indicators TWO', NULL, NULL, '', 1, 1, NULL, '', '2025-07-08 12:50:35', '1', '2025-07-08 12:50:35', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE `proposal` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `action_officer_id` int(11) DEFAULT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `total_cost` decimal(15,2) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('pending','submitted','approved','rated') NOT NULL DEFAULT 'pending',
  `status_by` int(11) DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `rating_score` decimal(3,2) DEFAULT NULL,
  `rated_at` datetime DEFAULT NULL,
  `rated_by` int(11) DEFAULT NULL,
  `rate_remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal`
--

INSERT INTO `proposal` (`id`, `workplan_id`, `activity_id`, `supervisor_id`, `action_officer_id`, `province_id`, `district_id`, `date_start`, `date_end`, `total_cost`, `location`, `status`, `status_by`, `status_at`, `status_remarks`, `rating_score`, `rated_at`, `rated_by`, `rate_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 2, 4, 6, 115, 117, '2025-05-13', '2025-05-30', 200.00, 'Wewak', 'rated', 4, '2025-05-14 10:39:09', 'Good work completed', 4.00, '2025-05-14 10:39:09', 4, 'This is an Awesome job!!', '2025-05-08 15:42:40', 4, '2025-05-14 10:39:09', 4, NULL, NULL),
(2, 1, 1, 4, 6, 115, 117, '2025-06-01', '2025-06-19', 500.00, 'Maprik', 'approved', 4, '2025-05-14 07:32:41', 'Very wonderful work', NULL, NULL, NULL, NULL, '2025-05-08 20:10:59', 4, '2025-05-14 07:32:41', 4, NULL, NULL),
(3, 1, 3, 4, 6, 115, 117, '2025-05-15', '2025-05-24', 700.00, 'Wosera ', 'approved', 4, '2025-05-14 09:46:47', 'Good work', NULL, NULL, NULL, NULL, '2025-05-08 20:12:30', 4, '2025-05-14 09:46:47', 4, NULL, NULL),
(4, 1, 1, 4, 6, 115, 117, '2025-05-16', '2025-05-28', 3000.00, '1st Training', 'submitted', 6, '2025-05-09 15:23:44', 'Activity submitted for supervision by action officer.', NULL, NULL, NULL, NULL, '2025-05-09 15:18:28', 4, '2025-05-09 15:23:44', 6, NULL, NULL),
(5, 1, 5, 4, NULL, 168, 228, '2025-06-05', '2025-06-21', 50000.00, 'Pagwi', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-13 15:48:30', 4, '2025-07-08 17:08:52', 1, NULL, NULL),
(6, 1, 3, 4, NULL, 174, 205, '2025-05-16', '2025-05-30', 400.00, 'Unspec Location', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-13 15:58:08', 4, '2025-07-08 17:09:18', 1, NULL, NULL),
(7, 5, 9, 11, 12, 168, 229, '2025-07-28', '2025-08-10', 5000.00, 'Mandi Village', 'rated', 7, '2025-07-25 14:00:08', 'Wonderful work', 5.00, '2025-07-25 14:00:08', 7, 'This is an excellent job', '2025-07-25 13:20:30', 11, '2025-07-25 14:00:08', 7, NULL, NULL),
(8, 5, 13, 13, 14, 168, 229, '2025-07-30', '2025-08-09', 5000.00, 'Mandi Village', 'rated', 7, '2025-07-25 14:59:17', 'Good job', 5.00, '2025-07-25 14:59:17', 7, 'Beautiful work', '2025-07-25 14:40:17', 13, '2025-07-25 14:59:17', 7, NULL, NULL),
(9, 5, 9, 11, 4, 168, 229, '2025-08-22', '2025-08-30', 5000.00, 'Mandi Village', 'rated', 7, '2025-08-06 16:27:54', 'Work 60% coplete', 4.00, '2025-08-06 16:27:54', 7, 'Good work', '2025-08-06 16:07:23', 11, '2025-08-06 16:27:54', 7, NULL, NULL),
(10, 5, 9, 11, 4, 168, 229, '2025-08-14', '2025-08-22', 7000.00, 'Mandi Village', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-06 16:08:51', 11, '2025-08-06 16:08:51', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `remarks`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Northern Region', 'This is Notern', 7, 7, NULL, '2025-05-13 01:23:48', '2025-05-13 01:24:13', '2025-05-13 11:24:13'),
(2, 'Northern Region', 'This is north', 6, 6, NULL, '2025-05-13 23:49:59', '2025-05-13 23:49:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `region_province_link`
--

CREATE TABLE `region_province_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `region_id` int(11) UNSIGNED NOT NULL,
  `province_id` int(11) UNSIGNED NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `region_province_link`
--

INSERT INTO `region_province_link` (`id`, `region_id`, `province_id`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 115, 6, 6, NULL, '2025-05-13 23:50:10', '2025-05-13 23:50:10', NULL),
(2, 2, 168, 1, 1, NULL, '2025-06-23 01:48:42', '2025-06-23 01:48:42', NULL),
(3, 2, 167, 1, 1, NULL, '2025-06-23 01:48:42', '2025-06-23 01:48:42', NULL),
(4, 2, 191, 1, 1, NULL, '2025-06-23 01:48:42', '2025-06-23 01:48:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sme`
--

CREATE TABLE `sme` (
  `id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `llg_id` int(11) NOT NULL,
  `village_name` varchar(255) DEFAULT NULL,
  `sme_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `gps_coordinates` varchar(100) DEFAULT NULL,
  `contact_details` text DEFAULT NULL,
  `logo_filepath` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sme`
--

INSERT INTO `sme` (`id`, `province_id`, `district_id`, `llg_id`, `village_name`, `sme_name`, `description`, `gps_coordinates`, `contact_details`, `logo_filepath`, `status`, `status_at`, `status_by`, `status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(3, 118, 119, 120, 'Wanaku Village', 'Cool SME ', 'Bus Act 1\r\nBus Act 2\r\nBus Act 3\r\nBus Act 4', '-3.574620, 143.67766', '724444\r\nemail@email.com', 'uploads/sme_logos/1745487466_3defaa98b5fd85758c29.jpg', 'active', '2025-04-24 09:08:01', 1, NULL, '2025-04-24 09:08:01', NULL, '2025-04-24 09:38:21', NULL, NULL, NULL),
(4, 118, 119, 120, 'Wanaku Village', 'Cool SME ', 'Bus Act', '-3.574620, 143.67766', '55554435', NULL, 'active', '2025-04-24 09:22:24', 1, 'Actorm', '2025-04-24 09:10:48', NULL, '2025-04-24 09:22:24', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sme_staff`
--

CREATE TABLE `sme_staff` (
  `id` int(11) NOT NULL,
  `sme_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `dobirth` date DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `contacts` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `id_photo_path` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sme_staff`
--

INSERT INTO `sme_staff` (`id`, `sme_id`, `fname`, `lname`, `gender`, `dobirth`, `designation`, `contacts`, `remarks`, `id_photo_path`, `status`, `status_at`, `status_by`, `status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 4, 'Testas', 'Cook Mix', 'male', '2002-04-02', 'Cool Boss', '4443334', 'MyNotes', 'uploads/sme_staff_photos/1745488102_2727f9f94be223c569c4.jpg', 'active', '2025-04-24 09:48:22', 1, NULL, '2025-04-24 09:48:22', NULL, '2025-04-24 09:55:46', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` text NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `dobirth` date DEFAULT NULL,
  `place_birth` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `employee_number` varchar(100) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `grade` varchar(100) DEFAULT NULL,
  `report_to_id` int(11) DEFAULT NULL,
  `is_evaluator` tinyint(1) NOT NULL DEFAULT 0,
  `is_supervisor` tinyint(1) NOT NULL DEFAULT 0,
  `commodity_id` int(11) DEFAULT NULL,
  `role` enum('admin','supervisor','user','guest','commodity') NOT NULL,
  `joined_date` date DEFAULT NULL,
  `id_photo_filepath` varchar(255) DEFAULT NULL,
  `user_status` tinyint(1) DEFAULT 1,
  `user_status_remarks` text NOT NULL,
  `user_status_at` datetime DEFAULT NULL,
  `user_status_by` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ucode`, `password`, `email`, `phone`, `fname`, `lname`, `gender`, `dobirth`, `place_birth`, `address`, `employee_number`, `branch_id`, `designation`, `grade`, `report_to_id`, `is_evaluator`, `is_supervisor`, `commodity_id`, `role`, `joined_date`, `id_photo_filepath`, `user_status`, `user_status_remarks`, `user_status_at`, `user_status_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 'ADM_67ec813a2ab922.26957702', '$2y$10$kR0V6MVKURpxKolBKxsP6.9vCTiC82EBJ8C4fnanSQB4FXcLWA/VG', 'aitapeitu@gmail.com', '6677886', 'Aitapeti', 'ITUs', 'male', '1997-07-01', '', '', '', 0, '', '', 0, 0, 0, 0, 'admin', '0000-00-00', 'public/uploads/profile/1_1748317968.jpg', 1, 'User activated by Dakoii Nols on 2025-05-13 15:09:45', '2025-05-13 15:09:45', 8, 0, 1, '2025-04-01 14:13:46', '2025-08-06 07:40:42', NULL, NULL),
(4, 'AMIS_67ef6e7676d61', '$2y$10$TKbUTROKDmCAE8C8SlKzTuD66l56Mpi499NobOXxk0s/fmfdPmIk.', 'testa@dakoiims.com', '2534645', 'Testa', 'Mangi', 'male', '1983-05-07', '', '', '', 116, '', '', 0, 0, 1, 0, 'user', '2025-05-07', NULL, 1, 'DAsdasjo', '2025-04-07 01:18:16', 1, 1, 1, '2025-04-03 19:30:41', '2025-08-06 08:04:39', NULL, NULL),
(5, 'AMIS_680e44ea5ba81', '$2y$10$Arnf33oxXAYfGTT5T7WgK.1opvuClHNZGLfFm/ZS0oj.KPdlzRaTu', 'anziinols@gmail.com', '2534645', 'Anzii', 'Ori Nols', 'male', '1993-04-29', '', 'RRRRR', '', 116, '', '', 4, 0, 0, 1, 'commodity', '2025-04-30', NULL, 1, '', NULL, 0, 1, 1, '2025-04-27 04:54:04', '2025-07-09 01:43:10', NULL, NULL),
(7, 'AMIS_681c3d1007d5f', '$2y$10$sF5wTQsAi1EZTUJ.Iox75OZ2Bm/HoTJ9M2Gvl1XrpzsT.p0V2q3Py', 'crystalmarimbukie@gmail.com', '', 'Crystal', 'Marim', 'female', '1982-07-01', '', '', '5555', 116, '', '', 0, 1, 0, NULL, 'supervisor', '0000-00-00', NULL, 1, '', NULL, 0, 4, 1, '2025-05-08 05:14:22', '2025-08-06 08:26:38', NULL, NULL),
(11, 'AMIS_6882f631a685a', '$2y$10$73ynJKD8hJCRuqB/J9Raaun2mrdgyO52Bc62ANrXn.7IF1061vaC2', 'vanimoitu@gmail.com', '', 'Vanimo', 'Itu', '', '0000-00-00', '', '', '', 116, '', '', 0, 0, 0, NULL, 'supervisor', '0000-00-00', NULL, 1, '', NULL, 0, 1, 1, '2025-07-25 03:13:36', '2025-08-06 08:02:22', NULL, NULL),
(12, 'AMIS_6882f67ca396f', '$2y$10$V0l96MhdcUIZO.8S4LBQ1.JNFpuPt4YSoXb8EVO2JVYR3ND75qrZy', 'yonkifishman@gmail.com', '', 'Yonki', 'Fista', '', '0000-00-00', '', '', '', 116, '', '', 0, 0, 0, NULL, 'user', '0000-00-00', NULL, 1, '', NULL, 0, 1, 1, '2025-07-25 03:14:36', '2025-07-25 03:14:36', NULL, NULL),
(13, 'AMIS_688305fa0a924', '$2y$10$hbTvxp9NYQp99hqecZQjtunyyDH/JM5fWWf14DBMpqxKODfnbWJ3K', 'kanagat@gmail.com', '', 'Kanagat', 'Alyshbaev', 'male', '0000-00-00', '', '', '', 116, '', '', 0, 0, 0, NULL, 'supervisor', '0000-00-00', NULL, 1, '', NULL, 0, 1, 1, '2025-07-25 06:23:05', '2025-07-25 06:23:05', NULL, NULL),
(14, 'AMIS_688306bb994a6', '$2y$10$U9gyLE0aOB/XdZDH7gv46uSR8RyGKxSlhql727krpDjOCNAiNo89W', 'matifn@gmail.com', '', 'Mohammad', 'Atif', 'male', '0000-00-00', '', '', '', 116, '', '', 13, 0, 0, NULL, 'user', '0000-00-00', 'public/uploads/profile/14_1753417654.png', 1, '', NULL, 0, 1, 1, '2025-07-25 06:24:09', '2025-07-25 06:27:59', NULL, NULL),
(15, 'AMIS_68830e01e7adf', '$2y$10$FgsBSn7SjvWWSwGgR7bov.5fYxITY.so4xLuf/redWfpvU2OzAFte', 'yonkimastafish@gmail.com', '', 'Masta', 'Fisha', 'male', '0000-00-00', '', '', '', 116, '', '', 0, 1, 0, NULL, 'supervisor', '0000-00-00', NULL, 1, '', NULL, 0, 1, 1, '2025-07-25 06:55:41', '2025-07-25 06:55:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplans`
--

CREATE TABLE `workplans` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('draft','in_progress','completed','on_hold','cancelled') DEFAULT 'draft',
  `objectives` longtext DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplans`
--

INSERT INTO `workplans` (`id`, `branch_id`, `title`, `description`, `supervisor_id`, `start_date`, `end_date`, `status`, `objectives`, `remarks`, `is_deleted`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 116, '1st Quarter Workplan', 'Description Edited', 7, '2025-04-08', '2025-04-25', 'in_progress', 'Objecteee', 'Remakdeke', 0, '2025-04-27 22:39:40', 1, '2025-05-13 15:16:54', 8, NULL, NULL),
(2, 116, '2nd Quarter Workplan', 'Tfsdofnslkfnsl', 7, '2025-03-31', '2025-06-28', 'draft', '', 'This work freaken plan', 0, '2025-05-02 18:25:19', 1, '2025-05-08 15:14:44', 4, NULL, NULL),
(3, 116, '3rd Quarter Workplan', 'FSGFDGDFj', 4, '2025-04-30', '2025-05-09', 'draft', 'flsdml;fmgmd', 'REoafsodn', 0, '2025-05-05 14:20:39', 1, '2025-05-08 12:13:16', 5, NULL, NULL),
(4, 116, 'Quarter 4 Workplan', 'This is Qisfn', 4, '2025-04-28', '2025-05-24', 'in_progress', 'Objefsdn', '', 0, '2025-05-08 14:37:22', 4, '2025-05-08 14:37:22', 4, NULL, NULL),
(5, 116, 'Workplan 2025 Mid Year', 'Description', 11, '2025-07-02', '2025-08-29', 'in_progress', 'Objective', '', 0, '2025-07-25 13:16:52', 11, '2025-07-25 13:16:52', 11, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_activities`
--

CREATE TABLE `workplan_activities` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `activity_type` enum('training','inputs','infrastructure','output') NOT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_activities`
--

INSERT INTO `workplan_activities` (`id`, `workplan_id`, `branch_id`, `title`, `description`, `activity_type`, `supervisor_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 116, 'Training for Cocoa Farmers - part', 'Identify the land for cocoa planting ', 'training', 4, '2025-05-06 12:22:38', NULL, '2025-05-13 15:32:27', 8, NULL, NULL),
(2, 1, 116, 'Distribution of Seedlings', 'This is cocoa seedlings', 'inputs', 7, '2025-05-08 11:01:16', 4, '2025-05-08 15:19:27', 4, NULL, NULL),
(3, 1, 116, 'Establishment of Nursery', 'Nursery', 'infrastructure', 4, '2025-05-08 11:21:46', 4, '2025-05-08 20:08:22', 4, NULL, NULL),
(4, 4, 116, 'Cocoa Seedling Selling', 'This is seddling input', 'inputs', 4, '2025-05-08 14:47:39', 4, '2025-05-08 14:52:22', 4, NULL, NULL),
(5, 1, 116, 'Training Cocoa', 'This is the training', 'training', 4, '2025-05-13 15:30:41', 8, '2025-05-13 15:30:41', 8, NULL, NULL),
(6, 1, 116, 'Thisi is the output Actttt', 'This output', 'output', 4, '2025-06-24 12:11:53', 1, '2025-06-24 12:51:31', 1, NULL, NULL),
(7, 1, 1, 'Test Output Activity', 'This is a test output activity to verify the type display', 'output', 4, '2025-06-24 12:52:26', 1, '2025-06-24 13:12:27', 4, NULL, NULL),
(8, 1, 116, 'Activity 25-2025', 'This is training input', 'training', 7, '2025-07-25 11:58:40', 7, '2025-07-25 11:58:40', 7, NULL, NULL),
(9, 5, 116, 'Activity 2025 One - Training', 'Tdsfds', 'training', 11, '2025-07-25 13:17:54', 11, '2025-07-25 13:17:54', 11, NULL, NULL),
(10, 5, 116, 'Actiity 2025 - Inputs', '', 'inputs', 11, '2025-07-25 13:23:14', 11, '2025-07-25 13:23:14', 11, NULL, NULL),
(11, 5, 116, 'Activity 2025 - Infrastructure', '', 'infrastructure', 11, '2025-07-25 13:24:23', 11, '2025-07-25 13:24:23', 11, NULL, NULL),
(12, 5, 116, 'Activity 2025 - Outputs', 'Thisdfd', 'output', 11, '2025-07-25 13:42:20', 11, '2025-07-25 13:42:20', 11, NULL, NULL),
(13, 5, 116, 'Distribution of Fermentery Kits to the MSMEs ', 'Distribution of Fermentery Kits to the MSMEs ', 'inputs', 13, '2025-07-25 14:30:33', 1, '2025-07-25 14:30:33', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_corporate_plan_link`
--

CREATE TABLE `workplan_corporate_plan_link` (
  `id` int(11) NOT NULL,
  `workplan_activity_id` int(11) NOT NULL,
  `corporate_plan_id` int(11) NOT NULL,
  `overarching_objective_id` int(11) DEFAULT NULL,
  `objective_id` int(11) DEFAULT NULL,
  `kra_id` int(11) DEFAULT NULL,
  `strategies_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_corporate_plan_link`
--

INSERT INTO `workplan_corporate_plan_link` (`id`, `workplan_activity_id`, `corporate_plan_id`, `overarching_objective_id`, `objective_id`, `kra_id`, `strategies_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 128, NULL, NULL, NULL, NULL, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL),
(2, 2, 122, NULL, NULL, NULL, NULL, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL),
(3, 1, 116, 117, 123, 128, 129, '2025-05-07 08:10:41', 1, '2025-05-08 22:39:05', NULL, '2025-05-08 22:39:05', NULL),
(4, 1, 116, 117, 118, 122, 130, '2025-05-08 22:50:09', 6, '2025-05-09 00:05:15', NULL, '2025-05-09 00:05:15', NULL),
(5, 1, 131, 132, 135, 138, 140, '2025-05-09 00:09:49', 6, '2025-05-09 00:09:49', NULL, NULL, NULL),
(6, 8, 131, 132, 135, 138, 140, '2025-07-25 11:58:59', 7, '2025-07-25 11:58:59', NULL, NULL, NULL),
(7, 9, 131, 132, 135, 138, 140, '2025-07-25 13:18:29', 11, '2025-07-25 13:18:29', NULL, NULL, NULL),
(8, 10, 131, 132, 135, 138, 141, '2025-07-25 13:23:31', 11, '2025-07-25 13:23:31', NULL, NULL, NULL),
(9, 11, 131, 132, 135, 138, 140, '2025-07-25 13:41:46', 11, '2025-07-25 13:41:46', NULL, NULL, NULL),
(10, 13, 131, 132, 135, 138, 140, '2025-07-25 14:34:09', 1, '2025-07-25 14:34:09', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_infrastructure_activities`
--

CREATE TABLE `workplan_infrastructure_activities` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `proposal_id` int(11) DEFAULT NULL,
  `infrastructure` varchar(255) NOT NULL,
  `gps_coordinates` varchar(100) DEFAULT NULL,
  `infrastructure_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`infrastructure_images`)),
  `infrastructure_files` longtext DEFAULT NULL,
  `signing_sheet_filepath` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_infrastructure_activities`
--

INSERT INTO `workplan_infrastructure_activities` (`id`, `workplan_id`, `activity_id`, `proposal_id`, `infrastructure`, `gps_coordinates`, `infrastructure_images`, `infrastructure_files`, `signing_sheet_filepath`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 3, 3, 'This is the cocoa Nursery House', '-3.978805, 144.398300', '[\"public\\/uploads\\/infrastructure\\/1746699799_08d1fec35840401df194.jpg\",\"public\\/uploads\\/infrastructure\\/1746699799_26eea12e70a3782c433d.jpg\",\"public\\/uploads\\/infrastructure\\/1746699799_05518b7679889899d565.jpg\"]', '[]', 'public/uploads/signing_sheets/1747179905_c332359f3b0977df6e82.pdf', '2025-05-08 20:23:19', 6, '2025-05-14 09:45:05', 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_input_activities`
--

CREATE TABLE `workplan_input_activities` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `proposal_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `input_images` longtext DEFAULT NULL,
  `input_files` longtext DEFAULT NULL,
  `inputs` longtext DEFAULT NULL,
  `gps_coordinates` varchar(255) DEFAULT NULL,
  `signing_sheet_filepath` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_input_activities`
--

INSERT INTO `workplan_input_activities` (`id`, `workplan_id`, `proposal_id`, `activity_id`, `input_images`, `input_files`, `inputs`, `gps_coordinates`, `signing_sheet_filepath`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 1, '[\"public\\/uploads\\/inputs\\/1746686703_e8468b91d9d6d1e3bbad.jpeg\",\"public\\/uploads\\/inputs\\/1746686703_a977ba26faf594db342d.jpg\",\"public\\/uploads\\/inputs\\/1746687617_9f8ccd4d21be6a95ff55.png\",\"public\\/uploads\\/inputs\\/1746687617_70fd19686994aa2ee719.png\"]', '[]', '[{\"name\":\"Seedlings\",\"quantity\":\"20\",\"unit\":\"cups\",\"remarks\":\"Two Villages\"},{\"name\":\"Budwood\",\"quantity\":\"10\",\"unit\":\"trees\",\"remarks\":\"One Farmer\"}]', NULL, NULL, '2025-05-08 16:45:03', 6, '2025-05-08 17:00:17', 6, NULL, NULL),
(2, 1, 1, 2, '[\"public\\/uploads\\/inputs\\/1746699276_442918d83621d7d6351e.jpg\",\"public\\/uploads\\/inputs\\/1746699276_3a30fc1fa09e21b7c12c.jpg\",\"public\\/uploads\\/inputs\\/1746699276_31a6071a8358fb992563.jpg\",\"public\\/uploads\\/inputs\\/1746702172_7ea6026d7794ff7a8a92.jpg\"]', '[]', '[{\"name\":\"Seedlings\",\"quantity\":\"40\",\"unit\":\"poly bags\",\"remarks\":\"Two Villages\"},{\"name\":\"Plants\",\"quantity\":\"20\",\"unit\":\"cups\",\"remarks\":\"One Farmer\"}]', NULL, NULL, '2025-05-08 20:14:36', 6, '2025-05-08 21:02:52', 6, NULL, NULL),
(3, 5, 8, 13, '[\"public\\/uploads\\/inputs\\/1753419034_ca5e473229cace5972f1.jpg\"]', '[]', '[{\"name\":\"FERMENTARY\",\"quantity\":\"10\",\"unit\":\"NO\",\"remarks\":\"SDFJKLDSJFLKD\"}]', '-3,142', 'public/uploads/signing_sheets/1753419034_95b34dcc5dfd1571e85f.jpg', '2025-07-25 14:50:34', 14, '2025-07-25 14:50:34', 14, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_mtdp_link`
--

CREATE TABLE `workplan_mtdp_link` (
  `id` int(11) NOT NULL,
  `workplan_activity_id` int(11) NOT NULL,
  `mtdp_id` int(11) DEFAULT NULL,
  `spa_id` int(11) DEFAULT NULL,
  `dip_id` int(11) DEFAULT NULL,
  `sa_id` int(11) DEFAULT NULL,
  `investment_id` int(11) DEFAULT NULL,
  `kra_id` int(11) DEFAULT NULL,
  `strategies_id` int(11) DEFAULT NULL,
  `indicators_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_mtdp_link`
--

INSERT INTO `workplan_mtdp_link` (`id`, `workplan_activity_id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `investment_id`, `kra_id`, `strategies_id`, `indicators_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL),
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-02 18:25:19', 1, '2025-05-08 22:59:19', NULL, '2025-05-08 22:59:19', NULL),
(3, 1, 2, 3, 7, 10, 10, 1, 1, 2, '2025-05-07 09:17:34', 1, '2025-05-08 22:39:16', NULL, '2025-05-08 22:39:16', NULL),
(4, 1, 2, 3, 7, 10, 10, NULL, NULL, NULL, '2025-05-07 09:18:31', 1, '2025-05-08 22:39:00', NULL, '2025-05-08 22:39:00', NULL),
(5, 3, 3, 5, 10, 12, 12, 2, 4, NULL, '2025-05-08 11:22:51', 4, '2025-05-08 11:22:51', NULL, NULL, NULL),
(6, 1, 3, 5, 10, 12, 12, 2, 4, NULL, '2025-05-08 22:39:37', 6, '2025-05-09 00:10:04', NULL, '2025-05-09 00:10:04', NULL),
(7, 1, 3, 5, 10, 12, 12, 2, 3, NULL, '2025-05-08 22:51:33', 6, '2025-05-09 00:09:58', NULL, '2025-05-09 00:09:58', NULL),
(8, 1, 3, 5, 10, 12, 12, 2, 4, NULL, '2025-05-09 00:13:39', 6, '2025-05-09 00:13:44', NULL, '2025-05-09 00:13:44', NULL),
(9, 1, 3, 5, 10, 12, 12, 2, 3, NULL, '2025-05-09 00:14:08', 6, '2025-05-09 00:14:08', NULL, NULL, NULL),
(10, 1, 3, 5, 10, 12, 12, 2, 2, NULL, '2025-05-27 12:01:54', 8, '2025-05-27 12:01:54', NULL, NULL, NULL),
(11, 8, 3, 5, 10, 12, 12, 2, 2, NULL, '2025-07-25 11:59:04', 7, '2025-07-25 11:59:04', NULL, NULL, NULL),
(12, 9, 3, 5, 10, 12, 12, 2, 2, NULL, '2025-07-25 13:18:34', 11, '2025-07-25 13:18:34', NULL, NULL, NULL),
(13, 10, 3, 5, 10, 12, 12, 2, 3, NULL, '2025-07-25 13:23:35', 11, '2025-07-25 13:23:35', NULL, NULL, NULL),
(14, 12, 3, 5, 10, 12, 12, 2, 3, NULL, '2025-07-25 13:42:30', 11, '2025-07-25 13:42:30', NULL, NULL, NULL),
(15, 13, 3, 5, 10, 12, 12, 2, 3, NULL, '2025-07-25 14:34:20', 1, '2025-07-25 14:34:20', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_nasp_link`
--

CREATE TABLE `workplan_nasp_link` (
  `id` int(11) NOT NULL,
  `workplan_activity_id` int(11) NOT NULL,
  `nasp_id` int(11) NOT NULL,
  `apa_id` int(11) DEFAULT NULL,
  `dip_id` int(11) DEFAULT NULL,
  `specific_area_id` int(11) DEFAULT NULL,
  `objective_id` int(11) DEFAULT NULL,
  `output_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_nasp_link`
--

INSERT INTO `workplan_nasp_link` (`id`, `workplan_activity_id`, `nasp_id`, `apa_id`, `dip_id`, `specific_area_id`, `objective_id`, `output_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 121, NULL, NULL, NULL, NULL, NULL, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL),
(2, 2, 122, NULL, NULL, NULL, NULL, NULL, '2025-05-02 18:25:19', 1, '2025-05-08 22:59:24', NULL, '2025-05-08 22:59:24', NULL),
(3, 1, 117, NULL, NULL, NULL, NULL, NULL, '2025-05-07 07:53:49', 1, '2025-05-08 22:26:58', NULL, '2025-05-08 22:26:58', NULL),
(4, 1, 117, NULL, NULL, NULL, NULL, NULL, '2025-05-07 07:54:07', 1, '2025-05-08 22:26:49', NULL, '2025-05-08 22:26:49', NULL),
(5, 1, 130, 131, 144, 145, 147, 148, '2025-05-08 22:27:06', 6, '2025-05-09 00:13:53', NULL, '2025-05-09 00:13:53', NULL),
(6, 1, 130, 131, 144, 145, 147, 150, '2025-05-09 00:13:58', 6, '2025-07-08 12:43:34', NULL, '2025-07-08 12:43:34', NULL),
(7, 1, 130, 131, 144, 145, 147, 149, '2025-05-27 11:41:43', 8, '2025-07-08 12:43:31', NULL, '2025-07-08 12:43:31', NULL),
(8, 1, 130, 131, 144, 145, 147, 148, '2025-05-27 13:26:25', 1, '2025-07-08 12:43:28', NULL, '2025-07-08 12:43:28', NULL),
(9, 1, 1, 2, 4, 6, 8, 10, '2025-07-08 14:23:30', 1, '2025-07-08 14:23:30', NULL, NULL, NULL),
(10, 1, 1, 2, 4, 6, 8, 11, '2025-07-08 14:23:37', 1, '2025-07-08 14:23:37', NULL, NULL, NULL),
(11, 1, 1, 2, 4, 6, 8, 10, '2025-07-08 14:23:41', 1, '2025-07-08 14:23:46', NULL, '2025-07-08 14:23:46', NULL),
(12, 8, 1, 2, 4, 6, 8, 10, '2025-07-25 11:58:54', 7, '2025-07-25 11:58:54', NULL, NULL, NULL),
(13, 9, 1, 2, 4, 6, 8, 10, '2025-07-25 13:18:17', 11, '2025-07-25 13:18:17', NULL, NULL, NULL),
(14, 10, 1, 2, 4, 6, 8, 11, '2025-07-25 13:23:27', 11, '2025-07-25 13:23:27', NULL, NULL, NULL),
(15, 11, 1, 2, 4, 6, 8, 10, '2025-07-25 13:41:41', 11, '2025-07-25 13:41:41', NULL, NULL, NULL),
(16, 13, 1, 2, 4, 6, 8, 10, '2025-07-25 14:30:51', 1, '2025-07-25 14:30:51', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_others_link`
--

CREATE TABLE `workplan_others_link` (
  `id` int(11) NOT NULL,
  `workplan_activity_id` int(11) NOT NULL,
  `link_type` enum('recurrent','special_project','emergency','other') NOT NULL DEFAULT 'other',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `justification` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `priority_level` enum('low','medium','high','critical') DEFAULT 'medium',
  `expected_outcome` text DEFAULT NULL,
  `target_beneficiaries` text DEFAULT NULL,
  `budget_estimate` decimal(15,2) DEFAULT NULL,
  `duration_months` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive','completed','cancelled') DEFAULT 'active',
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_others_link`
--

INSERT INTO `workplan_others_link` (`id`, `workplan_activity_id`, `link_type`, `title`, `description`, `justification`, `category`, `priority_level`, `expected_outcome`, `target_beneficiaries`, `budget_estimate`, `duration_months`, `start_date`, `end_date`, `status`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(6, 0, 'recurrent', 'Monthly Staff Meetings', 'Regular monthly meetings for staff coordination and planning', 'Essential for maintaining communication and coordination among staff members', 'Administrative', 'medium', 'Improved staff coordination and communication', 'All staff members', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-24 12:30:36', 1, '2025-06-24 12:30:36', NULL, NULL, NULL),
(7, 0, 'recurrent', 'Quarterly Equipment Maintenance', 'Regular maintenance of agricultural equipment and machinery', 'Preventive maintenance to ensure equipment longevity and optimal performance', 'Maintenance', 'high', 'Extended equipment lifespan and reduced downtime', 'Equipment users and farmers', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-24 12:30:36', 1, '2025-06-24 12:30:36', NULL, NULL, NULL),
(8, 0, 'recurrent', 'Annual Budget Planning', 'Yearly budget preparation and financial planning activities', 'Required for proper financial management and resource allocation', 'Financial', 'high', 'Effective budget management and resource allocation', 'Management and finance team', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-24 12:30:36', 1, '2025-06-24 12:30:36', NULL, NULL, NULL),
(9, 0, 'recurrent', 'Seasonal Crop Monitoring', 'Regular monitoring of crop conditions and pest management', 'Critical for early detection of crop issues and timely interventions', 'Agricultural', 'high', 'Improved crop yields and reduced losses', 'Farmers and agricultural officers', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-24 12:30:36', 1, '2025-06-24 12:30:36', NULL, NULL, NULL),
(10, 0, 'recurrent', 'Community Outreach Programs', 'Regular community engagement and awareness programs', 'Important for maintaining community relationships and awareness', 'Community', 'medium', 'Enhanced community engagement and awareness', 'Local communities', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-24 12:30:36', 1, '2025-06-24 12:30:36', NULL, NULL, NULL),
(11, 6, 'other', 'Test Others Link', 'This is a simple test description for the others link.', 'This link is necessary to connect this activity with other important initiatives that support the overall project objectives.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-24 12:43:10', NULL, '2025-06-24 12:46:10', NULL, '2025-06-24 12:46:10', NULL),
(12, 6, 'other', 'This is the others link', 'Description of others link', 'Very others necessary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-24 12:45:00', 1, '2025-06-24 12:46:16', 1, '2025-06-24 12:46:16', NULL),
(13, 6, 'other', 'Offff', 'FSDfdsfd', 'fsdfsdfs', 'Admin', 'medium', '', '', 0.00, 0, '0000-00-00', '0000-00-00', 'active', '', '2025-06-24 12:45:57', NULL, '2025-06-24 12:46:56', NULL, NULL, NULL),
(14, 8, 'other', 'This is Others Link', '', 'Not other link related', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2025-07-25 11:59:24', 7, '2025-07-25 11:59:24', 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_output_activities`
--

CREATE TABLE `workplan_output_activities` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `proposal_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `outputs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`outputs`)),
  `output_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`output_images`)),
  `output_files` longtext DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_location` varchar(255) DEFAULT NULL,
  `beneficiaries` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`beneficiaries`)),
  `total_value` decimal(15,2) DEFAULT NULL,
  `gps_coordinates` varchar(255) DEFAULT NULL,
  `signing_sheet_filepath` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_output_activities`
--

INSERT INTO `workplan_output_activities` (`id`, `workplan_id`, `proposal_id`, `activity_id`, `outputs`, `output_images`, `output_files`, `delivery_date`, `delivery_location`, `beneficiaries`, `total_value`, `gps_coordinates`, `signing_sheet_filepath`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 1, '[{\"item\":\"Cocoa Processing Equipment\",\"description\":\"Industrial cocoa bean processing machine\",\"quantity\":\"2\",\"unit\":\"units\",\"specifications\":\"Model XYZ-2024, 500kg/hour capacity\"},{\"item\":\"Training Manuals\",\"description\":\"Comprehensive cocoa farming guide\",\"quantity\":\"50\",\"unit\":\"copies\",\"specifications\":\"200 pages, full color, English and Tok Pisin\"}]', '[\"public\\/uploads\\/outputs\\/1746686703_e8468b91d9d6d1e3bbad.jpeg\",\"public\\/uploads\\/outputs\\/1746686703_a977ba26faf594db342d.jpg\"]', '[]', '2025-06-15', 'Kokopo Agricultural Center', '[{\"name\":\"Kokopo Farmers Association\",\"contact\":\"John Doe\",\"phone\":\"+675 123 4567\",\"members\":25},{\"name\":\"East New Britain Cocoa Growers\",\"contact\":\"Mary Smith\",\"phone\":\"+675 987 6543\",\"members\":40}]', 75000.00, '-4.352083, 152.263611', 'public/uploads/signing_sheets/1746686703_output_delivery.pdf', 'Equipment delivered and installed successfully. Training conducted for 65 farmers.', '2025-05-08 16:45:03', 6, '2025-05-08 17:00:17', 6, NULL, NULL),
(2, 1, 2, 2, '[{\"item\":\"Improved Seed Varieties\",\"description\":\"High-yield cocoa seedlings\",\"quantity\":\"1000\",\"unit\":\"seedlings\",\"specifications\":\"Trinitario variety, disease-resistant\"},{\"item\":\"Fertilizer Package\",\"description\":\"Organic fertilizer for cocoa cultivation\",\"quantity\":\"50\",\"unit\":\"bags\",\"specifications\":\"25kg bags, NPK 15-15-15\"}]', '[\"public\\/uploads\\/outputs\\/1746699276_442918d83621d7d6351e.jpg\",\"public\\/uploads\\/outputs\\/1746699276_3a30fc1fa09e21b7c12c.jpg\"]', '[]', '2025-07-20', 'Rabaul District Office', '[{\"name\":\"Rabaul Smallholder Farmers\",\"contact\":\"Peter Wilson\",\"phone\":\"+675 456 7890\",\"members\":30}]', 25000.00, '-4.192067, 152.156111', NULL, 'Seedlings distributed to 30 farmers across 5 villages. Follow-up training scheduled.', '2025-05-08 20:14:36', 6, '2025-05-08 21:02:52', 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_training_activities`
--

CREATE TABLE `workplan_training_activities` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `trainers` text DEFAULT NULL,
  `topics` text DEFAULT NULL,
  `trainees` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`trainees`)),
  `training_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`training_images`)),
  `training_files` longtext DEFAULT NULL,
  `gps_coordinates` varchar(255) DEFAULT NULL,
  `signing_sheet_filepath` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_training_activities`
--

INSERT INTO `workplan_training_activities` (`id`, `workplan_id`, `proposal_id`, `activity_id`, `trainers`, `topics`, `trainees`, `training_images`, `training_files`, `gps_coordinates`, `signing_sheet_filepath`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 2, 1, 'Trainer One,\r\nTrainer Two', 'Cocoa Nursery,\r\nCocoa Harvest,', '[{\"name\":\"Farmer One\",\"age\":\"40\",\"gender\":\"Male\",\"phone\":\"776666\",\"email\":\"farmerone@gmail.com\",\"remarks\":\"Best Farmer\"},{\"name\":\"Farmer Two\",\"age\":\"30\",\"gender\":\"Female\",\"phone\":\"444556\",\"email\":\"\",\"remarks\":\"\"},{\"name\":\"Farmer Three\",\"age\":\"51\",\"gender\":\"Female\",\"phone\":\"44456\",\"email\":\"fthree@gmail.com\",\"remarks\":\"active farmer\"}]', '[\"public\\/uploads\\/training\\/1746699634_f12fff30991a35291b0e.jpeg\",\"public\\/uploads\\/training\\/1746699634_064005e71bb5189fe94c.jpg\",\"public\\/uploads\\/training\\/1746702055_a46a1c1367a9c822e82b.jpg\"]', '[]', '-3.57462000, 145.554657', 'public/uploads/signing_sheets/1747170982_22a6725c481a62898cd7.pdf', '2025-05-08 20:20:34', 6, '2025-05-14 07:16:22', 6, NULL, NULL),
(2, 1, 4, 1, 'John Rit', 'Cocoa Seedling', '[{\"name\":\"Farmer One\",\"age\":\"22\",\"gender\":\"Male\",\"phone\":\"4444\",\"email\":\"Emai@gmailc.com\",\"remarks\":\"fsdfs\"},{\"name\":\"Farmer Two\",\"age\":\"40\",\"gender\":\"Female\",\"phone\":\"5555\",\"email\":\"fmale@gmail.com\",\"remarks\":\"ggggg\"}]', '[\"public\\/uploads\\/training\\/1746768175_b1b1349d7c67210d9d1d.png\",\"public\\/uploads\\/training\\/1746768175_e5b25f2ccc52b9ac99bb.jpg\",\"public\\/uploads\\/training\\/1746768175_4417af40dea780c18757.jpg\"]', '[]', NULL, NULL, '2025-05-09 15:22:55', 6, '2025-05-09 15:22:55', 6, NULL, NULL),
(3, 5, 7, 9, 'One Trainer, Two Trainer', 'One tpic two Tpoic', '[{\"name\":\"Farmer\",\"age\":\"45\",\"gender\":\"Male\",\"phone\":\"5556\",\"email\":\"gg@gg.com\",\"remarks\":\"dfsdf\"},{\"name\":\"Cookf\",\"age\":\"34\",\"gender\":\"Female\",\"phone\":\"444\",\"email\":\"\",\"remarks\":\"\"},{\"name\":\"Foodine\",\"age\":\"22\",\"gender\":\"Female\",\"phone\":\"777\",\"email\":\"\",\"remarks\":\"\"}]', '[\"public\\/uploads\\/training\\/1753415339_daf360c6789f6815cd88.png\",\"public\\/uploads\\/training\\/1753415339_54f844f5f17a9fe9d4f6.jpg\",\"public\\/uploads\\/training\\/1753415339_33461e35fc4c63dbf0ca.png\",\"public\\/uploads\\/training\\/1753415339_25be11e6eb6622280f6e.jpg\"]', '[]', '144.993240, 322.423434', 'public/uploads/signing_sheets/1753415339_e8de9ec879a9bb70e40a.pdf', '2025-07-25 13:48:59', 12, '2025-07-25 13:48:59', 12, NULL, NULL),
(4, 5, 9, 9, 'Atif\r\nNoland', 'CAPI\r\nPAPI', '[{\"name\":\"Patu\",\"age\":\"60\",\"gender\":\"Female\",\"phone\":\"766666\",\"email\":\"p@yahoo.com\",\"remarks\":\"Good student\"},{\"name\":\"Rubina\",\"age\":\"70\",\"gender\":\"Female\",\"phone\":\"78888\",\"email\":\"\",\"remarks\":\"\"}]', '[\"public\\/uploads\\/training\\/1754461019_8d0141e07fcfd42a40bf.png\",\"public\\/uploads\\/training\\/1754461019_453ab1336cbd1b54895d.jpg\",\"public\\/uploads\\/training\\/1754461019_e4aa4d8ca8a57b3dfdb2.png\",\"public\\/uploads\\/training\\/1754461019_c5d8335baa3a102c813a.jpg\"]', '[]', '-3,142', 'public/uploads/signing_sheets/1754461019_0136e8c6f413005f7162.pdf', '2025-08-06 16:16:59', 4, '2025-08-06 16:16:59', 4, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adx_country`
--
ALTER TABLE `adx_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agreements`
--
ALTER TABLE `agreements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commodities`
--
ALTER TABLE `commodities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commodity_code` (`commodity_code`);

--
-- Indexes for table `commodity_prices`
--
ALTER TABLE `commodity_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commodity_id` (`commodity_id`),
  ADD KEY `price_date` (`price_date`),
  ADD KEY `market_type` (`market_type`),
  ADD KEY `commodity_id_price_date_market_type` (`commodity_id`,`price_date`,`market_type`);

--
-- Indexes for table `commodity_production`
--
ALTER TABLE `commodity_production`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dakoii_users`
--
ALTER TABLE `dakoii_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gov_structure`
--
ALTER TABLE `gov_structure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `org_settings`
--
ALTER TABLE `org_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans_corporate_plan`
--
ALTER TABLE `plans_corporate_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans_mtdp`
--
ALTER TABLE `plans_mtdp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_spa_id` (`spa_id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

--
-- Indexes for table `plans_mtdp_indicators`
--
ALTER TABLE `plans_mtdp_indicators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans_mtdp_investments`
--
ALTER TABLE `plans_mtdp_investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_spa_id` (`spa_id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

--
-- Indexes for table `plans_mtdp_kra`
--
ALTER TABLE `plans_mtdp_kra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans_mtdp_spa`
--
ALTER TABLE `plans_mtdp_spa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_spa_mtdp` (`mtdp_id`);

--
-- Indexes for table `plans_mtdp_specific_area`
--
ALTER TABLE `plans_mtdp_specific_area`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_spa_id` (`spa_id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

--
-- Indexes for table `plans_mtdp_strategies`
--
ALTER TABLE `plans_mtdp_strategies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans_nasp`
--
ALTER TABLE `plans_nasp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `region_province_link`
--
ALTER TABLE `region_province_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sme`
--
ALTER TABLE `sme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sme_staff`
--
ALTER TABLE `sme_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplans`
--
ALTER TABLE `workplans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_activities`
--
ALTER TABLE `workplan_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_corporate_plan_link`
--
ALTER TABLE `workplan_corporate_plan_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_infrastructure_activities`
--
ALTER TABLE `workplan_infrastructure_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_input_activities`
--
ALTER TABLE `workplan_input_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_mtdp_link`
--
ALTER TABLE `workplan_mtdp_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_nasp_link`
--
ALTER TABLE `workplan_nasp_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_others_link`
--
ALTER TABLE `workplan_others_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_activity_id` (`workplan_activity_id`),
  ADD KEY `idx_link_type` (`link_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `workplan_output_activities`
--
ALTER TABLE `workplan_output_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_id` (`workplan_id`),
  ADD KEY `idx_proposal_id` (`proposal_id`),
  ADD KEY `idx_activity_id` (`activity_id`),
  ADD KEY `idx_delivery_date` (`delivery_date`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `workplan_training_activities`
--
ALTER TABLE `workplan_training_activities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adx_country`
--
ALTER TABLE `adx_country`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `agreements`
--
ALTER TABLE `agreements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `commodity_prices`
--
ALTER TABLE `commodity_prices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `commodity_production`
--
ALTER TABLE `commodity_production`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dakoii_users`
--
ALTER TABLE `dakoii_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gov_structure`
--
ALTER TABLE `gov_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `org_settings`
--
ALTER TABLE `org_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_corporate_plan`
--
ALTER TABLE `plans_corporate_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `plans_mtdp`
--
ALTER TABLE `plans_mtdp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `plans_mtdp_indicators`
--
ALTER TABLE `plans_mtdp_indicators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plans_mtdp_investments`
--
ALTER TABLE `plans_mtdp_investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `plans_mtdp_kra`
--
ALTER TABLE `plans_mtdp_kra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plans_mtdp_spa`
--
ALTER TABLE `plans_mtdp_spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `plans_mtdp_specific_area`
--
ALTER TABLE `plans_mtdp_specific_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `plans_mtdp_strategies`
--
ALTER TABLE `plans_mtdp_strategies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plans_nasp`
--
ALTER TABLE `plans_nasp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `region_province_link`
--
ALTER TABLE `region_province_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sme`
--
ALTER TABLE `sme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sme_staff`
--
ALTER TABLE `sme_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `workplans`
--
ALTER TABLE `workplans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `workplan_activities`
--
ALTER TABLE `workplan_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `workplan_corporate_plan_link`
--
ALTER TABLE `workplan_corporate_plan_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `workplan_infrastructure_activities`
--
ALTER TABLE `workplan_infrastructure_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workplan_input_activities`
--
ALTER TABLE `workplan_input_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `workplan_mtdp_link`
--
ALTER TABLE `workplan_mtdp_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `workplan_nasp_link`
--
ALTER TABLE `workplan_nasp_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `workplan_others_link`
--
ALTER TABLE `workplan_others_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `workplan_output_activities`
--
ALTER TABLE `workplan_output_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workplan_training_activities`
--
ALTER TABLE `workplan_training_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  ADD CONSTRAINT `fk_dip_mtdp` FOREIGN KEY (`mtdp_id`) REFERENCES `plans_mtdp` (`id`);

--
-- Constraints for table `plans_mtdp_spa`
--
ALTER TABLE `plans_mtdp_spa`
  ADD CONSTRAINT `fk_spa_mtdp` FOREIGN KEY (`mtdp_id`) REFERENCES `plans_mtdp` (`id`);

--
-- Constraints for table `workplan_output_activities`
--
ALTER TABLE `workplan_output_activities`
  ADD CONSTRAINT `fk_output_activity` FOREIGN KEY (`activity_id`) REFERENCES `workplan_activities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_output_proposal` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_output_workplan` FOREIGN KEY (`workplan_id`) REFERENCES `workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

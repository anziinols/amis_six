-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2025 at 04:42 PM
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
-- Database: `amis_db`
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

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event` enum('create','read','update','delete') NOT NULL,
  `table_name` varchar(128) NOT NULL,
  `row_pk` varchar(64) NOT NULL,
  `changed_columns` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`changed_columns`)),
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `actor_id` bigint(20) DEFAULT NULL,
  `actor_type` varchar(32) DEFAULT NULL,
  `request_id` char(36) DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `http_method` varchar(10) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `parent_id`, `abbrev`, `name`, `remarks`, `branch_status`, `branch_status_by`, `branch_status_at`, `branch_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 0, 'INFO', 'Information Branch', 'Information Branch', 1, 1, '2025-08-10 18:32:22', 'Initial activation', '2025-08-10 18:32:22', 0, '2025-08-10 18:32:22', 0, NULL, NULL);

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
(1, 'CC1', 'Cocoa', NULL, '#b07611', '2025-08-11 00:17:17', '1', '2025-08-11 00:17:23', '1', NULL, NULL, 0);

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

-- --------------------------------------------------------

--
-- Table structure for table `dakoii_users`
--

CREATE TABLE `dakoii_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `dakoii_user_status` tinyint(1) DEFAULT 1,
  `dakoii_user_status_remarks` text DEFAULT NULL,
  `dakoii_user_status_at` datetime DEFAULT NULL,
  `dakoii_user_status_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dakoii_users`
--

INSERT INTO `dakoii_users` (`id`, `name`, `username`, `password`, `role`, `dakoii_user_status`, `dakoii_user_status_remarks`, `dakoii_user_status_at`, `dakoii_user_status_by`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 'Fred Kenny', 'fkenny', '$2y$10$k23bv9dWfqxK6hl8csxzheUmSDtPUb/b86i8D.HMMtvmA1h2rBzS.', 'admin', 1, NULL, NULL, NULL, '2025-08-10 01:08:19', '2025-08-10 06:24:25', NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `gov_structure`
--

CREATE TABLE `gov_structure` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `json_id` varchar(255) NOT NULL,
  `level` enum('province','district','llg','ward') NOT NULL COMMENT 'province, district, llg, ward',
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `flag_filepath` varchar(255) NOT NULL,
  `map_center` varchar(100) NOT NULL,
  `map_zoom` varchar(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gov_structure`
--

INSERT INTO `gov_structure` (`id`, `parent_id`, `json_id`, `level`, `code`, `name`, `flag_filepath`, `map_center`, `map_zoom`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(23, 0, '01', 'province', '01', 'Western Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(24, 0, '02', 'province', '02', 'Gulf Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(25, 0, '03', 'province', '03', 'Central Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(26, 0, '04', 'province', '04', 'Milne Bay Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(27, 0, '05', 'province', '05', 'Oro Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(28, 0, '06', 'province', '06', 'Southern Highlands Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(29, 0, '07', 'province', '07', 'Southern Highlands Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(30, 0, '08', 'province', '08', 'Enga Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(31, 0, '09', 'province', '09', 'Western Highlands Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(32, 0, '10', 'province', '10', 'Chimbu Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(33, 0, '11', 'province', '11', 'Eastern Highlands Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(34, 0, '12', 'province', '12', 'Morobe Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(35, 0, '13', 'province', '13', 'Madang Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(36, 0, '14', 'province', '14', 'East Sepik', '', '-4.043810,143.270506', '8', '2025-08-10 18:00:42', 1, '2025-08-10 18:30:41', 1, NULL, NULL),
(37, 0, '15', 'province', '15', 'West Sepik Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(38, 0, '16', 'province', '16', 'Manus Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(39, 0, '17', 'province', '17', 'New Ireland Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(40, 0, '18', 'province', '18', 'East New Britain Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(41, 0, '19', 'province', '19', 'West New Britain Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(42, 0, '20', 'province', '20', 'Autonomous Region of Bougainville', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(43, 0, '21', 'province', '21', 'National Capital District', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(44, 0, '22', 'province', '22', 'Hela Province', '', '', '', '2025-08-10 18:00:42', 1, '2025-08-10 18:00:42', 1, NULL, NULL),
(45, 36, '1401', 'district', '1401', 'Ambunti/Drekikier District', '', '', '', '2025-08-10 18:19:19', 1, '2025-08-10 18:31:20', 1, NULL, NULL),
(46, 36, '1402', 'district', '1402', 'Angoram', '', '', '', '2025-08-10 18:19:19', 1, '2025-08-10 18:19:19', 1, NULL, NULL),
(47, 36, '1403', 'district', '1403', 'Maprik', '', '', '', '2025-08-10 18:19:19', 1, '2025-08-10 18:19:19', 1, NULL, NULL),
(48, 36, '1404', 'district', '1404', 'Wewak', '', '', '', '2025-08-10 18:19:19', 1, '2025-08-10 18:19:19', 1, NULL, NULL),
(49, 36, '1405', 'district', '1405', 'Wosera Gawi', '', '', '', '2025-08-10 18:19:19', 1, '2025-08-10 18:19:19', 1, NULL, NULL),
(50, 36, '1406', 'district', '1406', 'Yangoru Saussia', '', '', '', '2025-08-10 18:19:19', 1, '2025-08-10 18:19:19', 1, NULL, NULL),
(51, 45, '140101', 'llg', '140101', 'Ambunti Rural', '', '-4.149215,142.680098', '12', '2025-08-10 18:19:38', 1, '2025-08-10 18:31:36', 1, NULL, NULL),
(52, 45, '140102', 'llg', '140102', 'Dreikikier Rural', '', '', '', '2025-08-10 18:19:38', 1, '2025-08-10 18:19:38', 1, NULL, NULL),
(53, 45, '140103', 'llg', '140103', 'Gawanga Rural', '', '', '', '2025-08-10 18:19:38', 1, '2025-08-10 18:19:38', 1, NULL, NULL),
(54, 45, '140104', 'llg', '140104', 'Tunap/Hustein', '', '', '', '2025-08-10 18:19:38', 1, '2025-08-10 18:19:38', 1, NULL, NULL),
(55, 46, '140205', 'llg', '140205', 'Angoram/Middle Sepik', '', '', '', '2025-08-10 18:19:58', 1, '2025-08-10 18:19:58', 1, NULL, NULL),
(56, 46, '140206', 'llg', '140206', 'Karawari Rural', '', '', '', '2025-08-10 18:19:58', 1, '2025-08-10 18:19:58', 1, NULL, NULL),
(57, 46, '140207', 'llg', '140207', 'Keram Rural', '', '', '', '2025-08-10 18:19:58', 1, '2025-08-10 18:19:58', 1, NULL, NULL),
(58, 46, '140208', 'llg', '140208', 'Marienberg Rural', '', '', '', '2025-08-10 18:19:58', 1, '2025-08-10 18:19:58', 1, NULL, NULL),
(59, 46, '140209', 'llg', '140209', 'Yuat Rural', '', '', '', '2025-08-10 18:19:58', 1, '2025-08-10 18:19:58', 1, NULL, NULL),
(60, 47, '140310', 'llg', '140310', 'Albiges/Mablep Rural', '', '', '', '2025-08-10 18:20:19', 1, '2025-08-10 18:20:19', 1, NULL, NULL),
(61, 47, '140311', 'llg', '140311', 'Bumbita/Muhian Rural', '', '', '', '2025-08-10 18:20:19', 1, '2025-08-10 18:20:19', 1, NULL, NULL),
(62, 47, '140312', 'llg', '140312', 'Maprik/Wora Rural', '', '', '', '2025-08-10 18:20:19', 1, '2025-08-10 18:20:19', 1, NULL, NULL),
(63, 47, '140313', 'llg', '140313', 'Yamil/Tamaui Rural', '', '', '', '2025-08-10 18:20:19', 1, '2025-08-10 18:20:19', 1, NULL, NULL),
(64, 48, '140414', 'llg', '140414', 'Boikin/Dagua Rural', '', '', '', '2025-08-10 18:20:51', 1, '2025-08-10 18:20:51', 1, NULL, NULL),
(65, 48, '140415', 'llg', '140415', 'Turubu Rural LLG', '', '', '', '2025-08-10 18:20:51', 1, '2025-08-10 18:20:51', 1, NULL, NULL),
(66, 48, '140418', 'llg', '140418', 'Wewak Urban', '', '', '', '2025-08-10 18:20:51', 1, '2025-08-10 18:20:51', 1, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_corporate_plan`
--

INSERT INTO `plans_corporate_plan` (`id`, `parent_id`, `type`, `code`, `title`, `date_from`, `date_to`, `remarks`, `corp_plan_status`, `corp_plan_status_by`, `corp_plan_status_at`, `corp_plan_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 0, 'plans', 'COP2023', 'Corporate Plan 2023 ', '2023-07-29', '2027-08-14', '', 1, 0, NULL, '', '2025-08-10 19:42:29', 1, '2025-08-10 19:42:29', NULL, NULL, NULL),
(2, 1, 'overarching_objective', 'OVB1', 'Overaching One', NULL, NULL, '', 1, 0, NULL, '', '2025-08-10 19:43:24', 1, '2025-08-10 19:43:30', 1, NULL, NULL),
(3, 2, 'objective', 'OBJ1', 'Objective One', NULL, NULL, '', 1, 0, NULL, '', '2025-08-10 19:43:52', 1, '2025-08-10 19:43:52', NULL, NULL, NULL),
(4, 3, 'kra', 'KRA1', 'KRA One', NULL, NULL, '', 1, 0, NULL, '', '2025-08-10 19:44:06', 1, '2025-08-10 19:44:06', NULL, NULL, NULL),
(5, 4, 'strategy', 'STG1', 'Strategy One', NULL, NULL, '', 1, 0, NULL, '', '2025-08-10 19:44:22', 1, '2025-08-10 19:44:41', 1, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp`
--

INSERT INTO `plans_mtdp` (`id`, `abbrev`, `title`, `date_from`, `date_to`, `remarks`, `mtdp_status`, `mtdp_status_by`, `mtdp_status_at`, `mtdp_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'MTDPIV', 'Medium Term Development Plan IV', '2022-01-01', '2027-12-31', '', 1, 1, '2025-08-10 19:30:30', '', '2025-08-10 19:30:31', 1, '2025-08-10 19:30:31', NULL, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_dip`
--

INSERT INTO `plans_mtdp_dip` (`id`, `mtdp_id`, `spa_id`, `dip_code`, `dip_title`, `dip_remarks`, `investments`, `kras`, `strategies`, `indicators`, `dip_status`, `dip_status_by`, `dip_status_at`, `dip_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 'DIP1', 'DIP One', '', '[]', '[]', '[]', '[]', 1, 1, '2025-08-10 19:32:35', '', '2025-08-10 19:32:35', 1, '2025-08-10 19:32:35', NULL, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_indicators`
--

INSERT INTO `plans_mtdp_indicators` (`id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `investment_id`, `kra_id`, `strategies_id`, `indicator`, `source`, `baseline`, `year_one`, `year_two`, `year_three`, `year_four`, `year_five`, `indicators_status`, `indicators_status_by`, `indicators_status_at`, `indicators_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 'Indicator One', 'Indix', '40', '42', '44', '45', '47', '59', 1, 1, '2025-08-10 19:35:41', '', '2025-08-10 19:35:41', 1, '2025-08-10 19:35:41', NULL, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_investments`
--

INSERT INTO `plans_mtdp_investments` (`id`, `mtdp_id`, `spa_id`, `sa_id`, `dip_id`, `dip_link_dip_id`, `investment`, `year_one`, `year_two`, `year_three`, `year_four`, `year_five`, `funding_sources`, `investment_status`, `investment_status_by`, `investment_status_at`, `investment_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 1, 1, 1, 'Investment One', 200.00, 300.00, 400.00, 500.00, 600.00, 'GoFUND', 1, 1, '2025-08-10 19:33:32', '', '2025-08-10 19:33:32', 1, '2025-08-10 19:33:32', NULL, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_kra`
--

INSERT INTO `plans_mtdp_kra` (`id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `investment_id`, `kpi`, `year_one`, `year_two`, `year_three`, `year_four`, `year_five`, `responsible_agencies`, `kra_status`, `kra_status_by`, `kra_status_at`, `kra_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 1, 1, 1, 'KPI One\r\nKPI Two\r\nKPI Three', '2', '3', '5', '2', '3', 'DAL', 1, 1, '2025-08-10 19:34:30', '', '2025-08-10 19:34:30', 1, '2025-08-10 19:34:30', NULL, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_spa`
--

INSERT INTO `plans_mtdp_spa` (`id`, `mtdp_id`, `code`, `title`, `remarks`, `spa_status`, `spa_status_by`, `spa_status_at`, `spa_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 'SPA1', 'SPA One', '', 1, 1, '2025-08-10 19:32:16', '', '2025-08-10 19:32:16', 1, '2025-08-10 19:32:16', NULL, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_specific_area`
--

INSERT INTO `plans_mtdp_specific_area` (`id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_code`, `sa_title`, `sa_remarks`, `sa_status`, `sa_status_by`, `sa_status_at`, `sa_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 1, 'SA1', 'Specific Are One', '', 1, 1, '2025-08-10 19:32:57', '', '2025-08-10 19:32:57', 1, '2025-08-10 19:32:57', NULL, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_mtdp_strategies`
--

INSERT INTO `plans_mtdp_strategies` (`id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `investment_id`, `kra_id`, `strategy`, `policy_reference`, `strategies_status`, `strategies_status_by`, `strategies_status_at`, `strategies_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 1, 1, 1, 1, 'Strategy One', 'Poll Reference', 1, 1, '2025-08-10 19:34:58', '', '2025-08-10 19:34:58', 1, '2025-08-10 19:34:58', NULL, NULL, NULL);

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
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans_nasp`
--

INSERT INTO `plans_nasp` (`id`, `parent_id`, `type`, `code`, `title`, `date_from`, `date_to`, `remarks`, `nasp_status`, `nasp_status_by`, `nasp_status_at`, `nasp_status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 0, 'plans', 'NASP2023', 'NASP 2023 to 2033', '2023-07-30', '2033-08-14', '', 1, 1, NULL, '', '2025-08-10 19:45:35', 1, '2025-08-10 19:45:35', NULL, NULL, NULL),
(2, 1, 'apas', 'APA1', 'APA One', NULL, NULL, '', 1, 1, NULL, '', '2025-08-10 19:45:55', 1, '2025-08-10 19:45:55', NULL, NULL, NULL),
(3, 2, 'dips', 'DIP1', 'DIP One', NULL, NULL, '', 1, 1, NULL, '', '2025-08-10 19:46:12', 1, '2025-08-10 19:46:12', NULL, NULL, NULL),
(4, 3, 'specific_areas', 'SPA1', 'Specific One', NULL, NULL, '', 1, 1, NULL, '', '2025-08-10 19:46:28', 1, '2025-08-10 19:46:28', NULL, NULL, NULL),
(5, 4, 'objectives', 'Obj1', 'Objective One', NULL, NULL, '', 1, 1, NULL, '', '2025-08-10 19:46:40', 1, '2025-08-10 19:46:40', NULL, NULL, NULL),
(6, 5, 'outputs', 'OPT1', 'Output One', NULL, NULL, '', 1, 1, NULL, '', '2025-08-10 19:46:54', 1, '2025-08-10 19:46:54', NULL, NULL, NULL),
(7, 6, 'indicators', 'IND1', 'Indicator One', NULL, NULL, '', 1, 1, NULL, '', '2025-08-10 19:47:07', 1, '2025-08-10 19:47:07', NULL, NULL, NULL);

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
(1, 1, 2, 3, 2, 36, 48, '2025-08-12', '2025-08-15', 5000.00, 'Mandi Village', 'approved', 3, '2025-08-10 20:54:24', 'Booo', NULL, NULL, NULL, NULL, '2025-08-10 20:07:37', 3, '2025-08-10 20:54:24', 3, NULL, NULL);

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
(1, 'Momase', '', 1, 1, NULL, '2025-08-10 08:21:21', '2025-08-10 08:21:21', NULL);

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
(1, 1, 36, 1, 1, NULL, '2025-08-10 08:21:43', '2025-08-10 08:21:43', NULL),
(2, 1, 35, 1, 1, NULL, '2025-08-10 08:21:43', '2025-08-10 08:21:43', NULL),
(3, 1, 34, 1, 1, NULL, '2025-08-10 08:21:43', '2025-08-10 08:21:43', NULL),
(4, 1, 37, 1, 1, NULL, '2025-08-10 08:21:43', '2025-08-10 08:21:43', NULL);

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
(1, 36, 45, 51, 'Smart Village', 'Smart SME', 'Busienss activitiy one\r\nActiviti two', '', '7999666555', 'public/uploads/sme_logos/1754835570_2b871b984410480d8d78.jpg', 'active', '2025-08-11 00:18:35', 1, NULL, '2025-08-11 00:18:35', NULL, '2025-08-11 00:19:30', NULL, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
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
  `user_status_remarks` text DEFAULT NULL,
  `user_status_at` datetime DEFAULT NULL,
  `user_status_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `activation_token` varchar(255) DEFAULT NULL COMMENT 'Secure token for account activation',
  `activation_expires_at` datetime DEFAULT NULL COMMENT 'Expiration timestamp for activation token',
  `activated_at` datetime DEFAULT NULL COMMENT 'Timestamp when user completed activation',
  `is_activated` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Activation status flag (0=pending, 1=activated)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ucode`, `password`, `email`, `phone`, `fname`, `lname`, `gender`, `dobirth`, `place_birth`, `address`, `employee_number`, `branch_id`, `designation`, `grade`, `report_to_id`, `is_evaluator`, `is_supervisor`, `commodity_id`, `role`, `joined_date`, `id_photo_filepath`, `user_status`, `user_status_remarks`, `user_status_at`, `user_status_by`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `activation_token`, `activation_expires_at`, `activated_at`, `is_activated`) VALUES
(1, 'AITAPEITU', '$2y$10$HHPlfvFjdM5TU1g2KDIom.bhTCWk1s1mnOfGXSiOtmBEm/8v.u73W', 'aitapeitu@gmail.com', '', 'Aitape', 'ITU', 'male', NULL, '', '', '', NULL, '', '', NULL, 0, 0, NULL, 'admin', NULL, NULL, 1, NULL, NULL, NULL, '2025-08-10 16:05:27', 1, '2025-08-10 16:52:14', 1, NULL, NULL, NULL, NULL, NULL, 0),
(2, 'USR202508109819', '$2y$10$v2ry1jPX9fI8zltpQLzzD.dy4FLRtxrK6XMToWosRCJ2rO/i2rMNu', 'vanimoitu@gmail.com', '', 'Vanimo', 'ITUsee', 'male', '0000-00-00', '', '', '', 1, '', '', 0, 0, 0, NULL, 'user', '0000-00-00', 'public/uploads/profile/2_1754820540.jpg', 1, NULL, NULL, NULL, '2025-08-10 17:45:21', 1, '2025-08-10 20:09:00', 2, NULL, NULL, NULL, NULL, '2025-08-10 17:45:55', 1),
(3, 'USR202508101454', '$2y$10$xvmeT6Nc75D3nKNElH3EGOTvW1priJ8L2Zt0LcPvAi99Xi5w4t.C6', 'testa@dakoiims.com', '', 'Testa', 'Mangi', '', '0000-00-00', '', '', '', 1, '', '', 0, 1, 0, NULL, 'supervisor', '0000-00-00', NULL, 1, NULL, NULL, NULL, '2025-08-10 18:40:19', 1, '2025-08-10 22:39:55', 3, NULL, NULL, NULL, NULL, '2025-08-10 18:42:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vulnerability`
--

CREATE TABLE `vulnerability` (
  `id` int(11) NOT NULL,
  `province_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `vulnerability_type` varchar(255) DEFAULT NULL,
  `vulnerability_category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `severity_level` enum('low','medium','high','critical') DEFAULT 'medium',
  `affected_population` int(11) DEFAULT NULL,
  `geographic_scope` varchar(255) DEFAULT NULL,
  `seasonal_pattern` varchar(255) DEFAULT NULL,
  `risk_factors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`risk_factors`)),
  `impact_assessment` text DEFAULT NULL,
  `coping_mechanisms` text DEFAULT NULL,
  `intervention_needs` text DEFAULT NULL,
  `priority_ranking` int(11) DEFAULT NULL,
  `data_source` varchar(255) DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `next_review_date` date DEFAULT NULL,
  `vulnerability_status` tinyint(1) DEFAULT 1,
  `vulnerability_status_by` int(11) DEFAULT NULL,
  `vulnerability_status_at` datetime DEFAULT NULL,
  `vulnerability_status_remarks` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 1, '1st Quarter Workplan', 'This is the 1st Quater workplans', 3, '2025-01-30', '2025-06-21', 'in_progress', 'Get the 1st Quater done', '', 0, '2025-08-10 18:44:29', 3, '2025-08-10 18:44:50', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_activities`
--

CREATE TABLE `workplan_activities` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `activity_code` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `activity_type` enum('training','inputs','infrastructure','output') NOT NULL,
  `q_one_target` decimal(15,2) DEFAULT NULL,
  `q_two_target` decimal(15,2) DEFAULT NULL,
  `q_three_target` decimal(15,2) DEFAULT NULL,
  `q_four_target` decimal(15,2) DEFAULT NULL,
  `q_one_achieved` decimal(15,2) DEFAULT NULL,
  `q_two_achieved` decimal(15,2) DEFAULT NULL,
  `q_three_achieved` decimal(15,2) DEFAULT NULL,
  `q_four_achieved` decimal(15,2) DEFAULT NULL,
  `total_budget` decimal(15,2) DEFAULT NULL,
  `rated_at` datetime DEFAULT NULL,
  `rated_by` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `reated_remarks` text DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
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

INSERT INTO `workplan_activities` (`id`, `workplan_id`, `branch_id`, `activity_code`, `title`, `description`, `activity_type`, `q_one_target`, `q_two_target`, `q_three_target`, `q_four_target`, `q_one_achieved`, `q_two_achieved`, `q_three_achieved`, `q_four_achieved`, `total_budget`, `rated_at`, `rated_by`, `rating`, `reated_remarks`, `supervisor_id`, `status`, `status_by`, `status_at`, `status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, '', 'Seedling Distribution', 'This is the inputs', 'inputs', 500.00, 300.00, 200.00, 100.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2025-08-10 19:27:37', 3, '2025-08-10 19:29:02', 3, NULL, NULL),
(2, 1, 1, 'ACT25001', 'Budding Training', 'this is ', 'training', 3.00, 4.00, 5.00, 2.00, 2.00, 1.00, 3.00, 2.00, 11450.00, '2025-08-10 22:32:31', 1, 3, 'We need to complete the rest of the achievements', 3, NULL, NULL, NULL, NULL, '2025-08-10 20:01:16', 3, '2025-08-10 22:32:31', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_corporate_plan_link`
--

CREATE TABLE `workplan_corporate_plan_link` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) DEFAULT NULL,
  `workplan_activity_id` int(11) NOT NULL,
  `corporate_plan_id` int(11) NOT NULL,
  `link_type` varchar(255) DEFAULT NULL,
  `alignment_notes` text DEFAULT NULL,
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

INSERT INTO `workplan_corporate_plan_link` (`id`, `workplan_id`, `workplan_activity_id`, `corporate_plan_id`, `link_type`, `alignment_notes`, `overarching_objective_id`, `objective_id`, `kra_id`, `strategies_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, NULL, 1, 1, NULL, NULL, 2, 3, 4, 5, '2025-08-10 19:47:29', 3, '2025-08-10 19:47:29', NULL, NULL, NULL),
(2, NULL, 2, 1, NULL, NULL, 2, 3, 4, 5, '2025-08-10 20:01:34', 3, '2025-08-10 20:01:34', NULL, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `workplan_mtdp_link`
--

CREATE TABLE `workplan_mtdp_link` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) DEFAULT NULL,
  `workplan_activity_id` int(11) NOT NULL,
  `mtdp_id` int(11) DEFAULT NULL,
  `spa_id` int(11) DEFAULT NULL,
  `dip_id` int(11) DEFAULT NULL,
  `sa_id` int(11) DEFAULT NULL,
  `investment_id` int(11) DEFAULT NULL,
  `kra_id` int(11) DEFAULT NULL,
  `strategies_id` int(11) DEFAULT NULL,
  `indicators_id` int(11) DEFAULT NULL,
  `link_type` varchar(255) DEFAULT NULL,
  `alignment_notes` text DEFAULT NULL,
  `indicator_id` int(11) DEFAULT NULL,
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

INSERT INTO `workplan_mtdp_link` (`id`, `workplan_id`, `workplan_activity_id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `investment_id`, `kra_id`, `strategies_id`, `indicators_id`, `link_type`, `alignment_notes`, `indicator_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, NULL, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, '2025-08-10 19:40:15', 3, '2025-08-10 19:40:22', NULL, '2025-08-10 19:40:22', NULL),
(2, NULL, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, '2025-08-10 19:41:25', 3, '2025-08-10 19:41:25', NULL, NULL, NULL),
(3, NULL, 2, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, '2025-08-10 20:01:39', 3, '2025-08-10 20:01:39', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_nasp_link`
--

CREATE TABLE `workplan_nasp_link` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) DEFAULT NULL,
  `workplan_activity_id` int(11) NOT NULL,
  `nasp_id` int(11) NOT NULL,
  `apa_id` int(11) DEFAULT NULL,
  `dip_id` int(11) DEFAULT NULL,
  `specific_area_id` int(11) DEFAULT NULL,
  `objective_id` int(11) DEFAULT NULL,
  `output_id` int(11) DEFAULT NULL,
  `link_type` varchar(255) DEFAULT NULL,
  `alignment_notes` text DEFAULT NULL,
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

INSERT INTO `workplan_nasp_link` (`id`, `workplan_id`, `workplan_activity_id`, `nasp_id`, `apa_id`, `dip_id`, `specific_area_id`, `objective_id`, `output_id`, `link_type`, `alignment_notes`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, NULL, 1, 1, 2, 3, 4, 5, 6, NULL, NULL, '2025-08-10 19:47:36', 3, '2025-08-10 19:47:36', NULL, NULL, NULL),
(2, NULL, 2, 1, 2, 3, 4, 5, 6, NULL, NULL, '2025-08-10 20:01:29', 3, '2025-08-10 20:01:29', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_others_link`
--

CREATE TABLE `workplan_others_link` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) DEFAULT NULL,
  `external_plan_name` varchar(255) DEFAULT NULL,
  `external_plan_type` varchar(255) DEFAULT NULL,
  `link_description` text DEFAULT NULL,
  `alignment_notes` text DEFAULT NULL,
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

INSERT INTO `workplan_others_link` (`id`, `workplan_id`, `external_plan_name`, `external_plan_type`, `link_description`, `alignment_notes`, `workplan_activity_id`, `link_type`, `title`, `description`, `justification`, `category`, `priority_level`, `expected_outcome`, `target_beneficiaries`, `budget_estimate`, `duration_months`, `start_date`, `end_date`, `status`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, 1, 'other', 'Onters', '', 'Thisisi', 'Admin', 'medium', '', '', 0.00, 0, '0000-00-00', '0000-00-00', 'active', '', '2025-08-10 19:40:37', 3, '2025-08-10 19:40:57', 3, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `workplan_training_activities`
--

CREATE TABLE `workplan_training_activities` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `training_type` varchar(255) DEFAULT NULL,
  `training_topic` varchar(255) DEFAULT NULL,
  `curriculum` text DEFAULT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `trainer_requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`trainer_requirements`)),
  `venue_requirements` text DEFAULT NULL,
  `materials_needed` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`materials_needed`)),
  `certification_type` varchar(255) DEFAULT NULL,
  `evaluation_criteria` text DEFAULT NULL,
  `follow_up_plan` text DEFAULT NULL,
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

INSERT INTO `workplan_training_activities` (`id`, `workplan_id`, `proposal_id`, `activity_id`, `training_type`, `training_topic`, `curriculum`, `duration_days`, `max_participants`, `trainer_requirements`, `venue_requirements`, `materials_needed`, `certification_type`, `evaluation_criteria`, `follow_up_plan`, `trainers`, `topics`, `trainees`, `training_images`, `training_files`, `gps_coordinates`, `signing_sheet_filepath`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Big Trainer\r\nMiddle Trainer\r\nSmall Trainer', 'Big Topic\r\nMiddle Topic\r\nSmall Topic', '[{\"name\":\"The Wan\",\"age\":\"40\",\"gender\":\"Female\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"},{\"name\":\"The Two\",\"age\":\"23\",\"gender\":\"Male\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"},{\"name\":\"The Three\",\"age\":\"57\",\"gender\":\"Male\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"}]', '[\"public\\/uploads\\/training\\/1754820686_080cbfc79fdbb099afba.jpg\",\"public\\/uploads\\/training\\/1754820686_3f7686230d64da857956.jpg\",\"public\\/uploads\\/training\\/1754820686_bc6ceeb6f9fba4d611f2.jpg\"]', '[]', '-3,142', 'public/uploads/signing_sheets/1754820686_3dea2e10c89b801e570b.jpg', '2025-08-10 20:11:26', 2, '2025-08-10 20:17:15', 2, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adx_country`
--
ALTER TABLE `adx_country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_code` (`code`);

--
-- Indexes for table `agreements`
--
ALTER TABLE `agreements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_branch_id` (`branch_id`),
  ADD KEY `idx_effective_date` (`effective_date`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_table_row` (`table_name`,`row_pk`),
  ADD KEY `idx_audit_event` (`event`),
  ADD KEY `idx_audit_actor` (`actor_id`),
  ADD KEY `idx_audit_request` (`request_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_branch_status` (`branch_status`);

--
-- Indexes for table `commodities`
--
ALTER TABLE `commodities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commodity_code` (`commodity_code`),
  ADD KEY `idx_commodity_name` (`commodity_name`);

--
-- Indexes for table `commodity_prices`
--
ALTER TABLE `commodity_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_commodity_id` (`commodity_id`),
  ADD KEY `idx_price_date` (`price_date`);

--
-- Indexes for table `commodity_production`
--
ALTER TABLE `commodity_production`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_commodity_id` (`commodity_id`),
  ADD KEY `idx_date_from` (`date_from`);

--
-- Indexes for table `dakoii_users`
--
ALTER TABLE `dakoii_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_dakoii_user_status` (`dakoii_user_status`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_branch_id` (`branch_id`),
  ADD KEY `idx_folder_id` (`folder_id`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_branch_id` (`branch_id`),
  ADD KEY `idx_parent_folder_id` (`parent_folder_id`);

--
-- Indexes for table `gov_structure`
--
ALTER TABLE `gov_structure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_level` (`level`),
  ADD KEY `idx_code` (`code`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_branch_id` (`branch_id`),
  ADD KEY `idx_meeting_date` (`meeting_date`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `org_settings`
--
ALTER TABLE `org_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_settings_code` (`settings_code`);

--
-- Indexes for table `plans_corporate_plan`
--
ALTER TABLE `plans_corporate_plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Indexes for table `plans_mtdp`
--
ALTER TABLE `plans_mtdp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_abbrev` (`abbrev`);

--
-- Indexes for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`),
  ADD KEY `idx_spa_id` (`spa_id`);

--
-- Indexes for table `plans_mtdp_indicators`
--
ALTER TABLE `plans_mtdp_indicators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

--
-- Indexes for table `plans_mtdp_investments`
--
ALTER TABLE `plans_mtdp_investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

--
-- Indexes for table `plans_mtdp_kra`
--
ALTER TABLE `plans_mtdp_kra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

--
-- Indexes for table `plans_mtdp_spa`
--
ALTER TABLE `plans_mtdp_spa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

--
-- Indexes for table `plans_mtdp_specific_area`
--
ALTER TABLE `plans_mtdp_specific_area`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`),
  ADD KEY `idx_spa_id` (`spa_id`);

--
-- Indexes for table `plans_mtdp_strategies`
--
ALTER TABLE `plans_mtdp_strategies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

--
-- Indexes for table `plans_nasp`
--
ALTER TABLE `plans_nasp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_type` (`type`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_id` (`workplan_id`),
  ADD KEY `idx_activity_id` (`activity_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`);

--
-- Indexes for table `region_province_link`
--
ALTER TABLE `region_province_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_region_id` (`region_id`),
  ADD KEY `idx_province_id` (`province_id`);

--
-- Indexes for table `sme`
--
ALTER TABLE `sme`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_province_id` (`province_id`),
  ADD KEY `idx_district_id` (`district_id`),
  ADD KEY `idx_llg_id` (`llg_id`);

--
-- Indexes for table `sme_staff`
--
ALTER TABLE `sme_staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sme_id` (`sme_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ucode` (`ucode`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_branch_id` (`branch_id`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_commodity_id` (`commodity_id`),
  ADD KEY `idx_activation_token` (`activation_token`),
  ADD KEY `idx_is_activated` (`is_activated`),
  ADD KEY `idx_activation_expires` (`activation_expires_at`);

--
-- Indexes for table `vulnerability`
--
ALTER TABLE `vulnerability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_province_id` (`province_id`),
  ADD KEY `idx_district_id` (`district_id`),
  ADD KEY `idx_severity_level` (`severity_level`);

--
-- Indexes for table `workplans`
--
ALTER TABLE `workplans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_branch_id` (`branch_id`),
  ADD KEY `idx_supervisor_id` (`supervisor_id`);

--
-- Indexes for table `workplan_activities`
--
ALTER TABLE `workplan_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_id` (`workplan_id`),
  ADD KEY `idx_activity_type` (`activity_type`);

--
-- Indexes for table `workplan_corporate_plan_link`
--
ALTER TABLE `workplan_corporate_plan_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_activity_id` (`workplan_activity_id`);

--
-- Indexes for table `workplan_infrastructure_activities`
--
ALTER TABLE `workplan_infrastructure_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_id` (`workplan_id`);

--
-- Indexes for table `workplan_input_activities`
--
ALTER TABLE `workplan_input_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_id` (`workplan_id`);

--
-- Indexes for table `workplan_mtdp_link`
--
ALTER TABLE `workplan_mtdp_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_activity_id` (`workplan_activity_id`);

--
-- Indexes for table `workplan_nasp_link`
--
ALTER TABLE `workplan_nasp_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_activity_id` (`workplan_activity_id`);

--
-- Indexes for table `workplan_others_link`
--
ALTER TABLE `workplan_others_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_activity_id` (`workplan_activity_id`);

--
-- Indexes for table `workplan_output_activities`
--
ALTER TABLE `workplan_output_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_id` (`workplan_id`);

--
-- Indexes for table `workplan_training_activities`
--
ALTER TABLE `workplan_training_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_id` (`workplan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adx_country`
--
ALTER TABLE `adx_country`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agreements`
--
ALTER TABLE `agreements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `commodity_prices`
--
ALTER TABLE `commodity_prices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commodity_production`
--
ALTER TABLE `commodity_production`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dakoii_users`
--
ALTER TABLE `dakoii_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gov_structure`
--
ALTER TABLE `gov_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `org_settings`
--
ALTER TABLE `org_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_corporate_plan`
--
ALTER TABLE `plans_corporate_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `plans_mtdp`
--
ALTER TABLE `plans_mtdp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_mtdp_indicators`
--
ALTER TABLE `plans_mtdp_indicators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_mtdp_investments`
--
ALTER TABLE `plans_mtdp_investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_mtdp_kra`
--
ALTER TABLE `plans_mtdp_kra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_mtdp_spa`
--
ALTER TABLE `plans_mtdp_spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_mtdp_specific_area`
--
ALTER TABLE `plans_mtdp_specific_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_mtdp_strategies`
--
ALTER TABLE `plans_mtdp_strategies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_nasp`
--
ALTER TABLE `plans_nasp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `region_province_link`
--
ALTER TABLE `region_province_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sme`
--
ALTER TABLE `sme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sme_staff`
--
ALTER TABLE `sme_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vulnerability`
--
ALTER TABLE `vulnerability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplans`
--
ALTER TABLE `workplans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workplan_activities`
--
ALTER TABLE `workplan_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workplan_corporate_plan_link`
--
ALTER TABLE `workplan_corporate_plan_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workplan_infrastructure_activities`
--
ALTER TABLE `workplan_infrastructure_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_input_activities`
--
ALTER TABLE `workplan_input_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_mtdp_link`
--
ALTER TABLE `workplan_mtdp_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `workplan_nasp_link`
--
ALTER TABLE `workplan_nasp_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workplan_others_link`
--
ALTER TABLE `workplan_others_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workplan_output_activities`
--
ALTER TABLE `workplan_output_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_training_activities`
--
ALTER TABLE `workplan_training_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

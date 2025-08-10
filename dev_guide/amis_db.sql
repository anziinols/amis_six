-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2025 at 07:44 AM
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
(1, 'Fred Kenny', 'fkenny', 'dakoii123', 'admin', 1, NULL, NULL, NULL, '2025-08-10 01:08:19', '2025-08-10 01:08:19', NULL, NULL);

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
  `level` int(11) NOT NULL COMMENT 'Government level',
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
  `user_status_remarks` text DEFAULT NULL,
  `user_status_at` datetime DEFAULT NULL,
  `user_status_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `workplan_activities`
--

CREATE TABLE `workplan_activities` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `gps_coordinates` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `activity_type` enum('training','inputs','infrastructure','output') NOT NULL,
  `q_one` decimal(15,2) DEFAULT NULL,
  `q_two` decimal(15,2) DEFAULT NULL,
  `q_three` decimal(15,2) DEFAULT NULL,
  `q_four` decimal(15,2) DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `total_cost` decimal(15,2) DEFAULT NULL,
  `image_paths` longtext DEFAULT NULL,
  `trainers` text DEFAULT NULL,
  `trainees` longtext DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quantity` decimal(15,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `idx_commodity_id` (`commodity_id`);

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
  ADD KEY `idx_activity_type` (`activity_type`),
  ADD KEY `idx_province_id` (`province_id`),
  ADD KEY `idx_district_id` (`district_id`);

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
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp`
--
ALTER TABLE `plans_mtdp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_indicators`
--
ALTER TABLE `plans_mtdp_indicators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_investments`
--
ALTER TABLE `plans_mtdp_investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_kra`
--
ALTER TABLE `plans_mtdp_kra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_spa`
--
ALTER TABLE `plans_mtdp_spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_specific_area`
--
ALTER TABLE `plans_mtdp_specific_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_strategies`
--
ALTER TABLE `plans_mtdp_strategies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_nasp`
--
ALTER TABLE `plans_nasp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `region_province_link`
--
ALTER TABLE `region_province_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sme`
--
ALTER TABLE `sme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sme_staff`
--
ALTER TABLE `sme_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vulnerability`
--
ALTER TABLE `vulnerability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplans`
--
ALTER TABLE `workplans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_activities`
--
ALTER TABLE `workplan_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_corporate_plan_link`
--
ALTER TABLE `workplan_corporate_plan_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_nasp_link`
--
ALTER TABLE `workplan_nasp_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_others_link`
--
ALTER TABLE `workplan_others_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_output_activities`
--
ALTER TABLE `workplan_output_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workplan_training_activities`
--
ALTER TABLE `workplan_training_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

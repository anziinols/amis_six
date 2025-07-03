-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 12:12 PM
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
(110, 1, '13', 'Madang Province', '4', 0, 0, NULL, 'branch', '2024-08-31 02:07:03', 'ngande@gmail.com', '2024-11-25 08:21:58', '19', NULL, NULL),
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
(7, 116, 1, 'public', 'This is NASP', 'DAS', '2025-05-07 00:00:00', 'Book NASP', '', '', '', '2025-05-09 13:30:29', 6, '2025-05-09 13:30:29', NULL, NULL, NULL);

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
(2, 116, 1, 'Child Folder', 'This is the meee', 'internal', '2025-04-25 00:19:43', 1, '2025-04-25 00:19:43', NULL, NULL, NULL);

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
(115, 0, 'OCNPNG003014', 'province', 'OCNPNG003014', 'East Sepik', '', '-4.043810,143.270506', '6', '2025-04-04 09:37:16', '1', '2025-04-08 14:27:39', '1', NULL, NULL),
(117, 115, 'OCNPNG00301401', 'district', 'OCNPNG00301401', 'Ambunti/Drekikier District', '', '', '10', '2025-04-04 10:10:59', '1', '2025-04-04 10:10:59', '1', NULL, NULL),
(118, 0, 'OCNPNG001003', 'province', 'OCNPNG001003', 'Central', '', '-9.555978,147.945866', '8', '2025-04-08 18:56:31', '1', '2025-04-09 06:19:17', '1', NULL, NULL),
(119, 118, 'OCNPNG00100301', 'district', 'OCNPNG00100301', 'Abau District', '', '', '', '2025-04-08 18:57:01', '1', '2025-04-09 06:19:26', '1', NULL, NULL),
(120, 119, 'OCNPNG0010030102', 'llg', 'OCNPNG0010030102', 'Aroma Rural', '', '', '12', '2025-04-08 18:57:50', '1', '2025-04-08 19:13:49', '1', NULL, NULL),
(123, 118, 'OCNPNG00100303', 'district', 'OCNPNG00100303', 'Kairuku - Hiri District', '', '', '', '2025-04-08 19:22:01', '1', '2025-04-08 19:22:01', '1', NULL, NULL),
(124, 123, 'OCNPNG0010060103', 'llg', 'OCNPNG0010060103', 'Afore Ruralee', '', '-9.348588,148.145645', '12', '2025-04-08 19:22:16', '1', '2025-04-08 19:22:37', '1', NULL, NULL),
(125, 123, 'OCNPNG0030120515', 'llg', 'OCNPNG0030120515', 'Ahi Rural', '', '-6.703243,147.000685', '12', '2025-04-08 19:22:23', '1', '2025-04-09 06:19:33', '1', NULL, NULL);

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
  `type` varchar(20) NOT NULL,
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
(116, 0, 'plans', 'NASP223', 'NASP23', '2023-01-01', '2030-12-31', 'This s ithe plans', 1, 5, '2025-04-27 21:29:54', 'DFSDFsd', '2025-04-04 15:03:37', '1', '2025-05-08 22:57:07', '1', '2025-05-08 22:57:07', NULL),
(117, 0, 'plans', 'NASP222', 'NASP22', '2025-04-01', '2025-04-25', 'Descriptions', 1, 1, '2025-04-09 06:24:56', 'CAss', '2025-04-04 15:19:58', '1', '2025-05-08 22:57:16', '1', '2025-05-08 22:57:16', NULL),
(118, 116, 'kras', 'KRA1', 'The KRA2', NULL, NULL, 'rermermere', 1, 1, NULL, '', '2025-04-04 15:21:46', '1', '2025-05-08 22:57:07', '1', '2025-05-08 22:57:07', NULL),
(119, 118, 'objectives', 'Obj1', 'This is Objective one2', NULL, NULL, 'Remarks one obj 1', 0, 1, '2025-04-07 05:28:26', 'Cool', '2025-04-07 04:55:42', '1', '2025-05-08 22:57:07', '1', '2025-05-08 22:57:07', NULL),
(120, 117, 'kras', 'KRA455', 'KDAOSDKSA', NULL, NULL, 'DFDS', 1, 1, '2025-04-24 02:15:41', '\r\ndafjsifdi', '2025-04-07 05:34:22', '1', '2025-05-08 22:57:16', '1', '2025-05-08 22:57:16', NULL),
(121, 120, 'objectives', 'Obj455', 'Thfisdfih', NULL, NULL, 'Tsdfdso', 1, 1, '2025-04-24 02:43:55', 'cczxc', '2025-04-24 02:09:01', '1', '2025-05-08 22:57:16', '1', '2025-05-08 22:57:16', NULL),
(122, 120, 'objectives', 'Obj456', 'Thfisdfih', NULL, NULL, '', 1, 1, NULL, '', '2025-04-24 02:16:50', '1', '2025-05-08 22:57:16', '', '2025-05-08 22:57:16', NULL),
(123, 116, 'kras', 'KRA1', 'National Agriculture Sector Plan999', NULL, NULL, 'adfsd', 0, 1, '2025-04-24 02:54:39', 'csdsd', '2025-04-24 02:52:29', '1', '2025-05-08 22:57:07', '1', '2025-05-08 22:57:07', NULL),
(124, 116, 'kras', 'APA01', 'APAPA', NULL, NULL, 'REfmafma', 1, 1, NULL, '', '2025-05-07 11:10:06', '1', '2025-05-08 22:57:07', '', '2025-05-08 22:57:07', NULL),
(125, 118, 'objectives', 'DIP3', 'DIPDIP', NULL, NULL, 'Relmofms', 1, 1, NULL, '', '2025-05-07 11:31:44', '1', '2025-05-08 22:57:07', '1', '2025-05-08 22:57:07', NULL),
(126, 119, 'specific_area', 'SA01', 'SA Areas Foor', NULL, NULL, 'Reramfk', 1, 1, NULL, '', '2025-05-07 12:18:42', '1', '2025-05-08 22:57:07', '', '2025-05-08 22:57:07', NULL),
(127, 126, 'objectives', 'Object012', 'Objective Two', NULL, NULL, 'Treamfkamd', 1, 1, NULL, '', '2025-05-07 12:31:29', '1', '2025-05-08 22:57:07', '1', '2025-05-08 22:57:07', NULL),
(128, 127, 'outputs', 'Opt01', 'This outputs', NULL, NULL, 'Remarks Outpute', 1, 1, NULL, '', '2025-05-07 13:36:24', '1', '2025-05-08 22:57:07', '', '2025-05-08 22:57:07', NULL),
(129, 128, 'indicators', 'IND012', 'This is indeicator 1', NULL, NULL, 'Remaodk', 1, 1, NULL, '', '2025-05-07 14:02:04', '1', '2025-05-08 22:57:07', '1', '2025-05-08 22:57:07', NULL),
(130, 0, 'plans', 'NASP2023-2033', 'National Agriculture Sector Plan 2023-2033', '2023-01-01', '2033-12-31', 'The Agriculture Sector Plan', 1, 1, NULL, '', '2025-05-07 14:31:03', '1', '2025-05-07 14:31:03', '', NULL, NULL),
(131, 130, 'kras', 'APA1', 'Enhanced Productivity, Increased Scale of Production and Market  Competitiveness', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:32:30', '1', '2025-05-07 14:32:30', '', NULL, NULL),
(132, 130, 'kras', 'APA2', 'Improved Infrastructure and Access to Markets', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:32:54', '1', '2025-05-07 14:32:54', '', NULL, NULL),
(133, 130, 'kras', 'APA3', 'Increased Agriculture Commodity Investments and Exports', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:33:12', '1', '2025-05-07 14:33:12', '', NULL, NULL),
(134, 130, 'kras', 'APA4', 'Effective Land Mobilisation, Use and Management', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:33:27', '1', '2025-05-07 14:33:27', '', NULL, NULL),
(135, 130, 'kras', 'APA5', 'Enabling Policy and Legal Environment for Strategic Private  Sector Participation and Investor-Friendly Climate', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:33:42', '1', '2025-05-07 14:33:42', '', NULL, NULL),
(136, 130, 'kras', 'APA6', 'Comprehensive Research and Development', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:33:59', '1', '2025-05-07 14:33:59', '', NULL, NULL),
(137, 130, 'kras', 'APA7', 'Integrated Agriculture Education, Training and Extension  Services', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:34:38', '1', '2025-05-07 14:34:38', '', NULL, NULL),
(138, 130, 'kras', 'APA8', 'Biosecurity', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:36:21', '1', '2025-05-07 14:36:21', '', NULL, NULL),
(139, 130, 'kras', 'APA9', 'Food and Nutrition Security and Safety Standards', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:36:41', '1', '2025-05-07 14:36:41', '', NULL, NULL),
(140, 130, 'kras', 'APA10', 'Climate-Smart Agriculture', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:36:55', '1', '2025-05-07 14:36:55', '', NULL, NULL),
(141, 130, 'kras', 'APA11', 'Institutional Reform and Sector Development', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:37:08', '1', '2025-05-07 14:37:08', '', NULL, NULL),
(142, 130, 'kras', 'APA12', 'Information Management and Use - Information Communication  Technology', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:37:25', '1', '2025-05-07 14:37:25', '', NULL, NULL),
(143, 130, 'kras', 'APA13', 'National Agriculture Sector Plan Management', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:37:44', '1', '2025-05-07 14:37:44', '', NULL, NULL),
(144, 131, 'objectives', 'DIP1', 'DOWNSTREAM PROCESSING AND VALUE ADDITION (DIP 1.11)', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:39:41', '1', '2025-05-07 14:39:41', '', NULL, NULL),
(145, 144, 'specific_area', 'SA1.2', 'Cocoa Downstream Processing', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:40:17', '1', '2025-05-07 14:40:17', '', NULL, NULL),
(146, 144, 'specific_area', 'SA1.3', 'Oil Palm Downstream Processing', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:41:15', '1', '2025-05-07 14:41:15', '', NULL, NULL),
(147, 145, 'objectives', 'OBJ1', 'Downstream processing and value adding products of cocoa achieved', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:41:42', '1', '2025-05-07 14:41:42', '', NULL, NULL),
(148, 147, 'outputs', 'OUTPUT1', 'Production Resources Mobilized and Secured. ', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:42:34', '1', '2025-05-07 14:42:34', '', NULL, NULL),
(149, 147, 'outputs', 'OUTPUT2', ' Production Facilities Established and Tested. ', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:42:52', '1', '2025-05-07 14:42:52', '', NULL, NULL),
(150, 147, 'outputs', 'OUTPUT3', 'Value Added Products Manufactured and Marketed.', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 14:43:08', '1', '2025-05-07 14:43:08', '', NULL, NULL),
(151, 148, 'indicators', 'IND1.1', 'Investment Partners Identified and Secured.', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:20:16', '1', '2025-05-07 15:20:16', '', NULL, NULL),
(152, 148, 'indicators', 'IND1.2', 'Manufacturing plant site identified and secured.', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:20:34', '1', '2025-05-07 15:20:34', '', NULL, NULL),
(153, 148, 'indicators', 'IND1.3', 'Architectural, civil and construction designs prepared. ', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:20:49', '1', '2025-05-07 15:20:49', '', NULL, NULL),
(154, 148, 'indicators', 'IND1.4', 'Manufacturing processes and engineering designs are secured. ', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:21:05', '1', '2025-05-07 15:21:05', '', NULL, NULL),
(155, 148, 'indicators', 'IND1.5', 'Skilled, technical and managerial talents are mobilized and secured. ', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:21:20', '1', '2025-05-07 15:21:20', '', NULL, NULL),
(156, 148, 'indicators', 'IND1.6', 'Plant Technicians and support labor are mobilized and secured. ', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:21:34', '1', '2025-05-07 15:22:13', '1', NULL, NULL),
(157, 148, 'indicators', 'IND1.7', 'Legal framework established', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:21:58', '1', '2025-05-07 15:22:21', '1', NULL, NULL),
(158, 149, 'indicators', 'IND2.1', 'Process and production plant and equipment sourced and procured.', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:22:51', '1', '2025-05-07 15:22:51', '', NULL, NULL),
(159, 149, 'indicators', 'IND2.2', 'Process and production facilities established and tested.', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:23:08', '1', '2025-05-07 15:23:08', '', NULL, NULL),
(160, 149, 'indicators', 'IND2.3', 'Production facilities commissioned and operated. ', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:23:19', '1', '2025-05-07 15:23:19', '', NULL, NULL),
(161, 149, 'indicators', 'IND2.4', 'Production facilities inspected and certified. ', NULL, NULL, '', 1, 1, NULL, '', '2025-05-07 15:23:32', '1', '2025-05-07 15:23:32', '', NULL, NULL);

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
(5, 1, 5, 4, 5, 115, 117, '2025-06-05', '2025-06-21', 50000.00, 'Pagwi', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-13 15:48:30', 4, '2025-05-13 15:48:30', NULL, NULL, NULL),
(6, 1, 3, 4, 5, 118, 123, '2025-05-16', '2025-05-30', 400.00, 'Unspec Location', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-13 15:58:08', 4, '2025-05-13 16:00:33', 4, NULL, NULL);

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
(1, 2, 115, 6, 6, NULL, '2025-05-13 23:50:10', '2025-05-13 23:50:10', NULL);

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
  `commodity_id` int(11) NOT NULL,
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
(1, 'ADM_67ec813a2ab922.26957702', '$2y$10$LdT6J3JEiN7FN2T17DDtfeWaJ.r7WFwO41I2gjA0QVQz58WLgWIBO', 'aitapeitu@gmail.com', '6677886', 'Aitapeti', 'ITUs', 'male', '0000-00-00', '', '', '', 0, '', '', NULL, 0, 0, 0, 'admin', '0000-00-00', 'public/uploads/profile/1_1743727835.jpg', 1, 'User activated by Dakoii Nols on 2025-05-13 15:09:45', '2025-05-13 15:09:45', 8, 0, 8, '2025-04-01 14:13:46', '2025-05-13 05:09:45', NULL, NULL),
(4, 'AMIS_67ef6e7676d61', '$2y$10$liIk/sx3XCrNwSKEUNwr8umFm8SZGoxXRRCZmSvZZVvDOOh8.d/Ea', 'testa@dakoiims.com', '2534645', 'Testa', 'Mangi', '', '2002-05-07', '', '', '', 0, '', '', 0, 1, 0, 0, 'supervisor', '2025-05-07', NULL, 1, 'DAsdasjo', '2025-04-07 01:18:16', 1, 1, 6, '2025-04-03 19:30:41', '2025-05-13 00:46:22', NULL, NULL),
(5, 'AMIS_680e44ea5ba81', '$2y$10$Arnf33oxXAYfGTT5T7WgK.1opvuClHNZGLfFm/ZS0oj.KPdlzRaTu', 'anziinols@gmail.com', '2534645', 'Anzii', 'Ori Nols', '', '2001-04-29', '', 'RRRRR', '', 116, '', '', 4, 0, 0, 1, 'commodity', '2025-04-30', NULL, 1, '', NULL, 0, 1, 5, '2025-04-27 04:54:04', '2025-05-27 00:46:30', NULL, NULL),
(6, 'AMIS_681bfc9dd8854', '$2y$10$jnaivvhrEogDNgk0XjxZZu9QyT/nsmSB.v/qU2Us/k3otJrhoMCW6', 'vanimoitu@gmail.com', '66777645', 'Vanimo', 'ITU', 'male', '2020-05-05', '', '', '', 116, '', '', 4, 0, 0, 0, 'user', '0000-00-00', NULL, 1, '', NULL, 0, 4, 4, '2025-05-08 00:38:13', '2025-05-08 00:38:13', NULL, NULL),
(7, 'AMIS_681c3d1007d5f', '$2y$10$pk3qSD1xSXRdL6eLgcNLM.xbU51bF.y7gpMH/uU3bW4Wu5XlNwzBW', 'crystalmarimbukie@gmail.com', '', 'Crystal', 'Marim', 'female', '0000-00-00', '', '', '5555', 116, '', '', 4, 0, 0, 0, 'supervisor', '0000-00-00', NULL, 1, '', NULL, 0, 4, 8, '2025-05-08 05:14:22', '2025-05-13 04:56:14', NULL, NULL),
(8, 'AMIS_6822982c743c5', '$2y$10$dWIL8tkcLu8P0l7BbL8NBep4j.V958kpu8LPfkJxtPOpiruUbmOOq', 'ngande@dakoiims.com', '88888', 'Dakoii', 'Nols', 'male', '2006-06-20', 'PlaceBirth', 'Adddress', '55555', 107, 'My Design', '14', 4, 1, 0, 2, 'commodity', '2020-05-20', NULL, 1, '', NULL, 0, 6, 5, '2025-05-13 00:55:48', '2025-05-27 01:15:08', NULL, NULL);

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
(4, 116, 'Quarter 4 Workplan', 'This is Qisfn', 4, '2025-04-28', '2025-05-24', 'in_progress', 'Objefsdn', '', 0, '2025-05-08 14:37:22', 4, '2025-05-08 14:37:22', 4, NULL, NULL);

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
  `activity_type` enum('training','inputs','infrastructure') NOT NULL,
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
(5, 1, 116, 'Training Cocoa', 'This is the training', 'training', 4, '2025-05-13 15:30:41', 8, '2025-05-13 15:30:41', 8, NULL, NULL);

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
(5, 1, 131, 132, 135, 138, 140, '2025-05-09 00:09:49', 6, '2025-05-09 00:09:49', NULL, NULL, NULL);

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
(2, 1, 1, 2, '[\"public\\/uploads\\/inputs\\/1746699276_442918d83621d7d6351e.jpg\",\"public\\/uploads\\/inputs\\/1746699276_3a30fc1fa09e21b7c12c.jpg\",\"public\\/uploads\\/inputs\\/1746699276_31a6071a8358fb992563.jpg\",\"public\\/uploads\\/inputs\\/1746702172_7ea6026d7794ff7a8a92.jpg\"]', '[]', '[{\"name\":\"Seedlings\",\"quantity\":\"40\",\"unit\":\"poly bags\",\"remarks\":\"Two Villages\"},{\"name\":\"Plants\",\"quantity\":\"20\",\"unit\":\"cups\",\"remarks\":\"One Farmer\"}]', NULL, NULL, '2025-05-08 20:14:36', 6, '2025-05-08 21:02:52', 6, NULL, NULL);

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
(10, 1, 3, 5, 10, 12, 12, 2, 2, NULL, '2025-05-27 12:01:54', 8, '2025-05-27 12:01:54', NULL, NULL, NULL);

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
(6, 1, 130, 131, 144, 145, 147, 150, '2025-05-09 00:13:58', 6, '2025-05-09 00:13:58', NULL, NULL, NULL),
(7, 1, 130, 131, 144, 145, 147, 149, '2025-05-27 11:41:43', 8, '2025-05-27 11:41:43', NULL, NULL, NULL);

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
(2, 1, 4, 1, 'John Rit', 'Cocoa Seedling', '[{\"name\":\"Farmer One\",\"age\":\"22\",\"gender\":\"Male\",\"phone\":\"4444\",\"email\":\"Emai@gmailc.com\",\"remarks\":\"fsdfs\"},{\"name\":\"Farmer Two\",\"age\":\"40\",\"gender\":\"Female\",\"phone\":\"5555\",\"email\":\"fmale@gmail.com\",\"remarks\":\"ggggg\"}]', '[\"public\\/uploads\\/training\\/1746768175_b1b1349d7c67210d9d1d.png\",\"public\\/uploads\\/training\\/1746768175_e5b25f2ccc52b9ac99bb.jpg\",\"public\\/uploads\\/training\\/1746768175_4417af40dea780c18757.jpg\"]', '[]', NULL, NULL, '2025-05-09 15:22:55', 6, '2025-05-09 15:22:55', 6, NULL, NULL);

--
-- Indexes for dumped tables
--

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
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `workplan_training_activities`
--
ALTER TABLE `workplan_training_activities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gov_structure`
--
ALTER TABLE `gov_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `region_province_link`
--
ALTER TABLE `region_province_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `workplans`
--
ALTER TABLE `workplans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `workplan_activities`
--
ALTER TABLE `workplan_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `workplan_corporate_plan_link`
--
ALTER TABLE `workplan_corporate_plan_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `workplan_infrastructure_activities`
--
ALTER TABLE `workplan_infrastructure_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workplan_input_activities`
--
ALTER TABLE `workplan_input_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workplan_mtdp_link`
--
ALTER TABLE `workplan_mtdp_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `workplan_nasp_link`
--
ALTER TABLE `workplan_nasp_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `workplan_training_activities`
--
ALTER TABLE `workplan_training_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

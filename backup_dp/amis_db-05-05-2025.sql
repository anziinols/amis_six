-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 02:12 PM
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
  `classification` enum('private','internal','public') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `doc_date` datetime DEFAULT NULL,
  `authors` text DEFAULT NULL,
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

INSERT INTO `documents` (`id`, `branch_id`, `classification`, `title`, `description`, `doc_date`, `authors`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 116, 'internal', 'National Agriculture Sector Plan', 'Thisi si the file about', '2025-04-16 00:00:00', 'Fill, Coko, Tigim', '2025-04-25 00:17:27', 1, '2025-04-25 00:17:27', NULL, NULL, NULL),
(2, 116, 'private', 'East Sepik Province Integrated Development Plan 2018 - 2028', 'This is nice', '2025-04-14 00:00:00', 'Roko', '2025-04-25 00:20:39', 1, '2025-04-25 00:20:39', NULL, NULL, NULL),
(3, 116, 'private', 'Windful Docx', 'Tis setep', '2025-04-15 00:00:00', 'Flo', '2025-04-25 00:21:41', 1, '2025-04-25 00:21:41', NULL, NULL, NULL),
(4, 116, 'public', 'This is Docs', 'Exerrcise', '2025-04-08 00:00:00', 'Cool, Man, Gol', '2025-05-02 18:13:58', 1, '2025-05-02 18:13:58', NULL, NULL, NULL);

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
-- Table structure for table `plans_corporate_plan`
--

CREATE TABLE `plans_corporate_plan` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
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
(116, 0, 'plans', 'CP2021', 'Corporate Plan 204', '2025-01-01', '2025-02-01', 'Tifsdfs', 1, 1, '2025-04-08 10:01:07', '', '2025-04-04 15:23:19', '1', '2025-04-08 10:01:07', '1', NULL, NULL),
(117, 116, 'overarching_objective', 'OvBj45', 'OverAching Objective', NULL, NULL, 'Reamskrjdj', 1, 1, '2025-04-08 09:59:13', '', '2025-04-07 05:43:36', '1', '2025-04-08 09:59:13', '1', NULL, NULL),
(118, 117, 'objective', 'Objj', 'Obectttt', NULL, NULL, 'fsdjfsodj', 1, 0, NULL, '', '2025-04-07 06:00:44', '1', '2025-04-07 06:09:53', '1', NULL, NULL),
(119, 118, 'kra', 'WW01', 'Thisi and treee', NULL, NULL, 'Remarjssdf', 1, 1, '2025-04-08 07:56:53', '', '2025-04-07 06:10:21', '1', '2025-04-08 07:56:53', '1', NULL, NULL),
(120, 119, 'strategy', 'ST022', 'Stratenre', NULL, NULL, 'Tasjfdosh', 0, 1, '2025-04-08 07:53:27', '', '2025-04-07 06:10:42', '1', '2025-04-08 07:53:27', '1', NULL, NULL),
(121, 119, 'strategy', 'ST0212', 'Stretagy3ee', NULL, NULL, 'Remostatrr', 1, 0, NULL, '', '2025-04-08 07:44:52', '1', '2025-04-09 06:21:16', '1', NULL, NULL),
(122, 118, 'kra', 'KR2', 'Key Result', NULL, NULL, 'Remarkk', 1, 0, NULL, '', '2025-04-08 07:58:08', '1', '2025-04-09 06:21:05', '1', NULL, NULL),
(123, 117, 'objective', 'Obj44', 'Objective 44', NULL, NULL, 'Remarkssees', 1, 0, NULL, '', '2025-04-08 07:58:30', '1', '2025-04-09 06:20:36', '1', NULL, NULL),
(125, 116, 'overarching_objective', 'OvBj01', 'Over-Bjective Tree', NULL, NULL, 'faffe', 1, 0, NULL, '', '2025-04-08 09:58:49', '1', '2025-04-09 06:20:21', '1', NULL, NULL),
(126, 117, 'objective', 'Ojb66', 'Obj66oy', NULL, NULL, 'Remarkses', 1, 0, NULL, '', '2025-04-08 09:59:36', '1', '2025-04-08 09:59:36', '', NULL, NULL),
(127, 0, 'plans', 'Copro2022', 'Copplapal', '2022-01-01', '2023-12-01', 'Tojkojfos', 0, 1, '2025-04-08 10:02:00', '', '2025-04-08 10:01:47', '1', '2025-04-24 03:03:47', '1', NULL, NULL),
(128, 123, 'kra', 'KRRW', 'FKDFOSq', NULL, NULL, 'fmnksdfn', 1, 0, NULL, '', '2025-04-24 03:40:36', '1', '2025-04-24 03:40:36', '', NULL, NULL),
(129, 128, 'strategy', 'STRWTER', 'FSDFS', NULL, NULL, 'fsdflsn', 1, 0, NULL, '', '2025-04-24 03:40:53', '1', '2025-04-24 03:40:59', '1', NULL, NULL),
(130, 122, 'strategy', 'ST03', 'This isi Strategy', NULL, NULL, 'Temafdjn', 1, 0, NULL, '', '2025-04-24 06:29:09', '1', '2025-04-24 06:29:09', '', NULL, NULL);

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
(1, 'MTDEE', 'MDASEE', '2025-04-01', '2025-04-18', 'DAfafji', 1, 1, '2025-04-08 19:03:25', 'Put on', '2025-04-07 01:39:53', '1', '2025-04-08 19:03:25', '1', NULL, NULL),
(2, 'GGDODee', 'GKOLO', '2001-01-01', '2003-12-12', 'dasdjaspgggh', 1, 1, '2025-04-08 19:17:49', 'LODFSK', '2025-04-08 19:17:16', '1', '2025-04-09 06:13:22', '1', NULL, NULL);

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
(1, 1, 1, 'DIP1.2', 'Dip One two', '', '[]', '[]', '[]', '[]', 1, 1, '2025-04-07 03:00:04', '', '2025-04-07 03:00:04', '1', '2025-04-07 03:00:04', '', NULL, NULL),
(2, 1, 1, 'DIP1.2', 'Dip One two', 'REmeme', '[{\"item\":\"Iventisdfm\",\"amount\":20,\"year\":2020,\"funding\":\"FFF\"}]', '[{\"description\":\"KRAone\",\"period\":\"2020-2023\"},{\"description\":\"KRAtwo\",\"period\":\"2020-2021\"}]', '[{\"description\":\"Snake ron\"}]', '[{\"name\":\"MarkMAkr\",\"target\":\"30\",\"year\":2024}]', 1, 1, '2025-04-07 03:31:41', '', '2025-04-07 03:31:41', '1', '2025-04-07 03:58:50', '1', NULL, NULL),
(3, 1, 1, 'DIP3', 'DIP-Cooking', 'Remekkka', '[{\"item\":\"Invet\",\"amount\":20,\"year\":2024,\"funding\":\"Gogo\"},{\"item\":\"Invettwo\",\"amount\":30,\"year\":2021,\"funding\":\"Fundso\"}]', '[{\"description\":\"KRA-One\",\"period\":\"2021-2023\"},{\"description\":\"KRA-Two\",\"period\":\"2020-2021\"}]', '[{\"description\":\"Stratex\"},{\"description\":\"StackStack\"}]', '[{\"name\":\"IndexOne\",\"target\":\"40\",\"year\":2024},{\"name\":\"Indimex\",\"target\":\"20\",\"year\":2025}]', 1, 1, '2025-04-07 04:19:47', '', '2025-04-07 04:18:00', '1', '2025-04-07 04:19:47', '1', NULL, NULL),
(4, 2, 4, 'DIP101', 'DIPDIP', '', '[]', '[]', '[]', '[]', 1, 1, '2025-04-08 19:32:14', '', '2025-04-08 19:32:14', '1', '2025-04-08 19:32:14', '', NULL, NULL),
(5, 2, 4, 'DIP101', 'DIPDIP', '', '[]', '[]', '[]', '[]', 1, 1, '2025-04-08 19:59:57', '', '2025-04-08 19:59:57', '1', '2025-04-08 19:59:57', '', NULL, NULL),
(6, 2, 3, 'DIP1.3', 'Dip One three', 'dadasda', '[{\"item\":\"Investim\",\"amount\":40,\"year\":2024,\"funding\":\"Gogo\"}]', '[{\"description\":\"KRA22\",\"period\":\"2024-2023\"}]', '[]', '[]', 0, 1, '2025-04-24 06:02:22', 'dasdsa', '2025-04-22 06:09:20', '1', '2025-04-24 06:02:22', '1', NULL, NULL),
(7, 2, 3, 'DIP1.3', 'Dip One three', 'czxcaf', '[]', '[]', '[]', '[]', 1, 1, '2025-04-24 00:34:11', '', '2025-04-24 00:34:11', '1', '2025-04-24 00:34:11', '', NULL, NULL),
(8, 2, 3, 'DIP1.34', 'Dip One three four', '', '[{\"item\":\"asda\",\"amount\":233,\"year\":2024,\"funding\":\"FOFO\"}]', '[{\"description\":\"KRA Frefenf\",\"period\":\"2024-2025\"}]', '[{\"description\":\"Strategies Tragis\"}]', '[{\"name\":\"Insi\",\"target\":\"30\",\"year\":2025}]', 1, 1, '2025-04-24 00:40:10', '', '2025-04-24 00:40:10', '1', '2025-04-24 00:40:10', '', NULL, NULL),
(9, 2, 3, 'DIP1.35', 'Dip One three five', 'Fronkoko', '[{\"item\":\"asda\",\"amount\":233,\"year\":2024,\"funding\":\"FOFO\"},{\"item\":\"Dafa\",\"amount\":244,\"year\":2024,\"funding\":\"GGO\"},{\"item\":\"Fogko\",\"amount\":29,\"year\":2023,\"funding\":\"ROko\"}]', '[{\"description\":\"KRA Frefenf\",\"period\":\"2024-2025\"},{\"description\":\"KRF\",\"period\":\"2024-2026\"},{\"description\":\"Gomodi\",\"period\":\"2024-2027\"}]', '[{\"description\":\"Strategies Tragis\"},{\"description\":\"Style Mangi\"},{\"description\":\"MoreMex\"},{\"description\":\"Steampot\"}]', '[{\"name\":\"Insi\",\"target\":\"30\",\"year\":2025},{\"name\":\"Indexfo\",\"target\":\"20\",\"year\":2026},{\"name\":\"Inforex\",\"target\":\"10\",\"year\":2023},{\"name\":\"Infrefre\",\"target\":\"14\",\"year\":2022}]', 1, 1, '2025-04-24 00:41:39', '', '2025-04-24 00:41:39', '1', '2025-04-24 01:07:44', '1', NULL, NULL);

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
(1, 2, 3, 7, 10, 10, 1, 1, 'Indiccations', 'Fodo', 'Bassssed', 'FAFofsd', 'kfnksdfn', 'lnfksdn', 'mfksdk', 'fsdkfn', 1, 1, '2025-05-05 12:53:49', '', '2025-05-05 12:53:49', '1', '2025-05-05 13:04:17', '1', NULL, NULL),
(2, 2, 3, 7, 10, 10, 1, 1, 'Indicacmdsk', 'Sourcess', '', 'Yeafo 1', 'Yodans 2', 'ofjosfndo 3', 'Yfsdfn 4', 'Yaiafb 5', 1, 1, '2025-05-05 12:57:19', '', '2025-05-05 12:57:19', '1', '2025-05-05 12:57:19', '', NULL, NULL),
(3, 2, 3, 7, 10, 10, 1, 1, 'Incaionsfdsfn', 'Sososo', 'Baselinerrrr', 'Yesf 1', 'Y2 ', 'Y3 ', 'Y 4', 'Y 5', 1, 1, '2025-05-05 13:04:57', '', '2025-05-05 13:04:57', '1', '2025-05-05 13:04:57', '', NULL, NULL);

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
(10, 2, 3, 10, 7, 8, 'Good Invest', 200.00, 300.00, 400.00, 500.00, 600.00, 'Fund Source', 1, 1, '2025-05-05 02:14:04', '', '2025-05-05 02:14:04', '1', '2025-05-05 02:15:50', '1', NULL, NULL);

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
(1, 2, 3, 7, 10, 10, 'KPI Number Wan', 'Enforce legistation', '12', 'Enforce legistation', 'Enforce legistation', 'Enforce legistation', 'DAL, OPIC', 1, 1, '2025-05-05 02:31:51', 'Re-Activiates', '2025-05-05 02:30:46', '1', '2025-05-05 02:31:51', '1', NULL, NULL);

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
(1, 1, 'SPA1', 'SPA One tee', 'Rerer', 1, 1, '2025-04-08 19:09:27', 'dageee', '2025-04-07 02:09:45', '1', '2025-04-08 19:09:27', '1', NULL, NULL),
(2, 1, 'SPA2', 'Speamddee', 'dasdas', 0, 1, '2025-04-08 19:15:33', 'dasdasee', '2025-04-08 19:15:13', '1', '2025-04-08 19:15:33', '1', NULL, NULL),
(3, 2, 'SPAN2', 'FSDFDSKO', 'fsdfe', 1, 1, '2025-04-08 19:18:17', '', '2025-04-08 19:18:17', '1', '2025-04-08 19:18:17', '', NULL, NULL),
(4, 2, 'SPAN4', 'REcsdcsd', 'RREe', 1, 1, '2025-04-08 19:24:10', '', '2025-04-08 19:24:10', '1', '2025-04-08 19:24:10', '', NULL, NULL);

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
(1, 1, 1, 0, 'DIP1.2', 'Dip One two', '', 1, 1, '2025-04-07 03:00:04', '', '2025-04-07 03:00:04', '1', '2025-04-07 03:00:04', '', NULL, NULL),
(2, 1, 1, 0, 'DIP1.2', 'Dip One two', 'REmeme', 1, 1, '2025-04-07 03:31:41', '', '2025-04-07 03:31:41', '1', '2025-04-07 03:58:50', '1', NULL, NULL),
(3, 1, 1, 0, 'DIP3', 'DIP-Cooking', 'Remekkka', 1, 1, '2025-04-07 04:19:47', '', '2025-04-07 04:18:00', '1', '2025-04-07 04:19:47', '1', NULL, NULL),
(4, 2, 4, 0, 'DIP101', 'DIPDIP', '', 1, 1, '2025-04-08 19:32:14', '', '2025-04-08 19:32:14', '1', '2025-04-08 19:32:14', '', NULL, NULL),
(5, 2, 4, 0, 'DIP101', 'DIPDIP', '', 1, 1, '2025-04-08 19:59:57', '', '2025-04-08 19:59:57', '1', '2025-04-08 19:59:57', '', NULL, NULL),
(6, 2, 3, 0, 'DIP1.3', 'Dip One three', 'dadasda', 0, 1, '2025-04-24 06:02:22', 'dasdsa', '2025-04-22 06:09:20', '1', '2025-04-24 06:02:22', '1', NULL, NULL),
(7, 2, 3, 0, 'DIP1.3', 'Dip One three', 'czxcaf', 1, 1, '2025-04-24 00:34:11', '', '2025-04-24 00:34:11', '1', '2025-04-24 00:34:11', '', NULL, NULL),
(8, 2, 3, 0, 'DIP1.34', 'Dip One three four', '', 1, 1, '2025-04-24 00:40:10', '', '2025-04-24 00:40:10', '1', '2025-04-24 00:40:10', '', NULL, NULL),
(9, 2, 3, 0, 'DIP1.35', 'Dip One three five', 'Fronkoko', 1, 1, '2025-04-24 00:41:39', '', '2025-04-24 00:41:39', '1', '2025-04-24 01:07:44', '1', NULL, NULL),
(10, 2, 3, 7, 'SA02', 'This is Specific Area', 'Remaodkan', 1, 1, '2025-05-05 01:28:33', '', '2025-05-05 01:28:33', '1', '2025-05-05 01:28:55', '1', NULL, NULL);

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
(1, 2, 3, 7, 10, 10, 1, 'This is the Strategyicic', 'POL Refff', 1, 1, '2025-05-05 12:41:37', 'ogjojgi', '2025-05-05 12:41:08', '1', '2025-05-05 12:41:37', '1', NULL, NULL);

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
(116, 0, 'plans', 'NASP 2023 - 2030', '22National Agriculture Sector Plans', '2023-01-01', '2030-12-31', 'This s ithe plans', 1, 5, '2025-04-27 21:29:54', 'DFSDFsd', '2025-04-04 15:03:37', '1', '2025-04-27 21:29:54', '1', NULL, NULL),
(117, 0, 'plans', 'NASP 2023 - 2030', 'National Agriculture Sector Plan', '2025-04-01', '2025-04-25', 'Descriptions', 1, 1, '2025-04-09 06:24:56', 'CAss', '2025-04-04 15:19:58', '1', '2025-04-09 06:24:56', '1', NULL, NULL),
(118, 116, 'kras', 'KRA1', 'The KRA2', NULL, NULL, 'rermermere', 1, 1, NULL, '', '2025-04-04 15:21:46', '1', '2025-04-07 05:23:39', '1', NULL, NULL),
(119, 118, 'objectives', 'Obj1', 'This is Objective one2', NULL, NULL, 'Remarks one obj 1', 0, 1, '2025-04-07 05:28:26', 'Cool', '2025-04-07 04:55:42', '1', '2025-04-24 03:03:11', '1', NULL, NULL),
(120, 117, 'kras', 'KRA455', 'KDAOSDKSA', NULL, NULL, 'DFDS', 1, 1, '2025-04-24 02:15:41', '\r\ndafjsifdi', '2025-04-07 05:34:22', '1', '2025-04-24 02:15:46', '1', NULL, NULL),
(121, 120, 'objectives', 'Obj455', 'Thfisdfih', NULL, NULL, 'Tsdfdso', 1, 1, '2025-04-24 02:43:55', 'cczxc', '2025-04-24 02:09:01', '1', '2025-04-24 02:43:55', '1', NULL, NULL),
(122, 120, 'objectives', 'Obj456', 'Thfisdfih', NULL, NULL, '', 1, 1, NULL, '', '2025-04-24 02:16:50', '1', '2025-04-24 02:16:50', '', NULL, NULL),
(123, 116, 'kras', 'KRA1', 'National Agriculture Sector Plan999', NULL, NULL, 'adfsd', 0, 1, '2025-04-24 02:54:39', 'csdsd', '2025-04-24 02:52:29', '1', '2025-04-24 02:54:39', '1', NULL, NULL);

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
  `role` enum('admin','supervisor','user','guest') NOT NULL,
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

INSERT INTO `users` (`id`, `ucode`, `password`, `email`, `phone`, `fname`, `lname`, `gender`, `dobirth`, `place_birth`, `address`, `employee_number`, `branch_id`, `designation`, `grade`, `report_to_id`, `role`, `joined_date`, `id_photo_filepath`, `user_status`, `user_status_remarks`, `user_status_at`, `user_status_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 'ADM_67ec813a2ab922.26957702', '$2y$10$LdT6J3JEiN7FN2T17DDtfeWaJ.r7WFwO41I2gjA0QVQz58WLgWIBO', 'aitapeitu@gmail.com', '6677886', 'Aitapeti', 'ITUs', 'male', '0000-00-00', '', '', '', 0, '', '', NULL, 'admin', '0000-00-00', 'public/uploads/profile/1_1743727835.jpg', 1, '', NULL, 0, 0, 2, '2025-04-01 14:13:46', '2025-04-03 14:50:35', NULL, NULL),
(4, 'AMIS_67ef6e7676d61', '$2y$10$liIk/sx3XCrNwSKEUNwr8umFm8SZGoxXRRCZmSvZZVvDOOh8.d/Ea', 'testa@dakoiims.com', '2534645', 'Testa', 'Mangi', '', '0000-00-00', '', '', '', 0, '', '', 0, 'supervisor', '0000-00-00', NULL, 1, 'DAsdasjo', '2025-04-07 01:18:16', 1, 1, 1, '2025-04-03 19:30:41', '2025-04-06 15:18:16', NULL, NULL),
(5, 'AMIS_680e44ea5ba81', '$2y$10$Arnf33oxXAYfGTT5T7WgK.1opvuClHNZGLfFm/ZS0oj.KPdlzRaTu', 'anziinols@gmail.com', '2534645', 'Anzii', 'Ori Nols', '', '0000-00-00', '', 'RRRRR', '', 116, '', '', 4, 'user', '0000-00-00', NULL, 1, '', NULL, 0, 1, 1, '2025-04-27 04:54:04', '2025-04-27 04:54:04', NULL, NULL);

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
  `objectives` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`objectives`)),
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
(1, 116, 'Workplix', 'DAsdsji', 4, '2025-04-08', '2025-04-25', 'draft', NULL, 'Remakdeke', 0, '2025-04-27 22:39:40', 1, '2025-04-27 22:39:40', 1, NULL, NULL),
(2, 116, 'Cook Workplan', 'Tfsdofnslkfnsl', 4, '2025-03-31', '2025-06-28', 'draft', NULL, 'This work freaken plan', 0, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_corporate_plan_link`
--

CREATE TABLE `workplan_corporate_plan_link` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `corporate_plan_id` int(11) NOT NULL,
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

INSERT INTO `workplan_corporate_plan_link` (`id`, `workplan_id`, `corporate_plan_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 128, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL),
(2, 2, 122, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_mtdp_dip_link`
--

CREATE TABLE `workplan_mtdp_dip_link` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `indicator_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_mtdp_dip_link`
--

INSERT INTO `workplan_mtdp_dip_link` (`id`, `workplan_id`, `indicator_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 2, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL),
(2, 2, 3, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workplan_nasp_link`
--

CREATE TABLE `workplan_nasp_link` (
  `id` int(11) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `nasp_id` int(11) NOT NULL,
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

INSERT INTO `workplan_nasp_link` (`id`, `workplan_id`, `nasp_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 121, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL),
(2, 2, 122, '2025-05-02 18:25:19', 1, '2025-05-02 18:25:19', NULL, NULL, NULL);

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
  ADD KEY `idx_mtdp_id` (`mtdp_id`);

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
-- Indexes for table `workplan_corporate_plan_link`
--
ALTER TABLE `workplan_corporate_plan_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_mtdp_dip_link`
--
ALTER TABLE `workplan_mtdp_dip_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan_nasp_link`
--
ALTER TABLE `workplan_nasp_link`
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
-- AUTO_INCREMENT for table `dakoii_users`
--
ALTER TABLE `dakoii_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `plans_corporate_plan`
--
ALTER TABLE `plans_corporate_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `plans_mtdp`
--
ALTER TABLE `plans_mtdp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `plans_mtdp_indicators`
--
ALTER TABLE `plans_mtdp_indicators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `plans_mtdp_investments`
--
ALTER TABLE `plans_mtdp_investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `plans_mtdp_kra`
--
ALTER TABLE `plans_mtdp_kra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_mtdp_spa`
--
ALTER TABLE `plans_mtdp_spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plans_mtdp_specific_area`
--
ALTER TABLE `plans_mtdp_specific_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `plans_mtdp_strategies`
--
ALTER TABLE `plans_mtdp_strategies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans_nasp`
--
ALTER TABLE `plans_nasp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `workplans`
--
ALTER TABLE `workplans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workplan_corporate_plan_link`
--
ALTER TABLE `workplan_corporate_plan_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workplan_mtdp_dip_link`
--
ALTER TABLE `workplan_mtdp_dip_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workplan_nasp_link`
--
ALTER TABLE `workplan_nasp_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  ADD CONSTRAINT `fk_dip_mtdp` FOREIGN KEY (`mtdp_id`) REFERENCES `plans_mtdp` (`id`),
  ADD CONSTRAINT `fk_dip_spa` FOREIGN KEY (`spa_id`) REFERENCES `plans_mtdp_spa` (`id`);

--
-- Constraints for table `plans_mtdp_spa`
--
ALTER TABLE `plans_mtdp_spa`
  ADD CONSTRAINT `fk_spa_mtdp` FOREIGN KEY (`mtdp_id`) REFERENCES `plans_mtdp` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

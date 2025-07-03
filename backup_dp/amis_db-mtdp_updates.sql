-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 09:43 AM
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
  `baseline_year` year(4) NOT NULL,
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
  `investment` decimal(18,2) NOT NULL,
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
(1, 1, 1, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, 1, '2025-04-07 03:00:04', '', '2025-04-07 03:00:04', '1', '2025-04-07 03:00:04', '', NULL, NULL),
(2, 1, 1, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, 1, '2025-04-07 03:31:41', '', '2025-04-07 03:31:41', '1', '2025-04-07 03:58:50', '1', NULL, NULL),
(3, 1, 1, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, 1, '2025-04-07 04:19:47', '', '2025-04-07 04:18:00', '1', '2025-04-07 04:19:47', '1', NULL, NULL),
(4, 2, 4, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, 1, '2025-04-08 19:32:14', '', '2025-04-08 19:32:14', '1', '2025-04-08 19:32:14', '', NULL, NULL),
(5, 2, 4, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, 1, '2025-04-08 19:59:57', '', '2025-04-08 19:59:57', '1', '2025-04-08 19:59:57', '', NULL, NULL),
(6, 2, 3, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0, 1, '2025-04-24 06:02:22', 'dasdsa', '2025-04-22 06:09:20', '1', '2025-04-24 06:02:22', '1', NULL, NULL),
(7, 2, 3, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, 1, '2025-04-24 00:34:11', '', '2025-04-24 00:34:11', '1', '2025-04-24 00:34:11', '', NULL, NULL),
(8, 2, 3, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, 1, '2025-04-24 00:40:10', '', '2025-04-24 00:40:10', '1', '2025-04-24 00:40:10', '', NULL, NULL),
(9, 2, 3, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, 1, '2025-04-24 00:41:39', '', '2025-04-24 00:41:39', '1', '2025-04-24 01:07:44', '1', NULL, NULL);

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
(9, 2, 3, 0, 'DIP1.35', 'Dip One three five', 'Fronkoko', 1, 1, '2025-04-24 00:41:39', '', '2025-04-24 00:41:39', '1', '2025-04-24 01:07:44', '1', NULL, NULL);

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
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `plans_mtdp_indicators`
--
ALTER TABLE `plans_mtdp_indicators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_investments`
--
ALTER TABLE `plans_mtdp_investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `plans_mtdp_kra`
--
ALTER TABLE `plans_mtdp_kra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans_mtdp_specific_area`
--
ALTER TABLE `plans_mtdp_specific_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `plans_mtdp_strategies`
--
ALTER TABLE `plans_mtdp_strategies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plans_mtdp_dip`
--
ALTER TABLE `plans_mtdp_dip`
  ADD CONSTRAINT `fk_dip_mtdp` FOREIGN KEY (`mtdp_id`) REFERENCES `plans_mtdp` (`id`),
  ADD CONSTRAINT `fk_dip_spa` FOREIGN KEY (`spa_id`) REFERENCES `plans_mtdp_spa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

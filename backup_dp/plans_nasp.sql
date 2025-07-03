-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 11:40 AM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `plans_nasp`
--
ALTER TABLE `plans_nasp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `plans_nasp`
--
ALTER TABLE `plans_nasp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 11:10 PM
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
-- Database: `amis_six_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `workplan_period_id` int(11) NOT NULL,
  `performance_output_id` int(11) NOT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `action_officer_id` int(11) DEFAULT NULL,
  `activity_title` varchar(500) NOT NULL,
  `activity_description` text NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `total_cost` decimal(15,2) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `type` enum('documents','trainings','meetings','agreements','inputs','infrastructures','outputs') NOT NULL,
  `status` enum('pending','active','submitted','approved','rated') NOT NULL DEFAULT 'pending',
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
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `workplan_period_id`, `performance_output_id`, `supervisor_id`, `action_officer_id`, `activity_title`, `activity_description`, `province_id`, `district_id`, `date_start`, `date_end`, `total_cost`, `location`, `type`, `status`, `status_by`, `status_at`, `status_remarks`, `rating_score`, `rated_at`, `rated_by`, `rate_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 3, 2, 'Write policy about cocoa', 'This is the policy paper about cocoa', 36, 48, '2025-08-12', '2025-08-15', 5000.00, 'Mandi Village', 'documents', 'approved', 3, '2025-08-10 20:54:24', 'Booo', NULL, NULL, NULL, NULL, '2025-08-10 20:07:37', 3, '2025-08-14 01:02:28', 1, NULL, NULL),
(2, 1, 1, 3, 1, 'This is a document paper', 'This is document', 36, 46, '2025-08-07', '2025-08-23', NULL, 'Mandi Village', 'documents', 'approved', 1, '2025-08-14 12:10:25', 'Wonderful job', NULL, NULL, NULL, NULL, '2025-08-14 01:22:10', 1, '2025-08-14 12:10:25', 1, NULL, NULL),
(3, 1, 2, 3, 1, 'SME Training', 'This is the SME Training', 36, 49, '2025-08-20', '2025-08-29', 607.00, 'Pagwi', 'trainings', 'rated', 1, '2025-08-14 11:32:08', 'Activity approved by supervisor.', 5.00, '2025-08-14 12:15:01', 1, 'This is a wonderful job', '2025-08-14 08:06:05', 1, '2025-08-14 12:15:01', 1, NULL, NULL),
(4, 1, 1, 3, 2, 'Farmer Training on Modern Agriculture Techniques', 'Comprehensive training program for farmers on modern agriculture techniques including crop rotation, pest management, and sustainable farming practices.', 36, 48, '2025-08-20', '2025-08-22', 15000.00, 'Mandi Village Community Center', 'trainings', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-14 08:16:57', 3, '2025-08-14 08:16:57', 3, NULL, NULL),
(5, 1, 1, 3, 2, 'Youth Entrepreneurship Training', 'Training program focused on developing entrepreneurship skills among rural youth, covering business planning, financial management, and market analysis.', 36, 48, '2025-08-25', '2025-08-27', 12000.00, 'District Youth Center', 'trainings', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-14 08:16:57', 3, '2025-08-14 08:16:57', 3, NULL, NULL),
(6, 1, 1, 3, 2, 'Women Empowerment Workshop', 'Workshop designed to empower women through skills development, leadership training, and awareness about women rights and opportunities.', 36, 48, '2025-09-01', '2025-09-03', 8000.00, 'Women Community Hall', 'trainings', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-14 08:16:57', 3, '2025-08-14 08:16:57', 3, NULL, NULL),
(7, 1, 1, 3, 2, 'Document Management System Implementation', 'Implementation of a comprehensive document management system for better record keeping and information management.', 36, 48, '2025-08-15', '2025-08-18', 5000.00, 'District Office', 'documents', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-14 08:16:57', 3, '2025-09-25 14:32:54', 2, NULL, NULL),
(8, 1, 2, 3, 2, 'Meeting for conducting Trainings', 'This meeting is a prior meeting conduct traiinings', 36, 48, '2025-09-02', '2025-09-02', NULL, 'Sawre', 'meetings', 'active', 1, '2025-09-23 16:57:35', NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:57:35', 1, '2025-09-25 17:32:11', 2, NULL, NULL),
(9, 1, 2, 3, 2, 'MOU with SME Council', 'This is the MOU Thing', 36, 46, '2025-09-25', '2025-09-25', 600.00, 'Wewak', 'agreements', 'active', 2, '2025-09-25 17:34:02', NULL, NULL, NULL, NULL, NULL, '2025-09-25 17:34:02', 2, '2025-09-25 19:27:26', 2, NULL, NULL),
(10, 1, 1, 3, 2, 'Inputing Documentation', 'Description input, as ', 36, 47, '2025-09-25', '2025-09-26', 400.00, 'Maprik Admin', 'inputs', 'active', 2, '2025-09-25 19:36:40', NULL, NULL, NULL, NULL, NULL, '2025-09-25 19:36:40', 2, '2025-09-25 22:58:15', 2, NULL, NULL),
(11, 1, 2, 3, 2, 'Traiing Hall', 'Training Bikpla Hall', 36, 49, '2025-09-25', '2025-10-04', 2345.00, 'Yambi', 'infrastructures', 'active', 2, '2025-09-25 23:00:02', NULL, NULL, NULL, NULL, NULL, '2025-09-25 23:00:02', 2, '2025-09-25 23:47:35', 2, NULL, NULL),
(12, 1, 2, 3, 2, 'Final Result', 'Ths is iresult', 36, 50, '2025-09-26', '2025-09-27', 500.00, 'GHP', 'outputs', 'active', 2, '2025-09-25 23:49:18', NULL, NULL, NULL, NULL, NULL, '2025-09-25 23:49:18', 2, '2025-09-26 00:55:32', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activities_agreements`
--

CREATE TABLE `activities_agreements` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `agreement_type` varchar(100) DEFAULT NULL,
  `parties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parties`)),
  `effective_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` enum('draft','active','expired','terminated','archived') DEFAULT 'draft',
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
-- Dumping data for table `activities_agreements`
--

INSERT INTO `activities_agreements` (`id`, `activity_id`, `title`, `description`, `agreement_type`, `parties`, `effective_date`, `expiry_date`, `status`, `attachments`, `remarks`, `is_deleted`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 9, 'Agreement for SME fundings', 'This is the description agreement', 'MOU', '[{\"name\":\"One Party\",\"organization\":\"Organization\",\"role\":\"Signatory\",\"contact\":\"email@gmail.com\"},{\"name\":\"Two Party\",\"organization\":\"Our ICT\",\"role\":\"Wittness\",\"contact\":\"wittness@gmail.com\"},{\"name\":\"Three Pary\",\"organization\":\"\",\"role\":\"\",\"contact\":\"\"}]', '2025-09-25', '2025-09-30', 'active', '[{\"filename\":\"My MOU\",\"original_name\":\"TA N. Gande Aitape.pdf\",\"path\":\"public\\/uploads\\/agreement_attachments\\/1758789884_c1501de293054629ba4a.pdf\",\"description\":\"My MOU\"},{\"filename\":\"Hand writing MOU\",\"original_name\":\"dr_handwriting_test.jpg\",\"path\":\"public\\/uploads\\/agreement_attachments\\/1758789884_aa71e14c98023eea2706.jpg\",\"description\":\"Hand writing MOU\"}]', 'Additional Remarks', 0, '2025-09-25 18:44:44', 2, '2025-09-25 19:27:26', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activities_documents`
--

CREATE TABLE `activities_documents` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `document_files` longtext DEFAULT NULL,
  `remarks` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities_documents`
--

INSERT INTO `activities_documents` (`id`, `activity_id`, `document_files`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, '[{\"file_path\":\"public\\/uploads\\/documents\\/1755100999_c0a6722eb260306455f3.pdf\",\"caption\":\"cooking documents\",\"original_name\":\"2b4.pdf\"},{\"file_path\":\"public\\/uploads\\/documents\\/1755100999_a540c3adbeaccc67ac12.docx\",\"caption\":\"Answers documents\",\"original_name\":\"ANSWERS TO QUESTIONS GIVEN.docx\"},{\"file_path\":\"public\\/uploads\\/documents\\/1755136586_205ff0d9fa4450c684ca.pdf\",\"caption\":\"This is ambunti\",\"original_name\":\"LLG - Ambunti.pdf\"}]', 'These are three important files', '2025-08-14 02:03:19', 1, '2025-08-14 11:56:39', 1, NULL, NULL),
(2, 7, '[{\"file_path\":\"public\\/uploads\\/documents\\/1758774774_8c753871ce76c0f60320.pdf\",\"caption\":\"Registration\",\"original_name\":\"application_register_report_ESPA_Internal_Advertisement_2025-09-09_08-02-27.pdf\"},{\"file_path\":\"public\\/uploads\\/documents\\/1758774774_428d6c5755ddbf4c074a.pdf\",\"caption\":\"Reciepts\",\"original_name\":\"Receipt08Sep2025_1757310862182.pdf\"}]', 'These are the requested documents', '2025-09-25 14:32:54', 2, '2025-09-25 14:32:54', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activities_infrastructure`
--

CREATE TABLE `activities_infrastructure` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `infrastructure` varchar(255) NOT NULL,
  `gps_coordinates` varchar(100) DEFAULT NULL,
  `infrastructure_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`infrastructure_images`)),
  `infrastructure_files` longtext DEFAULT NULL,
  `signing_scheet_filepath` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities_infrastructure`
--

INSERT INTO `activities_infrastructure` (`id`, `activity_id`, `infrastructure`, `gps_coordinates`, `infrastructure_images`, `infrastructure_files`, `signing_scheet_filepath`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 11, 'Training Hall Infrastructure', '-3,142', '[\"public\\/uploads\\/infrastructure_images\\/1758807905_c21734247bb692c546b7.jpg\",\"public\\/uploads\\/infrastructure_images\\/1758807905_7127393f82efbb51d125.jpg\",\"public\\/uploads\\/infrastructure_images\\/1758807905_f8662e76070eff6362be.jpg\",\"public\\/uploads\\/infrastructure_images\\/1758808055_23729ca07bb297f7aa85.jpg\"]', '[{\"filename\":\"Character Reform\",\"original_name\":\"Charcter-Ref-form-2 (1)_240725_094438.pdf\",\"path\":\"public\\/uploads\\/infrastructure_files\\/1758807905_f7ef14774db6f61b9130.pdf\"},{\"filename\":\"Cash Text\",\"original_name\":\"cash-receipt.xlsx\",\"path\":\"public\\/uploads\\/infrastructure_files\\/1758807905_0eea56e6d3311c33ab28.xlsx\"}]', 'public/uploads/signing_sheets/1758807905_6c024d809271d74cb788.jpg', '2025-09-25 23:45:05', 2, '2025-09-25 23:47:35', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activities_input`
--

CREATE TABLE `activities_input` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `input_images` longtext DEFAULT NULL,
  `input_files` longtext DEFAULT NULL,
  `inputs` longtext DEFAULT NULL,
  `gps_coordinates` varchar(255) DEFAULT NULL,
  `signing_sheet_filepath` varchar(255) DEFAULT NULL,
  `remarks` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities_input`
--

INSERT INTO `activities_input` (`id`, `activity_id`, `input_images`, `input_files`, `inputs`, `gps_coordinates`, `signing_sheet_filepath`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 10, '[]', '[]', '[{\"name\":\"Seedlings\",\"quantity\":\"34\",\"unit\":\"Packs\",\"remarks\":\"Cooking Pack\"},{\"name\":\"Cooking\",\"quantity\":\"12\",\"unit\":\"KG\",\"remarks\":\"\"}]', '-3,142', 'public/uploads/signing_sheets/1758802878_6c74b7597b6eb2edee7a.jpg', 'This is the remarke', '2025-09-25 22:21:18', 2, '2025-09-25 22:58:15', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activities_meetings`
--

CREATE TABLE `activities_meetings` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
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
  `remarks` text DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `signing_sheet_filepath` varchar(500) DEFAULT NULL,
  `gps_coordinates` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities_meetings`
--

INSERT INTO `activities_meetings` (`id`, `activity_id`, `branch_id`, `title`, `agenda`, `meeting_date`, `start_time`, `end_time`, `location`, `participants`, `status`, `minutes`, `attachments`, `remarks`, `is_deleted`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `signing_sheet_filepath`, `gps_coordinates`) VALUES
(1, 8, 0, 'Meeting Wan', 'Working comittee', '2025-09-25 00:00:00', '2025-09-25 16:59:00', '2025-09-25 15:59:00', 'PHQ', '[{\"name\":\"Meet PArt Wan\",\"organization\":\"\"},{\"name\":\"Meet Part Two\",\"organization\":\"\"}]', 'completed', '[{\"topic\":\"Topic Wane\",\"discussion\":\"\"},{\"topic\":\"Topic Twone\",\"discussion\":\"Discussion points key\"}]', '[{\"filename\":\"designs\",\"original_name\":\"CamScanner 08-09-2025 11.31_7.jpg\",\"path\":\"public\\/uploads\\/meeting_attachments\\/1758785264_2ec8cb8143e75efc3c28.jpg\"},{\"filename\":\"handwritings\",\"original_name\":\"dr_handwriting_test.jpg\",\"path\":\"public\\/uploads\\/meeting_attachments\\/1758785264_ebedf20046c502a1c0c9.jpg\"}]', 'This is remarks', 0, '2025-09-25 14:05:14', 2, '2025-09-25 17:32:11', 2, NULL, NULL, 'public/uploads/signing_sheets/1758782092_c222405132017872f7b8.jpg', '-3,142');

-- --------------------------------------------------------

--
-- Table structure for table `activities_output`
--

CREATE TABLE `activities_output` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `outputs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`outputs`)),
  `output_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`output_images`)),
  `output_files` longtext DEFAULT NULL,
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
-- Dumping data for table `activities_output`
--

INSERT INTO `activities_output` (`id`, `activity_id`, `outputs`, `output_images`, `output_files`, `beneficiaries`, `total_value`, `gps_coordinates`, `signing_sheet_filepath`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 12, '[{\"name\":\"Dry Beans Cocoa\",\"quantity\":\"200\",\"unit\":\"Bags\",\"description\":\"\"},{\"name\":\"Wet beans\",\"quantity\":\"240\",\"unit\":\"KG\",\"description\":\"\"}]', '[\"public\\/uploads\\/output_images\\/1758812132_60c12dab3227f92565cc.jpg\",\"public\\/uploads\\/output_images\\/1758812132_e2991c3e8184f2c50194.jpg\",\"public\\/uploads\\/output_images\\/1758812132_21fcbee797fe11bf3327.jpg\"]', '[{\"filename\":\"Cook files\",\"original_name\":\"TA N. Gande Aitape.pdf\",\"path\":\"public\\/uploads\\/output_files\\/1758812132_ff9bdcc6a131efbdba86.pdf\"},{\"filename\":\"CamScanner 07-28-2025 11.22_2.jpg\",\"original_name\":\"CamScanner 07-28-2025 11.22_2.jpg\",\"path\":\"public\\/uploads\\/output_files\\/1758812132_f96153944fe608974019.jpg\"}]', '[{\"name\":\"Cocoa Farmers\",\"organization\":\"\",\"contact\":\"\",\"type\":\"group\"},{\"name\":\"Cocoa Buyers\",\"organization\":\"\",\"contact\":\"\",\"type\":\"individual\"}]', 456700.00, '-3,142', 'public/uploads/signing_sheets/1758812132_8a75d54a219d7aee7323.jpg', 'This remarkable', '2025-09-26 00:55:32', 2, '2025-09-26 00:55:32', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activities_training`
--

CREATE TABLE `activities_training` (
  `id` int(11) NOT NULL,
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
-- Dumping data for table `activities_training`
--

INSERT INTO `activities_training` (`id`, `activity_id`, `trainers`, `topics`, `trainees`, `training_images`, `training_files`, `gps_coordinates`, `signing_sheet_filepath`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 'Big Trainer\r\nMiddle Trainer\r\nSmall Trainer', 'Big Topic\r\nMiddle Topic\r\nSmall Topic', '[{\"name\":\"The Wan\",\"age\":\"40\",\"gender\":\"Female\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"},{\"name\":\"The Two\",\"age\":\"23\",\"gender\":\"Male\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"},{\"name\":\"The Three\",\"age\":\"57\",\"gender\":\"Male\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"}]', '[\"public\\/uploads\\/training\\/1754820686_080cbfc79fdbb099afba.jpg\",\"public\\/uploads\\/training\\/1754820686_3f7686230d64da857956.jpg\",\"public\\/uploads\\/training\\/1754820686_bc6ceeb6f9fba4d611f2.jpg\"]', '[]', '-3,142', 'public/uploads/signing_sheets/1754820686_3dea2e10c89b801e570b.jpg', '2025-08-10 20:11:26', 2, '2025-08-10 20:17:15', 2, NULL, NULL),
(2, 3, 'Trainer One', 'Topic Wan', '[{\"name\":\"Part Wan\",\"age\":\"43\",\"gender\":\"Male\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"Good student\"},{\"name\":\"Right man\",\"age\":\"32\",\"gender\":\"Female\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"Awesome man\"},{\"name\":\"Cool man\",\"age\":\"18\",\"gender\":\"Male\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"}]', '[\"public\\/uploads\\/training\\/1755129040_e60811d846dbf5e5b665.jpg\",\"public\\/uploads\\/training\\/1755129040_ed872053dd2efc9bdac5.png\",\"public\\/uploads\\/training\\/1755129040_61e3c8a6d8812c824926.jpg\",\"public\\/uploads\\/training\\/1755129040_76d043be3220bf5583c2.jpg\",\"public\\/uploads\\/training\\/1755129040_12001bf489ec71323329.png\"]', '[{\"caption\":\"PDF Document\",\"original_name\":\"pdf.pdf\",\"file_path\":\"public\\/uploads\\/training_files\\/1755130254_fd4d8ce91516e0330f62.pdf\"},{\"caption\":\"Cool file\",\"original_name\":\"CNA.docx\",\"file_path\":\"public\\/uploads\\/training_files\\/1755130280_4c6d4e4a7d16ec19afd3.docx\"},{\"caption\":\"The Simple receipts file\",\"original_name\":\"reciept.pdf\",\"file_path\":\"public\\/uploads\\/training_files\\/1755135058_9d84467a9bdaa3b923e2.pdf\"}]', '-4.056169, 143.029902', 'public/uploads/signing_sheets/1755129040_bc5dc146923cf52361b4.docx', '2025-08-14 09:50:40', 1, '2025-08-14 11:30:58', 1, NULL, NULL),
(3, 4, 'Trainer One', 'Topic Wan', '[{\"name\":\"Wan Name\",\"age\":\"43\",\"gender\":\"Male\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"},{\"name\":\"Two name\",\"age\":\"23\",\"gender\":\"Female\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"},{\"name\":\"Tree\",\"age\":\"55\",\"gender\":\"Female\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"Rearmkes\"},{\"name\":\"Four Treanee\",\"age\":\"22\",\"gender\":\"Male\",\"phone\":\"\",\"email\":\"\",\"remarks\":\"\"}]', '[\"public\\/uploads\\/training\\/1758775815_53b464be0eefd824e191.jpg\",\"public\\/uploads\\/training\\/1758775815_942be2eab1ebd2d6d37b.jpg\",\"public\\/uploads\\/training\\/1758775815_bc79244ff2d8f05a67a5.jpg\",\"public\\/uploads\\/training\\/1758775935_601f862253cdcfa9f469.jpg\"]', '[{\"caption\":\"Scoring\",\"original_name\":\"applications_scoring_EPHA CS 001_2025-09-02_08-58-59.pdf\",\"file_path\":\"public\\/uploads\\/training_files\\/1758775815_6a6dda5c1f93ceff4fc1.pdf\"},{\"caption\":\"Desing file paper\",\"original_name\":\"disign.pdf\",\"file_path\":\"public\\/uploads\\/training_files\\/1758775935_030e88c614b34f0e97c3.pdf\"}]', '-3,142', 'public/uploads/signing_sheets/1758775815_00de8b666be575f23b45.jpg', '2025-09-25 14:50:15', 2, '2025-09-25 14:52:15', 2, NULL, NULL);

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
-- Table structure for table `duty_instructions`
--

CREATE TABLE `duty_instructions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workplan_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `duty_instruction_number` varchar(50) NOT NULL,
  `duty_instruction_title` varchar(255) NOT NULL,
  `duty_instruction_description` text DEFAULT NULL,
  `duty_instruction_filepath` varchar(500) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `status_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duty_instructions`
--

INSERT INTO `duty_instructions` (`id`, `workplan_id`, `user_id`, `supervisor_id`, `duty_instruction_number`, `duty_instruction_title`, `duty_instruction_description`, `duty_instruction_filepath`, `status`, `status_by`, `status_at`, `status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 0, 0, 'DI-692189', 'This is my duty Instruction Vr 1 2025', 'DInstuctions', 'uploads/duty_instructions/1755055753_4f7cff25028efc78979c.pdf', 'pending', 1, '2025-08-13 13:29:13', NULL, '2025-08-13 13:29:13', 1, '2025-08-13 13:29:50', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `duty_instructions_corporate_plan_link`
--

CREATE TABLE `duty_instructions_corporate_plan_link` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `duty_items_id` bigint(20) UNSIGNED NOT NULL,
  `corp_strategies_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `duty_instruction_items`
--

CREATE TABLE `duty_instruction_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `duty_instruction_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `instruction_number` varchar(50) NOT NULL,
  `instruction` text NOT NULL,
  `status` varchar(50) DEFAULT 'active',
  `status_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duty_instruction_items`
--

INSERT INTO `duty_instruction_items` (`id`, `duty_instruction_id`, `user_id`, `instruction_number`, `instruction`, `status`, `status_by`, `status_at`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 0, '4.1', 'Number of field visits, consultations, meetings and awareness.', 'active', 1, '2025-08-13 13:31:09', '', '2025-08-13 13:31:09', 1, '2025-08-13 13:31:09', NULL, NULL, NULL),
(2, 1, 0, '4.1.1', 'Organize Regional, Provincial & Districts Consultations', 'active', NULL, NULL, '•	NGI provinces, districts, selected LLGs & farmers empowered.\n•	Liaise with all our stakeholders and major players in the agriculture sector to identify topics of utmost importance for consultations to be well prepared for with solutions.', '2025-08-13 13:40:18', 1, '2025-08-13 13:40:18', NULL, NULL, NULL),
(3, 1, 0, '7', 'dasds', 'active', NULL, NULL, 'dasda this is link', '2025-08-13 13:42:31', 1, '2025-08-13 13:49:40', 1, '2025-08-13 13:49:40', NULL),
(4, 1, 0, '4.2', 'Number of extension activities at Resource Centres', 'active', NULL, NULL, '', '2025-08-13 13:50:54', 1, '2025-08-13 13:50:54', NULL, NULL, NULL),
(5, 1, 0, '4.2.1', 'Organize and facilitate specific trainings', 'active', NULL, NULL, 'Organise and facilitate a TOT backyard farming training', '2025-08-13 13:51:24', 1, '2025-08-13 13:51:24', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `flyway_schema_history`
--

CREATE TABLE `flyway_schema_history` (
  `installed_rank` int(11) NOT NULL,
  `version` varchar(50) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL,
  `script` varchar(1000) NOT NULL,
  `checksum` int(11) DEFAULT NULL,
  `installed_by` varchar(100) NOT NULL,
  `installed_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `execution_time` int(11) NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flyway_schema_history`
--

INSERT INTO `flyway_schema_history` (`installed_rank`, `version`, `description`, `type`, `script`, `checksum`, `installed_by`, `installed_on`, `execution_time`, `success`) VALUES
(1, '1', '<< Flyway Baseline >>', 'BASELINE', '<< Flyway Baseline >>', NULL, 'root', '2025-08-16 08:39:32', 0, 1);

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

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(2, '2025-01-17-000001', 'App\\Database\\Migrations\\CreateSecurityAuditTables', 'default', 'App', 1758691682, 1);

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
-- Table structure for table `performance_indicators_kra`
--

CREATE TABLE `performance_indicators_kra` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workplan_period_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('kra','performance_indicator') NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `item` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance_indicators_kra`
--

INSERT INTO `performance_indicators_kra` (`id`, `workplan_period_id`, `parent_id`, `type`, `code`, `item`, `description`, `status_by`, `status_at`, `status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, NULL, 'kra', 'KRA001', 'KRA One', '', NULL, NULL, NULL, '2025-08-13 20:56:59', 1, '2025-08-13 20:56:59', NULL, NULL, NULL),
(2, 1, 1, 'performance_indicator', 'PI001', 'Performance Indicator One', '', NULL, NULL, NULL, '2025-08-13 20:57:40', 1, '2025-08-13 20:57:40', NULL, NULL, NULL),
(3, 1, 1, 'performance_indicator', 'PI002', 'Performance Indicator Two', '', NULL, NULL, NULL, '2025-08-13 20:58:00', 1, '2025-08-13 20:58:10', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `performance_outputs`
--

CREATE TABLE `performance_outputs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kra_performance_indicator_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `output` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` varchar(20) NOT NULL,
  `unit_of_measurement` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `status_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance_outputs`
--

INSERT INTO `performance_outputs` (`id`, `kra_performance_indicator_id`, `user_id`, `output`, `description`, `quantity`, `unit_of_measurement`, `status`, `status_by`, `status_at`, `status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 2, 'Write policy Paper document', '', '', '', 'approved', 1, '2025-08-13 21:08:40', '', '2025-08-13 21:08:03', 1, '2025-08-13 21:08:40', 1, NULL, NULL),
(2, 2, 2, 'Conduct Trainings', 'This will conduct Trainings', '2', 'trainings', 'active', 1, '2025-08-13 21:14:49', NULL, '2025-08-13 21:14:49', 1, '2025-08-14 00:46:57', 1, NULL, NULL);

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
(1, 0, 'plans', 'COP2024-2028', 'Corporate Plan 2024 - 2028', '2024-01-02', '2028-12-31', '', 1, 0, NULL, '', '2025-08-10 19:42:29', 1, '2025-08-13 12:40:22', 1, NULL, NULL),
(2, 1, 'overarching_objective', 'OVB1', 'Overaching One', NULL, NULL, '', 1, 0, NULL, '', '2025-08-10 19:43:24', 1, '2025-08-10 19:43:30', 1, NULL, NULL),
(3, 2, 'objective', 'OBJ1', 'Objective One', NULL, NULL, '', 1, 0, NULL, '', '2025-08-10 19:43:52', 1, '2025-08-10 19:43:52', NULL, NULL, NULL),
(4, 3, 'kra', 'KRA1', 'KRA One', NULL, NULL, '', 1, 0, NULL, '', '2025-08-10 19:44:06', 1, '2025-08-10 19:44:06', NULL, NULL, NULL),
(5, 4, 'strategy', 'STG1', 'Strategy One', NULL, NULL, '', 1, 0, NULL, '', '2025-08-10 19:44:22', 1, '2025-08-10 19:44:41', 1, NULL, NULL),
(6, 1, 'objective', '01', 'Increase Productivity, Improve Scale of Production and Enhance competitiveness of agricultural  products in the Market.', NULL, NULL, '', 1, 0, NULL, '', '2025-08-13 13:36:01', 1, '2025-08-13 13:38:45', 1, NULL, NULL),
(7, 6, 'kra', '1.1', 'Relevant policies to increase the  efficiency and scale of production,  productivity, and markets for  commercial agriculture  downstream processing, Value  adding and value chains  developed.', NULL, NULL, '', 1, 0, NULL, '', '2025-08-13 13:37:10', 1, '2025-08-13 13:37:10', 1, NULL, NULL),
(8, 7, 'strategy', '1.1.1', 'To gather information  through surveys and  consultation with  relevant stakeholders. ', NULL, NULL, 'da', 1, 0, NULL, '', '2025-08-13 13:37:37', 1, '2025-08-13 13:38:32', 1, NULL, NULL);

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
  `is_admin` tinyint(3) NOT NULL DEFAULT 0,
  `commodity_id` int(11) DEFAULT NULL,
  `role` enum('user','guest') NOT NULL,
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

INSERT INTO `users` (`id`, `ucode`, `password`, `email`, `phone`, `fname`, `lname`, `gender`, `dobirth`, `place_birth`, `address`, `employee_number`, `branch_id`, `designation`, `grade`, `report_to_id`, `is_evaluator`, `is_supervisor`, `is_admin`, `commodity_id`, `role`, `joined_date`, `id_photo_filepath`, `user_status`, `user_status_remarks`, `user_status_at`, `user_status_by`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `activation_token`, `activation_expires_at`, `activated_at`, `is_activated`) VALUES
(1, 'AITAPEITU', '$2y$10$22r2t0oPnH1ZdijYMyp.ceoYN5T2z/90nFYkvT5JC2SYVp31Y2Rfu', 'aitapeitu@gmail.com', '566666', 'Aitape', 'ITU', 'male', '0000-00-00', '', '', '', 0, '', '', 0, 0, 0, 1, NULL, 'user', '0000-00-00', NULL, 1, NULL, NULL, NULL, '2025-08-10 16:05:27', 1, '2025-09-25 13:02:19', 1, NULL, NULL, 'fc238b2336539cd97c72a629ecccacb9d4a5a77c71e20837fe9449cdc3c657ca', '2025-09-26 11:57:02', NULL, 0),
(2, 'USR202508109819', '$2y$10$v2ry1jPX9fI8zltpQLzzD.dy4FLRtxrK6XMToWosRCJ2rO/i2rMNu', 'vanimoitu@gmail.com', '', 'Vanimo', 'ITUsee', 'male', '0000-00-00', '', '', '', 1, '', '', 0, 0, 0, 0, NULL, 'user', '0000-00-00', 'public/uploads/profile/2_1754820540.jpg', 1, NULL, NULL, NULL, '2025-08-10 17:45:21', 1, '2025-08-10 20:09:00', 2, NULL, NULL, NULL, NULL, '2025-08-10 17:45:55', 1),
(3, 'USR202508101454', '$2y$10$wFM6vF1B2wcBrMhVbrDx2ummGqLJQr/HFpxZnQNaUz8Wo1pYHaNXu', 'testa@dakoiims.com', '4432423', 'Testa', 'Mangi', '', '0000-00-00', '', '', '', 1, '', '', 0, 1, 1, 0, NULL, 'user', '0000-00-00', NULL, 1, NULL, NULL, NULL, '2025-08-10 18:40:19', 1, '2025-08-13 20:46:11', 3, NULL, NULL, NULL, NULL, '2025-08-10 18:42:38', 1);

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
(1, 1, '1st Quarter Workplan', 'This is the 1st Quater workplans', 3, '2025-01-30', '2025-06-21', 'in_progress', 'Get the 1st Quater done', '', 0, '2025-08-10 18:44:29', 3, '2025-08-10 18:44:50', 3, NULL, NULL),
(2, 1, 'FDfsd', 'fdsfds', 3, '2025-08-06', '2025-08-23', 'draft', 'fdfs', '', 0, '2025-08-12 09:54:12', 3, '2025-08-12 09:54:12', 3, NULL, NULL);

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
-- Table structure for table `workplan_period`
--

CREATE TABLE `workplan_period` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `duty_instruction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `workplan_period_filepath` varchar(500) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `status_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workplan_period`
--

INSERT INTO `workplan_period` (`id`, `user_id`, `duty_instruction_id`, `title`, `description`, `workplan_period_filepath`, `status`, `status_by`, `status_at`, `status_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 2, 1, 'Performance 2025', '', NULL, 'approved', 1, '2025-08-14 00:54:40', 'Is approved', '2025-08-13 20:56:12', 1, '2025-08-14 00:54:40', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_agreements`
--
ALTER TABLE `activities_agreements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_effective_date` (`effective_date`);

--
-- Indexes for table `activities_documents`
--
ALTER TABLE `activities_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_infrastructure`
--
ALTER TABLE `activities_infrastructure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_input`
--
ALTER TABLE `activities_input`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_meetings`
--
ALTER TABLE `activities_meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_branch_id` (`branch_id`),
  ADD KEY `idx_meeting_date` (`meeting_date`),
  ADD KEY `idx_activity_id` (`activity_id`);

--
-- Indexes for table `activities_output`
--
ALTER TABLE `activities_output`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_training`
--
ALTER TABLE `activities_training`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `duty_instructions`
--
ALTER TABLE `duty_instructions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workplan_id` (`workplan_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `duty_instructions_corporate_plan_link`
--
ALTER TABLE `duty_instructions_corporate_plan_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_duty_items_id` (`duty_items_id`),
  ADD KEY `idx_corp_strategies_id` (`corp_strategies_id`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `duty_instruction_items`
--
ALTER TABLE `duty_instruction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_duty_instruction_id` (`duty_instruction_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `flyway_schema_history`
--
ALTER TABLE `flyway_schema_history`
  ADD PRIMARY KEY (`installed_rank`),
  ADD KEY `flyway_schema_history_s_idx` (`success`);

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
-- Indexes for table `performance_indicators_kra`
--
ALTER TABLE `performance_indicators_kra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_performance_period_id` (`workplan_period_id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `performance_outputs`
--
ALTER TABLE `performance_outputs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kra_performance_indicator_id` (`kra_performance_indicator_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

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
-- Indexes for table `workplan_period`
--
ALTER TABLE `workplan_period`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_duty_instruction_id` (`duty_instruction_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `activities_agreements`
--
ALTER TABLE `activities_agreements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `activities_documents`
--
ALTER TABLE `activities_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `activities_infrastructure`
--
ALTER TABLE `activities_infrastructure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `activities_input`
--
ALTER TABLE `activities_input`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `activities_meetings`
--
ALTER TABLE `activities_meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `activities_output`
--
ALTER TABLE `activities_output`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `activities_training`
--
ALTER TABLE `activities_training`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `duty_instructions`
--
ALTER TABLE `duty_instructions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `duty_instructions_corporate_plan_link`
--
ALTER TABLE `duty_instructions_corporate_plan_link`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `duty_instruction_items`
--
ALTER TABLE `duty_instruction_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `org_settings`
--
ALTER TABLE `org_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performance_indicators_kra`
--
ALTER TABLE `performance_indicators_kra`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `performance_outputs`
--
ALTER TABLE `performance_outputs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plans_corporate_plan`
--
ALTER TABLE `plans_corporate_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `workplan_period`
--
ALTER TABLE `workplan_period`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 10, 2025 at 10:39 PM
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
-- Database: `dakoiim1_amis_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `act_agreements`
--

CREATE TABLE `act_agreements` (
  `id` int(11) NOT NULL,
  `ucode` varchar(100) NOT NULL,
  `workplan_id` int(11) DEFAULT NULL,
  `workplan_details_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `mtdp_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `llg` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `agreement_with` varchar(255) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `mov` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file` varchar(522) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_agreements`
--

INSERT INTO `act_agreements` (`id`, `ucode`, `workplan_id`, `workplan_details_id`, `branch_id`, `branch`, `mtdp_id`, `country_id`, `province`, `district`, `llg`, `ward`, `village`, `title`, `start_date`, `end_date`, `agreement_with`, `purpose`, `amount`, `mov`, `user_id`, `file`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'AGR-6707144fde1d52.57477139', 1, 2, 3, 'Northern Branch Tomotoorw', NULL, NULL, '107', '40', '2', '1', 'Abuniti ples', 'Agree Title Cooooo tikotko', '2024-10-09 09:38:00', '2024-10-12 09:38:00', 'NGO Ambutni', 'To Agree', 3002.00, 'Look and See', 19, 'public/uploads/agreement_files/1728527153_14e8885bc5627b8a4c81.pdf', 'Remarksesee', '2024-10-10 09:39:59', '19', '2024-10-10 12:26:31', 'ngande@gmail.com'),
(2, 'AGR-6707164681e539.09633293', 4, 4, 3, 'Northern Branch Tomotoorw', 110, 1, '108', '45', '6', '8', 'Abuniti ples', 'Agree Title', '2024-10-05 09:47:00', '2024-10-15 09:47:00', 'NGO Ambutni', 'To Agree', 3002.00, 'Look and See', 23, 'public/uploads/agreement_files1728517702_d34d903e0a5560974824.docx', 'This is the remarks', '2024-10-10 09:48:22', 'ngande@gmail.com', '2024-10-10 09:48:22', NULL),
(3, 'AGR-67071b85eb7778.43239374', 3, 3, 2, 'Northern Branch Tomotoorw', 110, 1, '107', '40', '2', '1', 'Cook Village', 'East Sepik Province Integrated Development Plan 2018 - 2028', '2024-10-12 10:10:00', '2024-10-18 10:10:00', 'NGO Ambutni', 'To Cook', 100.00, 'Look and See and think', 20, 'public/uploads/agreement_files/1728519045_83b2d77c68a80a940d1d.docx', 'Remarks and remakes', '2024-10-10 10:10:45', 'ngande@gmail.com', '2024-10-10 10:10:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `act_assign_users`
--

CREATE TABLE `act_assign_users` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `workplan_details_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `level` varchar(255) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_no` varchar(50) DEFAULT NULL,
  `activity_type` varchar(255) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `satisfaction` varchar(255) DEFAULT NULL,
  `supervisor` varchar(255) DEFAULT NULL,
  `assign_date` datetime NOT NULL,
  `assessment_date` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `act_documents`
--

CREATE TABLE `act_documents` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `level` varchar(255) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `doc_type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `prepared_by` varchar(255) NOT NULL,
  `weblink` varchar(255) DEFAULT NULL,
  `doc_date` date NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_documents`
--

INSERT INTO `act_documents` (`id`, `ucode`, `workplan_id`, `level_id`, `level`, `mtdp_id`, `doc_type`, `title`, `prepared_by`, `weblink`, `doc_date`, `publisher`, `filepath`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '66b4348bf196b1723085963', 1, 107, 'province', 1, 'Report', 'Wonda Report', 'Kingkongs', 'https://www.google.com/maps', '2020-12-12', 'Pub guy', '/public/uploads/documents/doc_1723679234_efb6309e0a493d22bd6e.html', 'This si reamrks', '2024-08-08 12:59:23', 'ngande@gmail.com', '2024-08-15 09:47:14', 'ngande@gmail.com'),
(2, '66bd42e9eb3f81723679465', 1, 107, 'province', 1, 'Circular', 'Cool Circular', 'Cool Boy', '', '2024-08-01', 'Publist', '/public/uploads/documents/doc_1723679465_3762892ae413f32218ba.txt', 'Remarks this now!!', '2024-08-15 09:51:05', 'ngande@gmail.com', '2024-08-15 09:51:05', '');

-- --------------------------------------------------------

--
-- Table structure for table `act_infrastructure`
--

CREATE TABLE `act_infrastructure` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `workplan_details_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` varchar(255) NOT NULL,
  `district_id` varchar(255) NOT NULL,
  `llg_id` varchar(255) NOT NULL,
  `ward_id` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `x_coordinates` varchar(50) NOT NULL,
  `y_coordinates` varchar(50) NOT NULL,
  `budget` decimal(15,2) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `supervisor_status` varchar(100) NOT NULL,
  `rating` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_infrastructure`
--

INSERT INTO `act_infrastructure` (`id`, `ucode`, `workplan_id`, `workplan_details_id`, `branch_id`, `branch`, `mtdp_id`, `country_id`, `province_id`, `district_id`, `llg_id`, `ward_id`, `village`, `x_coordinates`, `y_coordinates`, `budget`, `date_start`, `date_end`, `name`, `remarks`, `status`, `supervisor_status`, `rating`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '66bac1a36f2b01723515299', 1, 1, 3, 'Eastern Branch', 110, 1, '107', '40', '2', '1', 'Cook Village', '99.99999999', '-3.57462000', 285.35, '2024-10-03', '2024-10-17', 'Infrastick Name', NULL, '', '', '3', '2024-08-13 12:14:59', 'ngande@gmail.com', '2024-11-25 10:16:50', 'ngande@gmail.com'),
(2, '66bf38149e0d01723807764', 1, 1, 2, 'Northern Branch Tomotoorw', 110, 1, '107', '40', '2', '1', 'Wande Wax', '143.771730', '-4.089733', 3224.00, '2024-07-31', '2024-08-30', 'Chicken farm house', 'Remarkeses like', '', 'approved', '3', '2024-08-16 21:29:24', 'ngande@gmail.com', '2024-11-19 10:15:45', 'ngande@gmail.com'),
(3, '6703468844e561728267912', 4, 4, 0, '', 110, 1, '107', '41', '8', '9', 'Kawax', '-3.57462000', '143.65926900', 2005.00, '2024-10-03', '2024-10-19', 'Fish Hatchery', 'This is the fish hatchery loo', 'progress', 'rejected', '2', '2024-10-07 12:25:12', 'ngande@gmail.com', '2024-10-09 19:42:30', NULL),
(4, '6703b2b7b36b31728295607', 1, 1, 2, 'Northern Branch Tomotoorw', 110, 1, '107', '41', '8', '9', 'Villketoo', '143.65926900', '-3.57462000', 4895.00, '2024-10-02', '2024-10-17', 'This is the infrax', 'This remarks  mix', '', '', '', '2024-10-07 20:06:47', 'ngande@gmail.com', '2024-11-19 10:13:26', 'ngande@gmail.com'),
(5, '6705ce1cb8cc81728433692', 1, 2, 3, 'Eastern Branch', 110, 1, '108', '45', '6', '7', 'One Village', '-3.57462000', '143.65926900', 567.00, '2024-10-04', '2024-10-19', 'New Classroom', 'This is New Classroom Remarkses\r\n', 'pending', 'rejected', '3', '2024-10-09 10:28:12', 'ngande@gmail.com', '2024-11-03 15:14:06', 'ngande@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `act_infra_details`
--

CREATE TABLE `act_infra_details` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `workplan_details_id` int(11) NOT NULL,
  `infra_id` int(11) NOT NULL,
  `work_description` text NOT NULL,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `photo3` varchar(255) DEFAULT NULL,
  `photo4` varchar(255) DEFAULT NULL,
  `photo5` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `pc_completed` decimal(5,2) NOT NULL,
  `user_update_at` datetime DEFAULT NULL,
  `user_remarks` text DEFAULT NULL,
  `satisfaction` varchar(100) NOT NULL,
  `verification_date` date NOT NULL,
  `verification_status` varchar(100) DEFAULT NULL,
  `verification_by` varchar(255) DEFAULT NULL,
  `verification_remarks` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_infra_details`
--

INSERT INTO `act_infra_details` (`id`, `ucode`, `workplan_id`, `workplan_details_id`, `infra_id`, `work_description`, `photo1`, `photo2`, `photo3`, `photo4`, `photo5`, `file`, `pc_completed`, `user_update_at`, `user_remarks`, `satisfaction`, `verification_date`, `verification_status`, `verification_by`, `verification_remarks`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '66bad48c72ce71723520140', 1, 1, 1, 'This si is Creaming', NULL, NULL, NULL, NULL, NULL, NULL, 20.00, NULL, NULL, 'Yes', '2002-02-21', NULL, NULL, NULL, 'Tremakrs', '2024-08-13 13:35:40', '19', '2024-08-13 14:00:56', 'ngande@gmail.com'),
(2, '', 0, 0, 0, 'This si is cool mixing toki', NULL, NULL, NULL, NULL, NULL, NULL, 20.00, NULL, NULL, 'Yes', '2002-02-21', NULL, NULL, NULL, 'Tremakrs', '2024-08-13 13:58:56', NULL, '2024-08-13 13:58:56', 'ngande@gmail.com'),
(5, '6705ce57cfa561728433751', 1, 2, 5, 'Foudation Work', NULL, NULL, NULL, NULL, NULL, NULL, 80.00, NULL, 'This work is completed aye', '', '2024-09-30', NULL, NULL, NULL, 'This work is completed', '2024-10-09 10:29:11', 'ngande@gmail.com', '2024-10-09 13:58:59', 'flora@gmail.com'),
(6, '6705ef6e933dd1728442222', 1, 2, 5, 'Frameworks', NULL, NULL, NULL, NULL, NULL, NULL, 50.00, NULL, 'This is \r\n', '', '0000-00-00', NULL, NULL, NULL, NULL, '2024-10-09 12:50:22', 'flora@gmail.com', '2024-10-09 12:53:01', 'flora@gmail.com'),
(8, '670612af8fc631728451247', 4, 4, 3, 'This one okat', NULL, NULL, NULL, NULL, NULL, NULL, 40.00, NULL, 'Remarkes this is the new remarks', 'Wonderfully', '2024-10-01', NULL, 'ngande@gmail.com', 'Remarkes this is the new remarks too cool', NULL, '2024-10-09 15:20:47', 'ngande@gmail.com', '2024-10-09 15:53:34', 'ngande@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `act_inoutput`
--

CREATE TABLE `act_inoutput` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `workplan_details_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `level` varchar(255) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `llg` varchar(255) NOT NULL,
  `ward` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `x_coordinates` varchar(255) NOT NULL,
  `y_coordinates` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(15,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `supervisor_status` varchar(50) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `photo3` varchar(255) DEFAULT NULL,
  `photo4` varchar(255) DEFAULT NULL,
  `photo5` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `remarks` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_inoutput`
--

INSERT INTO `act_inoutput` (`id`, `ucode`, `workplan_id`, `workplan_details_id`, `level_id`, `level`, `mtdp_id`, `country_id`, `province`, `district`, `llg`, `ward`, `village`, `name`, `x_coordinates`, `y_coordinates`, `start_date`, `end_date`, `budget`, `status`, `supervisor_status`, `rating`, `photo1`, `photo2`, `photo3`, `photo4`, `photo5`, `file`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, '66bc0deb30a0b1723600363', 1, 1, 107, 'province', 0, 1, '107', '40', '2', '1', 'Villlivivi', 'Proper NAme', '143.871585', '-3.714100', '2024-07-31', '2024-08-14', 244.45, 'Complete', 'approved', 5.00, 'public/uploads/input_output_files/1723601656_3fdd90973bcd00d7e9e3.png', 'public/uploads/input_output_files/1723601656_fd9c1a3b85dafdc91373.png', 'public/uploads/input_output_files/1723601656_819b2f0702f66d8ccb10.gif', 'public/uploads/input_output_files/1723601656_a50976aa836fac79a529.jpeg', NULL, 'public/uploads/input_output_files/1723600363_092643d57102447dd197.kml', 'Remarkeses loko', '2024-08-14 11:52:43', '19', '2024-11-19 10:17:28', '19'),
(3, '66bc12c8241ee1723601608', 1, 1, 107, 'province', 110, 1, '107', '40', '2', '1', 'Villock', 'This si the name', '143.032051', '-3.980744', '2024-04-29', '2024-08-16', 2265.00, 'Progressing', NULL, NULL, 'public/uploads/input_output_files/1723601608_03707ca8c9807b74bc16.png', 'public/uploads/input_output_files/1723601608_95f7a2b4a745fec2fe82.png', 'public/uploads/input_output_files/1723601608_3ec32147b14faf38b527.jpg', 'public/uploads/input_output_files/1723601608_035dddc5a8999d82add4.png', 'public/uploads/input_output_files/1723601608_41b53154fba6d6844136.png', 'public/uploads/input_output_files/1723601608_1f417ea387b563a0bef1.csv', 'This is the cream of it', '2024-08-14 12:13:28', '19', '2024-11-19 10:18:30', '19'),
(4, '66bc160da88e51723602445', 1, 1, 107, 'province', 110, 1, '107', '41', '8', '9', 'Villketoo', 'This is the Input', '144.993240', '-5.192687', '2024-07-30', '2024-08-09', 2265.00, 'pending', NULL, NULL, 'public/uploads/input_output_files/1723602445_cf6a5641ec2065b8ce06.png', 'public/uploads/input_output_files/1723602445_bbef75a2ba66300da7f7.jpg', NULL, NULL, NULL, NULL, 'This remarks  mix', '2024-08-14 12:27:25', '19', '2024-11-19 10:19:21', '19');

-- --------------------------------------------------------

--
-- Table structure for table `act_inoutput_details`
--

CREATE TABLE `act_inoutput_details` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `workplan_details_id` int(11) NOT NULL,
  `input_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `contactno` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_inoutput_details`
--

INSERT INTO `act_inoutput_details` (`id`, `ucode`, `workplan_id`, `workplan_details_id`, `input_id`, `fname`, `lname`, `contactno`, `gender`, `age`, `amount`, `quantity`, `unit`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '66bc30562bd0a1723609174', 1, 1, 2, 'Coolex', 'Leaf', '5564665 777', 'Female', 23, 12.00, 332, '7', 'This is the new detail', '2024-08-14 14:19:34', 0, '2024-10-09 19:36:07', 0),
(3, '66bf54c37e1eb1723815107', 1, 1, 2, 'New', 'Freak', '5346536', 'Male', 21, NULL, 45, '3', 'This is a cocoa seedlying', '2024-08-16 23:31:47', 0, '2024-08-16 23:31:47', NULL),
(4, '67061d4764c2c1728453959', 1, 1, 4, 'Cool estest', 'Wan', '546456', 'Male', 24, 500.00, 44, '67', 'This is the remarks', '2024-10-09 16:05:59', 0, '2024-10-09 16:50:36', 0),
(6, '67062487a0c9d1728455815', 1, 1, 4, 'Niurex', 'Bunch', '0945378', 'Male', 43, 56.00, 50, '25', 'Foorex', '2024-10-09 16:36:55', 0, '2024-10-09 19:34:21', 0);

-- --------------------------------------------------------

--
-- Table structure for table `act_meeting`
--

CREATE TABLE `act_meeting` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `workplan_details_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `llg` varchar(255) NOT NULL,
  `ward` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `x_coordinates` decimal(10,8) NOT NULL,
  `y_coordinates` decimal(11,8) NOT NULL,
  `purpose` text NOT NULL,
  `organization` varchar(255) NOT NULL,
  `meeting_type` varchar(255) NOT NULL,
  `participants` varchar(255) DEFAULT NULL,
  `output` text NOT NULL,
  `follow_up` text DEFAULT NULL,
  `from_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `photo3` varchar(255) DEFAULT NULL,
  `photo4` varchar(255) DEFAULT NULL,
  `photo5` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_meeting`
--

INSERT INTO `act_meeting` (`id`, `ucode`, `workplan_id`, `workplan_details_id`, `branch_id`, `branch`, `mtdp_id`, `country_id`, `province`, `district`, `llg`, `ward`, `village`, `title`, `x_coordinates`, `y_coordinates`, `purpose`, `organization`, `meeting_type`, `participants`, `output`, `follow_up`, `from_date`, `to_date`, `photo1`, `photo2`, `photo3`, `photo4`, `photo5`, `file`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '6706616a3f8491728471402', 1, 3, 2, 'Northern Branch Tomotoorw', 110, 1, '108', '45', '6', '8', 'Cok', 'Cooking Meeting', 99.99999999, 143.65926900, 'To Cook', 'Cooklet', 'Virtual', '345', 'Wonderful outcome', 'Next Time', '1970-01-01 10:00:00', '1970-01-01 10:00:00', NULL, NULL, NULL, NULL, NULL, 'public/uploads/meeting_files/1728471402_29f3a063062f8fab83d3.pdf', 'This is cool', '2024-10-09 20:56:42', '19', '2024-10-09 22:47:39', 'ngande@gmail.com'),
(2, '6706670e8ae9e1728472846', 4, 4, 3, 'Eastern Branch', 110, 1, '107', '40', '2', '1', 'Cok', 'Cooking Meetings', 99.99999999, 143.65926900, 'To Cook', 'Cooklet', 'Virtual', '54', 'Wonderful outcome', 'Next Time', '2024-10-08 21:18:00', '2024-10-11 21:18:00', 'public/uploads/training_images/1728505405_5849b44615c7e71157c7.jpg', 'public/uploads/training_images/1728505405_d611125214b3f5746eee.jpg', 'public/uploads/training_images/1728505405_1571ce2f6ce2e9e65a08.jpg', 'public/uploads/training_images/1728476785_d7e4017316a860809caf.png', 'public/uploads/training_images/1728476785_26b0511e6c861746302e.png', 'public/uploads/training_files/1728505378_a78e9f4b6db73f980e44.pdf', 'Remanrek', '2024-10-09 21:20:46', '19', '2024-10-10 06:23:25', 'ngande@gmail.com'),
(3, '6706eba44c2071728506788', 4, 2, 3, 'Eastern Branch', 110, 0, '107', '40', '2', '1', 'Avatip Village', 'Wonderful Meeting', 0.00000000, 0.00000000, 'To Agree', 'Wonder Cooks', 'Physical', '55', 'Agree on it', 'Next Meeting', '1970-01-01 10:00:00', '1970-01-01 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'djasfandin', '2024-10-10 06:46:28', 'ngande@gmail.com', '2024-11-19 09:15:13', 'ngande@gmail.com'),
(4, '6706ebcf391821728506831', 4, 0, 3, 'Eastern Branch', 110, 0, '', '', '', '', '', '', 0.00000000, 0.00000000, '', 'Wonder Cooks', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-10 06:47:11', 'ngande@gmail.com', '2024-10-10 06:47:11', NULL),
(5, '6706ec05d04461728506885', 4, 0, 3, 'Eastern Branch', 110, 0, '', '', '', '', '', '', 0.00000000, 0.00000000, '', 'Wonder Cooks', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-10 06:48:05', 'ngande@gmail.com', '2024-10-10 06:48:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `act_meeting_details`
--

CREATE TABLE `act_meeting_details` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_meeting_details`
--

INSERT INTO `act_meeting_details` (`id`, `ucode`, `workplan_id`, `meeting_id`, `branch_id`, `branch`, `mtdp_id`, `firstname`, `lastname`, `designation`, `organization`, `sex`, `age`, `email`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, '6706efc30e3731728507843', 4, 2, 3, 'Eastern Branch', 110, 'BRENDAN', 'Voir', 'Cooking boy', 'Wonder Cooks', 'Male', 23, 'bv@gmail.com', '2024-10-10 07:04:03', 'ngande@gmail.com', '2024-10-10 07:04:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `act_proposals`
--

CREATE TABLE `act_proposals` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `workplan_details_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `task_no` varchar(255) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `actual_expenditure` varchar(100) NOT NULL,
  `report_file` varchar(522) NOT NULL,
  `unit_measurement` varchar(100) NOT NULL,
  `target` varchar(552) NOT NULL,
  `male` int(11) DEFAULT 0,
  `female` int(11) DEFAULT 0,
  `user_status` varchar(255) NOT NULL,
  `user_status_at` datetime DEFAULT NULL,
  `supervisor_satisfaction` varchar(255) DEFAULT NULL,
  `supervisor_status` varchar(255) DEFAULT NULL,
  `supervisor_status_at` datetime DEFAULT NULL,
  `assign_date` date DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `proposal_remarks` text DEFAULT NULL,
  `user_remarks` text NOT NULL,
  `supervisor_remarks` text NOT NULL,
  `supervisor_rating` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_proposals`
--

INSERT INTO `act_proposals` (`id`, `ucode`, `workplan_id`, `workplan_details_id`, `branch_id`, `branch`, `mtdp_id`, `user_id`, `supervisor_id`, `task_no`, `activity_type`, `activity_id`, `start_date`, `end_date`, `budget`, `actual_expenditure`, `report_file`, `unit_measurement`, `target`, `male`, `female`, `user_status`, `user_status_at`, `supervisor_satisfaction`, `supervisor_status`, `supervisor_status_at`, `assign_date`, `assessment_date`, `proposal_remarks`, `user_remarks`, `supervisor_remarks`, `supervisor_rating`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(14, '673fdbe4de8421732238308', 1, 1, 3, 'Eastern Branch', 110, 23, 21, '20247', 'Training', 2, '2024-12-01', '2024-11-06', 300.00, '', '', 'KG', '200', NULL, NULL, '', NULL, NULL, 'approved', NULL, NULL, NULL, 'This is remarks', '', '', 0, '2024-11-22 11:18:28', 'ngande@gmail.com', '2024-11-26 14:36:59', 'ngande@gmail.com'),
(15, '67400b427cc451732250434', 1, 1, 2, 'Northern Branch Tomotoorw', 110, 21, 23, '20248', 'Infrastructure', 1, '2024-12-01', '2024-12-19', 32043.00, '', '', 'KG', '200', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'thisi s', '', '', 0, '2024-11-22 14:40:34', 'ngande@gmail.com', '2024-11-22 14:40:34', NULL),
(16, '67400ba7ac36a1732250535', 1, 1, 2, 'Northern Branch Tomotoorw', 110, 21, 23, '20249', 'Training', 3, '2024-12-01', '2024-12-12', 300.00, '', '', 'KG', '300', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'This is is ', '', '', 0, '2024-11-22 14:42:15', 'ngande@gmail.com', '2024-11-22 14:42:15', NULL),
(17, '67400c7c5d6f81732250748', 1, 1, 2, 'Northern Branch Tomotoorw', 110, 21, 23, '202410', 'Infrastructure', 2, '2024-12-05', '2024-12-12', 300.00, '', '', '300', '304', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'This is is ', '', '', 0, '2024-11-22 14:45:48', 'ngande@gmail.com', '2024-11-22 15:03:34', 'ngande@gmail.com'),
(18, '67417494399291732342932', 1, 1, 2, 'Northern Branch Tomotoorw', 110, 21, 23, '202411', 'Infrastructure', 4, '2024-11-25', '2024-11-29', 890.00, '', '', 'KG', '400', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'Dasdjaiosdjajds', '', '', 0, '2024-11-23 16:22:12', 'ngande@gmail.com', '2024-11-23 16:22:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `act_training`
--

CREATE TABLE `act_training` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `workplan_details_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `llg` varchar(255) NOT NULL,
  `ward` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `x_coordinates` varchar(50) NOT NULL,
  `y_coordinates` varchar(50) NOT NULL,
  `budget` decimal(15,2) NOT NULL,
  `males` int(11) NOT NULL,
  `females` int(11) NOT NULL,
  `actual` decimal(11,2) DEFAULT NULL,
  `workplan_remarks` text DEFAULT NULL,
  `user_remarks` text NOT NULL,
  `supervisor_remarks` text NOT NULL,
  `resourceperson` varchar(255) DEFAULT NULL,
  `methodology` text DEFAULT NULL,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `photo3` varchar(255) DEFAULT NULL,
  `photo4` varchar(255) DEFAULT NULL,
  `photo5` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `user_status` varchar(255) DEFAULT NULL,
  `user_status_by` varchar(255) DEFAULT NULL,
  `user_status_at` datetime DEFAULT NULL,
  `supervisor_status` varchar(255) DEFAULT NULL,
  `supervisor_status_by` varchar(255) DEFAULT NULL,
  `supervisor_status_at` datetime DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_training`
--

INSERT INTO `act_training` (`id`, `ucode`, `workplan_id`, `workplan_details_id`, `branch_id`, `branch`, `proposal_id`, `mtdp_id`, `name`, `country_id`, `province`, `district`, `llg`, `ward`, `village`, `x_coordinates`, `y_coordinates`, `budget`, `males`, `females`, `actual`, `workplan_remarks`, `user_remarks`, `supervisor_remarks`, `resourceperson`, `methodology`, `photo1`, `photo2`, `photo3`, `photo4`, `photo5`, `file`, `user_status`, `user_status_by`, `user_status_at`, `supervisor_status`, `supervisor_status_by`, `supervisor_status_at`, `rating`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, '66bbfaf95fbb11723595513', 1, 1, 107, 'province', 0, 110, 'This is the training', 1, '107', '40', '2', '1', 'Cook Village', '-3.57462000', '143.65926900', 3234.00, 6, 34, 4005.00, 'remarkessness', 'it\'s in preogress', 'This is a very good job', 'Cool Resource boy', 'This si imehtod', 'public/uploads/training_images/1728476712_b6d9a72d6d537dfc23cb.jpg', 'public/uploads/training_images/1728476712_1be1ee48b7c2c00087c1.png', 'public/uploads/training_images/1728476712_807cd49e2389ce181f62.png', 'public/uploads/training_images/1728476712_c93097fa62e85dc7c430.png', 'public/uploads/training_images/1728364182_f0611644c0b25712a74d.jpg', 'public/uploads/training_files/1728359417_a0f47ead2505f88b5fc0.pdf', 'pending', NULL, '2024-10-08 14:33:38', 'approved', 'ngande@gmail.com', '2024-11-26 14:36:58', 0, '2024-08-14 10:31:53', 'ngande@gmail.com', '2024-11-26 14:36:58', 'ngande@gmail.com'),
(3, '66c352cb657841724076747', 1, 1, 107, 'province', 0, 110, 'Cocoa Start Training', 1, '107', '40', '2', '1', 'Beglam Village', '-5.55728100', '145.79498300', 200.04, 5, 6, 0.00, 'This Training is the introduction training for cocoa', '', '', '', '', 'public/uploads/training_images/1728433059_c31ec2a5e5f086811189.jpg', 'public/uploads/training_images/1728433059_a08041acc33ef071544e.jpg', NULL, NULL, NULL, 'public/uploads/training_files/1730583807_007553e988b34e2c9b49.pdf', 'progressing', NULL, '2024-11-22 16:46:48', 'approved', 'ngande@gmail.com', '2024-10-09 16:01:36', 3, '2024-08-20 00:12:27', 'ngande@gmail.com', '2024-11-22 16:46:48', 'ngande@gmail.com'),
(4, '66c368fde63b81724082429', 3, 3, 107, 'province', 0, 110, 'Cocoa Nursery Training', 1, '107', '40', '2', '1', 'Beglam Village', '0.00000000', '0.00000000', 223.04, 0, 0, 500.00, 'Cocoa Training 1', 'Wander reamrks', 'Complete the job', 'Cool Rec', 'Wanda Tehod', 'public/uploads/training_files/1724906537_f7661a3416ab00679ce8.png', 'public/uploads/training_files/1724906537_b7bea012948985420169.png', 'public/uploads/training_files/1724906537_1879c7b0a00fdbd10c4e.png', 'public/uploads/training_files/1724092038_f6aef27b9dc20eba0f96.png', 'public/uploads/training_files/1724092038_8c4f3a34e042b41da334.jpg', 'public/uploads/training_files/1724092019_5a55ccebf8df5da9bb15.pdf', 'progress', NULL, NULL, 'rejected', 'ngande@gmail.com', '2024-11-03 14:51:28', 1, '2024-08-20 01:47:09', 'ngande@gmail.com', '2024-11-03 14:51:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `act_training_details`
--

CREATE TABLE `act_training_details` (
  `id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `village` varchar(100) NOT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `age` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `act_training_details`
--

INSERT INTO `act_training_details` (`id`, `training_id`, `fname`, `lname`, `village`, `contact`, `gender`, `age`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, 2, 'Cool ', 'Boy', 'Villock', '778283', 'Female', 23, 'Wonderfillex', '2024-08-14 11:09:35', 'ngande@gmail.com', '2024-10-08 14:34:41', 'ngande@gmail.com'),
(3, 2, 'Two participant', 'LAnamix', 'Wonda Village', '775654', 'Male', 25, 'Cool participant', '2024-08-16 16:06:49', 'ngande@gmail.com', '2024-08-16 16:06:49', NULL),
(4, 2, 'Cool', 'Mangi', 'Cook Village', '55234', 'Male', 26, 'They are cool', '2024-10-01 12:17:08', 'ngande@gmail.com', '2024-10-08 13:00:26', 'ngande@gmail.com'),
(6, 3, 'Cooking', 'Man', 'Vilag', '44355', 'Male', 32, 'Cooking reech', '2024-10-09 10:14:52', 'ngande@gmail.com', '2024-11-22 16:23:16', 'vanimoitu@gmail.com'),
(7, 3, 'Freela', 'Voere', 'Vadds', '654556', 'Female', 67, 'fdskf', '2024-10-09 10:15:51', 'ngande@gmail.com', '2024-10-09 10:15:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adx_country`
--

CREATE TABLE `adx_country` (
  `id` int(11) UNSIGNED NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `map_center` varchar(255) NOT NULL,
  `map_zoom` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adx_country`
--

INSERT INTO `adx_country` (`id`, `ucode`, `name`, `code`, `flag`, `map_center`, `map_zoom`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'wer324234', 'Papua New Guinea', 'PG', '/public/uploads/gov/1720334913_2e134059cb076abab229.png', '-5.768426, 146.689070', '6', '2023-03-11 10:10:42', '', '2024-07-07 16:48:33', 'fkenny'),
(2, '16546456', 'Australia', 'AU', '', '', '', '2023-03-11 10:10:42', '', '2024-07-05 09:17:30', ''),
(11, '667917620975c1719211874', 'India', 'IN', '', '', '', '2024-06-24 16:51:14', 'fkenny', '2024-06-24 16:51:14', ''),
(12, '667918a8eb11e1719212200', 'India', 'IN', '', '', '', '2024-06-24 16:56:40', 'fkenny', '2024-06-24 16:56:40', '');

-- --------------------------------------------------------

--
-- Table structure for table `adx_district`
--

CREATE TABLE `adx_district` (
  `id` int(11) NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `district_code` varchar(20) NOT NULL,
  `district_name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `flag` varchar(255) DEFAULT NULL,
  `map_center` varchar(200) DEFAULT NULL,
  `map_zoom` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adx_district`
--

INSERT INTO `adx_district` (`id`, `ucode`, `district_code`, `district_name`, `country_id`, `province_id`, `flag`, `map_center`, `map_zoom`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(40, '668a3e3398ebb1720335923', '01', 'Ambunti/Drekikier District', 1, 107, '/public/uploads/gov/1720485665_d9523287e381e6bfa2e9.png', '-4.226649, 143.556513', 10, '2024-07-07 17:05:23', '2024-07-20 08:42:53', 'fkenny', 'pman'),
(41, '669aea6f5b0d31721428591', '02', 'Angoram District', 1, 107, NULL, '-4.053967, 144.072293', 6, '2024-07-20 08:36:31', '2024-08-31 01:32:21', 'pman', 'ngande@gmail.com'),
(42, '66a1d0f24a7301721880818', '03', 'Yangoru District', 1, 107, NULL, '-3.747448, 143.450567', 7, '2024-07-25 14:13:38', '2024-08-31 01:33:29', NULL, 'ngande@gmail.com'),
(43, '66a1db139d9571721883411', '04', 'Wosera Gawi District', 1, 107, NULL, '-3.876237, 143.019682', 8, '2024-07-25 14:56:51', '2024-08-31 01:34:11', NULL, 'ngande@gmail.com'),
(44, '66d1e340ae9111725031232', 'WWK02', 'Wewak District', 0, 107, NULL, '-3.562240, 143.622447', 5, '2024-08-31 01:20:32', '2024-08-31 01:20:32', 'ngande@gmail.com', NULL),
(45, '66d1ff44034131725038404', 'NK01', 'Nuku District', 1, 108, NULL, '', 0, '2024-08-31 03:20:04', '2024-08-31 03:20:04', 'ngande@gmail.com', NULL),
(46, '6707394c491f61728526668', 'MADUB01', 'Usino Bundi', 1, 110, NULL, '', 0, '2024-10-10 12:17:48', '2024-10-10 12:17:48', 'ngande@gmail.com', NULL),
(47, '674387c3c39281732478915', '1904', 'Kainantu District', 1, 112, NULL, '', 0, '2024-11-25 06:08:35', '2024-11-25 06:08:35', 'ngande@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adx_llg`
--

CREATE TABLE `adx_llg` (
  `id` int(11) NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `llg_code` varchar(20) NOT NULL,
  `llg_name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `map_center` varchar(100) NOT NULL,
  `map_zoom` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adx_llg`
--

INSERT INTO `adx_llg` (`id`, `ucode`, `llg_code`, `llg_name`, `country_id`, `province_id`, `district_id`, `flag`, `map_center`, `map_zoom`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, '668c82dcad7ba1720484572', '02', 'Ambunti', 1, 107, 40, '/public/uploads/gov/1720487553_cba2bed0278fd1c37bbe.jpg', '-4.226649, 143.556513', 10, '2024-07-09 10:22:52', '2024-07-15 12:20:43', '', 'fkenny'),
(2, '668c86734528a1720485491', 'ABT03', 'Tunap/Hunstein', 1, 107, 40, '/public/uploads/gov/1721012911_43ad2ea27cbf0e5d7c4b.png', '-4.475922, 142.265302', 11, '2024-07-09 10:38:11', '2024-08-31 01:50:49', 'fkenny', 'ngande@gmail.com'),
(3, '66d1e9fa3ef101725032954', 'DRK02', 'Dreikikier LLG', 1, 107, 40, '', '-3.578155, 142.769982', 9, '2024-08-31 01:49:14', '2024-08-31 01:49:14', 'ngande@gmail.com', ''),
(4, '66d1ee5cc4b061725034076', 'WKU02', 'Wewak Urban LLG', 1, 107, 44, '', '-3.564665, 143.632203', 3, '2024-08-31 02:07:56', '2024-08-31 02:07:56', 'ngande@gmail.com', ''),
(5, '66d1f8d06f16e1725036752', 'DG03', 'Dagua LLG', 1, 107, 44, '', '', 0, '2024-08-31 02:52:32', '2024-08-31 02:52:32', 'ngande@gmail.com', ''),
(6, '66d2001b5ae6f1725038619', 'M03', 'Mai LLG', 1, 108, 45, '', '', 0, '2024-08-31 03:23:39', '2024-08-31 03:23:39', 'ngande@gmail.com', ''),
(7, '66d206b4b76431725040308', 'NK03', 'Nuku LLG', 1, 108, 45, '', '', 0, '2024-08-31 03:51:48', '2024-08-31 03:51:48', 'ngande@gmail.com', ''),
(8, '67032ad280fac1728260818', 'K01', 'Karawari ', 1, 107, 41, '', '', 0, '2024-10-07 10:26:58', '2024-10-07 10:26:58', 'ngande@gmail.com', ''),
(9, '674387f19b0701732478961', '190402', 'Agarabi LLG', 1, 112, 47, '', '', 0, '2024-11-25 06:09:21', '2024-11-25 06:09:21', 'ngande@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `adx_province`
--

CREATE TABLE `adx_province` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `province_code` varchar(20) NOT NULL,
  `province_name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `map_center` varchar(100) NOT NULL,
  `map_zoom` varchar(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adx_province`
--

INSERT INTO `adx_province` (`id`, `ucode`, `province_code`, `province_name`, `country_id`, `flag`, `map_center`, `map_zoom`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(107, '668a28186d45c1720330264', '14', 'East Sepik Province', 1, '/public/uploads/gov/1720334792_c60991ee1d4dadb08b19.png', '-4.226649, 143.556513', '6', NULL, 'fkenny', '2024-08-31 03:13:54', '19'),
(108, '668a283bb2aae1720330299', '15', 'Sandaun Province', 1, '', '', '5', NULL, 'fkenny', '2024-08-31 03:14:12', '19'),
(110, '3453565465', '13', 'Madang Province', 1, '', '-5.191644, 145.720761', '4', '2024-08-31 02:07:03', 'ngande@gmail.com', '2024-11-25 08:21:58', '19'),
(112, '674387acac8941732478892', '19', 'Eastern Highlands Province', 1, '', '', '', '2024-11-25 06:08:12', 'ngande@gmail.com', '2024-11-25 06:08:12', ''),
(113, '6743a6b3ef4061732486835', '05', 'National Capital District', 1, '', '', '', '2024-11-25 08:20:35', 'ngande@gmail.com', '2024-11-25 08:20:35', ''),
(114, '6743a6f376e911732486899', '18', 'Morobe Province', 1, '', '', '', '2024-11-25 08:21:39', 'ngande@gmail.com', '2024-11-25 08:21:39', '');

-- --------------------------------------------------------

--
-- Table structure for table `adx_village`
--

CREATE TABLE `adx_village` (
  `id` int(11) UNSIGNED NOT NULL,
  `ucode` varchar(20) NOT NULL,
  `village_code` varchar(20) NOT NULL,
  `village_name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `llg_id` int(11) NOT NULL,
  `ward_id` int(11) NOT NULL,
  `flag` varchar(255) DEFAULT NULL,
  `map_center` varchar(255) DEFAULT NULL,
  `map_zoom` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `updated_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adx_village`
--

INSERT INTO `adx_village` (`id`, `ucode`, `village_code`, `village_name`, `country_id`, `province_id`, `district_id`, `llg_id`, `ward_id`, `flag`, `map_center`, `map_zoom`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'dasjdjsakd', 'Vil01', 'Cool Village', 1, 107, 40, 2, 1, '1', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adx_ward`
--

CREATE TABLE `adx_ward` (
  `id` int(11) NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `ward_code` varchar(100) NOT NULL,
  `ward_name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `llg_id` int(11) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `map_center` varchar(100) NOT NULL,
  `map_zoom` int(11) NOT NULL,
  `pop_male` int(11) NOT NULL,
  `pop_female` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adx_ward`
--

INSERT INTO `adx_ward` (`id`, `ucode`, `ward_code`, `ward_name`, `country_id`, `province_id`, `district_id`, `llg_id`, `flag`, `map_center`, `map_zoom`, `pop_male`, `pop_female`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, '66948d02d59301721011458', 'Ward 2', 'Tunap Ward Two', 1, 107, 40, 2, '/public/uploads/gov/1721012718_00cd0060a1abdbbb6701.jpg', '-4.310433, 142.005536', 15, 0, 0, '2024-07-15 12:44:18', '2024-07-15 13:06:30', 'fkenny', 'fkenny'),
(2, '66d1ef895ca461725034377', 'WH01', 'Wewak Hill Ward One', 1, 107, 44, 4, '', '', 5, 20, 50, '2024-08-31 02:12:57', '2024-08-31 02:51:22', '', 'ngande@gmail.com'),
(3, '66d1f037072a21725034551', 'DG02', 'Dagua ', 1, 107, 44, 4, '', '', 23, 30, 5, '2024-08-31 02:15:51', '2024-08-31 02:51:37', '', 'ngande@gmail.com'),
(4, '66d1f53492eca1725035828', 'KD03', 'Kaindi ', 1, 107, 44, 4, '', '-3.572234, 143.615840', 4, 5, 30, '2024-08-31 02:37:08', '2024-08-31 02:45:59', 'ngande@gmail.com', 'ngande@gmail.com'),
(5, '66d1f8efad1711725036783', 'DG01', 'Dagu Ward one', 1, 107, 44, 5, '', '', 0, 5, 10, '2024-08-31 02:53:03', '2024-08-31 02:53:03', 'ngande@gmail.com', ''),
(6, '66d1f90ccd8271725036812', 'DG2', 'Dagu Ward two', 1, 107, 44, 5, '', '', 0, 4, 3, '2024-08-31 02:53:32', '2024-08-31 02:53:32', 'ngande@gmail.com', ''),
(7, '66d2028334fbf1725039235', 'Mw01', 'Mai Ward one', 1, 108, 45, 6, '', '', 0, 30, 21, '2024-08-31 03:33:55', '2024-08-31 03:33:55', 'ngande@gmail.com', ''),
(8, '66d206dad10c51725040346', 'MW02', 'Mai Ward two', 1, 108, 45, 6, '', '', 0, 3, 30, '2024-08-31 03:52:26', '2024-08-31 03:52:26', 'ngande@gmail.com', ''),
(9, '67032af1844551728260849', 'KW05', 'Ward 05', 1, 107, 41, 8, '', '', 0, 12, 45, '2024-10-07 10:27:29', '2024-10-07 10:27:29', 'ngande@gmail.com', ''),
(10, '67032b1ec344c1728260894', 'KW08', 'Ward 08', 1, 107, 41, 8, '', '', 0, 5, 10, '2024-10-07 10:28:14', '2024-10-07 10:28:14', 'ngande@gmail.com', ''),
(11, '670b71836cc391728803203', 'ABTW1', 'Ward One', 1, 107, 40, 1, '', '', 0, 23, 45, '2024-10-13 17:06:43', '2024-10-13 17:06:43', 'ngande@gmail.com', ''),
(12, '6743880e358f71732478990', '19040201', 'Anava Ward 1', 1, 112, 47, 9, '', '', 0, 200, 250, '2024-11-25 06:09:50', '2024-11-25 06:09:50', 'ngande@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `commodity_boards`
--

CREATE TABLE `commodity_boards` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodity_boards`
--

INSERT INTO `commodity_boards` (`id`, `name`, `address`, `contact_person`, `phone`, `email`, `status`, `remarks`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(2, 'Kokonut Indastri Koporesen', 'Kokonas Indastri Koporesen,\r\nPapua New Guinea', 'KIK', '23423840', 'kik@gmail.com', 'Active', 'This is KIK', '2024-11-19 06:21:15', '2024-11-25 05:58:01', NULL, NULL),
(5, 'Spice Board', 'Spice Board,\r\nPort Moresby, NCD, \r\nPapua New Guinea', 'Spice Admin', '23423840', 'pngspice@gmail.com', 'Active', 'This is the spice board', '2024-11-19 06:34:08', '2024-11-25 05:53:41', NULL, NULL),
(6, 'Cocoa Board', 'Cocoa Board,\r\nPapua New Guinea', 'Cocoa Admin', '7647657', 'cocoa@gmail.com', 'Active', 'This is cocoa board', '2024-11-19 07:21:58', '2024-11-25 05:55:32', NULL, NULL),
(7, 'Oil Plan Board', 'Oil Palm\r\nPapua New Guinea', 'Oil Palm Guy', '666777888', 'oilpalm@gmail.com', 'Active', 'This is oil palm', '2024-11-19 11:44:19', '2024-11-25 05:58:34', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--

CREATE TABLE `crops` (
  `id` int(11) NOT NULL,
  `crop_name` varchar(255) NOT NULL,
  `crop_icon` varchar(255) NOT NULL,
  `crop_color_code` varchar(7) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crops`
--

INSERT INTO `crops` (`id`, `crop_name`, `crop_icon`, `crop_color_code`, `remarks`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Cocoa', '/public/uploads/crops/1731965794_94bc3cade606a537d322.png', '#8a630f', 'This si cocoa', '2024-11-19 14:15:38', '2024-11-19 14:15:38', NULL, NULL),
(4, 'Oil Palm', '', '#4babbe', 'Oil Palm', '2024-11-19 14:15:38', '2024-11-19 14:15:38', NULL, NULL),
(5, 'Coffee', '', '#000000', '', '2024-11-25 10:56:23', '2024-11-25 10:56:23', NULL, NULL);

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dakoii_users`
--

INSERT INTO `dakoii_users` (`id`, `name`, `username`, `password`, `orgcode`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Free Kenny', 'fkenny', '$2y$10$A.8jXDJcv/wbzVi3l8bt/OPY6B0FpExgbUg.HOk6Khq9CYvKNQCyK', '', 'dakoii', 1, '2023-03-16 06:49:23', '2024-07-04 23:10:01');

-- --------------------------------------------------------

--
-- Table structure for table `dev_dip`
--

CREATE TABLE `dev_dip` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_code` varchar(100) NOT NULL,
  `dip_name` varchar(255) NOT NULL,
  `dip_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_dip`
--

INSERT INTO `dev_dip` (`id`, `ucode`, `country_id`, `mtdp_id`, `spa_id`, `dip_code`, `dip_name`, `dip_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(113, '66acda14d7e021722604052', 1, 110, 115, 'DIP1.1', 'Commercial Agriculture and Livestock Development', '', '2024-08-02 23:07:32', 'ngande@gmail.com', '2024-11-25 07:12:22', 'ngande@gmail.com'),
(114, '674392435223a1732481603', 1, 110, 123, 'DIP12.2', ' Development and Economic Cooperation', '', '2024-11-25 06:53:23', 'ngande@gmail.com', '2024-11-25 06:53:23', ''),
(115, '67439256147d11732481622', 1, 110, 123, 'DIP12.3', ' Private Sector', '', '2024-11-25 06:53:42', 'ngande@gmail.com', '2024-11-25 06:53:42', ''),
(116, '6743926be06cf1732481643', 1, 110, 122, 'DIP11.2', ' Youth Development and Labour Mobility Youth MSMEs', '', '2024-11-25 06:54:03', 'ngande@gmail.com', '2024-11-25 06:54:03', ''),
(117, '6743927a20c261732481658', 1, 110, 122, 'DIP11.4', 'Women Empowerment', '', '2024-11-25 06:54:18', 'ngande@gmail.com', '2024-11-25 06:54:18', ''),
(118, '67439296798951732481686', 1, 110, 121, 'DIP10.1', ' Climate Change Mitigation and Adaptation', '', '2024-11-25 06:54:46', 'ngande@gmail.com', '2024-11-25 06:54:46', ''),
(119, '674392b3065211732481715', 1, 110, 121, 'DIP10.2', 'National Disaster Management', '', '2024-11-25 06:55:15', 'ngande@gmail.com', '2024-11-25 06:55:15', ''),
(120, '6743958f2ba0e1732482447', 1, 110, 120, 'DIP9.3', ' Agriculture Research', '', '2024-11-25 07:07:27', 'ngande@gmail.com', '2024-11-25 07:07:27', ''),
(121, '674395a22a79b1732482466', 1, 110, 120, 'DIP9.6', 'Food Security', '', '2024-11-25 07:07:46', 'ngande@gmail.com', '2024-11-25 07:07:46', ''),
(122, '674395c19eca01732482497', 1, 110, 119, 'DIP8.1', ' Integrated Digital Government System', '', '2024-11-25 07:08:17', 'ngande@gmail.com', '2024-11-25 07:08:17', ''),
(123, '674395dc90d601732482524', 1, 110, 119, 'DIP8.2', ' National Statistical System', '', '2024-11-25 07:08:44', 'ngande@gmail.com', '2024-11-25 07:08:44', ''),
(124, '674395eb8c96e1732482539', 1, 110, 119, 'DIP8.6', 'Public Service Administration', '', '2024-11-25 07:08:59', 'ngande@gmail.com', '2024-11-25 07:08:59', ''),
(125, '674395fdd2baa1732482557', 1, 110, 119, 'DIP8.7', 'Public Service Governance', '', '2024-11-25 07:09:17', 'ngande@gmail.com', '2024-11-25 07:09:17', ''),
(126, '67439618b5e741732482584', 1, 110, 118, 'DIP6.4', ' Biosecurity - National Agriculture Quarantine and    Inspection Authority (NAQIA)', '', '2024-11-25 07:09:44', 'ngande@gmail.com', '2024-11-25 07:09:44', ''),
(127, '67439628b6cce1732482600', 1, 110, 118, 'DIP6.5', ' Import Product Certification', '', '2024-11-25 07:10:00', 'ngande@gmail.com', '2024-11-25 07:10:00', ''),
(128, '674396462329a1732482630', 1, 110, 117, 'DIP4.3', ' Elevation of Highlands Agriculture Training Institute    (HATI) to university', '', '2024-11-25 07:10:30', 'ngande@gmail.com', '2024-11-25 07:10:30', ''),
(129, '67439660d58b01732482656', 1, 110, 116, 'DIP2.1', ' Connect PNG Road Transport / District Commodity Roads', '', '2024-11-25 07:10:56', 'ngande@gmail.com', '2024-11-25 07:10:56', ''),
(130, '6743966e142341732482670', 1, 110, 116, 'DIP2.2', 'Connect PNG Water/Sea Transport', '', '2024-11-25 07:11:10', 'ngande@gmail.com', '2024-11-25 07:11:10', ''),
(131, '6743967c7ae961732482684', 1, 110, 116, 'DIP2.3', 'Connect PNG Air Transport', '', '2024-11-25 07:11:24', 'ngande@gmail.com', '2024-11-25 07:11:24', ''),
(132, '674396889494c1732482696', 1, 110, 116, 'DIP2.4', 'Connect PNG Telecommunication and ICT Connectivity', '', '2024-11-25 07:11:36', 'ngande@gmail.com', '2024-11-25 07:11:36', ''),
(133, '67439695284071732482709', 1, 110, 116, 'DIP2.5', ' Connect PNG Electricity Roll Out', '', '2024-11-25 07:11:49', 'ngande@gmail.com', '2024-11-25 07:11:49', ''),
(134, '674396c6310b41732482758', 1, 110, 115, 'DIP1.6', 'Micro, Small Medium Enterprises', '', '2024-11-25 07:12:38', 'ngande@gmail.com', '2024-11-25 07:12:38', ''),
(135, '674396d95cc541732482777', 1, 110, 115, 'DIP1.9', 'Manufacturing - Industrial Hub and Market Access Support    Programme', '', '2024-11-25 07:12:57', 'ngande@gmail.com', '2024-11-25 07:12:57', ''),
(136, '674396ea760f41732482794', 1, 110, 115, 'DIP1.10', ' Land Development', '', '2024-11-25 07:13:14', 'ngande@gmail.com', '2024-11-25 07:13:14', ''),
(137, '674396f6870d91732482806', 1, 110, 115, 'DIP1.11', 'Downstream Processing', '', '2024-11-25 07:13:26', 'ngande@gmail.com', '2024-11-25 07:13:26', ''),
(138, '6745198f727df1732581775', 0, 114, 124, 'DIP1.1', 'Commercial Agriculture and Livestock Development', '', '2024-11-26 10:42:55', 'itakinida@gmail.com', '2024-11-26 10:42:55', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_indicators`
--

CREATE TABLE `dev_indicators` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_id` int(11) NOT NULL,
  `invest_id` int(11) NOT NULL,
  `kra_id` int(11) NOT NULL,
  `in_code` varchar(100) NOT NULL,
  `in_indicator` text NOT NULL,
  `in_source` text NOT NULL,
  `in_baseline` text NOT NULL,
  `in_baseyear` varchar(20) NOT NULL,
  `in_year1` varchar(255) NOT NULL,
  `in_year2` varchar(255) NOT NULL,
  `in_year3` varchar(255) NOT NULL,
  `in_year4` varchar(255) NOT NULL,
  `in_year5` varchar(255) NOT NULL,
  `in_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_indicators`
--

INSERT INTO `dev_indicators` (`id`, `ucode`, `country_id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `invest_id`, `kra_id`, `in_code`, `in_indicator`, `in_source`, `in_baseline`, `in_baseyear`, `in_year1`, `in_year2`, `in_year3`, `in_year4`, `in_year5`, `in_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(112, '66acdbec8dc3e1722604524', 1, 110, 115, 113, 111, 112, 115, 'In1.2', 'Indicates', 'DAL', '23', '2012', '24', '56', '2.74', '1.3', '3.45', 'Indicator Remarkses', '2024-08-02 23:15:24', 'ngande@gmail.com', '2024-08-02 23:20:30', 'ngande@gmail.com'),
(113, '66b352fa06b0b1723028218', 1, 110, 115, 113, 111, 112, 115, '2.1', 'Incia', 'Cool source', '23', '2012', '11', '2', '23', '43', '43', 'This remarks', '2024-08-07 20:56:58', 'ngande@gmail.com', '2024-08-07 20:56:58', ''),
(114, '6726b187e91e81730589063', 1, 110, 115, 113, 111, 112, 116, '112', 'Indicating', 'For Cos', '299', '2012', '233', '22', '44', '33', '55', 'This si remarks', '2024-11-03 09:11:03', 'ngande@gmail.com', '2024-11-03 09:11:03', ''),
(115, '67439c08848c71732484104', 1, 110, 115, 113, 111, 112, 119, '1', 'Value of exports for all commodities (K’mil)', 'QEB', ' 3,783.1', '2020', '5434.5', '6008.0', '6665.3', '7556.4', '8585.9', '', '2024-11-25 07:35:04', 'ngande@gmail.com', '2024-11-25 07:35:04', ''),
(116, '67439c72e5e961732484210', 1, 110, 115, 113, 111, 112, 119, '2', ' Volume of exports for all commodities (‘000  tonnes)', ' QEB', '1,174.0', '2020', '1405.5', '1553.4', '1705.6', '1936.8', '2194.0', '', '2024-11-25 07:36:50', 'ngande@gmail.com', '2024-11-25 07:36:50', ''),
(117, '67439d265caa31732484390', 1, 110, 115, 113, 111, 112, 119, '3', ' GDP contribution of the sector (%)', 'DAL/NSO', ' 13.2', '2020', '13.4', '13.6', '13.8', '14', '14.2', '', '2024-11-25 07:39:50', 'ngande@gmail.com', '2024-11-25 07:39:50', ''),
(118, '67451c33e43f71732582451', 0, 114, 124, 138, 112, 117, 122, '2', 'Export Volume (000Tonnes)', 'QEB', '657.5', '2020', '944.0', '1014', '1090', '1185', '1300', '', '2024-11-26 10:54:11', 'itakinida@gmail.com', '2024-11-26 10:54:11', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_investments`
--

CREATE TABLE `dev_investments` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_id` int(11) NOT NULL,
  `invest_code` varchar(100) NOT NULL,
  `investment` varchar(255) NOT NULL,
  `in_funding_source` varchar(255) NOT NULL,
  `in_start_year` varchar(20) NOT NULL,
  `in_year1` varchar(255) NOT NULL,
  `in_year2` varchar(255) NOT NULL,
  `in_year3` varchar(255) NOT NULL,
  `in_year4` varchar(255) NOT NULL,
  `in_year5` varchar(255) NOT NULL,
  `invest_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_investments`
--

INSERT INTO `dev_investments` (`id`, `ucode`, `country_id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `invest_code`, `investment`, `in_funding_source`, `in_start_year`, `in_year1`, `in_year2`, `in_year3`, `in_year4`, `in_year5`, `invest_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(112, '66acda8e246851722604174', 1, 110, 115, 113, 111, '1.1.1', ' National Agriculture Development Program', 'GoPNG', '2023', '20', '20', '20', '10', '10', '', '2024-08-02 23:09:34', 'ngande@gmail.com', '2024-11-25 07:16:41', 'ngande@gmail.com'),
(113, '674397e3a64221732483043', 1, 110, 115, 113, 111, '1.1.2', ' Spice Development Program', 'GoPNG', '2023', '2', '3', '3', '3', '3', '', '2024-11-25 07:17:23', 'ngande@gmail.com', '2024-11-25 07:17:23', ''),
(114, '67439829bb12f1732483113', 1, 110, 115, 113, 111, '1.1.3', ' National Freight Subsidy Program', 'GoPNG', '2023', '20', '30', '35', '35', '45', '', '2024-11-25 07:18:33', 'ngande@gmail.com', '2024-11-25 07:18:33', ''),
(115, '6743984ae8fcf1732483146', 1, 110, 115, 113, 111, '1.1.5', 'Commodity Price Support Program', 'GoPNG', '2023', '30', '30', '30', '30', '30', '', '2024-11-25 07:19:06', 'ngande@gmail.com', '2024-11-25 07:19:06', ''),
(116, '67439899011a11732483225', 1, 110, 115, 113, 111, '1.1.6', 'Industrial Hub and Market access support program', 'GoPNG', '2023', '10', '10', '10', '10', '10', '', '2024-11-25 07:20:25', 'ngande@gmail.com', '2024-11-25 07:20:25', ''),
(117, '67451a953b54d1732582037', 0, 114, 124, 138, 112, 'DIP1.1', 'Oil Palm Development Program', 'GoPNG', '2023', '30', '30', '30', '30', '30', '', '2024-11-26 10:47:17', 'itakinida@gmail.com', '2024-11-26 10:47:17', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_kra`
--

CREATE TABLE `dev_kra` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_id` int(11) NOT NULL,
  `invest_id` int(11) NOT NULL,
  `kra_code` varchar(100) NOT NULL,
  `kra_description` text NOT NULL,
  `kra_start_year` varchar(20) NOT NULL,
  `kra_year1` varchar(255) NOT NULL,
  `kra_year2` varchar(255) NOT NULL,
  `kra_year3` varchar(255) NOT NULL,
  `kra_year4` varchar(255) NOT NULL,
  `kra_year5` varchar(255) NOT NULL,
  `kra_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_kra`
--

INSERT INTO `dev_kra` (`id`, `ucode`, `country_id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `invest_id`, `kra_code`, `kra_description`, `kra_start_year`, `kra_year1`, `kra_year2`, `kra_year3`, `kra_year4`, `kra_year5`, `kra_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(115, '66acdb44a9cb31722604356', 1, 110, 115, 113, 111, 112, '1', 'Number of large-scale run-down plantations rehabilitated \r\nand revived', '2023', '4', '8', '12', '14', '16', ' All Commodities/DAL/\r\n PGs/KCH', '2024-08-02 23:12:36', 'ngande@gmail.com', '2024-11-25 07:23:16', 'ngande@gmail.com'),
(116, '6726a2be6a3491730585278', 1, 110, 115, 113, 111, 112, '2', ' Number of smallholder farmers supported through MSME \r\nFinancing (000s)', '2023', '5', '12', '20', '30', '40', ' NDAL/MSMEC/DCI/\r\n PG', '2024-11-03 08:07:58', 'ngande@gmail.com', '2024-11-25 07:24:09', 'ngande@gmail.com'),
(117, '674399a6f326a1732483494', 1, 110, 115, 113, 111, 112, '3', ' Number of value chain/storage facilities established', '2023', '2', '5', '8', '12', '15', ' DAL/FPDA', '2024-11-25 07:24:54', 'ngande@gmail.com', '2024-11-25 07:24:54', ''),
(118, '67439aa21c3061732483746', 1, 110, 115, 113, 111, 112, '4', ' Number of Special Economic Zones for agriculture \r\ndeveloped', '2023', '1', '3', '5', '7', '10', ' NDAL/SEZA/DNPM/\r\n PG/PPP', '2024-11-25 07:29:06', 'ngande@gmail.com', '2024-11-25 07:29:24', 'ngande@gmail.com'),
(119, '67439b076c9d81732483847', 1, 110, 115, 113, 111, 112, '5', ' Number of Downstream Processing in agriculture', '2023', '0', '5', '10', '14', '20', ' SEZA/DAL/DITI', '2024-11-25 07:30:47', 'ngande@gmail.com', '2024-11-25 07:30:47', ''),
(120, '67439f18f0dd01732484888', 1, 110, 115, 113, 111, 116, '6', 'Number of Provincial and District markets established', '2023', '10', '25', '40', '60', '80', ' PG/DoWH/DDAs/DP', '2024-11-25 07:48:08', 'ngande@gmail.com', '2024-11-25 07:48:08', ''),
(121, '67439f66e3acc1732484966', 1, 110, 115, 113, 111, 114, '8', 'Number of farmers benefited from price stabilisation \r\nProgram (‘000s)', '2023', '10', '20', '30', '40', '50', ' DAL/ All Commodity \r\nBoards', '2024-11-25 07:49:26', 'ngande@gmail.com', '2024-11-25 07:49:26', ''),
(122, '67451b258ca651732582181', 0, 114, 124, 138, 112, 117, '2', 'Total Number of Rundown Oil Palm Plantations Redevelop', '2023', '1', '3', '5', '7', '10', '', '2024-11-26 10:49:41', 'itakinida@gmail.com', '2024-11-26 10:49:41', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_mtdp`
--

CREATE TABLE `dev_mtdp` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `mtdp_code` varchar(100) NOT NULL,
  `mtdp_name` varchar(255) NOT NULL,
  `mtdp_remarks` text NOT NULL,
  `baseline_year` int(11) NOT NULL,
  `start_year` int(11) NOT NULL,
  `end_year` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_mtdp`
--

INSERT INTO `dev_mtdp` (`id`, `ucode`, `country_id`, `mtdp_code`, `mtdp_name`, `mtdp_remarks`, `baseline_year`, `start_year`, `end_year`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(110, '66a6e4b3674901722213555', 1, 'MTDP4', 'Medium Term Development Plan IV', 'The MTDP4', 2020, 2023, 2027, 'active', '2024-07-29 10:39:15', 'ngande@gmail.com', '2024-11-25 06:41:39', 'ngande@gmail.com'),
(114, '674518a9207ad1732581545', 0, 'MTDP1V', 'Medium Term Development Plan 1V', '', 2020, 2023, 2027, 'active', '2024-11-26 10:39:05', 'itakinida@gmail.com', '2024-11-26 10:39:05', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_nasp`
--

CREATE TABLE `dev_nasp` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `level` varchar(100) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_nasp`
--

INSERT INTO `dev_nasp` (`id`, `parent_id`, `code`, `title`, `level`, `remarks`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(13, NULL, 'NSAP2024-2033', 'National Agriculture Sector Plan', 'plan', '', '19', '2024-11-25 07:52:25', NULL, '2024-11-25 07:52:25'),
(14, 13, 'KRA1', 'Enhanced Productivity, Increased Scale of Production and Market    Competitiveness', 'KRA', '', '19', '2024-11-25 07:53:21', NULL, '2024-11-25 07:53:21'),
(15, 13, 'KRA2', ' Improved Infrastructure and Access to Markets ', 'KRA', '', '19', '2024-11-25 07:53:37', NULL, '2024-11-25 07:53:37'),
(16, 13, 'KRA3', 'Increased Agriculture Commodity Investments and Exports', 'KRA', '', '19', '2024-11-25 07:53:49', NULL, '2024-11-25 07:53:49'),
(17, 13, 'KRA4', 'Effective Land Mobilisation, Use and Management', 'KRA', '', '19', '2024-11-25 07:54:03', NULL, '2024-11-25 07:54:03'),
(18, 13, 'KRA5', 'Enabling Policy and Legal Environment for Strategic Private    Sector Participation and Investor-Friendly Climate', 'KRA', '', '19', '2024-11-25 07:54:19', NULL, '2024-11-25 07:54:19'),
(19, 13, 'KRA6', 'Comprehensive Research and Development', 'KRA', '', '19', '2024-11-25 07:54:42', NULL, '2024-11-25 07:54:42'),
(20, 13, 'KRA7', 'Integrated Agriculture Education, Training and Extension    Services', 'KRA', '', '19', '2024-11-25 07:54:56', NULL, '2024-11-25 07:54:56'),
(21, 13, 'KRA8', 'Biosecurity', 'KRA', '', '19', '2024-11-25 07:55:08', NULL, '2024-11-25 07:55:08'),
(22, 13, 'KRA9', 'Food and Nutrition Security and Safety Standards ', 'KRA', '', '19', '2024-11-25 07:55:25', NULL, '2024-11-25 07:55:25'),
(23, 13, 'KRA10', ' Climate-Smart Agriculture ', 'KRA', '', '19', '2024-11-25 07:55:41', NULL, '2024-11-25 07:55:41'),
(24, 13, 'KRA11', 'Institutional Reform and Sector Development ', 'KRA', '', '19', '2024-11-25 07:55:54', NULL, '2024-11-25 07:55:54'),
(25, 13, 'KRA12', 'Information Management and Use - Information Communication    Technology', 'KRA', '', '19', '2024-11-25 07:56:06', NULL, '2024-11-25 07:56:06'),
(26, 13, 'KRA13', 'National Agriculture Sector Plan Management', 'KRA', '', '19', '2024-11-25 07:56:21', NULL, '2024-11-25 07:56:21'),
(27, 14, '1.1', ' Commodity Commercialisation', 'Objective', '', '19', '2024-11-25 07:58:32', NULL, '2024-11-25 07:58:32'),
(28, 14, '1.2', 'Downstream Processing of Selected Crops and Livestock ', 'Objective', '', '19', '2024-11-25 07:58:59', NULL, '2024-11-25 07:58:59'),
(29, 14, '1.3', 'Create enabling legislations and policies for downstream processing', 'Objective', '', '19', '2024-11-25 07:59:20', NULL, '2024-11-25 07:59:20'),
(30, 14, '1.4', 'Value Adding and Establishment of Value Chains', 'Objective', '', '19', '2024-11-25 07:59:34', NULL, '2024-11-25 07:59:34'),
(31, 14, '1.5', 'Rehabilitation and Revival of Large-Scale Run-Down Plantations', 'Objective', '', '19', '2024-11-25 07:59:48', NULL, '2024-11-25 07:59:48'),
(32, 14, '1.6', 'Developing Special Economic Zones for Agriculture', 'Objective', '', '19', '2024-11-25 08:00:04', NULL, '2024-11-25 08:00:04'),
(33, 14, '1.7', 'Developing Free Trade Zones for Agriculture', 'Objective', '', '19', '2024-11-25 08:00:16', NULL, '2024-11-25 08:00:16'),
(34, 14, '1.8', 'Development of Special Economic Zones for agriculture', 'Objective', '', '19', '2024-11-25 08:00:30', NULL, '2024-11-25 08:00:30'),
(35, 14, '1.9', ' Support Extension Services to farmers and agribusiness enterprises', 'Objective', '', '19', '2024-11-25 08:00:48', NULL, '2024-11-25 08:00:48'),
(36, 14, '1.10', 'Establish Freight Subsidy Scheme for Farmers', 'Objective', '', '19', '2024-11-25 08:01:03', NULL, '2024-11-25 08:01:03'),
(37, 14, '1.11', ' Establish Price Stabilization Program for Farmers', 'Objective', '', '19', '2024-11-25 08:01:17', NULL, '2024-11-25 08:01:17'),
(38, 14, '1.12', ' Support farmers and MSMEs - Access to Markets', 'Objective', '', '19', '2024-11-25 08:01:32', NULL, '2024-11-25 08:01:32'),
(39, 14, '1.13', 'Support Smallholder Farmers and (MSME) - Access to Financing', 'Objective', '', '19', '2024-11-25 08:01:51', NULL, '2024-11-25 08:01:51'),
(40, 15, '2.1', ' Improve infrastructure and transport for access to domestic markets ', 'Objective', '', '19', '2024-11-25 08:02:23', NULL, '2024-11-25 08:02:23'),
(41, 15, '2.2', 'Improve access to international markets and trade', 'Objective', '', '19', '2024-11-25 08:02:56', NULL, '2024-11-25 08:02:56'),
(42, 15, '2.3', 'Establish Provincial and District Infrastructure and Market Facilities', 'Objective', '', '19', '2024-11-25 08:03:21', NULL, '2024-11-25 08:03:21'),
(43, 16, '3.1', 'Economic Corridors for Investments in Agro-Industrial  Developments at Scale for domestic and international markets, and trade.', 'Objective', '', '19', '2024-11-25 08:04:24', NULL, '2024-11-25 08:04:24'),
(44, 17, '4.1', 'Secure and Develop Land  for agricultural hubs', 'Objective', '', '19', '2024-11-25 08:05:26', NULL, '2024-11-25 08:05:26'),
(45, 18, '5.1', ' Improve policy and legal  impediments to participation of private  sector', 'Objective', '', '19', '2024-11-25 08:06:05', NULL, '2024-11-25 08:06:05'),
(46, 19, '6.1', 'Support learning, research and development in innovations', 'Objective', '', '19', '2024-11-25 08:06:34', NULL, '2024-11-25 08:06:34'),
(47, 19, '6.2', 'Review biosecurity strategy for research programme on invasive   pests', 'Objective', '', '19', '2024-11-25 08:06:57', NULL, '2024-11-25 08:06:57'),
(48, 19, '6.3', 'Crops Research', 'Objective', '', '19', '2024-11-25 08:07:16', NULL, '2024-11-25 08:07:16'),
(49, 19, '6.4', ' Livestock Research', 'Objective', '', '19', '2024-11-25 08:07:36', NULL, '2024-11-25 08:07:36'),
(50, 19, '6.5', 'Market Research', 'Objective', '', '19', '2024-11-25 08:07:58', NULL, '2024-11-25 08:07:58'),
(51, 19, '6.6', ' Increasing Research Capacity', 'Objective', '', '19', '2024-11-25 08:08:16', NULL, '2024-11-25 08:08:16'),
(52, 19, '6.7', 'Fostering Collaborative Networks', 'Objective', '', '19', '2024-11-25 08:08:35', NULL, '2024-11-25 08:08:35'),
(53, 20, '7.1', 'Agriculture Education, Training Strategies', 'Objective', '', '19', '2024-11-25 08:09:32', NULL, '2024-11-25 08:09:32'),
(54, 20, '7.2', 'Promote effective agriculture extension delivery systems in    provinces ', 'Objective', '', '19', '2024-11-25 08:09:52', NULL, '2024-11-25 08:09:52'),
(55, 21, '8.1', 'Develop innovative  research programmes to address  invasive pests and insects', 'Objective', '', '19', '2024-11-25 08:10:25', NULL, '2024-11-25 08:10:25'),
(56, 22, '9.1', ' Focus at the national and agro-ecological levels on the development  of key staple crops.', 'Objective', '', '19', '2024-11-25 08:10:55', NULL, '2024-11-25 08:10:55'),
(57, 22, '9.2', 'Strengthen the national Codex management and coordination  system.', 'Objective', '', '19', '2024-11-25 08:11:11', NULL, '2024-11-25 08:11:11'),
(58, 22, '9.3', 'Enhance Codex and food safety activities. ', 'Objective', '', '19', '2024-11-25 08:11:39', NULL, '2024-11-25 08:11:39'),
(59, 22, '9.4', 'Ensure fair practice is maintained by food manufacturers and  importers.', 'Objective', '', '19', '2024-11-25 08:11:56', NULL, '2024-11-25 08:11:56'),
(60, 24, '11.1', ' Improved leadership, governance, and management  Strategies', 'Objective', '', '19', '2024-11-25 08:12:37', NULL, '2024-11-25 08:12:37'),
(61, 24, '11.2', ' Improved Financial Management within agriculture sector ', 'Objective', '', '19', '2024-11-25 08:12:56', NULL, '2024-11-25 08:12:56'),
(62, 24, '11.3', ' Qualified and Skilled Workforce capable of meeting sector needs ', 'Objective', '', '19', '2024-11-25 08:13:13', NULL, '2024-11-25 08:13:13'),
(63, 24, '11.4', ' Establish Strong Governance and Administration System to drive the  Sector.', 'Objective', '', '19', '2024-11-25 08:13:28', NULL, '2024-11-25 08:13:28'),
(64, 24, '11.5', ' Effective Sector Management and Coordination', 'Objective', '', '19', '2024-11-25 08:13:43', NULL, '2024-11-25 08:13:43'),
(66, 26, '13.1', ' Establish a Secretariat responsible for effective management of NASP  implementation', 'Objective', '', '19', '2024-11-25 08:14:28', NULL, '2024-11-25 08:14:28'),
(67, 27, 'KRA1 A', 'Enhance Productivity Increase Scale of Production and Market Competitiveness', 'KRA', '', '39', '2024-11-26 10:58:09', NULL, '2024-11-26 10:58:09'),
(68, 67, 'Obective 1.1', 'Commodity Commercilisation', 'Objective', '', '39', '2024-11-26 10:59:28', NULL, '2024-11-26 10:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `dev_spa`
--

CREATE TABLE `dev_spa` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_code` varchar(100) NOT NULL,
  `spa_name` varchar(255) NOT NULL,
  `spa_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_spa`
--

INSERT INTO `dev_spa` (`id`, `ucode`, `country_id`, `mtdp_id`, `spa_code`, `spa_name`, `spa_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(115, '66acd9f84b26b1722604024', 1, 110, 'SPA1', 'Strategic Economic Investments', 'Objective: To build a robust and resilient economy', '2024-08-02 23:07:04', 'ngande@gmail.com', '2024-11-25 06:42:43', 'ngande@gmail.com'),
(116, '66c2c88904fc81724041353', 1, 110, 'SPA2', 'Connect PNG Infrastructure', 'Objective:  Building country-wide critical enabling infrastructure  \r\nfor socio-economic connectivity', '2024-08-19 14:22:33', 'ngande@gmail.com', '2024-11-25 06:45:33', 'ngande@gmail.com'),
(117, '674390a63d4c31732481190', 1, 110, 'SPA4', ' Quality Education and Skilled Human Capital', 'Objective:  \r\nAchieving an educated, skilled and productive human   \r\ncapital that provides the enabling environment for resilient  \r\neconomic growth', '2024-11-25 06:46:30', 'ngande@gmail.com', '2024-11-25 06:46:30', ''),
(118, '674390e6104521732481254', 1, 110, 'SPA6', 'National Security', 'Goal: Strengthen national security through capacity enhancement of  \r\nsecurity agencies', '2024-11-25 06:47:34', 'ngande@gmail.com', '2024-11-25 06:47:34', ''),
(119, '674391155ff801732481301', 1, 110, 'SPA8', 'Digital Government, National Statistics, Public Service Governance', 'Objective: Strengthen good governance, efficient public service through \r\ndigital government transformation and anchored on a digitally \r\ndriven robust data collection, classification and statistical \r\nsystem', '2024-11-25 06:48:21', 'ngande@gmail.com', '2024-11-25 06:48:21', ''),
(120, '67439143a4a931732481347', 1, 110, 'SPA9', 'Research, Science and Technology', 'Objective: \r\nFor informed decision making through innovative  \r\nresearch, science and technology', '2024-11-25 06:49:07', 'ngande@gmail.com', '2024-11-25 06:49:07', ''),
(121, '6743916e481071732481390', 1, 110, 'SPA10', ' Climate Change and Natural Environment Protection', 'Objective:  Building a resilient economy from the adverse effect of  \r\nclimate change, environment degradation and natural   \r\ndisasters', '2024-11-25 06:49:50', 'ngande@gmail.com', '2024-11-25 06:49:50', ''),
(122, '674391b5cc0421732481461', 1, 110, 'SPA11', ' Population, Youth and Women Empowerment', 'Objective:  \r\nSustainable, Inclusive and Productive Population for   \r\ndevelopment', '2024-11-25 06:51:01', 'ngande@gmail.com', '2024-11-25 06:51:01', ''),
(123, '674391ea86cda1732481514', 1, 110, 'SPA12', ' Strategic Partnerships', 'Goal: Forging strategic partnerships for greater development results', '2024-11-25 06:51:54', 'ngande@gmail.com', '2024-11-25 06:51:54', ''),
(124, '674519070a9a01732581639', 0, 114, 'SPA 1', 'Strategic Economic Investment', '', '2024-11-26 10:40:39', 'itakinida@gmail.com', '2024-11-26 10:40:39', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_specific_areas`
--

CREATE TABLE `dev_specific_areas` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_code` varchar(100) NOT NULL,
  `sa_name` varchar(255) NOT NULL,
  `sa_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_specific_areas`
--

INSERT INTO `dev_specific_areas` (`id`, `ucode`, `country_id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_code`, `sa_name`, `sa_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(111, '66acda37775731722604087', 1, 110, 115, 113, '1.1', ' Commercial Agriculture and Livestock Development', '', '2024-08-02 23:08:07', 'ngande@gmail.com', '2024-11-25 07:15:26', 'ngande@gmail.com'),
(112, '674519fe62ff41732581886', 0, 114, 124, 138, '1.1.1', 'OIL PALM', '', '2024-11-26 10:44:46', 'itakinida@gmail.com', '2024-11-26 10:44:46', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_strategies`
--

CREATE TABLE `dev_strategies` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sa_id` int(11) NOT NULL,
  `invest_id` int(11) NOT NULL,
  `kra_id` int(11) NOT NULL,
  `st_code` varchar(100) NOT NULL,
  `st_description` text NOT NULL,
  `st_policyref` text NOT NULL,
  `st_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dev_strategies`
--

INSERT INTO `dev_strategies` (`id`, `ucode`, `country_id`, `mtdp_id`, `spa_id`, `dip_id`, `sa_id`, `invest_id`, `kra_id`, `st_code`, `st_description`, `st_policyref`, `st_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(112, '66acdb8cc7b931722604428', 1, 110, 0, 0, 111, 112, 115, 'ST1.2', 'Strategy description', 'Policy Reference', 'Remarks Strategys', '2024-08-02 23:13:48', 'ngande@gmail.com', '2024-08-02 23:14:03', 'ngande@gmail.com'),
(113, '6726b15e6356b1730589022', 1, 110, 0, 0, 111, 112, 116, 'Kool Straget', 'Stret Desciption', 'Pol1, Pol2 Pol3', 'REmarksess', '2024-11-03 09:10:22', 'ngande@gmail.com', '2024-11-03 09:10:22', ''),
(114, '67439b3da98561732483901', 1, 110, 0, 0, 111, 112, 119, '1', ' Provide legislative and policy frameworks to encourage downstream processing.', ' Agriculture MTDP', '', '2024-11-25 07:31:41', 'ngande@gmail.com', '2024-11-25 07:31:41', ''),
(115, '67439b6887fd21732483944', 1, 110, 0, 0, 111, 112, 119, '2', ' Establish strong governance and administration system with clear policy guidelines to drive the sector \r\nforward', 'Agriculture MTDP', '', '2024-11-25 07:32:24', 'ngande@gmail.com', '2024-11-25 07:32:24', ''),
(116, '67439b882a39e1732483976', 1, 110, 0, 0, 111, 112, 119, '3', ' Address major impediments to the growth of the sector which are infrastructure, law and order, customary \r\nland registration', ' Agriculture MTDP', '', '2024-11-25 07:32:56', 'ngande@gmail.com', '2024-11-25 07:32:56', ''),
(117, '67439f94cebec1732485012', 1, 110, 0, 0, 111, 114, 121, '9', 'Government subsidises Air Niugini to do international flights to deliver foods, vegetables and supplies \r\ndirectly to international niche markets', 'MTDP', '', '2024-11-25 07:50:12', 'ngande@gmail.com', '2024-11-25 07:50:12', ''),
(118, '67451b88081431732582280', 0, 114, 0, 0, 112, 117, 122, '2', 'Rehabilitate Blockholders Roads Infrastructure', 'MTDP 2018-2022', '', '2024-11-26 10:51:20', 'itakinida@gmail.com', '2024-11-26 10:51:20', '');

-- --------------------------------------------------------

--
-- Table structure for table `level_groups`
--

CREATE TABLE `level_groups` (
  `id` int(11) NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `ward_code` varchar(100) NOT NULL,
  `ward_name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `llg_id` int(11) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `map_center` varchar(100) NOT NULL,
  `map_zoom` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `level_groups`
--

INSERT INTO `level_groups` (`id`, `ucode`, `ward_code`, `ward_name`, `country_id`, `province_id`, `district_id`, `llg_id`, `flag`, `map_center`, `map_zoom`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, '66948d02d59301721011458', 'Ward 2', 'Tunap Ward Two', 1, 107, 40, 2, '/public/uploads/gov/1721012718_00cd0060a1abdbbb6701.jpg', '-4.310433, 142.005536', 15, '2024-07-15 12:44:18', '2024-07-15 13:06:30', 'fkenny', 'fkenny');

-- --------------------------------------------------------

--
-- Table structure for table `org_branches`
--

CREATE TABLE `org_branches` (
  `id` int(11) NOT NULL,
  `ucode` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_code` varchar(50) NOT NULL,
  `parent_branch_id` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `opening_date` date DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_by` varchar(100) NOT NULL,
  `updated_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `org_branches`
--

INSERT INTO `org_branches` (`id`, `ucode`, `branch_name`, `branch_code`, `parent_branch_id`, `address`, `city`, `province_id`, `country_id`, `postal_code`, `phone_number`, `email`, `manager_id`, `opening_date`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, '66cfeca9cb9741724902569', 'MOMASE Region Branch', 'NB002', 1, 'Momase Regional Office', 'Lae', 114, 1, '254', '712344556', 'momase@gmail.com', 35, '2024-08-01', 'Active', 'ngande@gmail.com', 'ngande@gmail.com', '2024-08-29 03:36:09', '2024-11-24 22:22:16'),
(3, '66d1161dba4311724978717', 'Highlands Region Branch', 'NB003', 1, 'Highlands Regional Branch', 'Goroka', 112, 1, '445', '74444444', 'highlandbranch@gmail.com', 33, '2024-08-14', 'Active', 'ngande@gmail.com', 'ngande@gmail.com', '2024-08-30 00:45:17', '2024-11-24 22:21:04'),
(4, '674386f89bd3b1732478712', 'HQ', 'NB001', 1, 'HQ in POM', 'Port Moresby', 113, 1, '556', '766666', 'hq@gmail.com', 29, '2024-11-13', 'Active', 'ngande@gmail.com', 'ngande@gmail.com', '2024-11-24 20:05:12', '2024-11-24 22:20:49');

-- --------------------------------------------------------

--
-- Table structure for table `production_data_commodity_boards`
--

CREATE TABLE `production_data_commodity_boards` (
  `id` int(11) NOT NULL,
  `data_source_id` int(11) NOT NULL,
  `crop_id` int(11) NOT NULL,
  `date_entry` date NOT NULL,
  `item` varchar(255) NOT NULL,
  `unit_of_measure` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_data_commodity_boards`
--

INSERT INTO `production_data_commodity_boards` (`id`, `data_source_id`, `crop_id`, `date_entry`, `item`, `unit_of_measure`, `quantity`, `remarks`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 6, 1, '2024-11-07', 'Rubber Cups', 'ML', 23.00, 'Tjdap', 0, '2024-11-18 22:27:03', 0, '2024-11-20 07:02:20'),
(2, 7, 4, '2024-11-12', 'Oil Palm Sales', 'KG', 20.00, '', 0, '2024-11-19 01:47:12', 0, '2024-11-20 07:11:40'),
(3, 6, 1, '2024-10-30', 'Dry Beans', 'Tonnes', 20.00, '', 0, '2024-11-25 04:58:17', NULL, '2024-11-25 04:58:17'),
(4, 6, 1, '2024-10-30', 'Dry Beans', 'Tonnes', 20.00, '', 0, '2024-11-27 03:14:25', NULL, '2024-11-27 03:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `production_data_provinces`
--

CREATE TABLE `production_data_provinces` (
  `id` int(11) NOT NULL,
  `data_source_id` int(11) NOT NULL,
  `crop_id` int(11) NOT NULL,
  `date_entry` date NOT NULL,
  `item` varchar(255) NOT NULL,
  `unit_of_measure` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_data_provinces`
--

INSERT INTO `production_data_provinces` (`id`, `data_source_id`, `crop_id`, `date_entry`, `item`, `unit_of_measure`, `quantity`, `remarks`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 107, 1, '2024-11-15', 'Coco bags', 'KG', 34.45, 'fssdffh', 0, '2024-11-18 22:41:34', 0, '2024-11-20 07:11:47');

-- --------------------------------------------------------

--
-- Table structure for table `production_data_sme`
--

CREATE TABLE `production_data_sme` (
  `id` int(11) NOT NULL,
  `data_source_id` int(11) NOT NULL,
  `crop_id` int(11) NOT NULL,
  `date_entry` date NOT NULL,
  `item` varchar(255) NOT NULL,
  `unit_of_measure` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_data_sme`
--

INSERT INTO `production_data_sme` (`id`, `data_source_id`, `crop_id`, `date_entry`, `item`, `unit_of_measure`, `quantity`, `remarks`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 110, 1, '2024-04-10', 'Coco Bag', 'KG', 56.00, 'This is ', 0, '2024-11-18 22:25:39', 0, '2024-11-20 07:02:41'),
(2, 109, 1, '2024-06-11', 'Fermented Cocoa', 'KG', 12.00, 'This is ithere rmemark', 0, '2024-11-18 22:58:51', 0, '2024-11-18 23:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `remindertable`
--

CREATE TABLE `remindertable` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reminder_status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `send_1` tinyint(1) DEFAULT 0,
  `send_2` tinyint(1) DEFAULT 0,
  `send_3` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remindertable`
--

INSERT INTO `remindertable` (`id`, `employee_id`, `supervisor_id`, `proposal_id`, `start_date`, `end_date`, `reminder_status`, `created_at`, `updated_at`, `send_1`, `send_2`, `send_3`) VALUES
(1, 21, 23, 15, '2024-12-02', '2024-12-19', 'Pending', '2024-11-22 14:40:34', '2024-11-29 05:55:41', 1, 1, 1),
(2, 21, 23, 16, '2024-11-25', '2024-12-12', 'progressing', '2024-11-22 14:42:15', '2024-11-22 16:46:48', 1, 1, 1),
(3, 21, 23, 17, '2024-12-05', '2024-12-12', 'Pending', '2024-11-22 14:45:48', '2024-12-02 10:23:46', 1, 1, 1),
(4, 21, 23, 18, '2024-11-25', '2024-11-29', 'Pending', '2024-11-23 16:22:12', '2024-11-23 16:25:06', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `selection`
--

CREATE TABLE `selection` (
  `id` int(11) NOT NULL,
  `box` varchar(20) NOT NULL,
  `value` varchar(200) NOT NULL,
  `item` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `selection`
--

INSERT INTO `selection` (`id`, `box`, `value`, `item`) VALUES
(19, 'eduqual', '11', 'phd'),
(20, 'eduqual', '12', 'masters'),
(21, 'eduqual', '13', 'post.grad.Diploma'),
(22, 'eduqual', '14', 'post.grad.Certificate'),
(23, 'eduqual', '15', 'undergrad (bachelors)'),
(24, 'eduqual', '16', 'advanced diploma'),
(25, 'eduqual', '17', 'diploma'),
(26, 'eduqual', '18', 'certificate 4'),
(27, 'eduqual', '19', 'trade certificate'),
(28, 'eduqual', '20', 'up.secondary (Gr.12)'),
(29, 'eduqual', '21', 'low.secondary (Gr.10)');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `value` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `value`, `name`, `create_at`) VALUES
(1, 'PG', 'country', '2023-03-11 13:50:34');

-- --------------------------------------------------------

--
-- Table structure for table `smes`
--

CREATE TABLE `smes` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `llg_id` int(11) NOT NULL,
  `ward_id` int(11) NOT NULL,
  `village_id` int(11) NOT NULL,
  `sme_name` varchar(255) NOT NULL,
  `product` text NOT NULL,
  `production_area` text NOT NULL,
  `contact_person` text NOT NULL,
  `contact_no` varchar(100) NOT NULL,
  `reg_with` text NOT NULL,
  `reg_no` varchar(100) NOT NULL,
  `x_coordinates` varchar(255) NOT NULL,
  `y_coordinates` varchar(255) NOT NULL,
  `sme_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smes`
--

INSERT INTO `smes` (`id`, `ucode`, `country_id`, `province_id`, `district_id`, `llg_id`, `ward_id`, `village_id`, `sme_name`, `product`, `production_area`, `contact_person`, `contact_no`, `reg_with`, `reg_no`, `x_coordinates`, `y_coordinates`, `sme_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(109, '66ac361beac0f1722562075', 1, 107, 40, 2, 1, 1, 'Cool SME ', 'Profjsdsfji', '', '', '224244', 'Cool max', '223144', '143.659269', '-3.57462', 'This is remarks', '2024-08-02 11:27:55', 'ngande@gmail.com', '2024-08-07 10:34:26', 'ngande@gmail.com'),
(110, '66b2b3feeebcd1722987518', 1, 107, 40, 2, 1, 1, 'Wonder SME', 'This is the product information', '', '', '22424477', 'Cool pick rop', '772231554', '553545', '345665', 'This is the remarks ess', '2024-08-07 09:38:38', 'ngande@gmail.com', '2024-08-07 10:34:41', 'ngande@gmail.com'),
(111, '66fe0b48e8b341727925064', 1, 107, 40, 2, 1, 1, 'Dummy SME One', 'Cocoa', '', '', '64345345', '', '', '-5.909094', '134.4390589', '', '2024-10-03 13:11:04', 'ngande@gmail.com', '2024-10-03 13:11:04', '');

-- --------------------------------------------------------

--
-- Table structure for table `sme_staff`
--

CREATE TABLE `sme_staff` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `sme_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `dobirth` date DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `smestaff_remarks` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sme_staff`
--

INSERT INTO `sme_staff` (`id`, `ucode`, `country_id`, `sme_id`, `fname`, `lname`, `gender`, `dobirth`, `designation`, `smestaff_remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(109, '66b2c42f768631722991663', 1, 110, 'Flamixe', 'Lamix', 'Male', '2005-11-12', '', 'This is the remarkses', '2024-08-07 10:47:43', 'ngande@gmail.com', '2024-08-07 11:03:40', 'ngande@gmail.com'),
(111, '66b2fdacdcaa21723006380', 1, 109, 'Fmember', 'Lmember', 'Male', '2001-11-11', 'Cooking boy', 'This is the members remarks', '2024-08-07 14:53:00', 'ngande@gmail.com', '2024-08-15 13:02:11', 'ngande@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `stakeholders`
--

CREATE TABLE `stakeholders` (
  `id` int(11) UNSIGNED NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `org_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL,
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `description` text NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(200) NOT NULL,
  `updated_by` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stakeholders`
--

INSERT INTO `stakeholders` (`id`, `ucode`, `org_id`, `name`, `type`, `level`, `phone`, `email`, `address`, `description`, `logo`, `is_active`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(18, '668a2abf304821720330943', 1, 'Cool ', 'gov', 'national', '723429', 'ngande@dakoiims.com', '', '', '', 1, '2024-07-07 05:42:23', '2024-07-07 05:42:23', 'fkenny', ''),
(19, '668a3dceee2471720335822', 107, 'Province One', 'gov', 'province', '2342545', 'ngande@dakoiims.com', '', '', '', 1, '2024-07-07 07:03:43', '2024-10-30 23:32:06', 'fkenny', 'ngande@gmail.com'),
(20, '6694d21b757bc1721029147', 40, 'District ', 'gov', 'district', '234543', '6456@rtet.com', '', '', '', 1, '2024-07-15 07:39:07', '2024-07-15 07:39:07', 'fkenny', ''),
(21, '6695df041c5ab1721097988', 107, 'Province Two', 'gov', 'province', '674354', 'fff@gmail.com', '', '', '', 1, '2024-07-16 02:46:28', '2024-10-30 23:32:20', 'pman', 'ngande@gmail.com'),
(22, '6695f9417a6cc1721104705', 107, 'East Sepik Cocoa Boardzz', 'gov', 'province', '13234234', 'ggsd@sotoe.com', 'This is the cocoa board', '', '/public/uploads/stakeholders/1721106407_25efc31cfbfece2b5a1e.png', 1, '2024-07-16 04:38:25', '2024-07-16 05:06:47', '', 'pman'),
(23, '6722c23846f131730331192', 107, 'Kokonut Indastri Koporesen', '', '', '444444', 'kik@gmail.com', 'Adresssss', '', '', 1, '2024-10-30 23:33:12', '2024-10-30 23:33:12', 'ngande@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `stakeholders_reports_details`
--

CREATE TABLE `stakeholders_reports_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `ucode` varchar(50) NOT NULL,
  `org_id` int(10) UNSIGNED NOT NULL,
  `stakeholders_reports_header_id` int(10) UNSIGNED NOT NULL,
  `stakeholder_id` int(10) UNSIGNED NOT NULL,
  `item` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit_of_measure` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `amount` decimal(15,2) DEFAULT 0.00,
  `target_amount` decimal(15,2) DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `report_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stakeholders_reports_header`
--

CREATE TABLE `stakeholders_reports_header` (
  `id` int(10) UNSIGNED NOT NULL,
  `ucode` varchar(50) NOT NULL,
  `org_id` int(10) UNSIGNED NOT NULL,
  `stakeholder_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ucode` varchar(200) NOT NULL,
  `national_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `org_level` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `stakeholder_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `dobirth` date DEFAULT NULL,
  `place_birth` varchar(255) DEFAULT NULL,
  `joined_date` date DEFAULT NULL,
  `role` enum('admin','supervisor','user','guest') NOT NULL,
  `type` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL,
  `previledge` varchar(20) DEFAULT NULL,
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `address` text DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `supervisor_id` int(11) NOT NULL,
  `sector` varchar(255) DEFAULT NULL,
  `id_photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `create_by` varchar(200) NOT NULL,
  `update_by` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ucode`, `national_id`, `station_id`, `branch_id`, `org_id`, `org_level`, `category`, `stakeholder_id`, `fname`, `lname`, `username`, `password`, `gender`, `dobirth`, `place_birth`, `joined_date`, `role`, `type`, `level`, `previledge`, `phone`, `email`, `address`, `designation`, `supervisor_id`, `sector`, `id_photo`, `is_active`, `created_at`, `updated_at`, `create_by`, `update_by`) VALUES
(18, '668a2abf304821720330943', 1, 1, 0, 0, '', '', 0, 'Cool ', 'Frend', 'cfrend', '$2y$10$VgghXSErNrFoxPY8ryEgnOLxQ2uO76.iqctnLhsRvlgjK0338.Fom', NULL, NULL, NULL, NULL, 'admin', 'gov', 'national', 'super', '723429', 'ngande11@gmail.com', NULL, NULL, 0, NULL, NULL, 1, '2024-07-07 05:42:23', '2024-08-02 00:12:11', 'fkenny', ''),
(19, '668a3dceee2471720335822', 1, 107, 3, 107, 'province', 'NDAL', 0, 'Prov', 'Man', 'pman', '$2y$10$b8Ds2y0Uubre0kTGTKG8s./gc739t.uVLtayqUicI/A5stS3d.EKO', 'male', '2003-07-01', 'Wewak', '2024-07-03', 'admin', 'gov', 'province', 'default', '2314234', 'ngande@gmail.com', 'This Address', 'Designation', 23, 'Diviset', '/public/uploads/employees/id_1724982163_2301f50c5ddd21f2dc73.jpg', 1, '2024-07-07 07:03:43', '2024-11-19 02:29:52', 'fkenny', 'ngande@gmail.com'),
(20, '6694d21b757bc1721029147', 1, 1, 2, 107, 'province', 'Commodity', 0, 'LLg', 'Man', 'dman', '$2y$10$qwdEjv3ixViR8m.7TVIE8O7ZA6WBkoJbsbT4iOjSaOooY06y85AeO', 'female', '2003-02-12', 'Wewak', '2022-11-11', 'user', 'gov', 'llg', 'default', '     234543', 'dman@gmail.com', 'Wewak Town', 'Boss blo olgeda', 35, 'Olgeda', NULL, 1, '2024-07-15 07:39:07', '2024-11-19 02:32:34', 'fkenny', 'ngande@gmail.com'),
(21, '6695df041c5ab1721097988', 1, 107, 2, 107, 'province', 'NDAL', 0, 'Vanimo', 'Bata', 'pbata', '$2y$10$pc/N/PyKrG93vs7NdK0yzOyvu399niMvNZKZQDG8RVtzP0Da2G6Ae', 'female', '2005-01-23', '', '2001-01-01', 'admin', 'gov', 'province', 'default', '  674354', 'vanimoitu@gmail.com', 'RRRRR', 'Bosses', 29, 'Boxes', NULL, 0, '2024-07-16 02:46:28', '2025-03-04 02:23:18', 'pman', 'ngande@gmail.com'),
(23, '6699a83bc79b91721346107', 1, 40, 3, 107, 'province', '', 0, 'Aitape', 'Boy', 'cboy', '$2y$10$Fqti7BR6Fai42R58yxTmWexpauZfW6JAZW7mN.Elih7YYFxSGQwQC', 'male', '2003-12-12', 'Wewak', '2023-12-12', 'supervisor', 'gov', 'district', NULL, ' 777777', 'aitapeitu@gmail.com', 'Cool Address', 'Cool Boss', 33, 'Cool Sector', '/public/uploads/employees/id_1721625213_22b3db72d1d19c80ad8f.jpg', 1, '2024-07-18 23:41:47', '2024-11-22 06:19:41', 'pman', 'ngande@gmail.com'),
(25, '6699ad0c102931721347340', 1, 107, 0, 0, '', '', 0, 'Cool', 'Boy', 'noboy', '$2y$10$weGVUq/2VqdXewyGm8YwWejv02jI4olMl3/JWNexVG/po/jdEqwsy', 'male', '2003-12-12', 'Wewak', '2023-12-12', '', 'gov', 'province', 'default', '777777', 'nngg@gmail.com', 'Cool Address', 'Cool Boss', 0, 'Cool Sector', NULL, 1, '2024-07-19 00:02:20', '2024-08-15 03:15:17', 'pman', ''),
(26, '6699b7fd322301721350141', 1, 107, 0, 0, '', '', 0, 'Cool ', 'Boy', 'nobo', '$2y$10$t/aiXq9peEzC//qpc.s3C.9XUCR9QfHfU7Ta8oodgYjlcoNWC/Y/O', 'male', '2002-11-12', 'Wewak', '2024-07-12', '', 'gov', 'province', 'default', '777777', 'nngg@gmail.com', 'Cool Address', 'Cool Boss', 0, 'Cool Sector', '/public/uploads/employees/id_1721350141_8684aeb18167426830c1.png', 1, '2024-07-19 00:49:01', '2024-08-02 00:14:31', 'pman', ''),
(27, '6699b8512373e1721350225', 1, 107, 0, 0, '', '', 0, 'Cool ', 'Boy', 'nobol', '$2y$10$TLfYNp490yIZsR59MmUckePGfcwbqD6oo2OaAejGiUFn5KflSVelW', 'male', '2002-01-11', 'Wewak', '2024-06-19', '', 'gov', 'province', 'default', '777777', 'nngg@gmail.com', 'Cool Address', 'Cool Boss', 0, 'Cool Sector', NULL, 1, '2024-07-19 00:50:25', '2024-08-02 00:14:31', 'pman', ''),
(28, '6699cc17df5e11721355287', 1, 0, 0, 0, '', '', 0, 'Cool ', 'Boy', 'noko', '$2y$10$lWIS0Oop/S4.80JIdnGzcOd87lKRaJcCvTeBHVI7pnDa8AMEzf9C.', 'male', '2001-03-02', 'Wewak', '2024-04-23', '', 'gov', 'province', 'default', '777777', 'nngg@gmail.com', 'Cool Address', 'Cool Boss', 0, 'Cool Sector', '/public/uploads/employees/id_1721355287_62f8c7bd0c2379535059.png', 1, '2024-07-19 02:14:47', '2024-08-02 00:14:31', 'pman', ''),
(29, '6699cc6bdfbf61721355371', 1, 107, 0, 107, 'province', '', 0, 'Cool ', 'Boyo', 'nokol', '$2y$10$LKy8tYYr4mnXV6z0V9pTdOkJd5HcbhkDWC1Ka5wZ18igNeDof4qHW', 'male', '2003-02-11', 'Wewak', '2024-03-01', 'supervisor', 'gov', 'province', 'default', '   777777', 'testa@cool.com', 'Cool Address', 'Cool Boss', 0, 'Education', '/public/uploads/employees/id_1721359543_d4137e6774ce02fd6d8c.png', 1, '2024-07-19 02:16:11', '2024-08-15 03:57:18', 'pman', 'pman'),
(30, '6699cd5c3dfcc1721355612', 1, 107, 3, 107, 'province', '', 0, 'Wara', 'Boy', 'nokole', '$2y$10$jGWkI09daf2wJVz5aDITG.r8m/8DFP1WKCVTfcd0YMLE90MErM/FW', 'male', '2002-02-04', 'Wewak', '2020-03-01', 'user', 'gov', 'province', 'default', ' 777777', 'nngg@gmail.com', 'Cool Address', 'Wara Boss', 35, 'Cool Sector', '/public/uploads/employees/id_1721361403_846776244184d206c6a5.png', 1, '2024-07-19 02:20:12', '2024-08-30 05:47:45', 'pman', 'ngande@gmail.com'),
(31, '6699cdd41d2cf1721355732', 1, 2, 0, 107, 'province', '', 0, 'Cool ', 'Boy', 'nokolef', '$2y$10$Uzd7fpFdxNJah7kcnXEPquJ9sQtN6mHIlTr1m7.OZNrHdRID1tyKG', 'male', '0000-00-00', 'Wewak', '0000-00-00', '', 'gov', 'llg', 'default', '777777', 'nngg@gmail.com', 'Cool Address', 'Cool Boss', 0, 'Cool Sector', '/public/uploads/employees/id_1721355732_8de5eef7a3a7bde8f62c.png', 1, '2024-07-19 02:22:12', '2024-08-02 00:14:31', 'pman', ''),
(32, '669f3343189ee1721709379', 1, 41, 0, 107, 'province', '', 0, 'Cool ', 'Moro', '', '$2y$10$Qxh.XgHYNk49yBOUB93a8OCpik2NsZlEUS6NdHUPRHwC5E1e2bvvK', 'male', '2003-11-11', 'Wewak', '2023-10-12', '', 'gov', 'district', 'default', '7334234', 'cmoro@gmail.com', 'Wewak Town', 'Smart Assto', 0, 'Smart Sector', '/public/uploads/employees/id_1721709379_16af2bf7a5e2f3720c4d.jpg', 1, '2024-07-23 04:36:19', '2024-08-02 00:14:31', 'ngande@gmail.com', ''),
(33, '669f39d46bb9c1721711060', 1, 41, 0, 107, 'province', '', 0, 'Cool ', 'Moro', '', '$2y$10$dEhUfUoBPHaNaaqLIEcItelhoJ2RKNK2cCe3.qQkooMHQttdrdh0e', 'male', '2004-02-11', 'Wewak', '2023-12-09', 'supervisor', 'gov', 'district', 'default', '7334234', 'cmori@gmail.com', 'Wewak Hill', 'Smart Assto', 0, 'Smart Sector', '/public/uploads/employees/id_1721711060_23d343def74d73c66dc0.png', 1, '2024-07-23 05:04:20', '2024-08-15 03:57:34', 'ngande@gmail.com', ''),
(34, '66a676444fa3e1722185284', 1, 1, 0, 1, 'national', '', 0, 'PNG', 'National', '', '$2y$10$PEshxXMv6EERtQax2F0WTuo8ybAXz8mQ5m8Fb13G08qPgZiSQ5Txa', NULL, NULL, NULL, NULL, 'admin', 'gov', 'national', 'default', '', 'png@gmail.com', NULL, NULL, 0, NULL, NULL, 1, '2024-07-28 16:48:04', '2024-08-02 00:14:31', 'fkenny', ''),
(35, '66c2c4f613ab11724040438', 1, 107, 0, 107, 'province', '', 0, 'Flora', 'Laso', '', '$2y$10$aOlNoCIcCW/Oez7lc4gmJORyNOyf9K9UelXMtKdCaSNNusdEa/SW6', 'female', '2002-07-30', 'Wewak', '2024-07-27', 'supervisor', 'gov', 'province', 'default', '777777', 'flora@gmail.com', 'Wewak, ESP', 'Field Supervisor', 33, 'Oil Palm', '/public/uploads/employees/id_1724040438_d3f12e5497bcc47f5924.png', 1, '2024-08-19 04:07:18', '2024-11-26 01:32:02', 'ngande@gmail.com', 'ngande@gmail.com'),
(36, '66c2c664e2cc31724040804', 1, 107, 0, 107, 'province', '', 0, 'Terry', 'Mike', '', '$2y$10$uGTHXiyNef2UQj1HAgu50.rlNeSseHBvLj757uW2GzoLZVhG4nKa.', 'female', '2002-08-15', 'Wewak', '2024-07-30', 'user', 'gov', 'province', 'default', '777777', 'tmike@gmail.com', 'Wewak, ESP', 'Field Officer', 35, 'Oil Palm', NULL, 1, '2024-08-19 04:13:25', '2024-11-26 01:32:05', 'ngande@gmail.com', 'ngande@gmail.com'),
(38, '66fefd56089eb1727987030', 1, 0, 2, 107, 'province', '', 0, 'Vanimo', 'ITU', '', '$2y$10$DFTPbPoejFAGKuVrH6xNpu7mzkdRb0KjuYD9GO5ptNnY.x9.jcyri', 'male', '1995-10-15', 'Wewak', '2024-09-19', 'user', 'gov', '', NULL, '', 'vanimoitu@gmail.com', 'Vanimo', 'Vani Design', 23, '', '/public/uploads/employees/id_1727987030_79c29bce2f60654cbca3.png', 1, '2024-10-03 20:23:50', '2024-11-26 01:31:58', 'ngande@gmail.com', ''),
(39, '67450f247bb2c1732579108', 1, 0, 4, 107, 'province', 'NDAL', 0, 'Nida', 'Itaki', '', '$2y$10$0Z7bjE2nYQubPDLXFyeEku266fc9z97iaoe4OkHPOuW.w4gGdUhDC', 'male', '1967-03-25', 'Limanda', '1988-09-12', 'admin', 'gov', '', NULL, '', 'itakinida@gmail.com', '', 'Director - CME', 23, 'Compliance Monitoring & Evaluation', NULL, 1, '2024-11-26 01:58:28', '2024-11-26 01:31:55', 'ngande@gmail.com', ''),
(40, '674514739b7e21732580467', 1, 0, 4, 107, 'province', 'NDAL', 0, 'Melton', 'Keware', '', '$2y$10$OQ2pxp166RC1kgUJmLzo8.lDSYS9CaBboa1onhh6IohgffdovTx8G', 'male', '1992-09-28', 'Simbu', '2019-11-09', 'admin', 'gov', '', NULL, '', 'meltonkeware99@gmail.com', '', 'ICT - Policy', 23, 'Policy Division', NULL, 1, '2024-11-26 02:21:07', '2024-11-26 01:31:52', 'ngande@gmail.com', ''),
(41, '674520ff61dbd1732583679', 1, 0, 4, 107, '', 'NDAL', 0, 'Testa', 'Dakoi', '', '$2y$10$Q0DtyLbnxZvG2gLqkvS2x.zsaE35dsYZlxQwS6hGB5ip8gBNf9NG.', 'male', '2006-06-06', 'Kundiwa', '2016-06-06', 'supervisor', 'gov', '', NULL, '755555', 'testa@dakoii.com', 'Po.box 2033 Port Moresby', 'Testing Officer', 0, 'Policy', NULL, 1, '2024-11-26 03:14:39', '2024-11-26 01:32:32', 'itakinida@gmail.com', ''),
(42, '67465e391df0f1732664889', 1, 0, 4, 107, 'province', 'NDAL', 0, 'Ruth', 'Mahega', '', '$2y$10$0wpqEC70B8Twsr.VCTOOs.xr3ELs6UF9PMsoy05LTl2RGk6iEyidq', 'female', '1985-02-28', 'Kerema', '2024-02-02', 'admin', 'gov', '', NULL, '', 'mruth2802@gmail.com', '', 'Data Entry One', 0, 'Information - ITU', NULL, 1, '2024-11-27 01:48:09', '2024-11-27 01:48:09', 'ngande@gmail.com', ''),
(43, '67465eb1259171732665009', 1, 0, 4, 107, 'province', 'NDAL', 0, 'Sherylle', 'Kua', '', '$2y$10$kYFeww981GocdG06toHFKOUsSpbaE2hHm2VzaPnnSnKka3YFrV8vW', 'female', '1990-05-31', 'Wewak', '2024-01-02', 'admin', 'gov', '', NULL, '', 'sheryllekua2616@gmail.com', 'POM', 'Data Entry Two', 0, 'Information - ITU', NULL, 1, '2024-11-27 01:50:09', '2024-11-27 01:50:09', 'ngande@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `wk_workplan`
--

CREATE TABLE `wk_workplan` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `llg_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `level` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `year` year(4) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wk_workplan`
--

INSERT INTO `wk_workplan` (`id`, `ucode`, `country_id`, `province_id`, `district_id`, `llg_id`, `level_id`, `level`, `description`, `year`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '66b30b0b23312', 1, 1, 1, 1, 107, 'province', 'DAL Workplan', '2024', 'active', '2024-08-07 15:50:03', 'ngande@gmail.com', '2024-11-20 16:02:25', 'ngande@gmail.com'),
(2, '66b3239415b4a', 1, 1, 1, 1, 107, 'province', 'This is the work of the workplan', '2024', 'active', '2024-08-07 17:34:44', 'ngande@gmail.com', '2024-08-07 17:34:55', 'ngande@gmail.com'),
(3, '66c2ca82e2186', 1, 1, 1, 1, 107, 'province', '1st Qtr ', '2024', 'active', '2024-08-19 14:30:58', 'ngande@gmail.com', '2024-08-19 14:30:58', ''),
(4, '66d1df42eabe1', 1, 1, 1, 1, 107, 'province', 'Infrastructure Workplan', '2024', 'active', '2024-08-31 01:03:30', 'ngande@gmail.com', '2024-08-31 01:03:30', ''),
(5, '6726b1002b7a8', 1, 1, 1, 1, 107, 'province', 'This is the new workplan', '2024', 'active', '2024-11-03 09:08:48', 'ngande@gmail.com', '2024-11-03 09:08:48', ''),
(6, '67451dca57d19', 0, 1, 1, 1, 0, '', '1st Quarter Work plan', '2025', 'active', '2024-11-26 11:00:58', 'itakinida@gmail.com', '2024-11-26 11:00:58', '');

-- --------------------------------------------------------

--
-- Table structure for table `wk_workplan_details`
--

CREATE TABLE `wk_workplan_details` (
  `id` int(11) NOT NULL,
  `ucode` varchar(255) NOT NULL,
  `workplan_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `llg_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `level` varchar(255) NOT NULL,
  `mtdp_id` int(11) NOT NULL,
  `spa_id` int(11) NOT NULL,
  `dip_id` int(11) NOT NULL,
  `sparea_id` int(11) NOT NULL,
  `invest_id` int(11) NOT NULL,
  `kra_id` int(11) NOT NULL,
  `strategy_id` int(11) NOT NULL,
  `nasp_objective_id` int(11) NOT NULL,
  `activity` text NOT NULL,
  `unit_of_measure` varchar(255) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `Q1` decimal(10,2) NOT NULL,
  `Q2` decimal(10,2) NOT NULL,
  `Q3` decimal(10,2) NOT NULL,
  `Q4` decimal(10,2) NOT NULL,
  `responsible_user_id` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wk_workplan_details`
--

INSERT INTO `wk_workplan_details` (`id`, `ucode`, `workplan_id`, `country_id`, `province_id`, `district_id`, `llg_id`, `level_id`, `level`, `mtdp_id`, `spa_id`, `dip_id`, `sparea_id`, `invest_id`, `kra_id`, `strategy_id`, `nasp_objective_id`, `activity`, `unit_of_measure`, `unit_cost`, `Q1`, `Q2`, `Q3`, `Q4`, `responsible_user_id`, `remarks`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '66b34f9ec4cd2', 1, 1, 0, 0, 0, 107, 'province', 110, 115, 113, 115, 112, 115, 114, 50, 'This is activity makes this cool', 'Day', 2000.00, 250.00, 23.00, 9.00, 30.00, 29, 'Remarkses this cool mix', 'active', '2024-08-07 19:58:14', 'ngande@gmail.com', '2024-11-25 10:34:41', 'ngande@gmail.com'),
(2, '66b3514eb1535', 1, 1, 0, 0, 0, 107, 'province', 110, 115, 113, 115, 112, 115, 117, 36, 'This is the cool activity', 'Weeks', 23.00, 34.00, 56.00, 78.00, 89.00, 33, 'REmarkes me took', 'active', '2024-08-07 20:49:50', 'ngande@gmail.com', '2024-11-25 10:35:04', 'ngande@gmail.com'),
(3, '66c2cb0ddbd1c', 3, 1, 0, 0, 0, 107, 'province', 110, 115, 113, 115, 112, 115, 112, 112, 'Cocoa Nursery Rehabilitation', 'tonnes', 2000.00, 300.00, 400.00, 500.00, 600.00, 35, '', 'active', '2024-08-19 14:33:17', 'ngande@gmail.com', '2024-08-19 14:33:17', ''),
(4, '66d1dfa10c2d3', 4, 1, 0, 0, 0, 107, 'province', 110, 115, 113, 115, 112, 115, 112, 112, 'School Infrastructure Workplan', 'buildings', 300.00, 3.00, 4.00, 2.00, 1.00, 29, 'This work is to be done by the carpenters', 'active', '2024-08-31 01:05:05', 'ngande@gmail.com', '2024-08-31 01:05:05', ''),
(5, '6726b1cc1552b', 5, 1, 0, 0, 0, 107, 'province', 110, 115, 113, 115, 112, 116, 113, 114, 'New Work of the Day', 'Day', 200.00, 250.00, 250.00, 250.00, 250.00, 35, 'Do this job properly', 'active', '2024-11-03 09:12:12', 'ngande@gmail.com', '2024-11-03 09:12:12', ''),
(6, '6745225a51dd5', 6, 0, 0, 0, 0, 0, '', 114, 0, 0, 112, 117, 122, 118, 52, 'Compliance Monitoring &Evaluation', 'Number of Reports', 100000.00, 1.00, 1.00, 1.00, 1.00, 41, '', 'active', '2024-11-26 11:20:26', 'itakinida@gmail.com', '2024-11-26 11:20:26', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `act_agreements`
--
ALTER TABLE `act_agreements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_assign_users`
--
ALTER TABLE `act_assign_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_documents`
--
ALTER TABLE `act_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_infrastructure`
--
ALTER TABLE `act_infrastructure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_infra_details`
--
ALTER TABLE `act_infra_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_inoutput`
--
ALTER TABLE `act_inoutput`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_inoutput_details`
--
ALTER TABLE `act_inoutput_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_meeting`
--
ALTER TABLE `act_meeting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_meeting_details`
--
ALTER TABLE `act_meeting_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_proposals`
--
ALTER TABLE `act_proposals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_training`
--
ALTER TABLE `act_training`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `act_training_details`
--
ALTER TABLE `act_training_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_id` (`training_id`);

--
-- Indexes for table `adx_country`
--
ALTER TABLE `adx_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adx_district`
--
ALTER TABLE `adx_district`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adx_llg`
--
ALTER TABLE `adx_llg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adx_province`
--
ALTER TABLE `adx_province`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adx_village`
--
ALTER TABLE `adx_village`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ucode` (`ucode`),
  ADD UNIQUE KEY `country_id_2` (`country_id`,`province_id`,`district_id`,`llg_id`,`ward_id`);

--
-- Indexes for table `adx_ward`
--
ALTER TABLE `adx_ward`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commodity_boards`
--
ALTER TABLE `commodity_boards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crops`
--
ALTER TABLE `crops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dakoii_users`
--
ALTER TABLE `dakoii_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_dip`
--
ALTER TABLE `dev_dip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_indicators`
--
ALTER TABLE `dev_indicators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_investments`
--
ALTER TABLE `dev_investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_kra`
--
ALTER TABLE `dev_kra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_mtdp`
--
ALTER TABLE `dev_mtdp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_nasp`
--
ALTER TABLE `dev_nasp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_spa`
--
ALTER TABLE `dev_spa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_specific_areas`
--
ALTER TABLE `dev_specific_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dev_strategies`
--
ALTER TABLE `dev_strategies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `level_groups`
--
ALTER TABLE `level_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `org_branches`
--
ALTER TABLE `org_branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_code` (`branch_code`);

--
-- Indexes for table `production_data_commodity_boards`
--
ALTER TABLE `production_data_commodity_boards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_data_provinces`
--
ALTER TABLE `production_data_provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_data_sme`
--
ALTER TABLE `production_data_sme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remindertable`
--
ALTER TABLE `remindertable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selection`
--
ALTER TABLE `selection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smes`
--
ALTER TABLE `smes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sme_staff`
--
ALTER TABLE `sme_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stakeholders`
--
ALTER TABLE `stakeholders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stakeholders_reports_details`
--
ALTER TABLE `stakeholders_reports_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stakeholders_reports_header`
--
ALTER TABLE `stakeholders_reports_header`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wk_workplan`
--
ALTER TABLE `wk_workplan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wk_workplan_details`
--
ALTER TABLE `wk_workplan_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `act_agreements`
--
ALTER TABLE `act_agreements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `act_assign_users`
--
ALTER TABLE `act_assign_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `act_documents`
--
ALTER TABLE `act_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `act_infrastructure`
--
ALTER TABLE `act_infrastructure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `act_infra_details`
--
ALTER TABLE `act_infra_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `act_inoutput`
--
ALTER TABLE `act_inoutput`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `act_inoutput_details`
--
ALTER TABLE `act_inoutput_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `act_meeting`
--
ALTER TABLE `act_meeting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `act_meeting_details`
--
ALTER TABLE `act_meeting_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `act_proposals`
--
ALTER TABLE `act_proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `act_training`
--
ALTER TABLE `act_training`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `act_training_details`
--
ALTER TABLE `act_training_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `adx_country`
--
ALTER TABLE `adx_country`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `adx_district`
--
ALTER TABLE `adx_district`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `adx_llg`
--
ALTER TABLE `adx_llg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `adx_province`
--
ALTER TABLE `adx_province`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `adx_village`
--
ALTER TABLE `adx_village`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `adx_ward`
--
ALTER TABLE `adx_ward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `commodity_boards`
--
ALTER TABLE `commodity_boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `crops`
--
ALTER TABLE `crops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dakoii_users`
--
ALTER TABLE `dakoii_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_dip`
--
ALTER TABLE `dev_dip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `dev_indicators`
--
ALTER TABLE `dev_indicators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `dev_investments`
--
ALTER TABLE `dev_investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `dev_kra`
--
ALTER TABLE `dev_kra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `dev_mtdp`
--
ALTER TABLE `dev_mtdp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `dev_nasp`
--
ALTER TABLE `dev_nasp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `dev_spa`
--
ALTER TABLE `dev_spa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `dev_specific_areas`
--
ALTER TABLE `dev_specific_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `dev_strategies`
--
ALTER TABLE `dev_strategies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `level_groups`
--
ALTER TABLE `level_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `org_branches`
--
ALTER TABLE `org_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `production_data_commodity_boards`
--
ALTER TABLE `production_data_commodity_boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `production_data_provinces`
--
ALTER TABLE `production_data_provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `production_data_sme`
--
ALTER TABLE `production_data_sme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `remindertable`
--
ALTER TABLE `remindertable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `selection`
--
ALTER TABLE `selection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `smes`
--
ALTER TABLE `smes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `sme_staff`
--
ALTER TABLE `sme_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `stakeholders`
--
ALTER TABLE `stakeholders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `stakeholders_reports_details`
--
ALTER TABLE `stakeholders_reports_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stakeholders_reports_header`
--
ALTER TABLE `stakeholders_reports_header`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `wk_workplan`
--
ALTER TABLE `wk_workplan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wk_workplan_details`
--
ALTER TABLE `wk_workplan_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `act_training_details`
--
ALTER TABLE `act_training_details`
  ADD CONSTRAINT `act_training_details_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `act_training` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

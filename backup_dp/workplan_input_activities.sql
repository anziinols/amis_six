-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 11:38 AM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workplan_input_activities`
--
ALTER TABLE `workplan_input_activities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workplan_input_activities`
--
ALTER TABLE `workplan_input_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

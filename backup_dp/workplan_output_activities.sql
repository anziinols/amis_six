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

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workplan_output_activities`
--
ALTER TABLE `workplan_output_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Foreign key constraints for table `workplan_output_activities`
--
ALTER TABLE `workplan_output_activities`
  ADD CONSTRAINT `fk_output_workplan` FOREIGN KEY (`workplan_id`) REFERENCES `workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_output_proposal` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_output_activity` FOREIGN KEY (`activity_id`) REFERENCES `workplan_activities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Migration to create workplan_others_link table
-- This table stores links for activities that don't fit into NASP, MTDP, or Corporate Plan frameworks

CREATE TABLE `workplan_others_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_workplan_activity_id` (`workplan_activity_id`),
  KEY `idx_link_type` (`link_type`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_workplan_others_link_activity` FOREIGN KEY (`workplan_activity_id`) REFERENCES `workplan_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Note: Sample recurrent activity templates will be added after the table is created
-- These templates will have workplan_activity_id = 0 to indicate they are templates

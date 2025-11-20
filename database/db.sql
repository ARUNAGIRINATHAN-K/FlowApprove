CREATE DATABASE IF NOT EXISTS `vedan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vedan`;

-- User Authentication Module
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL, -- employee, manager, admin
  `department` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Request Submission Module
CREATE TABLE `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `request_type` varchar(50) NOT NULL,
  `request_title` varchar(255) NOT NULL,
  `request_description` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending', -- pending, approved, rejected
  `current_level` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `request_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  CONSTRAINT `request_attachments_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Multi-Level Approval Workflow
CREATE TABLE `approval_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_type` varchar(50) NOT NULL,
  `level` int(11) NOT NULL,
  `approver_role` varchar(20) NOT NULL, -- manager, finance, admin
  `approver_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `approval_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `action` varchar(20) NOT NULL, -- approved, rejected
  `comment` text DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `approver_id` (`approver_id`),
  CONSTRAINT `approval_actions_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`),
  CONSTRAINT `approval_actions_ibfk_2` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Audit Log Module
CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_by` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL, -- submit, approve, reject, update
  `request_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `action_by` (`action_by`),
  KEY `request_id` (`request_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`action_by`) REFERENCES `users` (`id`),
  CONSTRAINT `audit_logs_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
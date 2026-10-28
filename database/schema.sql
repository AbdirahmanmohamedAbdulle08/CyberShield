-- ============================================================================
-- CyberShield — Cybersecurity Awareness & Phishing Simulation Platform
-- Database Schema: cybershield_db
-- Target: MySQL 5.7+ / MariaDB 10.4+ (XAMPP Compatible)
-- Character Set: utf8mb4 / Engine: InnoDB
-- Educational Simulation Platform — No Credential Theft / Strict Safety
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `cybershield_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cybershield_db`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Users table (Admin, Campaign Manager, Instructor, Student accounts)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` ENUM('super_admin', 'campaign_manager', 'instructor', 'student') NOT NULL DEFAULT 'student',
  `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
  `last_login_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_users_role` (`role`),
  INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Training Groups (Student cohorts, classes at Hormuud University, etc.)
DROP TABLE IF EXISTS `training_groups`;
CREATE TABLE `training_groups` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `group_name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `instructor_id` INT UNSIGNED NULL,
  `status` ENUM('active', 'archived') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_groups_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Participants (Pseudonymous student training targets)
DROP TABLE IF EXISTS `participants`;
CREATE TABLE `participants` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `participant_token` VARCHAR(64) NOT NULL UNIQUE,
  `display_name` VARCHAR(100) NOT NULL,
  `pseudonym` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `institutional_id` VARCHAR(50) NULL,
  `training_group_id` INT UNSIGNED NULL,
  `consent_status` ENUM('consented', 'pending', 'opted_out') NOT NULL DEFAULT 'consented',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`training_group_id`) REFERENCES `training_groups` (`id`) ON DELETE SET NULL,
  INDEX `idx_part_group` (`training_group_id`),
  INDEX `idx_part_consent` (`consent_status`),
  INDEX `idx_part_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Campaigns (Fictional educational simulation scenarios)
DROP TABLE IF EXISTS `campaigns`;
CREATE TABLE `campaigns` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `campaign_name` VARCHAR(150) NOT NULL,
  `educational_objective` TEXT NOT NULL,
  `fictional_scenario` VARCHAR(100) NOT NULL,
  `sender_name` VARCHAR(100) NOT NULL,
  `sender_email` VARCHAR(120) NOT NULL,
  `message_subject` VARCHAR(200) NOT NULL,
  `message_body` TEXT NOT NULL,
  `training_group_id` INT UNSIGNED NULL,
  `public_token` VARCHAR(64) NOT NULL UNIQUE,
  `status` ENUM('draft', 'pending_authorization', 'authorized', 'running', 'completed', 'cancelled') NOT NULL DEFAULT 'draft',
  `created_by` INT UNSIGNED NOT NULL,
  `scheduled_at` DATETIME NULL,
  `started_at` DATETIME NULL,
  `completed_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`training_group_id`) REFERENCES `training_groups` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  INDEX `idx_camp_status` (`status`),
  INDEX `idx_camp_token` (`public_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Campaign Authorizations (Explicit safety audit record before starting)
DROP TABLE IF EXISTS `campaign_authorizations`;
CREATE TABLE `campaign_authorizations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT UNSIGNED NOT NULL,
  `authorized_by` INT UNSIGNED NOT NULL,
  `authorization_notes` TEXT NULL,
  `authorized_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('approved', 'rejected') NOT NULL DEFAULT 'approved',
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`authorized_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Simulation Events (Telemetry: clicks, training page visits, reports, quiz events)
DROP TABLE IF EXISTS `simulation_events`;
CREATE TABLE `simulation_events` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT UNSIGNED NOT NULL,
  `participant_token` VARCHAR(64) NULL,
  `event_type` ENUM(
    'simulation_link_clicked',
    'training_page_viewed',
    'lesson_started',
    'lesson_completed',
    'quiz_started',
    'quiz_completed',
    'suspicious_message_reported'
  ) NOT NULL,
  `metadata_json` JSON NULL,
  `ip_anonymized` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  INDEX `idx_event_type` (`event_type`),
  INDEX `idx_event_campaign` (`campaign_id`),
  INDEX `idx_event_participant` (`participant_token`),
  INDEX `idx_event_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Learning Modules (10 comprehensive cybersecurity awareness lessons)
DROP TABLE IF EXISTS `learning_modules`;
CREATE TABLE `learning_modules` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `module_number` INT UNSIGNED NOT NULL UNIQUE,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `difficulty_level` ENUM('beginner', 'intermediate', 'advanced') NOT NULL DEFAULT 'beginner',
  `estimated_minutes` INT UNSIGNED NOT NULL DEFAULT 10,
  `learning_objectives` TEXT NOT NULL,
  `lesson_content` MEDIUMTEXT NOT NULL,
  `suspicious_example` TEXT NOT NULL,
  `warning_checklist` TEXT NOT NULL,
  `recommended_response` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Quizzes
DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE `quizzes` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `module_id` INT UNSIGNED NOT NULL UNIQUE,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `passing_score` INT UNSIGNED NOT NULL DEFAULT 70,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`module_id`) REFERENCES `learning_modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Quiz Questions
DROP TABLE IF EXISTS `quiz_questions`;
CREATE TABLE `quiz_questions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `quiz_id` INT UNSIGNED NOT NULL,
  `question_text` TEXT NOT NULL,
  `explanation` TEXT NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 1,
  FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Quiz Options
DROP TABLE IF EXISTS `quiz_options`;
CREATE TABLE `quiz_options` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `question_id` INT UNSIGNED NOT NULL,
  `option_text` TEXT NOT NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 1,
  FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Quiz Attempts
DROP TABLE IF EXISTS `quiz_attempts`;
CREATE TABLE `quiz_attempts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `quiz_id` INT UNSIGNED NOT NULL,
  `participant_token` VARCHAR(64) NULL,
  `user_id` INT UNSIGNED NULL,
  `score` INT UNSIGNED NOT NULL,
  `total_questions` INT UNSIGNED NOT NULL,
  `percentage` DECIMAL(5,2) NOT NULL,
  `passed` TINYINT(1) NOT NULL DEFAULT 0,
  `answers_json` JSON NULL,
  `completed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_attempt_participant` (`participant_token`),
  INDEX `idx_attempt_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Participant Learning Progress
DROP TABLE IF EXISTS `participant_learning_progress`;
CREATE TABLE `participant_learning_progress` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `module_id` INT UNSIGNED NOT NULL,
  `participant_token` VARCHAR(64) NULL,
  `user_id` INT UNSIGNED NULL,
  `status` ENUM('not_started', 'in_progress', 'completed') NOT NULL DEFAULT 'not_started',
  `completed_at` DATETIME NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`module_id`) REFERENCES `learning_modules` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  UNIQUE KEY `uniq_part_module` (`module_id`, `participant_token`),
  INDEX `idx_prog_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Suspicious Message Reports
DROP TABLE IF EXISTS `suspicious_message_reports`;
CREATE TABLE `suspicious_message_reports` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT UNSIGNED NULL,
  `participant_token` VARCHAR(64) NULL,
  `user_id` INT UNSIGNED NULL,
  `reporter_name` VARCHAR(100) NOT NULL,
  `reason` ENUM(
    'suspicious_link',
    'unrealistic_reward',
    'urgent_threat',
    'unknown_sender',
    'request_for_sensitive_info',
    'impersonation',
    'other'
  ) NOT NULL,
  `message_subject` VARCHAR(200) NULL,
  `explanation` TEXT NULL,
  `status` ENUM('submitted', 'under_review', 'reviewed', 'resolved') NOT NULL DEFAULT 'submitted',
  `resolution_notes` TEXT NULL,
  `reviewed_by` INT UNSIGNED NULL,
  `reviewed_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_report_status` (`status`),
  INDEX `idx_report_campaign` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Audit Logs (Administrative and security action trail)
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NULL,
  `username` VARCHAR(50) NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `entity_type` VARCHAR(50) NOT NULL,
  `entity_id` VARCHAR(50) NULL,
  `details_json` JSON NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_audit_action` (`action`),
  INDEX `idx_audit_entity` (`entity_type`, `entity_id`),
  INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. System Settings
DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `setting_key` VARCHAR(50) PRIMARY KEY,
  `setting_value` TEXT NULL,
  `setting_group` VARCHAR(50) NOT NULL DEFAULT 'general',
  `description` VARCHAR(255) NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Login Rate Limiting / Lockout tracking
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ip_address` VARCHAR(45) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `attempted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_attempt_ip_time` (`ip_address`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 10, 2025 at 07:35 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u263857507_unity_care`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `care_notes`
--

CREATE TABLE `care_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `individual_profile_id` bigint(20) UNSIGNED NOT NULL,
  `dsp_user_id` bigint(20) UNSIGNED NOT NULL,
  `shift_date` date NOT NULL,
  `notes` text NOT NULL,
  `mood` varchar(255) DEFAULT NULL,
  `activities` text DEFAULT NULL,
  `meals` text DEFAULT NULL,
  `medications` text DEFAULT NULL,
  `incidents` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `individual_profile_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation_participants`
--

CREATE TABLE `conversation_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `last_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `individual_profiles`
--

CREATE TABLE `individual_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `pronouns` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `strengths_abilities` text DEFAULT NULL,
  `preferences_interests` text DEFAULT NULL,
  `communication_style` text DEFAULT NULL,
  `sensory_profile` text DEFAULT NULL,
  `triggers` text DEFAULT NULL,
  `calming_strategies` text DEFAULT NULL,
  `safety_notes` text DEFAULT NULL,
  `emergency_contacts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`emergency_contacts`)),
  `medical_info` text DEFAULT NULL,
  `goals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`goals`)),
  `profile_visibility_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`profile_visibility_settings`)),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `individual_profiles`
--

INSERT INTO `individual_profiles` (`id`, `family_user_id`, `first_name`, `last_name`, `date_of_birth`, `pronouns`, `profile_photo`, `strengths_abilities`, `preferences_interests`, `communication_style`, `sensory_profile`, `triggers`, `calming_strategies`, `safety_notes`, `emergency_contacts`, `medical_info`, `goals`, `profile_visibility_settings`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Michael', 'Johnson', '1995-06-15', 'He/Him', NULL, 'Great at art, loves music, excellent memory', 'Painting, listening to jazz, playing video games', 'Verbal, prefers visual cues', 'Sensitive to loud noises, enjoys soft textures', 'Sudden changes in routine, crowded spaces', 'Listening to music, deep breathing, quiet space', 'No known allergies, takes daily medication', NULL, NULL, NULL, NULL, 'active', '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(2, 1, 'John', 'Smith', '2025-12-19', NULL, NULL, 'Loves to sing', 'Enjoy Mexican food', 'Verbal', 'Loud noises', 'Umbrellas', 'Read a book', 'Allergic to bees\r\nitem reminder for programs', NULL, NULL, NULL, NULL, 'active', '2025-12-10 05:03:42', '2025-12-10 05:03:42');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_27_191129_create_individual_profiles_table', 1),
(5, '2025_11_27_191129_create_organizations_table', 1),
(6, '2025_11_27_191130_add_organization_fields_to_users_table', 1),
(7, '2025_11_27_191130_create_role_assignments_table', 1),
(8, '2025_11_27_191235_create_care_notes_table', 1),
(9, '2025_11_27_191235_create_conversations_table', 1),
(10, '2025_11_27_191235_create_messages_table', 1),
(11, '2025_11_27_191235_create_mood_checks_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mood_checks`
--

CREATE TABLE `mood_checks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `individual_profile_id` bigint(20) UNSIGNED NOT NULL,
  `submitted_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `check_date` date NOT NULL DEFAULT curdate(),
  `mood` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('agency','program','school','therapy_center','regional_center') NOT NULL DEFAULT 'agency',
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `hours_of_operation` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `accreditation_info` varchar(255) DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `status` enum('active','suspended','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `type`, `address`, `city`, `state`, `zip`, `phone`, `email`, `logo`, `description`, `hours_of_operation`, `license_number`, `accreditation_info`, `settings`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hope Care Services', 'agency', '123 Main Street', 'Los Angeles', 'CA', '90001', '555-1000', 'info@hopecare.com', NULL, 'Providing quality care services since 2010', NULL, NULL, NULL, NULL, 'active', '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(2, 'Sunshine Day Program', 'program', '456 Oak Avenue', 'Los Angeles', 'CA', '90002', '555-2000', 'info@sunshineprogram.com', NULL, 'Day program for adults with developmental disabilities', 'Mon-Fri 8am-4pm', NULL, NULL, NULL, 'active', '2025-12-05 23:38:36', '2025-12-05 23:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_assignments`
--

CREATE TABLE `role_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_type` enum('family_admin','individual','dsp','agency_admin','program_staff','case_manager','super_admin') NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `individual_profile_id` bigint(20) UNSIGNED DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `assigned_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_assignments`
--

INSERT INTO `role_assignments` (`id`, `user_id`, `role_type`, `organization_id`, `individual_profile_id`, `permissions`, `assigned_by_user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'family_admin', NULL, NULL, NULL, NULL, '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(2, 2, 'dsp', NULL, 1, NULL, NULL, '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(3, 3, 'agency_admin', 1, NULL, NULL, NULL, '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(4, 2, 'dsp', 1, 1, NULL, NULL, '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(5, 4, 'program_staff', 2, 1, NULL, NULL, '2025-12-05 23:38:36', '2025-12-05 23:38:36'),
(6, 5, 'super_admin', NULL, NULL, NULL, NULL, '2025-12-05 23:38:36', '2025-12-05 23:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` enum('active','suspended','inactive') NOT NULL DEFAULT 'active',
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `phone`, `profile_photo`, `status`, `two_factor_secret`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Sarah', 'Johnson', 'family@unitycarelink.com', NULL, '$2y$12$bFad.m1iP8kTviy52CdHjuB9HevOeMZ.A3Pj/eQt50tGlzhBmOVG6', '555-0101', NULL, 'active', NULL, NULL, '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(2, 'James', 'Martinez', 'dsp@unitycarelink.com', NULL, '$2y$12$tKW7iUMsdzNwUoSC2mxFdOvt6xvC2Ki67w0eD2zstAG7BP6uBazDi', '555-0102', NULL, 'active', NULL, NULL, '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(3, 'Linda', 'Williams', 'admin@unitycarelink.com', NULL, '$2y$12$ubNogPg/Si.w6tSNhXT58OMvWjBsL0qiD/odX6/BuDn824Da1JgE.', '555-0103', NULL, 'active', NULL, NULL, '2025-12-05 23:38:35', '2025-12-05 23:38:35'),
(4, 'Robert', 'Davis', 'program@unitycarelink.com', NULL, '$2y$12$Ilwpe0UVJOIz5fgNyHYnpeGdeoj/1ak7v9Zzx.2eNfTebaINvtayG', '555-0104', NULL, 'active', NULL, NULL, '2025-12-05 23:38:36', '2025-12-05 23:38:36'),
(5, 'Pete', 'Anderson', 'pete@unitycarelink.com', NULL, '$2y$12$IYc.uOrWURFey.Qy3iaGhe2ZP9Uxz35gYA2uLTMC.7jXlXCpNIWpu', '555-0105', NULL, 'active', NULL, NULL, '2025-12-05 23:38:36', '2025-12-05 23:38:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `care_notes`
--
ALTER TABLE `care_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `care_notes_dsp_user_id_foreign` (`dsp_user_id`),
  ADD KEY `care_notes_individual_profile_id_shift_date_index` (`individual_profile_id`,`shift_date`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_individual_profile_id_index` (`individual_profile_id`);

--
-- Indexes for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversation_participants_conversation_id_user_id_unique` (`conversation_id`,`user_id`),
  ADD KEY `conversation_participants_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `individual_profiles`
--
ALTER TABLE `individual_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `individual_profiles_family_user_id_foreign` (`family_user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_user_id_foreign` (`user_id`),
  ADD KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mood_checks`
--
ALTER TABLE `mood_checks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mood_checks_submitted_by_user_id_foreign` (`submitted_by_user_id`),
  ADD KEY `mood_checks_individual_profile_id_check_date_index` (`individual_profile_id`,`check_date`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `role_assignments`
--
ALTER TABLE `role_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_assignments_assigned_by_user_id_foreign` (`assigned_by_user_id`),
  ADD KEY `role_assignments_user_id_role_type_index` (`user_id`,`role_type`),
  ADD KEY `role_assignments_organization_id_index` (`organization_id`),
  ADD KEY `role_assignments_individual_profile_id_index` (`individual_profile_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `care_notes`
--
ALTER TABLE `care_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `individual_profiles`
--
ALTER TABLE `individual_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mood_checks`
--
ALTER TABLE `mood_checks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role_assignments`
--
ALTER TABLE `role_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `care_notes`
--
ALTER TABLE `care_notes`
  ADD CONSTRAINT `care_notes_dsp_user_id_foreign` FOREIGN KEY (`dsp_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `care_notes_individual_profile_id_foreign` FOREIGN KEY (`individual_profile_id`) REFERENCES `individual_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_individual_profile_id_foreign` FOREIGN KEY (`individual_profile_id`) REFERENCES `individual_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD CONSTRAINT `conversation_participants_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversation_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `individual_profiles`
--
ALTER TABLE `individual_profiles`
  ADD CONSTRAINT `individual_profiles_family_user_id_foreign` FOREIGN KEY (`family_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mood_checks`
--
ALTER TABLE `mood_checks`
  ADD CONSTRAINT `mood_checks_individual_profile_id_foreign` FOREIGN KEY (`individual_profile_id`) REFERENCES `individual_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mood_checks_submitted_by_user_id_foreign` FOREIGN KEY (`submitted_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_assignments`
--
ALTER TABLE `role_assignments`
  ADD CONSTRAINT `role_assignments_assigned_by_user_id_foreign` FOREIGN KEY (`assigned_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `role_assignments_individual_profile_id_foreign` FOREIGN KEY (`individual_profile_id`) REFERENCES `individual_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_assignments_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

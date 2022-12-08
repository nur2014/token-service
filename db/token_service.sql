-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2022 at 06:47 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `token_service`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu_wise_role`
--

CREATE TABLE `menu_wise_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `master_menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `component_id` bigint(20) UNSIGNED DEFAULT NULL,
  `module_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('0c2289cf08ea46adfe11ad018ece7b5e610c7e17c4f454c205d53be06c6080a9e8e6800e6260f0e9', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 06:39:26', '2022-12-07 06:39:26', '2023-12-07 12:39:26'),
('0c8dc94ebef165ad626037c4dd5157aaf824e38fd100b9fc7a83020fb2e139092f71314ca1591fb9', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:30:54', '2022-12-07 22:30:54', '2023-12-08 04:30:54'),
('13dde6b071931d67cfb3817881d7b0cc544b7f3f2879f5397eead6576497a8940b0fe532a191f729', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:46:00', '2022-12-07 22:46:00', '2023-12-08 04:46:00'),
('2127ba0fdb003306a8ee063fb055f0be9b01576e31020c8ad04df0678668e0031259aeb4f2926f6b', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:20:43', '2022-12-07 22:20:43', '2023-12-08 04:20:43'),
('212a0944a57bd5fa70ca9a18beef80482252c9ba0442d1a94dbcd57216f1aa8730ec8e6777d51943', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 06:56:39', '2022-12-07 06:56:39', '2023-12-07 12:56:39'),
('220002950f19bc1e92ccdbee634e12153a0c7a5dca3c76b1f66d2f9455d6b7c3feb52797e624ee0d', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 06:57:12', '2022-12-07 06:57:12', '2023-12-07 12:57:12'),
('34f6e655f6d8898251f5b5d3288bdb96cca3db4bdcd6d7c9b77bd45476cbc4ba477a970682c62c9f', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:13:48', '2022-12-07 22:13:48', '2023-12-08 04:13:48'),
('423dabb42fb57398d251888fbf262a417aae8d30cb383325c3b0abb4f6f54f8fffe5fa710bafc2d4', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 23:39:57', '2022-12-07 23:39:57', '2023-12-08 05:39:57'),
('4d79deb1d3e7b5c116b9939f608fb73aab8241ca4d4eb15bef2e4e5e9e5e5b369339dd86c35698c5', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:09:09', '2022-12-07 22:09:09', '2023-12-08 04:09:09'),
('4ef65b86c5a3cde48b2323c9bc08b1e0181e43605a146b89f62219143a406063156391e2f1743e6e', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 06:56:19', '2022-12-07 06:56:19', '2023-12-07 12:56:19'),
('5fde7724f33401b3b7164d1a85beb246b9e58d9b03cdb2c26a02837f4034147eea8b5ecfdc49e117', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 06:56:25', '2022-12-07 06:56:25', '2023-12-07 12:56:25'),
('75abee4f14dc127691831323640c493425f4205099036ec4df17eee11f39a3f5645db4108754ad7e', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 06:56:23', '2022-12-07 06:56:23', '2023-12-07 12:56:23'),
('91197c1cff202c3e0df3ab3fc3e09b973b69a18b978f21a48f1ea22cbbb6304adbe8e6aa88138e05', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 06:56:26', '2022-12-07 06:56:26', '2023-12-07 12:56:26'),
('a785958adb24a90507d11b080ab442bbfe8325a02a0c28c2e01a0a8178e3e7990fd941e87f31e1fb', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 06:37:51', '2022-12-07 06:37:51', '2023-12-07 12:37:51'),
('af14d35385d75958634d4f183d953e7cadfe73c508d7464f0f319ed48c55cf3e02c9aad9e27096e4', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 23:38:30', '2022-12-07 23:38:30', '2023-12-08 05:38:30'),
('b06bb54a8218f64481b6ecbf418b0843c5c3b816b33fefe5cb767ae299ebcf64d5f95efc7fceb78c', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:00:15', '2022-12-07 22:00:15', '2023-12-08 04:00:15'),
('cb95290046de9f79b271bf1b86de6b3849d72503c8bbb1c166ce4eec1b917eab8d9f1013e23f6f5c', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:03:28', '2022-12-07 22:03:28', '2023-12-08 04:03:28'),
('facc974940b295ac7c24be4ccfab41084c62c44cb3ece18604453c8c5bd4cf5c0dc1e32417b24259', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:24:53', '2022-12-07 22:24:53', '2023-12-08 04:24:53'),
('fe9ea1c3963743e1533bb72a4a1707dbe1e58a15fb68b7de3d2312e4805ac533befc918239b0a945', 1, 7, 'Personal Access Token', '[]', 0, '2022-12-07 22:06:40', '2022-12-07 22:06:40', '2023-12-08 04:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', '6riaQA0kWWZ7JO3kFYPSAb4IbCsML9NwlDtKAGhK', NULL, 'http://localhost', 1, 0, 0, '2021-02-13 19:17:28', '2021-02-13 19:17:28'),
(2, NULL, 'Laravel Password Grant Client', '02AShHDB49Xn0xjNqEWJIcdEbM5D8gUHCGq73xSw', 'users', 'http://localhost', 0, 1, 0, '2021-02-13 19:17:29', '2021-02-13 19:17:29'),
(3, NULL, 'Laravel Personal Access Client', 'ksmV1cBoC5eL2El28k9kg1EHeFDJ6lega60stoDO', NULL, 'http://localhost', 1, 0, 0, '2021-03-02 21:15:07', '2021-03-02 21:15:07'),
(4, NULL, 'Laravel Password Grant Client', 'qGtibN67yjSf5CZ8Qxh7gLH6V062uDAMsqicZdNg', 'users', 'http://localhost', 0, 1, 0, '2021-03-02 21:15:07', '2021-03-02 21:15:07'),
(5, NULL, 'Laravel Personal Access Client', 'SehqymtVzPMmgTy4p3PR8AlbDvl0nO1HZDbtk0Pz', NULL, 'http://localhost', 1, 0, 0, '2021-04-04 20:36:00', '2021-04-04 20:36:00'),
(6, NULL, 'Laravel Password Grant Client', '3JsCFyYoEWi3v7lw8qYrxGRwHhVy4zlozvAlHTnK', 'users', 'http://localhost', 0, 1, 0, '2021-04-04 20:36:02', '2021-04-04 20:36:02'),
(7, NULL, 'Laravel Personal Access Client', 'qfddhfJ38qsGIf6rFRPGSWBn7ZQEMaA1qYRCPyRB', NULL, 'http://localhost', 1, 0, 0, '2021-06-02 06:29:25', '2021-06-02 06:29:25'),
(8, NULL, 'Laravel Password Grant Client', 's0ghHuTS47xXgyWhGEre24vE55k4b9s8UV5CDf9U', 'users', 'http://localhost', 0, 1, 0, '2021-06-02 06:29:25', '2021-06-02 06:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-02-13 19:17:29', '2021-02-13 19:17:29'),
(2, 3, '2021-03-02 21:15:07', '2021-03-02 21:15:07'),
(3, 5, '2021-04-04 20:36:02', '2021-04-04 20:36:02'),
(4, 7, '2021-06-02 06:29:25', '2021-06-02 06:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `otp` char(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_at` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = expired',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_passwords_codes`
--

CREATE TABLE `reset_passwords_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiory_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_name_bn` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `org_id` bigint(20) UNSIGNED DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'অজানা নাম',
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_logged_in` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_type_id` int(11) NOT NULL DEFAULT 0 COMMENT '0 = internal users, 1 = irrigation farmer, 2= warehouse farmer , 3 = Ginner , 4 = Grower, 5=Divisional Head , 6 = dealer, 7=grant, 8=Trainee, 9=Trainer, 10=incentive-farmer',
  `is_org_admin` int(11) NOT NULL DEFAULT 0 COMMENT '0 = normal, 1 = org_admin',
  `dashboard_user` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `mobile_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_panel` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nothi_user_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `panels` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `name_bn`, `username`, `email`, `password`, `last_logged_in`, `created_at`, `updated_at`, `user_type_id`, `is_org_admin`, `dashboard_user`, `status`, `mobile_no`, `last_panel`, `nothi_user_id`, `panels`) VALUES
(1, 'Admin', 'অ্যাডমিন', 'admin', 'admin@gmail.com', '$2y$10$k3rd1i3vQMGW.xDy3ffPmegSbWbzGOUpvxnuOzhVFDdNdL8QgPrk.', NULL, '2021-03-05 18:26:11', '2021-11-10 10:26:15', 0, 0, 0, 0, '01723019000', '', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `users_temp`
--

CREATE TABLE `users_temp` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'অজানা নাম',
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_logged_in` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_id` bigint(20) UNSIGNED NOT NULL,
  `office_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `phone_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `sso` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sso`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `supervisor_id`, `name`, `email`, `name_bn`, `org_id`, `office_id`, `warehouse_id`, `phone_no`, `office_type_id`, `role_id`, `photo`, `created_by`, `updated_by`, `status`, `sso`, `created_at`, `updated_at`, `designation_id`) VALUES
(13, 1, NULL, 'Admin', 'admin@gmail.com', 'অ্যাডমিন', 1, 1, NULL, '01723019000', 1, 1, 'uploads/user-management/users/1636561575.jpg', 1, 1, 0, NULL, '2021-03-05 18:26:11', '2021-11-10 10:26:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `execution_type` int(11) NOT NULL COMMENT '0=insert, 1=update,2=delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu_wise_role`
--
ALTER TABLE `menu_wise_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reset_passwords_codes`
--
ALTER TABLE `reset_passwords_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_temp`
--
ALTER TABLE `users_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_details_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username_index` (`username`),
  ADD KEY `uid_index` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu_wise_role`
--
ALTER TABLE `menu_wise_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reset_passwords_codes`
--
ALTER TABLE `reset_passwords_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=703;

--
-- AUTO_INCREMENT for table `users_temp`
--
ALTER TABLE `users_temp`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

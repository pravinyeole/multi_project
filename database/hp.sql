-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2023 at 05:34 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hp`
--

-- --------------------------------------------------------

--
-- Table structure for table `helps`
--

CREATE TABLE `helps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_description` varchar(500) DEFAULT NULL,
  `menu_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` varchar(15) DEFAULT NULL,
  `modified_at` varchar(15) DEFAULT NULL,
  `deleted_at` varchar(15) DEFAULT NULL
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
(1, '2019_05_03_000001_create_customer_columns', 1),
(2, '2019_05_03_000002_create_subscriptions_table', 1),
(3, '2019_05_03_000003_create_subscription_items_table', 1),
(4, '2023_03_28_182715_create_departments_table', 1),
(5, '2023_03_29_160519_create_classes_table', 2),
(6, '2023_03_29_164247_create_subject_table', 3),
(7, '2023_03_29_164951_create_subject_table', 4),
(8, '2023_03_29_165216_create_subjects_table', 5),
(9, '2023_03_29_165329_create_subjects_table', 6),
(10, '2023_03_29_173928_add_deleted_at_to_classes', 7),
(11, '2023_03_29_193835_add_deleted_at_to_subjects', 8),
(12, '2023_03_29_173410_create_faculties_table', 9),
(13, '2023_03_30_162922_create_papercost_table', 9),
(14, '2023_03_30_170234_rename_papercost_table_to_papercosts', 10),
(15, '2023_03_30_170528_create_papercosts_table', 11),
(16, '2023_03_30_181936_create_office_orders_table', 12),
(17, '2023_03_30_184022_create_terms_table', 12),
(18, '2023_05_17_093229_create_teacher_papers_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `parameters`
--

CREATE TABLE `parameters` (
  `parameter_id` bigint(20) UNSIGNED NOT NULL,
  `parameter_key` varchar(100) DEFAULT NULL,
  `parameter_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parameters`
--

INSERT INTO `parameters` (`parameter_id`, `parameter_key`, `parameter_value`) VALUES
(1, 'mail_driver', 'smtp'),
(2, 'mail_host', 'smtp.gmail.com'),
(3, 'mail_port', '587'),
(4, 'mail_username', 'examsupport3@sanjivani.org.in'),
(5, 'mail_password', 'cadjnnpszcirxadm'),
(6, 'mail_encryption', 'tls'),
(7, 'mail_from_address', 'examsupport3@sanjivani.org.in'),
(8, 'mail_from_name', 'Sanjivani College of Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `mobile_id` varchar(255) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` enum('GH','SH') NOT NULL,
  `payment_type` enum('google_pay','phone_pay','uip','paytm','other') NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `status` enum('pending','completed') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_pin`
--

CREATE TABLE `request_pin` (
  `pin_request_id` int(11) NOT NULL,
  `admin_slug` varchar(200) NOT NULL,
  `no_of_pin` bigint(20) NOT NULL,
  `req_user_id` bigint(20) NOT NULL,
  `status` enum('pending','completed') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `request_pin`
--

INSERT INTO `request_pin` (`pin_request_id`, `admin_slug`, `no_of_pin`, `req_user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'AU9999', 200, 29, 'completed', '2023-07-09 09:51:14', '2023-07-09 11:32:50'),
(2, 'AU9999', 50, 35, 'completed', '2023-07-09 09:52:28', '2023-07-09 11:33:04'),
(3, 'AU9999', 200, 29, 'pending', '2023-07-09 09:52:39', '2023-07-09 09:52:39'),
(4, 'AU9999', 20, 35, 'completed', '2023-07-09 11:38:35', '2023-07-09 11:38:50'),
(5, 'SS9595', 222, 40, 'completed', '2023-07-10 17:24:36', '2023-07-10 17:45:05'),
(6, 'SS9595', 2222, 40, 'completed', '2023-07-10 17:46:13', '2023-07-10 17:46:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_fname` varchar(100) DEFAULT NULL,
  `user_lname` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `user_status` enum('Active','Inactive') NOT NULL,
  `user_last_login` varchar(15) DEFAULT NULL,
  `created_at` varchar(15) DEFAULT NULL,
  `modified_at` varchar(15) DEFAULT NULL,
  `deleted_at` varchar(15) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `user_sub_info_id` bigint(20) DEFAULT NULL,
  `upi` varchar(255) DEFAULT NULL,
  `google_pay` varchar(255) DEFAULT NULL,
  `phone_pay` varchar(255) DEFAULT NULL,
  `paytm` varchar(255) DEFAULT NULL,
  `other_payment_mode` varchar(255) DEFAULT NULL,
  `user_role` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_fname`, `user_lname`, `mobile_number`, `email`, `user_slug`, `email_verified_at`, `user_status`, `user_last_login`, `created_at`, `modified_at`, `deleted_at`, `remember_token`, `user_sub_info_id`, `upi`, `google_pay`, `phone_pay`, `paytm`, `other_payment_mode`, `user_role`) VALUES
(1, 'Shubham', 'Superadmin', '9595838283', 'test@yopmail.com', 'SS9595', NULL, 'Active', '2023-06-30 18:2', NULL, NULL, NULL, 'jjg0dUqF85nr01JdeyMZFBdV0DEvfW9GrrwzbGuVLxEIjd2IdiQVOfHsKsvZ', NULL, 'UIPsdasd', NULL, NULL, NULL, '', 'S'),
(25, 'Admin', 'User A', '9999999999', 'adminA@yopmail.com', 'AU9999', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(28, 'User', 'A', '8888888888', 'userA@yopmail.com', NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'U'),
(29, 'User', 'B', '7777777777', 'userB@yopmail.com', NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'U'),
(30, 'User', 'C', '6666666666', 'userC@yopmail.com', NULL, NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'U'),
(31, 'User', 'D', '5555555555', 'userD@yopmail.com', NULL, NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'U'),
(32, 'User', 'E', '4444444444', 'userE@yopmail.com', NULL, NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'U'),
(33, 'User', 'One', '3333333333', 'userOne@yopmail.com', NULL, NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'U'),
(35, 'User', 'Sunday09July', '9988776655', 'user09July@yopmail.com', 'US9988', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'U'),
(36, 'Rahul', 'Admin', '9090909090', 'rahulAdmin@yopmail.com', 'RA9090', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(37, 'Ganesh', 'Admin', '8989898989', 'ganeshAdmin@yopmail.com', 'GA8989', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(38, 'Rahul', 'admin', '8889998888', 'rahulAdmin@yopmail.com', 'Ra8889', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(39, 'Tejas', 'Admin', '9090990900', 'tejas@yopmail.com', 'TA9090', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(40, 'Test', 'Admin', '9909909909', 'testAdmin@yopmail.com', 'TA9909', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `user_map_new`
--

CREATE TABLE `user_map_new` (
  `id` bigint(10) NOT NULL,
  `mobile_id` varchar(255) NOT NULL,
  `user_id` int(10) NOT NULL,
  `type` enum('GH','SH') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_map_new`
--

INSERT INTO `user_map_new` (`id`, `mobile_id`, `user_id`, `type`, `created_at`, `updated_at`) VALUES
(9, 'AU0108072023', 28, 'GH', '2023-07-08 12:20:02', '2023-07-08 12:20:02'),
(10, 'AU0108072023', 29, 'GH', '2023-07-08 12:20:02', '2023-07-08 12:20:02'),
(11, 'UA0108072023', 1, 'GH', '2023-07-11 00:16:50', '2023-07-11 00:16:50'),
(12, 'UA0108072023', 25, 'GH', '2023-07-11 00:16:50', '2023-07-11 00:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_otp`
--

CREATE TABLE `user_otp` (
  `user_otp_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `phone_otp` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_otp`
--

INSERT INTO `user_otp` (`user_otp_id`, `user_id`, `phone_otp`, `created_at`, `updated_at`) VALUES
(13, 28, 331907, '2023-07-08 01:21:02', '2023-07-08 01:21:02'),
(18, 31, 994890, '2023-07-08 01:57:51', '2023-07-08 01:57:51'),
(26, 32, 557992, '2023-07-09 01:59:08', '2023-07-09 01:59:08'),
(29, 33, 435952, '2023-07-09 02:19:18', '2023-07-09 02:19:18'),
(30, 30, 709023, '2023-07-09 02:23:16', '2023-07-09 02:23:16'),
(31, 34, 994149, '2023-07-09 02:24:40', '2023-07-09 02:24:40'),
(41, 35, 950109, '2023-07-09 06:07:12', '2023-07-09 06:07:12'),
(44, 40, 424550, '2023-07-10 11:52:02', '2023-07-10 11:52:02'),
(48, 25, 918851, '2023-07-22 22:21:44', '2023-07-22 22:21:44'),
(52, 1, 302155, '2023-07-25 13:26:31', '2023-07-25 13:26:31'),
(53, 29, 570492, '2023-07-25 13:49:59', '2023-07-25 13:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_pins`
--

CREATE TABLE `user_pins` (
  `user_pin_id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `pins` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_pins`
--

INSERT INTO `user_pins` (`user_pin_id`, `user_id`, `pins`) VALUES
(1, 20, 2000),
(2, 16, 700),
(6, 19, 100),
(7, 19, 222),
(8, 23, 4980),
(9, 25, 1999),
(10, 28, 1999),
(11, 29, 2799),
(12, 35, 268),
(13, 36, 10000),
(14, 38, 2000),
(15, 39, 2000),
(16, 40, 22444);

-- --------------------------------------------------------

--
-- Table structure for table `user_referral`
--

CREATE TABLE `user_referral` (
  `user_referral_id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `referral_id` bigint(20) NOT NULL,
  `admin_slug` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_referral`
--

INSERT INTO `user_referral` (`user_referral_id`, `user_id`, `referral_id`, `admin_slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 29, 9999999999, '0', '2023-07-08 00:49:28', '2023-07-08 00:49:28', 2147483647),
(2, 28, 9999999999, '0', '2023-07-08 00:51:47', '2023-07-08 00:51:47', 2147483647),
(3, 32, 9999999999, '0', '2023-07-08 02:34:35', '2023-07-08 02:34:35', 2147483647),
(4, 33, 6666666666, '0', '2023-07-09 02:22:47', '2023-07-09 02:22:47', 2147483647),
(5, 34, 7777777777, '0', '2023-07-09 02:53:03', '2023-07-09 02:53:03', 2147483647),
(6, 35, 7777777777, 'AU9999', '2023-07-09 03:10:18', '2023-07-09 03:10:18', 2147483647),
(7, 38, 9595838283, 'SS9595', '2023-07-10 11:46:45', '2023-07-10 11:46:45', 2147483647),
(8, 39, 9595838283, 'SS9595', '2023-07-10 11:48:04', '2023-07-10 11:48:04', 2147483647),
(9, 40, 9595838283, 'SS9595', '2023-07-10 11:51:15', '2023-07-10 11:51:15', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `role` enum('S','A','U') DEFAULT NULL,
  `created_at` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_role_id`, `user_id`, `role`, `created_at`) VALUES
(1, 1, 'S', NULL),
(10, 28, 'U', NULL),
(11, 25, 'A', NULL),
(12, 29, 'U', NULL),
(13, 32, 'U', NULL),
(14, 32, 'U', NULL),
(15, 33, 'U', NULL),
(16, 34, 'U', NULL),
(17, 35, 'U', NULL),
(18, 36, 'A', NULL),
(19, 37, 'A', NULL),
(20, 38, 'A', NULL),
(21, 39, 'A', NULL),
(22, 40, 'A', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_info`
--

CREATE TABLE `user_sub_info` (
  `user_sub_info_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `mobile_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_sub_info`
--

INSERT INTO `user_sub_info` (`user_sub_info_id`, `user_id`, `mobile_id`, `created_at`) VALUES
(9, 28, 'UA0108072023', '2023-06-30 18:30:00'),
(10, 25, 'AU0108072023', '2023-07-07 18:30:00'),
(11, 29, 'UB0108072023', '2023-07-07 18:30:00'),
(12, 29, 'UB0208072023', '2023-07-07 18:30:00'),
(13, 29, 'UB0125072023', '2023-07-24 18:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `helps`
--
ALTER TABLE `helps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parameters`
--
ALTER TABLE `parameters`
  ADD PRIMARY KEY (`parameter_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `request_pin`
--
ALTER TABLE `request_pin`
  ADD PRIMARY KEY (`pin_request_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_stripe_id_index` (`user_sub_info_id`);

--
-- Indexes for table `user_map_new`
--
ALTER TABLE `user_map_new`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_otp`
--
ALTER TABLE `user_otp`
  ADD PRIMARY KEY (`user_otp_id`);

--
-- Indexes for table `user_pins`
--
ALTER TABLE `user_pins`
  ADD PRIMARY KEY (`user_pin_id`);

--
-- Indexes for table `user_referral`
--
ALTER TABLE `user_referral`
  ADD PRIMARY KEY (`user_referral_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`);

--
-- Indexes for table `user_sub_info`
--
ALTER TABLE `user_sub_info`
  ADD PRIMARY KEY (`user_sub_info_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `helps`
--
ALTER TABLE `helps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `parameters`
--
ALTER TABLE `parameters`
  MODIFY `parameter_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `request_pin`
--
ALTER TABLE `request_pin`
  MODIFY `pin_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `user_map_new`
--
ALTER TABLE `user_map_new`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_otp`
--
ALTER TABLE `user_otp`
  MODIFY `user_otp_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `user_pins`
--
ALTER TABLE `user_pins`
  MODIFY `user_pin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_referral`
--
ALTER TABLE `user_referral`
  MODIFY `user_referral_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `user_role_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_sub_info`
--
ALTER TABLE `user_sub_info`
  MODIFY `user_sub_info_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

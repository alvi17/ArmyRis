-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2019 at 03:11 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `armyrisdev`
--

-- --------------------------------------------------------

--
-- Table structure for table `acls`
--

CREATE TABLE `acls` (
  `id` int(10) UNSIGNED NOT NULL,
  `order` int(10) UNSIGNED DEFAULT NULL,
  `module_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '`acl_modules`.`id_acl_module`',
  `code` varchar(72) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Unique Code of Action',
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Unique Name of Action',
  `relative_url` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `is_menu` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `acls`
--

INSERT INTO `acls` (`id`, `order`, `module_id`, `code`, `name`, `relative_url`, `is_menu`, `is_active`, `created_at`) VALUES
(1, 1, 1, 'user-index', 'List Users', 'user/index.php', 1, 1, '2016-11-11 22:12:59'),
(2, 2, 1, 'user-detail', 'User Details', 'user/details.php', 0, 1, '2016-11-11 22:12:59'),
(3, 3, 1, 'user-add', 'Add User', 'user/add.php', 1, 1, '2016-11-11 22:12:59'),
(4, 4, 1, 'user-update', 'Update User', 'user/update.php', 0, 1, '2016-11-11 22:12:59'),
(5, 5, 1, 'user-delete', 'Delete User', 'user/delete.php', 0, 1, '2016-11-11 22:12:59'),
(6, 6, 2, 'subscriber-index', 'List Subscribers', 'subscriber/index.php', 1, 1, '2016-11-11 22:12:59'),
(7, 7, 2, 'subscriber-detail', 'Subscriber Details', 'subscriber/details.php', 0, 1, '2016-11-11 22:12:59'),
(8, 8, 2, 'subscriber-add', 'Add Subscriber', 'subscriber/add.php', 1, 1, '2016-11-11 22:12:59'),
(9, 9, 2, 'subscriber-update', 'Update Subscriber', 'subscriber/update.php', 0, 1, '2016-11-11 22:12:59'),
(10, 10, 2, 'subscriber-delete', 'Delete Subscriber', 'subscriber/delete.php', 0, 1, '2016-11-11 22:12:59'),
(11, 11, 3, 'role-index', 'List Roles', 'role/index.php', 1, 1, '2016-11-27 19:09:34'),
(12, 12, 3, 'role-add', 'Add New Role', 'role/add.php', 1, 1, '2016-11-27 19:10:00'),
(13, 13, 3, 'role-update', 'Update Role', 'role/update.php', 0, 1, '2016-11-27 19:10:55'),
(14, 14, 4, 'operation-area', 'Area', 'operation/area.php', 1, 1, '2017-01-09 17:06:07'),
(15, 15, 4, 'operation-area-add', 'Add Area', 'operation/area-add.php', 0, 1, '2017-01-09 17:06:56'),
(16, 16, 4, 'operation-area-update', 'Update Area', 'operation/area-update.php', 0, 1, '2017-01-09 17:07:28'),
(17, 17, 4, 'operation-building', 'Building', 'operation/building.php', 1, 1, '2017-01-09 17:08:11'),
(18, 18, 4, 'operation-building-add', 'Add Building', 'operation/building-add.php', 0, 1, '2017-01-09 17:09:21'),
(19, 19, 4, 'operation-building-update', 'Update Building', 'operation/building-update.php', 0, 1, '2017-01-09 17:12:14'),
(20, 20, 4, 'operation-package-index', 'Package', 'operation/package-index.php', 1, 1, '2016-12-09 06:03:58'),
(21, 21, 4, 'operation-package-add', 'Add Package', 'operation/package-add.php', 0, 1, '2016-12-09 06:04:26'),
(22, 22, 4, 'operation-package-update', 'Update Package', 'operation/package-update.php', 0, 1, '2016-12-09 06:04:32'),
(23, 23, 4, 'operation-building-router-mapping', 'Building-Router Mapping', 'operation/building-router-mapping.php', 1, 1, '2016-12-30 00:49:01'),
(24, 24, 4, 'operation-building-ip-mapping', 'Building IP Mapping', 'operation/building-ip-mapping.php', 1, 0, '2016-12-30 02:21:31'),
(25, 25, 4, 'operation-building-ip-mapping-update', 'Update Building IP Mapping', 'operation/building-ip-mapping-update.php', 0, 0, '2016-12-30 02:25:01'),
(26, 26, 4, 'operation-building-router-mapping-update', 'Update Area, Building and Router', 'operation/building-router-mapping-update.php', 0, 1, '2016-12-30 02:21:07'),
(27, 27, 4, 'operation-delete-inactive-subscribers', 'Delete Inactive Subscribers', 'operation/delete-inactive-subscribers.php', 1, 1, '2017-04-22 02:58:03'),
(28, 28, 4, 'operation-complementary-index', 'Complementary', 'operation/complementary-index.php', 1, 0, '2016-12-07 23:54:49'),
(29, 29, 4, 'operation-complementary-add', 'Add Complementary', 'operation/complementary-add.php', 0, 0, '2016-12-07 23:55:42'),
(30, 30, 4, 'operation-complementary-update', 'Update Complementary', 'operation/complementary-update.php', 0, 0, '2016-12-07 23:56:15'),
(31, 31, 4, 'operation-sms-index', 'SMS', 'operation/sms-index.php', 1, 0, '2016-12-07 23:57:26'),
(32, 32, 4, 'operation-sms-add', 'Add SMS', 'operation/sms-add.php', 0, 0, '2016-12-07 23:58:03'),
(33, 33, 2, 'subscriber-send-sms-notification', 'Send SMS Notification', 'subscriber/send-sms-notification.php', 1, 1, '2017-04-08 16:15:46'),
(34, 34, 5, 'support-scratchcard-search', 'Scratchcard Search', 'support/scratchcard-search.php', 1, 0, '2016-11-18 16:13:47'),
(35, 35, 5, 'support-password-search', 'Password Search', 'support/password-search.php', 1, 0, '2016-11-18 16:13:49'),
(36, 36, 5, 'support-payment-index', 'Payment History', 'support/payment-index.php', 1, 0, '2016-11-18 16:23:07'),
(37, 37, 5, 'support-payment-add', 'Add Payment', 'support/payment-add.php', 1, 0, '2016-11-18 16:23:24'),
(38, 38, 5, 'support-connectivity-index', 'Connectivity History', 'support/connectivity-index.php', 1, 0, '2016-11-18 16:24:27'),
(39, 39, 5, 'support-connectivity-add', 'Add Connectivity', 'support/connectivity-add.php', 1, 0, '2016-11-18 16:24:30'),
(40, 40, 5, 'support-payment-history-temporary', 'Payment History (Temporary)', 'support/payment-history-temporary.php', 1, 0, '2017-01-06 12:24:27'),
(41, 41, 6, 'complaint-add', 'Add Complaint', 'complaint/add.php', 1, 1, '2017-01-22 06:47:18'),
(42, 42, 6, 'complaint-details', 'Complaint Details', 'complaint/details.php', 0, 1, '2017-01-22 06:47:19'),
(43, 43, 6, 'complaint-general-report', 'General Report', 'complaint/general-report.php', 1, 1, '2017-01-22 06:47:20'),
(44, 44, 6, 'complaint-frequecny-report', 'Frequency Report', 'complaint/frequecny-report.php', 1, 1, '2017-01-22 06:47:21'),
(45, 45, 6, 'complaint-support-in-charge', 'Support in Charge', 'complaint/support-in-charge.php', 1, 1, '2017-01-22 06:47:22'),
(46, 46, 6, 'complaint-support-in-charge-add', 'Add Support in Charge', 'complaint/support-in-charge-add.php', 0, 0, '2017-01-22 06:47:22'),
(47, 47, 6, 'complaint-support-in-charge-update', 'Update Support in Charge', 'complaint/support-in-charge-update.php', 0, 1, '2017-01-22 06:47:23'),
(48, 48, 6, 'complaint-problem-type', 'Problem Types', 'complaint/problem-type.php', 1, 1, '2017-01-27 03:30:53'),
(49, 49, 6, 'complaint-problem-type-add', 'Add Problem Type', 'complaint/problem-type-add.php', 0, 1, '2017-01-27 03:32:11'),
(50, 50, 6, 'complaint-problem-type-update', 'Update Problem Type', 'complaint/problem-type-update.php', 0, 1, '2017-01-27 04:14:35'),
(51, 51, 6, 'complaint-support-type', 'Support Types', 'complaint/support-type.php', 1, 1, '2017-04-08 16:23:26'),
(52, 52, 6, 'complaint-support-type-add', 'Add Support Type', 'complaint/support-type-add.php', 0, 1, '2017-04-08 16:24:46'),
(53, 53, 6, 'complaint-support-type-update', 'Update Support Type', 'complaint/support-type-update.php', 1, 1, '2017-04-08 16:24:50'),
(54, 54, 7, 'scratchcard-index', 'Scratch Cards', 'scratchcard/index.php', 1, 1, '2017-01-11 20:56:58'),
(55, 55, 7, 'scratchcard-lots', 'Scratchcard Lots', 'scratchcard/lots.php', 1, 1, '2017-01-11 20:57:38'),
(56, 56, 7, 'scratchcard-lot-add', 'Generate Scratchcard', 'scratchcard/lot-add.php', 1, 1, '2017-01-11 20:57:40'),
(57, 57, 7, 'scratchcard-lot-update', 'Update Scratch Card Lot', 'scratchcard/lot-update.php', 0, 1, '2017-02-04 11:08:48'),
(58, 58, 7, 'scratchcard-distribute-cards', 'Distribute Scratch Cards', 'scratchcard/distribute-cards.php', 1, 0, '2017-02-04 11:10:29'),
(59, 59, 7, 'scratchcard-distributors', 'Scratch Card Distributors', 'scratchcard/distributors.php', 1, 0, '2017-02-04 11:11:10'),
(60, 60, 7, 'scratchcard-distributor-add', 'Add Scratch Card Distributor', 'scratchcard/distributor-add.php', 0, 0, '2017-02-04 11:12:55'),
(61, 61, 7, 'scratchcard-distributor-update', 'Update Scratch Card Distributor', 'scratchcard/distributor-update.php', 0, 0, '2017-02-04 11:12:56'),
(62, 62, 8, 'report-revenue', 'Revenue', 'report/revenue.php', 1, 1, '2017-01-06 09:55:03'),
(63, 63, 8, 'report-payment-history', 'Payment History', 'report/payment-history.php', 1, 1, '2017-01-06 13:15:57'),
(64, 64, 8, 'report-bank-deposit-list', 'Bank Deposit', 'report/bank-deposit-list.php', 1, 1, '2017-03-06 02:32:15'),
(65, 65, 8, 'report-bank-deposit-add', 'Add Bank Deposit', 'report/bank-deposit-add.php', 0, 1, '2017-03-06 02:33:38'),
(66, 66, 8, 'report-disconnect-forecast', 'Disconnect Forecast', 'report/disconnect-forecast.php', 1, 1, '2017-01-10 09:56:31'),
(67, 67, 8, 'report-disconnect-report', 'Disconnect Report', 'report/disconnect-report.php', 1, 1, '2017-02-18 11:21:08'),
(68, 68, 8, 'report-log-records', 'Log Records', 'report/log-records.php', 1, 1, '2017-04-22 02:47:18'),
(69, 70, 8, 'report-bandwidth-1', 'Bandwidth (Method 1)', 'report/bandwidth-1.php', 1, 1, '2017-04-10 11:47:18'),
(70, 86, 8, 'report-bandwidth-2', 'Bandwidth (Method 2)', 'report/bandwidth-2.php', 1, 1, '2017-04-11 03:55:12'),
(71, 71, 9, 'survey-index', 'Survey List', 'survey/index.php', 1, 1, '2017-03-18 11:14:29'),
(72, 72, 9, 'survey-add', 'Add New Survey', 'survey/add.php', 1, 1, '2017-03-18 11:14:57'),
(73, 73, 9, 'survey-update', 'Update Survey', 'survey/update.php', 0, 1, '2017-03-18 11:15:37'),
(74, 74, 9, 'survey-details', 'Survey Details', 'survey/details.php', 0, 1, '2017-03-18 11:22:38'),
(75, 75, 9, 'survey-questions', 'Survey Questions', 'survey/questions.php', 0, 1, '2017-03-18 15:40:44'),
(76, 76, 9, 'survey-participants', 'Survey Participants', 'survey/participants.php', 0, 1, '2017-04-08 01:52:51'),
(77, 77, 9, 'survey-participant-details', 'Survey Participant Details', 'survey/participant-details.php', 0, 1, '2017-04-08 01:53:46'),
(79, 79, 2, 'subscriber-suspended-till-now', 'Suspended Till Now', 'subscriber/suspended-till-now.php', 1, 1, '2017-05-13 18:52:11'),
(80, 80, 10, 'corporate-subscriber-list', 'Corporate Subscribers', 'corporate-subscriber/list.php', 1, 1, '2017-05-15 22:16:01'),
(81, 81, 10, 'corporate-subscriber-add', 'Add Corporate Subscriber', 'corporate-subscriber/add.php', 1, 1, '2017-05-15 22:18:02'),
(82, 82, 10, 'corporate-subscriber-details', 'Corporate Subscriber Details', 'corporate-subscriber/details.php', 0, 1, '2017-05-15 22:18:03'),
(83, 83, 10, 'corporate-subscriber-edit', 'Edit Corporate Subscriber', 'corporate-subscriber/edit.php', 0, 1, '2017-05-15 22:18:34'),
(84, 84, 10, 'corporate-subscriber-add-payment', 'Add Corporate Payment', 'corporate-subscriber/add-payment.php', 1, 1, '2017-05-19 08:21:30'),
(85, 85, 10, 'corporate-subscriber-send-sms-notification', 'Send SMS Notification', 'corporate-subscriber/send-sms-notification.php', 1, 1, '2017-05-27 04:54:01'),
(86, 69, 8, 'report-user-logs', 'User Log Records', 'report/user-logs.php', 1, 1, '2017-09-09 23:28:53'),
(87, 87, 6, 'complaint-rank-summary', 'Rank-wise Complaint Summary', 'complaint/rank-summary.php', 1, 1, '2017-10-07 17:01:34'),
(88, 88, 6, 'complaint-support-types-summary', 'Complaint Summary by Support Types', 'complaint/support-types-summary.php', 1, 1, '2017-10-07 17:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `acl_functions`
--

CREATE TABLE `acl_functions` (
  `id` int(10) UNSIGNED NOT NULL,
  `CODE` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `NAME` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acl_modules`
--

CREATE TABLE `acl_modules` (
  `id_acl_module` smallint(5) UNSIGNED NOT NULL,
  `module_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(5) UNSIGNED NOT NULL DEFAULT '999'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `acl_modules`
--

INSERT INTO `acl_modules` (`id_acl_module`, `module_name`, `order`) VALUES
(1, 'User', 9),
(2, 'Subscriber', 1),
(3, 'Role', 10),
(4, 'Operation', 5),
(5, 'Support', 6),
(6, 'Complaint', 3),
(7, 'Scratch Card', 7),
(8, 'Report', 4),
(9, 'Survey', 8),
(10, 'Corporate Subscriber', 2);

-- --------------------------------------------------------

--
-- Table structure for table `acl_pages`
--

CREATE TABLE `acl_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `module_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '`acl_modules`.`id`',
  `code` varchar(72) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Unique Code of Action',
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Unique Name of Action',
  `order` int(10) UNSIGNED DEFAULT NULL,
  `rel_url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rel_url_1` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Relative URL',
  `rel_url_2` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_role` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `is_menu` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `is_active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acl_pages_functions`
--

CREATE TABLE `acl_pages_functions` (
  `page_id` int(10) UNSIGNED NOT NULL,
  `function_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `acl_pages_functions`
--

INSERT INTO `acl_pages_functions` (`page_id`, `function_id`) VALUES
(10, 1),
(42, 1);

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id_area` int(10) UNSIGNED NOT NULL,
  `area_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive, 2=Deleted',
  `support_in_charge_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_deposits`
--

CREATE TABLE `bank_deposits` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `submit_by` int(10) UNSIGNED DEFAULT NULL,
  `purpose` varchar(510) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dtt_add` datetime DEFAULT NULL,
  `uid_add` int(10) UNSIGNED NOT NULL,
  `version` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bkp_payments`
--

CREATE TABLE `bkp_payments` (
  `id_payment_key` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `subscriber_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('Scratch Card','Complementary','Enable Internet','Corporate Payment','Balance Adjusted by Admin') COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `debit` int(6) UNSIGNED DEFAULT '0' COMMENT 'Amount Spent',
  `credit` int(6) UNSIGNED DEFAULT '0' COMMENT 'Amount Added',
  `balance` int(7) DEFAULT '0' COMMENT '`payments`.`balance` = `subscribers`.`payment_balance` - (`payments`.`credit` - `payments`.`debit`)',
  `version` int(10) UNSIGNED DEFAULT NULL COMMENT 'version id for individual subscriber',
  `ref_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '`scratch_cards`.`id_card_key` |',
  `created_at` datetime DEFAULT NULL COMMENT 'DateTime of Payment',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'entry by',
  `created_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'indicates whether entry is done by subscibrer or system-user',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bkp_scratch_cards`
--

CREATE TABLE `bkp_scratch_cards` (
  `id_card_key` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial_no` varbinary(20) DEFAULT NULL,
  `amount` int(5) DEFAULT NULL,
  `lot_id` int(10) NOT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT '5' COMMENT '5=available, 6=used, 7=deleted',
  `ref_id` int(10) UNSIGNED DEFAULT NULL COMMENT '`payments`.`id_payment_key`',
  `comment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'indicates whether update is done by subscibrer or system-user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id_building` int(10) UNSIGNED NOT NULL,
  `building_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `area_id` int(10) UNSIGNED DEFAULT NULL,
  `area_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `router_no` int(5) UNSIGNED DEFAULT NULL,
  `ip_block` varchar(48) COLLATE utf8_unicode_ci DEFAULT NULL,
  `local_ip` varchar(48) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_ip_first` varchar(48) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_ip_last` varchar(48) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive, 2=Deleted',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_ip_table_plotted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0=No, 1=Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_sms`
--

CREATE TABLE `bulk_sms` (
  `id` int(10) UNSIGNED NOT NULL,
  `channel` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msisdns` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `response` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dtt_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complains`
--

CREATE TABLE `complains` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscriber_id` int(10) NOT NULL COMMENT 'Subscriber Id who complained',
  `pb_since` datetime DEFAULT NULL,
  `pb_type` int(10) UNSIGNED NOT NULL,
  `pb_details` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Problem Details',
  `support_reason` int(10) UNSIGNED DEFAULT NULL,
  `support_details` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Support Details',
  `id_status` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '1=Informed, 2=In Progress, 3=On Hold, 4=Completed, 5=Cancled',
  `uid_add` int(10) NOT NULL COMMENT 'User id of complain submitter',
  `dtt_add` datetime NOT NULL COMMENT 'Date and time of complain submitted',
  `add_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL,
  `uid_mod` int(10) DEFAULT NULL COMMENT 'User id of last modified by',
  `dtt_mod` datetime DEFAULT NULL COMMENT 'Date and time of complian last modified',
  `mod_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL,
  `uid_in_charge` int(10) UNSIGNED DEFAULT NULL,
  `version` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `sms_receiver` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms_text` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms_send_response` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` enum('web','app') COLLATE utf8_unicode_ci DEFAULT 'web'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complains_audit`
--

CREATE TABLE `complains_audit` (
  `id` int(10) UNSIGNED NOT NULL,
  `complain_id` int(10) UNSIGNED NOT NULL COMMENT '`complains`.`id`',
  `subscriber_id` int(10) UNSIGNED NOT NULL,
  `pb_since` datetime DEFAULT NULL,
  `pb_type` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pb_details` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `support_reason` int(10) UNSIGNED DEFAULT NULL,
  `support_details` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_status` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '1=Informed, 2=In Progress, 3=On Hold, 4=Completed, 5=Cancled',
  `uid_mod` int(10) NOT NULL COMMENT 'User id of last modified by',
  `dtt_mod` datetime NOT NULL COMMENT 'Date and time of complian last modified',
  `mod_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL,
  `uid_in_charge` int(10) UNSIGNED DEFAULT NULL,
  `version` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_option_problems`
--

CREATE TABLE `complaint_option_problems` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `uid_add` int(11) NOT NULL,
  `dtt_add` datetime NOT NULL,
  `uid_mod` int(11) DEFAULT NULL,
  `dtt_mod` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_option_supports`
--

CREATE TABLE `complaint_option_supports` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `uid_add` int(11) NOT NULL,
  `dtt_add` datetime NOT NULL,
  `uid_mod` int(11) DEFAULT NULL,
  `dtt_mod` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complementaries`
--

CREATE TABLE `complementaries` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` int(6) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '0=created, 1=released, 2=cancled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ip_addresses`
--

CREATE TABLE `ip_addresses` (
  `id_ip_key` int(10) UNSIGNED NOT NULL,
  `ip` varchar(54) COLLATE utf8_unicode_ci DEFAULT NULL,
  `building_id` int(10) UNSIGNED DEFAULT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Available, 1=Occupied',
  `occupied_subscriber_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `version` int(10) UNSIGNED DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mikrotik_routers`
--

CREATE TABLE `mikrotik_routers` (
  `id_router` int(10) UNSIGNED NOT NULL,
  `router_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `router_ip` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_id` tinyint(1) DEFAULT '1' COMMENT '1=Active, 0=Inactive, 2=Deleted',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `varsion` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `mikrotik_routers`
--

INSERT INTO `mikrotik_routers` (`id_router`, `router_name`, `router_ip`, `username`, `password`, `status_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `varsion`) VALUES
(1, 'Fiber@Home', '203.202.246.42', 'software', 'software', 1, '2016-11-28 23:21:33', 1, NULL, NULL, 1),
(2, 'AAMRA', '203.202.246.42', 'software', 'software', 1, '2016-11-28 23:21:33', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mb_unit_value` decimal(10,2) DEFAULT '0.00' COMMENT 'Bandwidth value in mbps',
  `price` int(5) NOT NULL,
  `days` int(4) DEFAULT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive, 2=Deleted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id_payment_key` int(10) UNSIGNED NOT NULL,
  `subscriber_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('Scratch Card','Complementary','Enable Internet','Corporate Payment','Balance Adjusted by Admin') COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `debit` int(6) UNSIGNED DEFAULT '0' COMMENT 'Amount Spent',
  `credit` int(6) UNSIGNED DEFAULT '0' COMMENT 'Amount Added',
  `balance` int(7) DEFAULT '0' COMMENT '`payments`.`balance` = `subscribers`.`payment_balance` - (`payments`.`credit` - `payments`.`debit`)',
  `version` int(10) UNSIGNED DEFAULT NULL COMMENT 'version id for individual subscriber',
  `ref_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '`scratch_cards`.`id_card_key` |',
  `created_at` datetime DEFAULT NULL COMMENT 'DateTime of Payment',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'entry by',
  `created_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'indicates whether entry is done by subscibrer or system-user',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `id` int(5) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(5) UNSIGNED DEFAULT NULL COMMENT 'High to Low order. Smaller value - higher order.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`id`, `name`, `order`) VALUES
(1, 'Gen', 1),
(2, 'Lt Gen', 2),
(3, 'Maj Gen', 3),
(4, 'Brig Gen', 4),
(5, 'Col', 5),
(6, 'Lt Col', 6),
(7, 'Maj', 7),
(8, 'Capt', 8),
(9, 'Lt', 9),
(10, '2 Lt', 10),
(11, 'AFNS Maj', 12),
(12, 'AFNS Capt', 13),
(13, 'AFNS Lt', 14),
(14, 'AFNS 2 Lt', 15),
(15, 'MWO', 16),
(16, 'SWO', 17),
(17, 'WO', 18),
(18, 'Sgt', 19),
(19, 'Cpl', 20),
(20, 'Lcpl', 21),
(21, 'Snk', 22),
(22, 'Civil', 23),
(23, 'BJO', 24),
(24, 'CPO', 25),
(25, 'AFNS Lt Col', 11),
(26, 'Other', 100);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active,0=Inactive,2=Deleted',
  `created_at` datetime NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `version` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `status_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `version`) VALUES
(1, 'Super Admin', 1, '2016-11-12 16:27:53', 1, '2018-06-27 00:01:28', 3, 1),
(2, 'Admin', 1, '2016-11-12 16:27:56', 1, '2018-01-07 14:28:45', 3, 1),
(3, 'Support Technician', 1, '2016-11-12 16:28:03', 1, '2018-06-28 13:09:17', 3, 1),
(4, 'Common_View', 1, '2016-12-18 18:27:40', 1, '2018-12-05 00:00:38', 3, 1),
(5, 'Accounts', 1, '2017-01-11 11:35:00', 3, '2018-07-13 00:51:23', 3, 1),
(6, 'Complaint API', 1, '2017-10-20 11:27:35', 1, NULL, NULL, 1),
(7, 'Support Desk', 1, '2018-06-28 13:00:55', 3, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_acl`
--

CREATE TABLE `role_acl` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `acl_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_acl`
--

INSERT INTO `role_acl` (`role_id`, `acl_id`) VALUES
(6, 42),
(6, 43),
(6, 44),
(6, 48),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 33),
(2, 79),
(2, 80),
(2, 81),
(2, 82),
(2, 83),
(2, 84),
(2, 85),
(2, 41),
(2, 42),
(2, 43),
(2, 44),
(2, 45),
(2, 47),
(2, 48),
(2, 49),
(2, 50),
(2, 51),
(2, 52),
(2, 53),
(2, 62),
(2, 63),
(2, 64),
(2, 65),
(2, 66),
(2, 67),
(2, 69),
(2, 70),
(2, 14),
(2, 15),
(2, 16),
(2, 17),
(2, 18),
(2, 19),
(2, 20),
(2, 23),
(2, 54),
(2, 71),
(2, 72),
(2, 73),
(2, 74),
(2, 75),
(2, 76),
(2, 77),
(2, 1),
(2, 2),
(2, 4),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 33),
(1, 79),
(1, 41),
(1, 42),
(1, 43),
(1, 44),
(1, 45),
(1, 47),
(1, 48),
(1, 49),
(1, 50),
(1, 51),
(1, 52),
(1, 53),
(1, 62),
(1, 67),
(1, 68),
(1, 69),
(1, 18),
(1, 19),
(1, 20),
(1, 54),
(1, 55),
(1, 56),
(1, 71),
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(7, 6),
(7, 7),
(7, 8),
(7, 9),
(7, 79),
(7, 80),
(7, 82),
(7, 41),
(7, 42),
(7, 43),
(7, 44),
(7, 45),
(7, 48),
(7, 51),
(7, 87),
(7, 88),
(7, 62),
(7, 63),
(7, 64),
(7, 66),
(7, 67),
(7, 14),
(7, 17),
(7, 20),
(7, 23),
(3, 6),
(3, 7),
(3, 8),
(3, 79),
(3, 80),
(3, 41),
(3, 42),
(3, 43),
(3, 44),
(3, 48),
(3, 51),
(3, 62),
(3, 63),
(3, 66),
(3, 67),
(3, 14),
(3, 17),
(3, 20),
(3, 23),
(5, 6),
(5, 7),
(5, 79),
(5, 80),
(5, 82),
(5, 41),
(5, 42),
(5, 43),
(5, 44),
(5, 48),
(5, 51),
(5, 87),
(5, 88),
(5, 62),
(5, 63),
(5, 64),
(5, 65),
(5, 66),
(5, 67),
(5, 69),
(5, 70),
(5, 14),
(5, 17),
(5, 20),
(5, 23),
(5, 54),
(4, 6),
(4, 7),
(4, 79),
(4, 80),
(4, 82),
(4, 42),
(4, 43),
(4, 44),
(4, 45),
(4, 48),
(4, 51),
(4, 87),
(4, 88),
(4, 62),
(4, 63),
(4, 64),
(4, 66),
(4, 67),
(4, 68),
(4, 86),
(4, 69),
(4, 70),
(4, 14),
(4, 17),
(4, 20),
(4, 23),
(4, 71),
(4, 74),
(4, 75),
(4, 76),
(4, 77),
(4, 1),
(4, 2),
(4, 11);

-- --------------------------------------------------------

--
-- Table structure for table `scratch_cards`
--

CREATE TABLE `scratch_cards` (
  `id_card_key` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial_no` varbinary(20) DEFAULT NULL,
  `amount` int(5) DEFAULT NULL,
  `lot_id` int(10) NOT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT '5' COMMENT '5=available, 6=used, 7=deleted',
  `ref_id` int(10) UNSIGNED DEFAULT NULL COMMENT '`payments`.`id_payment_key`',
  `comment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'indicates whether update is done by subscibrer or system-user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scratch_card_lots`
--

CREATE TABLE `scratch_card_lots` (
  `id_lot_key` int(10) UNSIGNED NOT NULL,
  `description` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'eg: card of 300 taka',
  `amount` int(6) UNSIGNED NOT NULL COMMENT 'Price of each card',
  `qty` int(6) UNSIGNED NOT NULL COMMENT 'how many cards generated',
  `status_id` tinyint(1) UNSIGNED NOT NULL COMMENT '0=temporarily generated, 1=confirmed, 2=cancled',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scratch_card_wrong_tries`
--

CREATE TABLE `scratch_card_wrong_tries` (
  `id_try_key` bigint(20) UNSIGNED NOT NULL,
  `subscriber_id` bigint(20) UNSIGNED DEFAULT NULL,
  `card_no` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(10) UNSIGNED DEFAULT NULL,
  `ref_id` varchar(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'DateTime of wrong try',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'subscriber id by whom wrong tries done'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_notificatoin_send`
--

CREATE TABLE `sms_notificatoin_send` (
  `id_notification_key` int(10) UNSIGNED NOT NULL,
  `sms_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sms_receiver` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'subscriber_id:mobile_number|',
  `area_id` int(10) UNSIGNED DEFAULT NULL,
  `building_id` int(10) UNSIGNED DEFAULT NULL,
  `sms_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dtt_sent` datetime DEFAULT NULL,
  `uid_sent` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id_subscriber_key` int(10) UNSIGNED NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login_credential_version` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `ba_no` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rank_id` int(10) UNSIGNED NOT NULL,
  `official_mobile` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `personal_mobile` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `residential_phone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_balance` int(6) NOT NULL DEFAULT '0',
  `payment_version` int(10) UNSIGNED DEFAULT '0' COMMENT 'Version histories in `payments` table',
  `connection_from` datetime DEFAULT NULL,
  `connection_to` datetime DEFAULT NULL,
  `connection_version` int(10) UNSIGNED DEFAULT '0',
  `status_id` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '1=Active, 0=Suspended, 2=Deleted',
  `status_version` int(10) UNSIGNED DEFAULT '1',
  `router_no` int(5) UNSIGNED DEFAULT NULL,
  `area_id` int(10) UNSIGNED DEFAULT NULL,
  `building_id` int(10) UNSIGNED DEFAULT NULL,
  `house_no` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Address for corporate user',
  `local_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area_version` int(10) UNSIGNED DEFAULT '1',
  `subs_type` enum('default','corporate') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `category` enum('Paid','Complementary','Free') COLLATE utf8_unicode_ci NOT NULL,
  `complementary_amount` int(5) DEFAULT '0',
  `complemtntary_ratio_factor` decimal(10,4) DEFAULT '0.0000',
  `category_version` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `package_id` int(10) UNSIGNED DEFAULT NULL,
  `package_version` int(10) UNSIGNED DEFAULT '1',
  `corporate_package` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Corporate Package',
  `corporate_package_price` smallint(5) UNSIGNED DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers_areas_audit`
--

CREATE TABLE `subscribers_areas_audit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscriber_id` int(11) UNSIGNED NOT NULL,
  `router_no` int(3) UNSIGNED NOT NULL,
  `area_id` int(10) UNSIGNED NOT NULL,
  `building_id` int(10) UNSIGNED NOT NULL,
  `house_no` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `local_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `dtt_mod` datetime NOT NULL,
  `uid_mod` int(11) UNSIGNED NOT NULL,
  `user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers_categories_audit`
--

CREATE TABLE `subscribers_categories_audit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscriber_id` int(11) DEFAULT NULL,
  `category` enum('Paid','Complementary','Free') COLLATE utf8_unicode_ci NOT NULL,
  `complementary_amount` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `comment` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `dtt_mod` datetime NOT NULL,
  `uid_mod` int(11) UNSIGNED NOT NULL,
  `user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers_connections_audit`
--

CREATE TABLE `subscribers_connections_audit` (
  `id_connection_key` int(10) UNSIGNED NOT NULL,
  `subscriber_id` int(10) UNSIGNED DEFAULT NULL,
  `status_id` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1=Active, 0=Suspended, 2=Deleted',
  `package_id` int(10) UNSIGNED DEFAULT NULL,
  `total_bandwidth` decimal(10,4) DEFAULT '0.0000',
  `free_bandwidth` decimal(10,4) DEFAULT '0.0000',
  `paid_bandwidth` decimal(10,4) DEFAULT '0.0000',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `payment_ref_id` int(10) UNSIGNED DEFAULT NULL,
  `connection_from` datetime DEFAULT NULL,
  `connection_to` datetime DEFAULT NULL,
  `comment` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers_login_credentials_audit`
--

CREATE TABLE `subscribers_login_credentials_audit` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscriber_id` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `dtt_mod` datetime NOT NULL,
  `uid_mod` int(11) NOT NULL,
  `user_type` enum('system','susbscriber') COLLATE utf8_unicode_ci DEFAULT 'system'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers_miktorik_logs`
--

CREATE TABLE `subscribers_miktorik_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscriber_id` int(11) DEFAULT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `local_ip` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_ip` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `router_ip` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `miktorik_response` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `dtt_mod` datetime DEFAULT NULL,
  `uid_mod` int(11) UNSIGNED NOT NULL,
  `utype` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers_packages_audit`
--

CREATE TABLE `subscribers_packages_audit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscriber_id` int(11) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `dtt_mod` datetime NOT NULL,
  `uid_mod` int(11) UNSIGNED NOT NULL,
  `user_type` enum('system','subscriber') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriber_conection_revised_20180324`
--

CREATE TABLE `subscriber_conection_revised_20180324` (
  `subscriber_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `rank` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_no` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `building` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `router_no` int(5) UNSIGNED DEFAULT NULL,
  `local_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `package_code` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `status_id` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '1=Active, 0=Suspended, 2=Deleted',
  `connection_from` datetime DEFAULT NULL,
  `connection_to` datetime DEFAULT NULL,
  `category` enum('Paid','Complementary','Free') COLLATE utf8_unicode_ci NOT NULL,
  `is_modified` varchar(1) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tot_ques` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `tot_subs` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `dtt_create` datetime DEFAULT NULL,
  `uid_create` int(11) DEFAULT NULL,
  `dtt_mod` datetime DEFAULT NULL,
  `uid_mod` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

CREATE TABLE `survey_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `survey_id` int(10) UNSIGNED NOT NULL COMMENT '`surveys`.`id`',
  `question_text` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `question_type` enum('single_choice','multi_choice','text') COLLATE utf8_unicode_ci DEFAULT NULL,
  `dtt_add` datetime DEFAULT NULL,
  `uid_add` int(10) UNSIGNED DEFAULT NULL,
  `dtt_mod` datetime DEFAULT NULL,
  `uid_mod` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '1=active, 0=deleted, 2=disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey_question_answers`
--

CREATE TABLE `survey_question_answers` (
  `id` int(10) UNSIGNED NOT NULL,
  `survey_id` int(10) UNSIGNED DEFAULT NULL COMMENT '`surveys`.`id`',
  `question_id` int(10) UNSIGNED DEFAULT NULL COMMENT '`survey_questions`.id',
  `answers` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dtt_answer` datetime DEFAULT NULL,
  `uid_answer_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey_question_options`
--

CREATE TABLE `survey_question_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `survey_id` int(10) UNSIGNED NOT NULL COMMENT '`surveys`.`id`',
  `question_id` int(10) UNSIGNED NOT NULL COMMENT '`survey_questions`.`id`',
  `question_option_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '1=active, 0=deleted, 2=disabled',
  `uid_add` int(10) UNSIGNED NOT NULL,
  `dtt_add` datetime NOT NULL,
  `uid_mod` int(10) UNSIGNED DEFAULT NULL,
  `dtt_mod` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey_subscriber`
--

CREATE TABLE `survey_subscriber` (
  `id` int(10) UNSIGNED NOT NULL,
  `survey_id` int(10) UNSIGNED NOT NULL COMMENT '`surveys`.`id`',
  `subscriber_id` int(10) UNSIGNED NOT NULL COMMENT '`subscribers`.`id`',
  `dtt_start` datetime DEFAULT NULL,
  `dtt_complete` datetime DEFAULT NULL,
  `is_completed` tinyint(1) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_card_payments`
--

CREATE TABLE `tmp_card_payments` (
  `Router` varchar(8) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `amount` double DEFAULT '0',
  `comments` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `uniquecode` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `userName` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `billtime` datetime DEFAULT NULL,
  `disconnect_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_ip_addresses`
--

CREATE TABLE `tmp_ip_addresses` (
  `id_ip_key` int(10) UNSIGNED NOT NULL,
  `ip` varchar(54) COLLATE utf8_unicode_ci DEFAULT NULL,
  `building_id` int(10) UNSIGNED DEFAULT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Available, 1=Occupied',
  `occupied_subscriber_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `version` int(10) UNSIGNED DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_payments_nov_till`
--

CREATE TABLE `tmp_payments_nov_till` (
  `amount` double DEFAULT NULL,
  `comments` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `uniquecode` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `userName` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `billTime` datetime DEFAULT NULL,
  `disconnectTime` datetime DEFAULT NULL,
  `routerNo` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_payments_nov_till_unique`
--

CREATE TABLE `tmp_payments_nov_till_unique` (
  `amount` double DEFAULT NULL,
  `comments` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `uniquecode` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `userName` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `billTime` datetime DEFAULT NULL,
  `disconnectTime` datetime DEFAULT NULL,
  `routerNo` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_scratch_cards`
--

CREATE TABLE `tmp_scratch_cards` (
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `serial_no` varbinary(20) NOT NULL,
  `amount` int(5) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_scratch_cards_bkp_0115`
--

CREATE TABLE `tmp_scratch_cards_bkp_0115` (
  `id_card_key` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial_no` varbinary(20) DEFAULT NULL,
  `amount` int(5) DEFAULT NULL,
  `lot_id` int(10) NOT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT '5' COMMENT '5=available, 6=used, 7=deleted',
  `ref_id` int(10) UNSIGNED DEFAULT NULL COMMENT '`payments`.`id_payment_key`',
  `comment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_user_type` enum('system','subscriber') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'indicates whether update is done by subscibrer or system-user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_suspend_users`
--

CREATE TABLE `tmp_suspend_users` (
  `id_subscriber_key` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `router_no` int(5) UNSIGNED DEFAULT NULL,
  `category` enum('Paid','Complementary','Free') COLLATE utf8_unicode_ci NOT NULL,
  `connection_to` datetime DEFAULT NULL,
  `connection_version` int(11) UNSIGNED DEFAULT NULL,
  `dtt_mod` datetime NOT NULL,
  `is_processed` varchar(2) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ba_no` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` varchar(86) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(86) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rank` int(5) UNSIGNED NOT NULL,
  `mobile` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(124) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(124) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt` varchar(124) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_id` tinyint(1) DEFAULT '1' COMMENT '1=Active,0=Inactive,2=Deleted',
  `is_support_asst` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `version` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `ba_no`, `firstname`, `lastname`, `rank`, `mobile`, `email`, `password`, `salt`, `status_id`, `is_support_asst`, `created_at`, `created_by`, `updated_at`, `updated_by`, `version`) VALUES
(1, 'sysadmin', 'sysadmin', 'System', 'Admin', 22, '01911745532', 'rafiq@local.com', '1142df204da450b152987f8d9014394682d0e45b78dd677c0d0c322f35a4f57e', 'éÒ?Ðí o,í7{]½ó×0g˜a¤G4JÅøR—Âp+', 1, 1, '2016-11-15 00:00:19', 1, '2018-06-25 11:27:59', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(2, 1),
(2, 3),
(15, 3),
(19, 3),
(8, 4),
(18, 3),
(11, 1),
(17, 1),
(24, 1),
(5, 3),
(31, 6),
(4, 5),
(9, 3),
(12, 2),
(22, 3),
(16, 3),
(20, 3),
(14, 3),
(6, 3),
(21, 3),
(34, 3),
(35, 1),
(39, 3),
(38, 3),
(40, 3),
(28, 3),
(41, 3),
(42, 3),
(32, 1),
(1, 1),
(13, 3),
(10, 7),
(29, 3),
(27, 7),
(26, 7),
(7, 7),
(33, 7),
(43, 3),
(44, 2),
(37, 5),
(46, 7),
(47, 7),
(48, 6),
(49, 3),
(50, 3),
(52, 3),
(36, 7),
(45, 4),
(51, 3),
(25, 3),
(23, 7),
(52, 4),
(3, 1),
(30, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acls`
--
ALTER TABLE `acls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acl_functions`
--
ALTER TABLE `acl_functions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acl_modules`
--
ALTER TABLE `acl_modules`
  ADD PRIMARY KEY (`id_acl_module`);

--
-- Indexes for table `acl_pages`
--
ALTER TABLE `acl_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indexes for table `bank_deposits`
--
ALTER TABLE `bank_deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id_building`);

--
-- Indexes for table `bulk_sms`
--
ALTER TABLE `bulk_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complains`
--
ALTER TABLE `complains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complains_audit`
--
ALTER TABLE `complains_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cid` (`complain_id`);

--
-- Indexes for table `complaint_option_problems`
--
ALTER TABLE `complaint_option_problems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint_option_supports`
--
ALTER TABLE `complaint_option_supports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complementaries`
--
ALTER TABLE `complementaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_addresses`
--
ALTER TABLE `ip_addresses`
  ADD PRIMARY KEY (`id_ip_key`),
  ADD UNIQUE KEY `ip` (`ip`),
  ADD KEY `building_id` (`building_id`);

--
-- Indexes for table `mikrotik_routers`
--
ALTER TABLE `mikrotik_routers`
  ADD PRIMARY KEY (`id_router`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment_key`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `scratch_cards`
--
ALTER TABLE `scratch_cards`
  ADD PRIMARY KEY (`id_card_key`),
  ADD UNIQUE KEY `unique_code` (`code`,`amount`);

--
-- Indexes for table `scratch_card_lots`
--
ALTER TABLE `scratch_card_lots`
  ADD PRIMARY KEY (`id_lot_key`);

--
-- Indexes for table `scratch_card_wrong_tries`
--
ALTER TABLE `scratch_card_wrong_tries`
  ADD PRIMARY KEY (`id_try_key`);

--
-- Indexes for table `sms_notificatoin_send`
--
ALTER TABLE `sms_notificatoin_send`
  ADD PRIMARY KEY (`id_notification_key`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id_subscriber_key`),
  ADD UNIQUE KEY `remote_ip` (`remote_ip`),
  ADD KEY `login_id` (`username`),
  ADD KEY `rank_id` (`rank_id`),
  ADD KEY `server_area_id` (`area_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `official_mobile` (`official_mobile`);

--
-- Indexes for table `subscribers_areas_audit`
--
ALTER TABLE `subscribers_areas_audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers_categories_audit`
--
ALTER TABLE `subscribers_categories_audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers_connections_audit`
--
ALTER TABLE `subscribers_connections_audit`
  ADD PRIMARY KEY (`id_connection_key`);

--
-- Indexes for table `subscribers_login_credentials_audit`
--
ALTER TABLE `subscribers_login_credentials_audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers_miktorik_logs`
--
ALTER TABLE `subscribers_miktorik_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers_packages_audit`
--
ALTER TABLE `subscribers_packages_audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_question_answers`
--
ALTER TABLE `survey_question_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_question_options`
--
ALTER TABLE `survey_question_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_subscriber`
--
ALTER TABLE `survey_subscriber`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tmp_ip_addresses`
--
ALTER TABLE `tmp_ip_addresses`
  ADD PRIMARY KEY (`id_ip_key`),
  ADD UNIQUE KEY `ip` (`ip`);

--
-- Indexes for table `tmp_scratch_cards`
--
ALTER TABLE `tmp_scratch_cards`
  ADD UNIQUE KEY `serial_no` (`serial_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `ba_no` (`ba_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acls`
--
ALTER TABLE `acls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `acl_functions`
--
ALTER TABLE `acl_functions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `acl_modules`
--
ALTER TABLE `acl_modules`
  MODIFY `id_acl_module` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `acl_pages`
--
ALTER TABLE `acl_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bank_deposits`
--
ALTER TABLE `bank_deposits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id_building` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=279;

--
-- AUTO_INCREMENT for table `bulk_sms`
--
ALTER TABLE `bulk_sms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `complains`
--
ALTER TABLE `complains`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14982;

--
-- AUTO_INCREMENT for table `complains_audit`
--
ALTER TABLE `complains_audit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35815;

--
-- AUTO_INCREMENT for table `complaint_option_problems`
--
ALTER TABLE `complaint_option_problems`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `complaint_option_supports`
--
ALTER TABLE `complaint_option_supports`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `complementaries`
--
ALTER TABLE `complementaries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ip_addresses`
--
ALTER TABLE `ip_addresses`
  MODIFY `id_ip_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70418;

--
-- AUTO_INCREMENT for table `mikrotik_routers`
--
ALTER TABLE `mikrotik_routers`
  MODIFY `id_router` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101077;

--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `scratch_cards`
--
ALTER TABLE `scratch_cards`
  MODIFY `id_card_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195874;

--
-- AUTO_INCREMENT for table `scratch_card_lots`
--
ALTER TABLE `scratch_card_lots`
  MODIFY `id_lot_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `scratch_card_wrong_tries`
--
ALTER TABLE `scratch_card_wrong_tries`
  MODIFY `id_try_key` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_notificatoin_send`
--
ALTER TABLE `sms_notificatoin_send`
  MODIFY `id_notification_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id_subscriber_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4371;

--
-- AUTO_INCREMENT for table `subscribers_areas_audit`
--
ALTER TABLE `subscribers_areas_audit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17850;

--
-- AUTO_INCREMENT for table `subscribers_categories_audit`
--
ALTER TABLE `subscribers_categories_audit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5363;

--
-- AUTO_INCREMENT for table `subscribers_connections_audit`
--
ALTER TABLE `subscribers_connections_audit`
  MODIFY `id_connection_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115242;

--
-- AUTO_INCREMENT for table `subscribers_login_credentials_audit`
--
ALTER TABLE `subscribers_login_credentials_audit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5079;

--
-- AUTO_INCREMENT for table `subscribers_miktorik_logs`
--
ALTER TABLE `subscribers_miktorik_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4344;

--
-- AUTO_INCREMENT for table `subscribers_packages_audit`
--
ALTER TABLE `subscribers_packages_audit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6852;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `survey_questions`
--
ALTER TABLE `survey_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `survey_question_answers`
--
ALTER TABLE `survey_question_answers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `survey_question_options`
--
ALTER TABLE `survey_question_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `survey_subscriber`
--
ALTER TABLE `survey_subscriber`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmp_ip_addresses`
--
ALTER TABLE `tmp_ip_addresses`
  MODIFY `id_ip_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

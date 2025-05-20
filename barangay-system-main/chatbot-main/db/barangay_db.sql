-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 09:18 AM
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
-- Database: `barangay_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `baptismal_certification_requests`
--

CREATE TABLE `baptismal_certification_requests` (
  `id` int(11) NOT NULL,
  `parent_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `child_name` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baptismal_certification_requests`
--

INSERT INTO `baptismal_certification_requests` (`id`, `parent_name`, `address`, `child_name`, `purpose`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'qweqwewq', 'eqwewqeqe', 'wqeqeqeq', 'Baptismal', 'PICK UP', '2025-05-01 03:02:52', 'approved', 20.00, NULL),
(2, 'tung tung sahur', 'imus', 'Lechon Kawali', 'Baptismal', 'PICK UP', '2025-05-20 03:07:06', 'pending', 20.00, NULL),
(3, 'tung tung sahur', 'imus', 'Lechon Kawali', 'Baptismal', 'PICK UP', '2025-05-20 03:08:32', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `barangay_clearance`
--

CREATE TABLE `barangay_clearance` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `complete_address` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `age` int(11) NOT NULL,
  `civil_status` varchar(50) DEFAULT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `years_of_stay` varchar(50) DEFAULT NULL,
  `purpose` varchar(255) NOT NULL,
  `student_patient_name` varchar(255) NOT NULL,
  `student_patient_address` varchar(255) NOT NULL,
  `relationship` varchar(100) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_clearance`
--

INSERT INTO `barangay_clearance` (`id`, `first_name`, `middle_name`, `last_name`, `complete_address`, `birth_date`, `age`, `civil_status`, `mobile_number`, `years_of_stay`, `purpose`, `student_patient_name`, `student_patient_address`, `relationship`, `shipping_method`, `created_at`, `user_id`, `submitted_at`, `status`, `cost`) VALUES
(22, 'Eyser', 'De Ocampo', 'Santiaguel', '48 Bucandala 1 City of Imus, Cavite', '2001-02-02', 24, 'Single', '12321321312', '12', 'wqeqwe', 'qweqwqweqwe', 'qweqweqweqw', 'qweqweqweqqwe', 'PICK UP', '2025-05-13 03:41:12', NULL, '2025-05-13 11:41:12', 'pending', 20.00),
(23, 'Eyser', 'De Ocampo', 'Santiaguel', '48 Bucandala 1 City of Imus, Cavite', '2001-02-02', 24, 'Single', '12321321312', '12', 'wqeqwe', 'qweqwqweqwe', 'qweqweqweqw', 'qweqweqweqqwe', 'PICK UP', '2025-05-13 03:46:47', NULL, '2025-05-13 11:46:47', 'pending', 20.00),
(24, 'Eyser', 'De Ocampo', 'Santiaguel', '48 Bucandala 1 City of Imus, Cavite', '2001-02-02', 24, 'Single', '12321321312', '12', 'wqeqwe', 'qweqwqweqwe', 'qweqweqweqw', 'qweqweqweqqwe', 'PICK UP', '2025-05-13 03:46:48', NULL, '2025-05-13 11:46:48', 'rejected', 20.00),
(25, 'Eyser', 'De Ocampo', 'Santiaguel', '48 Bucandala 1 City of Imus, Cavite', '2001-02-02', 24, 'Single', '12321321312', '12', 'wqeqwe', 'qweqwqweqwe', 'qweqweqweqw', 'qweqweqweqqwe', 'PICK UP', '2025-05-13 03:46:48', NULL, '2025-05-13 11:46:48', 'rejected', 20.00),
(26, 'Eyser', 'De Ocampo', 'Santiaguel', '48 Bucandala 1 City of Imus, Cavite', '2001-02-02', 24, 'Single', '12321321312', '12', 'wqeqwe', 'qweqwqweqwe', 'qweqweqweqw', 'qweqweqweqqwe', 'PICK UP', '2025-05-13 03:46:49', NULL, '2025-05-13 11:46:49', 'approved', 20.00),
(27, 'Eyser', 'De Ocampo', 'Santiaguel', '48 Bucandala 1 City of Imus, Cavite', '2001-02-02', 24, 'Single', '12321321312', '12', 'wqeqwe', 'qweqwqweqwe', 'qweqweqweqw', 'qweqweqweqqwe', 'PICK UP', '2025-05-13 03:46:49', NULL, '2025-05-13 11:46:49', 'pending', 20.00),
(28, 'tung', 'tung', 'sahur', 'imus', '2025-05-01', 1, 'Widowed', '09129012049', '12', 'astig', 'Maangas', 'Dito', 'Bangus', 'PICK UP', '2025-05-19 08:29:37', 7, '2025-05-19 16:29:37', 'pending', 20.00),
(29, 'tung', 'tung', 'sahur', 'imus', '2025-05-01', 21, 'Widowed', '09129012049', '12', 'astig', 'Maangas', 'Dito', 'Bangus', 'PICK UP', '2025-05-20 02:23:20', 7, '2025-05-20 10:23:20', 'pending', 20.00),
(30, 'tung', 'tung', 'sahur', 'imus', '2025-05-01', 21, 'Widowed', '09129012049', '12', 'enrollment', 'aicer1 john santiaguel', 'sa barangay bucandala i', 'utensil', 'PICK UP', '2025-05-20 02:23:38', 7, '2025-05-20 10:23:38', 'rejected', 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `barangay_id_requests`
--

CREATE TABLE `barangay_id_requests` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `emergency_full_name` varchar(100) NOT NULL,
  `emergency_address` varchar(255) NOT NULL,
  `emergency_contact_number` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gov_id` varchar(100) NOT NULL,
  `shipping_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_id_requests`
--

INSERT INTO `barangay_id_requests` (`id`, `first_name`, `middle_name`, `last_name`, `address`, `contact_number`, `emergency_full_name`, `emergency_address`, `emergency_contact_number`, `date_of_birth`, `gov_id`, `shipping_method`, `created_at`, `user_id`, `submitted_at`, `status`, `cost`) VALUES
(3, 'qweqweqwe', 'qweqwe', 'wqeqweqewq', 'ewqewqewqe', '', '', '', '', '0125-04-12', 'qweqweqwe', 'PICK UP', '2025-04-29 09:33:34', 1, '2025-05-13 11:48:47', 'pending', 20.00),
(4, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '', '', '', '', '2006-02-08', 'TIN ID', 'PICK UP', '2025-05-13 08:31:12', NULL, '2025-05-13 16:31:12', 'pending', 20.00),
(5, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '', '', '', '', '2006-02-08', 'TIN ID', 'PICK UP', '2025-05-14 00:58:56', NULL, '2025-05-14 08:58:56', 'pending', 20.00),
(6, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '', '', '', '', '2006-02-08', 'Postal ID', 'PICK UP', '2025-05-14 01:22:32', NULL, '2025-05-14 09:22:32', 'pending', 20.00),
(7, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '', '', '', '', '2006-02-08', 'PRC ID', 'PICK UP', '2025-05-14 01:35:34', NULL, '2025-05-14 09:35:34', 'pending', 20.00),
(8, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '', '', '', '', '2006-02-08', 'PRC ID', 'PICK UP', '2025-05-14 01:38:40', NULL, '2025-05-14 09:38:40', 'pending', 20.00),
(9, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '', '', '', '', '2006-02-08', 'Philippine Passport', 'PICK UP', '2025-05-14 01:38:56', NULL, '2025-05-14 09:38:56', 'pending', 20.00),
(10, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '09561657974', '', 'Blk 2 lot 20 Mandarin coopville bayanan', '', '2006-02-08', 'PRC ID', 'PICK UP', '2025-05-14 01:58:08', NULL, '2025-05-14 09:58:08', 'pending', 20.00),
(11, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '09561657974', 'James Earl Galvan Carza', 'Blk 2 lot 20 Mandarin coopville bayanan', '09561657974', '2006-02-08', 'Driver’s License', 'PICK UP', '2025-05-14 02:01:21', NULL, '2025-05-14 10:01:21', 'approved', 20.00),
(12, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '09561657974', 'asd', 'asd', 'asd', '2006-02-08', 'Driver’s License', 'PICK UP', '2025-05-14 02:05:41', NULL, '2025-05-14 10:05:41', 'rejected', 20.00),
(13, 'tung', 'tung', 'sahur', 'imus', '09129012049', 'sir huele', 'sa barangay bucandala i', '09292933333', '2012-02-08', 'UMID', 'PICK UP', '2025-05-20 02:58:17', 7, '2025-05-20 10:58:17', 'pending', 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `certificate_of_good_moral_requests`
--

CREATE TABLE `certificate_of_good_moral_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `civil_status` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_of_good_moral_requests`
--

INSERT INTO `certificate_of_good_moral_requests` (`id`, `full_name`, `age`, `civil_status`, `address`, `purpose`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'qweqwe', 21, 'qwe', 'qweqweqwe', 'qweqweqweqw', 'PICK UP', '2025-05-01 03:01:21', 'pending', 20.00, NULL),
(2, 'qweqwe', 21, 'qwe', 'qweqweqwe', 'qweqweqweqw', 'PICK UP', '2025-05-01 03:01:22', 'pending', 20.00, NULL),
(3, 'tung tung sahur', 13, 'Widowed', 'imus', 'enrollment', 'PICK UP', '2025-05-20 03:05:15', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `certificate_of_indigency_requests`
--

CREATE TABLE `certificate_of_indigency_requests` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `civil_status` enum('single','married','widowed','divorced') NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `monthly_income` decimal(10,2) NOT NULL,
  `proof_of_residency` varchar(255) NOT NULL,
  `gov_id` varchar(100) NOT NULL,
  `spouse_name` varchar(100) DEFAULT NULL,
  `number_of_dependents` int(11) NOT NULL,
  `shipping_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_of_indigency_requests`
--

INSERT INTO `certificate_of_indigency_requests` (`id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `civil_status`, `occupation`, `monthly_income`, `proof_of_residency`, `gov_id`, `spouse_name`, `number_of_dependents`, `shipping_method`, `created_at`, `user_id`, `submitted_at`, `status`, `cost`) VALUES
(1, 'qwe', 'wqe', 'qwe', '1231-03-12', 'single', 'qwe', 2112.00, 'qwe', 'qweqwe', 'qeqwe', 12, 'PICK UP', '2025-04-24 10:26:46', NULL, '2025-05-13 12:00:08', 'rejected', 20.00),
(3, 'Eyser', 'De Ocampo', 'Santiaguel', '2001-02-02', 'single', 'qweqweq', 99999999.99, 'qweqweqw', 'Barangay ID', 'qweqweqwe', 12, 'PICK UP', '2025-05-13 04:03:19', NULL, '2025-05-13 12:03:19', 'approved', 20.00),
(4, 'Eyser', 'De Ocampo', 'Santiaguel', '2001-02-02', 'single', 'qweqwe', 1000.00, 'qweqwe', 'TIN ID', 'qweqwe', 1, 'PICK UP', '2025-05-13 04:04:16', NULL, '2025-05-13 12:04:16', 'pending', 20.00),
(5, 'tung', 'tung', 'sahur', '2025-05-01', 'married', 'Maangas', 1000000.00, 'HOA', 'Philippine Passport', 'Bombardino Crocodilo', 1, 'PICK UP', '2025-05-20 02:17:21', NULL, '2025-05-20 10:17:21', 'pending', 20.00),
(6, 'tung', 'tung', 'sahur', '2012-02-08', 'married', 'Maangas', 1000000.00, 'HOA', 'UMID', 'Bombardino Crocodilo', 1, 'PICK UP', '2025-05-20 02:59:42', NULL, '2025-05-20 10:59:42', 'pending', 20.00),
(7, 'tung', 'tung', 'sahur', '2012-02-08', 'married', 'Maangas', 1000000.00, 'HOA', 'Postal ID', 'Bombardino Crocodilo', 1, 'PICK UP', '2025-05-20 03:01:32', 7, '2025-05-20 11:01:32', 'approved', 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `certificate_of_residency_requests`
--

CREATE TABLE `certificate_of_residency_requests` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gov_id` varchar(100) NOT NULL,
  `complete_address` varchar(255) NOT NULL,
  `proof_of_residency` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_of_residency_requests`
--

INSERT INTO `certificate_of_residency_requests` (`id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `gov_id`, `complete_address`, `proof_of_residency`, `purpose`, `shipping_method`, `created_at`, `user_id`, `submitted_at`, `status`, `cost`) VALUES
(1, 'qwe', 'qwe', 'qwe', '0000-00-00', 'qwe', 'qwe', 'qwe', 'qwe', 'PICK UP', '2025-04-24 10:34:45', NULL, '2025-05-13 12:01:16', 'pending', 20.00),
(2, 'qweqas', 'asfasf', 'qwesa', '2025-05-02', 'asfas', 'qweq', 'asfa', 'zxc', 'asdqwe', '0000-00-00 00:00:00', 3, '2025-05-10 00:00:00', 'approved', 20.00),
(3, 'tung', 'tung', 'sahur', '2025-05-01', 'Philippine Passport', 'imus', 'HOA', 'enrollment', 'PICK UP', '2025-05-20 02:19:36', 7, '2025-05-20 10:19:36', 'pending', 20.00),
(4, 'tung', 'tung', 'sahur', '2012-02-08', 'Philippine Passport', 'imus', 'HOA', 'enrollment', 'PICK UP', '2025-05-20 02:58:57', 7, '2025-05-20 10:58:57', 'pending', 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `cohabitation_certification_requests`
--

CREATE TABLE `cohabitation_certification_requests` (
  `id` int(11) NOT NULL,
  `partner1_name` varchar(255) NOT NULL,
  `partner2_name` varchar(255) NOT NULL,
  `shared_address` varchar(255) NOT NULL,
  `cohabitation_duration` varchar(100) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cohabitation_certification_requests`
--

INSERT INTO `cohabitation_certification_requests` (`id`, `partner1_name`, `partner2_name`, `shared_address`, `cohabitation_duration`, `purpose`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'qweqwewq', 'wqewqewqewq', 'wqeqweqw', 'wqeqwewqewq', 'eqewqe', 'PICK UP', '2025-05-01 03:04:26', 'pending', 20.00, NULL),
(2, 'tung tung sahur', 'bombardino crocodilo', 'imus', '9', 'enrollment', 'PICK UP', '2025-05-20 02:45:08', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `construction_clearance_requests`
--

CREATE TABLE `construction_clearance_requests` (
  `id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `business_location` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `owner_address` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `construction_clearance_requests`
--

INSERT INTO `construction_clearance_requests` (`id`, `business_name`, `business_location`, `owner_name`, `owner_address`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'qweqwewqe', 'qweqweqeq', 'asdasdas', 'asdasd', 'PICK UP', '2025-05-01 03:05:18', 'pending', 20.00, NULL),
(2, 'JabiJabi', 'Nueno', 'tung tung sahur', 'imus', 'PICK UP', '2025-05-20 02:37:53', 'pending', 20.00, NULL),
(3, 'JabiJabi', 'Nueno', 'tung tung sahur', 'imus', 'PICK UP', '2025-05-20 02:39:15', 'pending', 20.00, 7),
(4, 'JabiJabi', 'Nueno', 'tung tung sahur', 'imus', 'PICK UP', '2025-05-20 02:58:30', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_inquiries`
--

INSERT INTO `contact_inquiries` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `status`, `user_id`) VALUES
(1, 'qwe', 'qwe@gmail.com', 'qwe', 'qwe', '2025-04-29 03:45:53', 'pending', NULL),
(2, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:46:46', 'pending', NULL),
(3, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:48', 'pending', NULL),
(4, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:48', 'pending', NULL),
(5, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:48', 'pending', NULL),
(6, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:49', 'pending', NULL),
(7, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:49', 'pending', NULL),
(8, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:49', 'rejected', NULL),
(9, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:49', 'pending', NULL),
(10, 'aicer', 'aicersantiaguel@gmail.com', 'wrtrw', 'qweqweqweqw', '2025-04-29 03:48:49', 'approved', NULL),
(13, 'qwe', 'qweqweqwe@gmail.com', 'qweqweqwe', 'qweqweqw', '2025-04-29 08:37:05', 'rejected', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `position` int(11) NOT NULL,
  `column_side` enum('left','right') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `position`, `column_side`) VALUES
(1, 'Ano ang kailangan sa pagkuha ng barangay ID?', 'Kailangan mong magdala ng valid ID at proof of residence tulad ng utility bill o barangay certificate.', 1, 'left'),
(2, 'Paano kumuha ng Barangay Indigency?', ' Magdala ng valid ID at certificate mula sa Barangay Kapitan na nagpapatunay ng iyong indigency status.', 2, 'left'),
(3, 'Ano ang kailangan sa pagkuha ng Barangay Clearance?', 'Magdala ng valid ID, barangay certificate, at bayaran ang kaukulang fee.', 3, 'left'),
(4, 'Saan maaaring mag-apply ng Barangay Business Permit?', '  Ang aplikasyon ay maaaring gawin sa Barangay Hall. Dalhin ang necessary business documents.\r\n', 4, 'left'),
(5, 'Kailan ang check-up ng mga buntis sa Barangay Health Center?', '  Ang check-up ay tuwing Lunes at Huwebes ng umaga. Tumawag sa health center para sa eksaktong iskedyul.', 1, 'right'),
(6, 'Anu-ano ang Requirements para sa Solo Parent I.D.? ', 'Ang mga sumusunod ay ang kwalipikado para sa SOLO PARENT I.D.\r\n                                    \r\nBiyuda\r\n     * Hiwalay sa asawa\r\n     * Nawalang bisa o Annulled ang kasal\r\n     * Inabandona ng asawa o ng kinakasama\r\n     * Sinumang indibidwal na tumatayo bilang head of the family bunga ng pag-abandona, pagkawala, matagal na pagkawalay ng magulang o ng solo parent\r\n     * Biktima ng panggagahasa\r\n     * Asawa ng nakakulong at/o nahatulang mabilanggo\r\n     * Hindi sapat ang mental na kapasidad', 2, 'right'),
(7, 'May bayad po ba ang Barangay I.D. at mga clearance?', ' Oo, may bayad depende sa dokumentong kukunin.', 3, 'right');

-- --------------------------------------------------------

--
-- Table structure for table `first_time_job_seeker_requests`
--

CREATE TABLE `first_time_job_seeker_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `residency_length` varchar(100) NOT NULL,
  `oath_acknowledged` enum('Yes','No') NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `first_time_job_seeker_requests`
--

INSERT INTO `first_time_job_seeker_requests` (`id`, `full_name`, `address`, `residency_length`, `oath_acknowledged`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'asdasdas', 'dasdasda', 'asdasda', 'Yes', 'PICK UP', '2025-05-01 03:06:28', 'pending', 0.00, NULL),
(2, 'tung tung sahur', 'imus', '2 years', 'Yes', 'PICK UP', '2025-05-20 03:06:09', 'pending', 0.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `late_birth_registration_requests`
--

CREATE TABLE `late_birth_registration_requests` (
  `id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `place_of_birth` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `fathers_name` varchar(255) NOT NULL,
  `mothers_name` varchar(255) NOT NULL,
  `years_in_barangay` varchar(100) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `late_birth_registration_requests`
--

INSERT INTO `late_birth_registration_requests` (`id`, `last_name`, `first_name`, `middle_name`, `address`, `marital_status`, `place_of_birth`, `date_of_birth`, `fathers_name`, `mothers_name`, `years_in_barangay`, `purpose`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'azcxzc', 'zxczxczx', 'zxczxczxczxcz', 'zxczxczxc', 'xzczxczxczxcz', 'xczxcxzc', '0124-03-12', 'zxczxczxczx', 'czxczxczx', 'zxczxczxcz', 'Late Registration of Birth Certificate', 'PICK UP', '2025-05-01 03:07:46', 'pending', 20.00, NULL),
(2, 'sahur', 'tung', 'tung', 'imus', 'Widowed', 'kawit', '2025-05-01', 'prr prr patapim', 'assassino cappucino', '5', 'Late Registration of Birth Certificate', 'PICK UP', '2025-05-20 02:30:03', 'pending', 20.00, NULL),
(3, 'sahur', 'tung', 'tung', 'imus', 'Widowed', 'kawit', '2012-02-08', 'prr prr patapim', 'assassino cappucino', '5', 'Late Registration of Birth Certificate', 'PICK UP', '2025-05-20 02:49:17', 'pending', 20.00, 7),
(4, 'sahur', 'tung', 'tung', 'imus', 'Widowed', 'kawit', '2012-02-08', 'prr prr patapim', 'assassino cappucino', '5', 'enrollment', 'PICK UP', '2025-05-20 02:50:51', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `non_residency_certification_requests`
--

CREATE TABLE `non_residency_certification_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `previous_address` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `non_residency_certification_requests`
--

INSERT INTO `non_residency_certification_requests` (`id`, `full_name`, `previous_address`, `purpose`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'adsagf', 'asdasdasd', 'asdasdas', 'PICK UP', '2025-05-01 03:10:20', 'pending', 20.00, NULL),
(2, 'tung tung sahur', 'Indang', 'Lilipat', 'PICK UP', '2025-05-20 02:51:40', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `no_income_certification_requests`
--

CREATE TABLE `no_income_certification_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `civil_status` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `no_income_statement` text NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `no_income_certification_requests`
--

INSERT INTO `no_income_certification_requests` (`id`, `full_name`, `date_of_birth`, `civil_status`, `address`, `no_income_statement`, `purpose`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'asffasda', '0000-00-00', 'asdasdasd', 'asdasdasd', 'asdasda', 'sdasdasda', 'PICK UP', '2025-05-01 03:09:07', 'pending', 20.00, NULL),
(2, 'tung tung sahur', '2012-02-08', 'Widowed', 'imus', 'Kuryenteng Ubod ng mahal', 'Bayad bill huhu', 'PICK UP', '2025-05-20 02:47:47', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `out_of_school_youth_requests`
--

CREATE TABLE `out_of_school_youth_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `citizenship` varchar(100) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `out_of_school_youth_requests`
--

INSERT INTO `out_of_school_youth_requests` (`id`, `full_name`, `address`, `citizenship`, `purpose`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'qweqweq', 'weqweqw', 'Filipino', 'qweqwe', 'PICK UP', '2025-05-01 03:11:14', 'pending', 20.00, NULL),
(2, 'tung tung sahur', 'imus', 'Filipino', 'astig', 'PICK UP', '2025-05-20 02:55:06', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_requests`
--

CREATE TABLE `solo_parent_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `solo_since` varchar(10) NOT NULL,
  `child_count` int(11) NOT NULL,
  `children_names` text NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_requests`
--

INSERT INTO `solo_parent_requests` (`id`, `full_name`, `address`, `solo_since`, `child_count`, `children_names`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'qweqweqwe', 'wqeqwewq', '12312', 21, 'qweqweqwe', 'PICK UP', '2025-05-01 03:12:00', 'pending', 20.00, NULL),
(2, 'qwe', 'qwe', '123', 12, 'qweqwe', 'PICK UP', '2025-05-01 05:07:23', 'pending', 20.00, NULL),
(3, 'tung tung sahur', 'imus', '2000', 1, 'Agubajaji', 'PICK UP', '2025-05-20 02:57:01', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `unemployment_certification_requests`
--

CREATE TABLE `unemployment_certification_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `birth_date` date NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unemployment_certification_requests`
--

INSERT INTO `unemployment_certification_requests` (`id`, `full_name`, `age`, `birth_date`, `civil_status`, `address`, `purpose`, `shipping_method`, `submitted_at`, `status`, `cost`, `user_id`) VALUES
(1, 'qweqwewqe', 21, '0000-00-00', 'Single', 'qweqweqwe', 'qweqweq', 'PICK UP', '2025-05-01 03:13:13', 'pending', 20.00, NULL),
(2, 'tung tung sahur', 13, '2012-02-08', 'Married', 'imus', 'enrollment', 'PICK UP', '2025-05-20 02:42:46', 'pending', 20.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `civil_status` enum('Single','Married','Widowed') NOT NULL,
  `government_id` varchar(100) NOT NULL,
  `id_number` varchar(100) NOT NULL,
  `emergency_contact_name` varchar(150) NOT NULL,
  `emergency_contact_number` varchar(50) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `dependents` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `address`, `email`, `username`, `password`, `dob`, `gender`, `civil_status`, `government_id`, `id_number`, `emergency_contact_name`, `emergency_contact_number`, `profile_pic`, `dependents`, `status`, `created_at`, `updated_at`, `is_admin`) VALUES
(1, 'Aicer John', 'De Ocampo', 'Santiaguel', '48 Bucandala 1 City of Imus, Cavite', 'aicersantiaguel@gmail.com', 'Eyser', '$2y$10$NMlnY01YT/GRqqbt8nm0GutOQpnP/QFzDFmYWidUJLCT.54MjXwsK', '2001-02-28', 'Male', 'Single', 'Philippine National ID', '123456', 'greys', '123123213', '', 'N/A', 'approved', '2025-05-13 04:31:05', '2025-05-14 20:48:22', 0),
(3, 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', 'qweqweqwe@gmail.com', 'Eyserr', '$2y$10$xGFyh.2rCocylTtwuS01..76DX.2IUGZfx6INu2esPPopt1bXGvbq', '2006-02-08', 'Male', 'Single', 'UMID', '123123213123', 'qweqwe', '123123312', 'n/a', 'qwe', 'approved', '2025-05-13 04:33:57', '2025-05-13 04:50:38', 1),
(5, 'qweqwe', 'qweqwe', 'qweqwe', 'qweqweqw', 'qweqweqqwewe@gmail.com', 'try', '$2y$10$PnqZXHdM8w.DCK/EjrGYmuYachRdZpj2XMKr2HoRqkVzY8Vk9ax1.', '2025-05-13', 'Male', 'Single', 'UMID', '123123', 'qwewqe', '12312312321', '', '', 'approved', '2025-05-13 05:07:34', '2025-05-13 05:15:46', 0),
(6, 'ric', 'john', 'anuat', 'imus', 'ricjohnanuat@cvsu.edu.ph', 'ric111', '$2y$10$nUITv5vwJ9z4.eVzI82GquMXx8zOdqMB8U6aJ4yMdUwLz0In8iblK', '2025-05-01', 'Male', 'Single', 'Philippine National ID', '11111111111', 'sir huele', '09292933333', '', '', 'approved', '2025-05-15 08:33:17', '2025-05-18 02:10:47', 0),
(7, 'tung', 'tung', 'sahur', 'imus', 'tungtungsahur@gmail.com', 'tungtungtung1', '$2y$10$.13oMzeHN4otGjCVYGHXWO97HDdkDrzillGPem.Nws4KEN16U4c82', '2012-02-08', 'Female', 'Widowed', 'Philippine National ID', '11111111111', 'sir huele', '09292933333', 'tung-tung-sahur-meme-2.avif', '', 'approved', '2025-05-18 03:02:14', '2025-05-20 02:42:19', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baptismal_certification_requests`
--
ALTER TABLE `baptismal_certification_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangay_clearance`
--
ALTER TABLE `barangay_clearance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangay_id_requests`
--
ALTER TABLE `barangay_id_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificate_of_good_moral_requests`
--
ALTER TABLE `certificate_of_good_moral_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificate_of_indigency_requests`
--
ALTER TABLE `certificate_of_indigency_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificate_of_residency_requests`
--
ALTER TABLE `certificate_of_residency_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cohabitation_certification_requests`
--
ALTER TABLE `cohabitation_certification_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `construction_clearance_requests`
--
ALTER TABLE `construction_clearance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `first_time_job_seeker_requests`
--
ALTER TABLE `first_time_job_seeker_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `late_birth_registration_requests`
--
ALTER TABLE `late_birth_registration_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `non_residency_certification_requests`
--
ALTER TABLE `non_residency_certification_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `no_income_certification_requests`
--
ALTER TABLE `no_income_certification_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `out_of_school_youth_requests`
--
ALTER TABLE `out_of_school_youth_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `solo_parent_requests`
--
ALTER TABLE `solo_parent_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unemployment_certification_requests`
--
ALTER TABLE `unemployment_certification_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `baptismal_certification_requests`
--
ALTER TABLE `baptismal_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `barangay_clearance`
--
ALTER TABLE `barangay_clearance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `barangay_id_requests`
--
ALTER TABLE `barangay_id_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `certificate_of_good_moral_requests`
--
ALTER TABLE `certificate_of_good_moral_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certificate_of_indigency_requests`
--
ALTER TABLE `certificate_of_indigency_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `certificate_of_residency_requests`
--
ALTER TABLE `certificate_of_residency_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cohabitation_certification_requests`
--
ALTER TABLE `cohabitation_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `construction_clearance_requests`
--
ALTER TABLE `construction_clearance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `first_time_job_seeker_requests`
--
ALTER TABLE `first_time_job_seeker_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `late_birth_registration_requests`
--
ALTER TABLE `late_birth_registration_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `non_residency_certification_requests`
--
ALTER TABLE `non_residency_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `no_income_certification_requests`
--
ALTER TABLE `no_income_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `out_of_school_youth_requests`
--
ALTER TABLE `out_of_school_youth_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `solo_parent_requests`
--
ALTER TABLE `solo_parent_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `unemployment_certification_requests`
--
ALTER TABLE `unemployment_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

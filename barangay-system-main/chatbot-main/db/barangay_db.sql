-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 05:24 AM
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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `baptismal_certification_requests`
--

INSERT INTO `baptismal_certification_requests` (`id`, `parent_name`, `address`, `child_name`, `purpose`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'qweqwewq', 'eqwewqeqe', 'wqeqeqeq', 'Baptismal', 'aicersantaiaguel@gmail.com', 'PICK UP', '2025-05-01 03:02:52');

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
  `status` varchar(50) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `years_of_stay` varchar(50) DEFAULT NULL,
  `purpose` varchar(255) NOT NULL,
  `student_patient_name` varchar(255) NOT NULL,
  `student_patient_address` varchar(255) NOT NULL,
  `relationship` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) 

--
-- Dumping data for table `barangay_clearance`
--

INSERT INTO `barangay_clearance` (`id`, `first_name`, `middle_name`, `last_name`, `complete_address`, `birth_date`, `age`, `status`, `mobile_number`, `years_of_stay`, `purpose`, `student_patient_name`, `student_patient_address`, `relationship`, `email`, `shipping_method`, `created_at`, `user_id`) VALUES
(1, 'qwe', 'qwe', 'qwe', 'qwe', '0121-03-12', 21, 'qwe', '12312123213', 'qweqwe', 'qweqweqwe', 'qweqweqw', 'qweqwe', 'qweqw', 'qwe@gmail.com', 'PICK UP', '2025-04-24 10:01:52', NULL),
(3, 'qwe', 'qwe12', 'qweqwewq', 'qweqweeqw', '5125-04-12', 21, 'qweqweqwe', '12573434634', '215', 'qwe', 'qweqwewqe', '12 weqweq', 'qweqwe', 'aqwdqwel@gmail.com', 'PICK UP', '2025-04-25 04:13:13', NULL),
(4, 'gracelyn', 'peralta', 'asotigue', 'qweqwewqe', '0012-03-12', 231, 'qwe', '12414125151', '21', 'qweqwe', 'qweqwe', 'qweqwe', 'qweqwe', 'sngiooi@gmail.com', 'PICK UP', '2025-04-25 05:36:32', NULL),
(5, 'wergerwg', 'ergergerg', 'ergerger', 'wrgergerger', '0123-03-12', 21, 'qweqwe', '15111771273', '21', 'swfaewfaw', 'wqfqfqwf', 'wqefwefwef', 'asdasdwq', 'qqweqs@gmail.com', 'PICK UP', '2025-04-25 05:38:22', NULL),
(6, 'adrian', 'd', 'santiaguel', 'qweqweqweqwe', '0000-00-00', 21, 'qweqwe', '12541251251', '12', '1qweqwe', 'qweqweqw', 'qweqwe', 'qweqwe', 'qweqwewq@gmail.com', 'PICK UP', '2025-04-29 04:18:27', NULL),
(7, 'qwey', 'adsad', 'aafasfa', 'fawfawfawf', '0512-05-12', 12, 'qtqwf', '16511176771', '61', 'qwtqwtq', 'qyqeyeqyqe', 'qweqtqstgs', 'qtqstqs', 'sqqqs@gmail.com', 'PICK UP', '2025-04-29 06:38:04', NULL),
(8, 'qweqweqw', 'eqweqw', 'eqweqwewq', 'ewqewqewqewqe', '1231-03-12', 11, 'qweqwe', '12341512511', '121', 'qwrqtrqt', 'qweqwrqr', 'qwrqwrtqwr', 'qwrqwrqwrqw', 'qsqqs@gmail.com', 'PICK UP', '2025-04-29 09:00:11', NULL),
(9, 'qwtqwt', 'qwtqwtqwtqw', 'tqwtqwtqw', 'tqtqwtqwtqtq', '0011-02-11', 121, 'qweqweqwe', '12412512512', '122', 'qweqweqwe', 'qqweqwe', 'qweqweqwe', 'qeqweqw', 'aqqwdel@gmail.com', 'PICK UP', '2025-04-29 09:02:07', NULL),
(10, 'sdfsdfsd', 'sdfsdfsdf', 'sdfsdfsdf', 'sdfsdf', '0000-00-00', 12, 'qweqsgrs', '16516171717', '121', 'qwdqwdq', 'dqwdqwd', 'qwdqwdq', 'wdqwdqwd', 'qwqweeqweqwe@gmail.com', 'PICK UP', '2025-04-29 09:06:09', NULL);

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
  `date_of_birth` date NOT NULL,
  `gov_id` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `shipping_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) 

--
-- Dumping data for table `barangay_id_requests`
--

INSERT INTO `barangay_id_requests` (`id`, `first_name`, `middle_name`, `last_name`, `address`, `date_of_birth`, `gov_id`, `email`, `shipping_method`, `created_at`, `user_id`) VALUES
(3, 'qweqwe', 'qweqwe', 'wqeqweqewq', 'ewqewqewqe', '0125-04-12', 'qweqweqwe', 'asd@gmail.com', 'PICK UP', '2025-04-29 09:33:34', NULL);

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `certificate_of_good_moral_requests`
--

INSERT INTO `certificate_of_good_moral_requests` (`id`, `full_name`, `age`, `civil_status`, `address`, `purpose`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'qweqwe', 21, 'qwe', 'qweqweqwe', 'qweqweqweqw', 'qqqwe@gmail.com', 'PICK UP', '2025-05-01 03:01:21'),
(2, 'qweqwe', 21, 'qwe', 'qweqweqwe', 'qweqweqweqw', 'qqqwe@gmail.com', 'PICK UP', '2025-05-01 03:01:22');

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
  `email` varchar(100) NOT NULL,
  `shipping_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) 

--
-- Dumping data for table `certificate_of_indigency_requests`
--

INSERT INTO `certificate_of_indigency_requests` (`id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `civil_status`, `occupation`, `monthly_income`, `proof_of_residency`, `gov_id`, `spouse_name`, `number_of_dependents`, `email`, `shipping_method`, `created_at`, `user_id`) VALUES
(1, 'qwe', 'wqe', 'qwe', '1231-03-12', 'single', 'qwe', 2112.00, 'qwe', 'qweqwe', 'qeqwe', 12, 'qwe@gmail.com', 'PICK UP', '2025-04-24 10:26:46', NULL);

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
  `email` varchar(100) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) 

--
-- Dumping data for table `certificate_of_residency_requests`
--

INSERT INTO `certificate_of_residency_requests` (`id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `gov_id`, `complete_address`, `proof_of_residency`, `purpose`, `email`, `shipping_method`, `created_at`, `user_id`) VALUES
(1, 'qwe', 'qwe', 'qwe', '0000-00-00', 'qwe', 'qwe', 'qwe', 'qwe', 'aicersantiaguel@gmail.com', 'PICK UP', '2025-04-24 10:34:45', NULL);

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `cohabitation_certification_requests`
--

INSERT INTO `cohabitation_certification_requests` (`id`, `partner1_name`, `partner2_name`, `shared_address`, `cohabitation_duration`, `purpose`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'qweqwewq', 'wqewqewqewq', 'wqeqweqw', 'wqeqwewqewq', 'eqewqe', 'qq@gmail.com', 'PICK UP', '2025-05-01 03:04:26');

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `construction_clearance_requests`
--

INSERT INTO `construction_clearance_requests` (`id`, `business_name`, `business_location`, `owner_name`, `owner_address`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'qweqwewqe', 'qweqweqeq', 'asdasdas', 'asdasd', 'asda@gmail.com', 'PICK UP', '2025-05-01 03:05:18');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
)

--
-- Dumping data for table `contact_inquiries`
--

INSERT INTO `contact_inquiries` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'qwe', 'qwe@gmail.com', 'qwe', 'qwe', '2025-04-29 03:45:53'),
(2, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:46:46'),
(3, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:48'),
(4, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:48'),
(5, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:48'),
(6, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:49'),
(7, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:49'),
(8, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:49'),
(9, 'aicer', 'aicersantiaguel@gmail.com', 'qwe', 'qweqweqweqw', '2025-04-29 03:48:49'),
(10, 'aicer', 'aicersantiaguel@gmail.com', 'wrtrw', 'qweqweqweqw', '2025-04-29 03:48:49'),
(13, 'qwe', 'qweqweqwe@gmail.com', 'qweqweqwe', 'qweqweqw', '2025-04-29 08:37:05');

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
)

--
-- Dumping data for table `first_time_job_seeker_requests`
--

INSERT INTO `first_time_job_seeker_requests` (`id`, `full_name`, `address`, `residency_length`, `oath_acknowledged`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'asdasdas', 'dasdasda', 'asdasda', 'Yes', 'aasda@gmail.com', 'PICK UP', '2025-05-01 03:06:28');

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `late_birth_registration_requests`
--

INSERT INTO `late_birth_registration_requests` (`id`, `last_name`, `first_name`, `middle_name`, `address`, `marital_status`, `place_of_birth`, `date_of_birth`, `fathers_name`, `mothers_name`, `years_in_barangay`, `purpose`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'azcxzc', 'zxczxczx', 'zxczxczxczxcz', 'zxczxczxc', 'xzczxczxczxcz', 'xczxcxzc', '0124-03-12', 'zxczxczxczx', 'czxczxczx', 'zxczxczxcz', 'Late Registration of Birth Certificate', 'qasd@gmail.com', 'PICK UP', '2025-05-01 03:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `non_residency_certification_requests`
--

CREATE TABLE `non_residency_certification_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `previous_address` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `non_residency_certification_requests`
--

INSERT INTO `non_residency_certification_requests` (`id`, `full_name`, `previous_address`, `purpose`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'adsagf', 'asdasdasd', 'asdasdas', 'qwqee@gmail.com', 'PICK UP', '2025-05-01 03:10:20');

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `no_income_certification_requests`
--

INSERT INTO `no_income_certification_requests` (`id`, `full_name`, `date_of_birth`, `civil_status`, `address`, `no_income_statement`, `purpose`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'asffasda', '0000-00-00', 'asdasdasd', 'asdasdasd', 'asdasda', 'sdasdasda', 'qwqeqweqqwe@gmail.com', 'PICK UP', '2025-05-01 03:09:07');

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
)

--
-- Dumping data for table `out_of_school_youth_requests`
--

INSERT INTO `out_of_school_youth_requests` (`id`, `full_name`, `address`, `citizenship`, `purpose`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'qweqweq', 'weqweqw', 'Filipino', 'qweqwe', 'qa@gmail.com', 'PICK UP', '2025-05-01 03:11:14');

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `solo_parent_requests`
--

INSERT INTO `solo_parent_requests` (`id`, `full_name`, `address`, `solo_since`, `child_count`, `children_names`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'qweqweqwe', 'wqeqwewq', '12312', 21, 'qweqweqwe', 'qweqweqqqwe@gmail.com', 'PICK UP', '2025-05-01 03:12:00');

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
  `email` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) 

--
-- Dumping data for table `unemployment_certification_requests`
--

INSERT INTO `unemployment_certification_requests` (`id`, `full_name`, `age`, `birth_date`, `civil_status`, `address`, `purpose`, `email`, `shipping_method`, `submitted_at`) VALUES
(1, 'qweqwewqe', 21, '0000-00-00', 'Single', 'qweqweqwe', 'qweqweq', 'qweqqweqwe@gmail.com', 'PICK UP', '2025-05-01 03:13:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) 

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `address`, `email`, `username`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, 'qwe', 'qwe', 'qwe', 'qwe@gmail.com', 'qwe', '$2y$10$WjmWDv2Bnn92y1grSoAJjuVps0RK7x8a9N0v4.sgqGWfdhOF5O1t2', 0, '2025-04-25 03:55:36', '2025-04-25 03:55:36'),
(2, 'try', 'try', '', 'try@gmail.com', 'try', '$2y$10$ZZJ5eCIZSF6QklkMeVXTN.i4Sdy/0jjH2RDjLxdarKSo473I0dKLe', 1, '2025-04-25 04:19:40', '2025-04-25 04:19:40'),
(3, 'qwe', 'try', 'BUCANDALA 1', 'qweqqwewewq@gmail.com', 'zxc', '$2y$10$NunP1abnKFFEoXo9VHtoOOpQvh3fD/Qbzie2vDd8IjargYdXzqjv6', 0, '2025-04-29 06:39:44', '2025-04-29 06:56:03');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barangay_clearance`
--
ALTER TABLE `barangay_clearance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `barangay_id_requests`
--
ALTER TABLE `barangay_id_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certificate_of_good_moral_requests`
--
ALTER TABLE `certificate_of_good_moral_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `certificate_of_indigency_requests`
--
ALTER TABLE `certificate_of_indigency_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `certificate_of_residency_requests`
--
ALTER TABLE `certificate_of_residency_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cohabitation_certification_requests`
--
ALTER TABLE `cohabitation_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `construction_clearance_requests`
--
ALTER TABLE `construction_clearance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `first_time_job_seeker_requests`
--
ALTER TABLE `first_time_job_seeker_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `late_birth_registration_requests`
--
ALTER TABLE `late_birth_registration_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `non_residency_certification_requests`
--
ALTER TABLE `non_residency_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `no_income_certification_requests`
--
ALTER TABLE `no_income_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `out_of_school_youth_requests`
--
ALTER TABLE `out_of_school_youth_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `solo_parent_requests`
--
ALTER TABLE `solo_parent_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unemployment_certification_requests`
--
ALTER TABLE `unemployment_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

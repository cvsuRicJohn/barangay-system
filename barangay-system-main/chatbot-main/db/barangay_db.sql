-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 12:41 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);

--
-- Dumping data for table `barangay_clearance`
--

INSERT INTO `barangay_clearance` (`id`, `first_name`, `middle_name`, `last_name`, `complete_address`, `birth_date`, `age`, `status`, `mobile_number`, `years_of_stay`, `purpose`, `student_patient_name`, `student_patient_address`, `relationship`, `email`, `shipping_method`, `created_at`) VALUES
(1, 'qwe', 'qwe', 'qwe', 'qwe', '0121-03-12', 21, 'qwe', '12312123213', 'qweqwe', 'qweqweqwe', 'qweqweqw', 'qweqwe', 'qweqw', 'qwe@gmail.com', 'PICK UP', '2025-04-24 10:01:52');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);

--
-- Dumping data for table `barangay_id_requests`
--

INSERT INTO `barangay_id_requests` (`id`, `first_name`, `middle_name`, `last_name`, `address`, `date_of_birth`, `gov_id`, `email`, `shipping_method`, `created_at`) VALUES
(1, 'qwe', 'qwe', 'qwe', 'qwe', '0000-00-00', 'qwe', 'qwe@gmail.com', 'PICK UP', '2025-04-24 10:22:47');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);

--
-- Dumping data for table `certificate_of_indigency_requests`
--

INSERT INTO `certificate_of_indigency_requests` (`id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `civil_status`, `occupation`, `monthly_income`, `proof_of_residency`, `gov_id`, `spouse_name`, `number_of_dependents`, `email`, `shipping_method`, `created_at`) VALUES
(1, 'qwe', 'wqe', 'qwe', '1231-03-12', 'single', 'qwe', 2112.00, 'qwe', 'qweqwe', 'qeqwe', 12, 'qwe@gmail.com', 'PICK UP', '2025-04-24 10:26:46'),
(2, 'qwe', 'qwe', '12312', '0123-03-12', 'single', 'we', 21.00, 'qwe', 'qwe', 'qwe', 21, 'aicersantiaguel@gmail.com', 'PICK UP', '2025-04-24 10:39:48');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);

--
-- Dumping data for table `certificate_of_residency_requests`
--

INSERT INTO `certificate_of_residency_requests` (`id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `gov_id`, `complete_address`, `proof_of_residency`, `purpose`, `email`, `shipping_method`, `created_at`) VALUES
(1, 'qwe', 'qwe', 'qwe', '0000-00-00', 'qwe', 'qwe', 'qwe', 'qwe', 'aicersantiaguel@gmail.com', 'PICK UP', '2025-04-24 10:34:45');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `address`, `email`, `username`, `password`, `created_at`) VALUES
(1, 'qwe', 'qwe', 'qwe', 'qwe', 'qew', '$2y$10$gwZbrhxQLWzUL0vfyk3IPuZhnJ/k6nnruW4tkxIf5ql0eiIWsmh0m', '2025-04-24 09:50:09');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `barangay_clearance`
--
ALTER TABLE `barangay_clearance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barangay_id_requests`
--
ALTER TABLE `barangay_id_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

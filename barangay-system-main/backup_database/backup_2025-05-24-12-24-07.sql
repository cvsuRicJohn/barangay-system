

CREATE TABLE `baptismal_certification_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `child_name` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `barangay_clearance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `barangay_clearance` VALUES ('36', 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', '2006-02-08', '19', 'Single', '12312312312', '123123', 'qweqwe', 'qweqwe', 'qweqwe', 'qweqwew', 'PICK UP', '2025-05-24 15:49:47', '3', '2025-05-24 15:49:47', 'approved', '20.00');


CREATE TABLE `barangay_id_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `certificate_of_good_moral_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `civil_status` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `certificate_of_indigency_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `age` int(11) NOT NULL,
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
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `certificate_of_indigency_requests` VALUES ('10', 'Aicer John', 'De Ocampo', 'Santiaguel', '2006-02-08', '19', 'single', 'qweqwe', '21.00', 'qweqwe', 'Barangay ID', 'qweqwe', '21', 'PICK UP', '2025-05-24 16:33:28', '3', '2025-05-24 16:33:28', 'approved', '20.00');


CREATE TABLE `certificate_of_residency_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `age` int(11) DEFAULT NULL,
  `complete_address` varchar(255) NOT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `certificate_of_residency_requests` VALUES ('7', 'Aicer John', 'De Ocampo', 'Santiaguel', '2006-02-08', '19', '41 Bucandala 1 City of Imus, Cavite', 'Single', 'qweqwe', 'PICK UP', '2025-05-24 15:52:24', '3', '2025-05-24 15:52:24', 'approved', '20.00');


CREATE TABLE `cohabitation_certification_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner1_name` varchar(255) NOT NULL,
  `partner2_name` varchar(255) NOT NULL,
  `shared_address` varchar(255) NOT NULL,
  `cohabitation_duration` varchar(100) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `construction_clearance_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_name` varchar(255) NOT NULL,
  `business_location` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `owner_address` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `contact_inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `position` int(11) NOT NULL,
  `column_side` enum('left','right') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `faqs` VALUES ('1', 'Ano ang kailangan sa pagkuha ng barangay ID?', 'Kailangan mong magdala ng valid ID at proof of residence tulad ng utility bill o barangay certificate.', '1', 'left');
INSERT INTO `faqs` VALUES ('2', 'Paano kumuha ng Barangay Indigency?', ' Magdala ng valid ID at certificate mula sa Barangay Kapitan na nagpapatunay ng iyong indigency status.', '2', 'left');
INSERT INTO `faqs` VALUES ('3', 'Ano ang kailangan sa pagkuha ng Barangay Clearance?', 'Magdala ng valid ID, barangay certificate, at bayaran ang kaukulang fee.', '3', 'left');
INSERT INTO `faqs` VALUES ('4', 'Saan maaaring mag-apply ng Barangay Business Permit?', '  Ang aplikasyon ay maaaring gawin sa Barangay Hall. Dalhin ang necessary business documents.\r\n', '4', 'left');
INSERT INTO `faqs` VALUES ('5', 'Kailan ang check-up ng mga buntis sa Barangay Health Center?', '  Ang check-up ay tuwing Lunes at Huwebes ng umaga. Tumawag sa health center para sa eksaktong iskedyul.', '1', 'right');
INSERT INTO `faqs` VALUES ('6', 'Anu-ano ang Requirements para sa Solo Parent I.D.? ', 'Ang mga sumusunod ay ang kwalipikado para sa SOLO PARENT I.D.\r\n                                    \r\nBiyuda\r\n     * Hiwalay sa asawa\r\n     * Nawalang bisa o Annulled ang kasal\r\n     * Inabandona ng asawa o ng kinakasama\r\n     * Sinumang indibidwal na tumatayo bilang head of the family bunga ng pag-abandona, pagkawala, matagal na pagkawalay ng magulang o ng solo parent\r\n     * Biktima ng panggagahasa\r\n     * Asawa ng nakakulong at/o nahatulang mabilanggo\r\n     * Hindi sapat ang mental na kapasidad', '2', 'right');
INSERT INTO `faqs` VALUES ('7', 'May bayad po ba ang Barangay I.D. at mga clearance?', ' Oo, may bayad depende sa dokumentong kukunin.', '3', 'right');


CREATE TABLE `first_time_job_seeker_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `residency_length` varchar(100) NOT NULL,
  `oath_acknowledged` enum('Yes','No') NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `late_birth_registration_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `late_birth_registration_requests` VALUES ('6', 'Santiaguel', 'Aicer John', 'De Ocampo', '41 Bucandala 1 City of Imus, Cavite', 'Single', 'qweqwe', '2006-02-08', 'qwe', 'qwe', 'qwe', 'Late Registration of Birth Certificate', 'PICK UP', '2025-05-24 16:40:14', 'approved', '20.00', '3');


CREATE TABLE `no_income_certification_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `no_income_certification_requests` VALUES ('4', 'Aicer John De Ocampo Santiaguel', '2006-02-08', 'Single', '41 Bucandala 1 City of Imus, Cavite', 'qweqwe', 'qweqwe', 'PICK UP', '2025-05-24 15:57:02', 'approved', '20.00', '3');


CREATE TABLE `non_residency_certification_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `previous_address` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `out_of_school_youth_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `citizenship` varchar(100) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `password_resets` VALUES ('1', '1', '32cc35549feababe6bed2324fa8e77dd76376df51279d89322f4a4dd36c840a05dbc97560d8f882a1aac1de92d41b81fecc9', '2025-05-21 13:32:58', '2025-05-21 19:02:58');
INSERT INTO `password_resets` VALUES ('2', '1', '878d775c67fc1f8cbb9321513f8eb90c27e3b96620b0e247850cb5cd22dbced28ab11609dc817c66cb3b85e3e4f66cf3305d', '2025-05-21 13:33:01', '2025-05-21 19:03:01');
INSERT INTO `password_resets` VALUES ('3', '1', '935dcef99c9e9f709592a6bc356831a8a7226045945f30f80dcdae1d54c50aeba2eb923912da488a26801b88a462d7801296', '2025-05-21 13:33:03', '2025-05-21 19:03:03');
INSERT INTO `password_resets` VALUES ('4', '1', '1f79c892a588f1bcefbe0424a81be6de3d402f676f3c7bd5733a500616bc02788874161754221d007f67ad837e3ce7a5e8f7', '2025-05-21 13:33:05', '2025-05-21 19:03:05');
INSERT INTO `password_resets` VALUES ('5', '1', 'b8f2d02a8e9b420550681a152283dcd87b91d7def26c87286a7d183b359ff6f449819ffd550331783175f8f308dbcc566d64', '2025-05-21 13:33:30', '2025-05-21 19:03:30');
INSERT INTO `password_resets` VALUES ('6', '1', 'f286a25d19c922c2b863a7a996bbfe18552974aed44b26b4ad8025d97e09b43119f22090c45ce1486456a1fe78458cc2584d', '2025-05-21 13:33:31', '2025-05-21 19:03:31');
INSERT INTO `password_resets` VALUES ('7', '1', '15b344aa514ab782339b23cd544761b268c0c25219ce0b17c25466d53f0a603896a7c2e528241f25071c321de52d89dc6b71', '2025-05-21 13:33:32', '2025-05-21 19:03:32');
INSERT INTO `password_resets` VALUES ('8', '1', '2a202579789a5091c2bdad66731127930f4ada930b2a54eb50d8d3484efd213af26f350b6d5f1f3613db9dc691e2ef64b179', '2025-05-21 13:33:32', '2025-05-21 19:03:32');
INSERT INTO `password_resets` VALUES ('9', '1', '6c6eb8f8a14384d751ea29b85833e3e1735478fc79d5ccfe2b93fea26bf4baaf3c8e067020428cd993ad85f6c18cd51e4a9f', '2025-05-21 13:33:50', '2025-05-21 19:03:50');
INSERT INTO `password_resets` VALUES ('10', '1', '4c8973d1fbae05e0bda22b4cb0614635168c773a1414d5fce56fafe57132df9ee8daefddf3fef5610fefed5ffd8682330522', '2025-05-21 13:35:27', '2025-05-21 19:05:27');


CREATE TABLE `solo_parent_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `solo_since` varchar(10) NOT NULL,
  `child_count` int(11) NOT NULL,
  `children_names` text NOT NULL,
  `shipping_method` varchar(100) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `cost` decimal(10,2) NOT NULL DEFAULT 20.00,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transactions` VALUES ('29', 'Aicer John Santiaguel', '20.00', '2025-05-24 15:49:53', 'completed');
INSERT INTO `transactions` VALUES ('30', 'Aicer John Santiaguel', '20.00', '2025-05-24 15:52:36', 'completed');
INSERT INTO `transactions` VALUES ('31', 'Aicer John De Ocampo Santiaguel', '20.00', '2025-05-24 15:57:16', 'completed');
INSERT INTO `transactions` VALUES ('32', 'Aicer John Santiaguel', '20.00', '2025-05-24 16:33:34', 'completed');
INSERT INTO `transactions` VALUES ('33', 'Aicer John Santiaguel', '20.00', '2025-05-24 16:40:26', 'completed');


CREATE TABLE `unemployment_certification_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES ('1', 'Aicer John', 'De Ocampo', 'Santiaguel', '48 Bucandala 1 City of Imus, Cavite', 'aicersantiaguel@gmail.com', 'Eyser', '$2y$10$NMlnY01YT/GRqqbt8nm0GutOQpnP/QFzDFmYWidUJLCT.54MjXwsK', '2001-02-28', 'Male', 'Single', 'Philippine National ID', '123456', 'greys', '123123213', '', 'N/A', 'approved', '2025-05-13 12:31:05', '2025-05-21 13:42:24', '1');
INSERT INTO `users` VALUES ('3', 'Aicer John', 'De Ocampo', 'Santiaguel', '41 Bucandala 1 City of Imus, Cavite', 'qweqweqwe@gmail.com', 'Eyserr', '$2y$10$xGFyh.2rCocylTtwuS01..76DX.2IUGZfx6INu2esPPopt1bXGvbq', '2006-02-08', 'Male', 'Single', 'UMID', '12123', 'qweqwe', '123', 'n/a', 'qwe', 'approved', '2025-05-13 12:33:57', '2025-05-21 13:42:24', '1');
INSERT INTO `users` VALUES ('5', 'qweqwe', 'qweqwe', 'qweqwe', 'qweqweqw', 'qweqweqqwewe@gmail.com', 'try', '$2y$10$PnqZXHdM8w.DCK/EjrGYmuYachRdZpj2XMKr2HoRqkVzY8Vk9ax1.', '2025-05-13', 'Male', 'Single', 'UMID', '123123', 'qwewqe', '12312312321', '', '', 'approved', '2025-05-13 13:07:34', '2025-05-13 13:15:46', '0');
INSERT INTO `users` VALUES ('6', 'ric', 'john', 'anuat', 'imus', 'ricjohnanuat@cvsu.edu.ph', 'ric111', '$2y$10$nUITv5vwJ9z4.eVzI82GquMXx8zOdqMB8U6aJ4yMdUwLz0In8iblK', '2025-05-01', 'Male', 'Single', 'Philippine National ID', '11111111111', 'sir huele', '09292933333', '', '', 'approved', '2025-05-15 16:33:17', '2025-05-18 10:10:47', '0');
INSERT INTO `users` VALUES ('7', 'tung', 'tung', 'sahur', 'imus', 'tungtungsahur@gmail.com', 'tungtungtung1', '$2y$10$.13oMzeHN4otGjCVYGHXWO97HDdkDrzillGPem.Nws4KEN16U4c82', '2012-02-08', 'Female', 'Widowed', 'Philippine National ID', '11111111111', 'sir huele', '09292933333', 'tung-tung-sahur-meme-2.avif', '', 'approved', '2025-05-18 11:02:14', '2025-05-21 13:57:24', '0');
INSERT INTO `users` VALUES ('8', 'Aicer John', 'De Ocampo', 'Santiaguel', 'BUCANDALA 1', 'aicersantiaguell@gmail.com', 'Eyserrr', '$2y$10$1rCm6HUpzucmfQhYUXfKJOBCh8E1VmB4edQWW8TvaXL7ZuUZQhjJe', '2001-02-28', 'Male', 'Single', 'Driver’s License', '123123123', 'qweqwe', '123123123', '', 'qweqwe', 'approved', '2025-05-20 15:38:16', '2025-05-21 13:40:33', '0');
INSERT INTO `users` VALUES ('9', '123', '123', '123', '123', '123@123', '123', '$2y$10$e7SspkDAVvRfpOc0rR0khO4Bf2tp7feZMEyi30XIxWQG8Xjf11AJa', '2025-04-30', 'Male', 'Married', 'UMID', '123123', '123123', '123123', '', '123', 'approved', '2025-05-21 14:22:30', '2025-05-21 14:22:55', '0');

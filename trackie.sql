-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 11:05 AM
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
-- Database: `trackie`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `A_id` int(11) NOT NULL,
  `A_fName` varchar(20) NOT NULL,
  `A_sName` varchar(20) NOT NULL,
  `A_address` varchar(30) NOT NULL,
  `A_area` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`A_id`, `A_fName`, `A_sName`, `A_address`, `A_area`) VALUES
(14, 'Admin', 'One', 'Special', 'Budhanilkantha');

--
-- Triggers `admin`
--
DELIMITER $$
CREATE TRIGGER `before_admin_delete` BEFORE DELETE ON `admin` FOR EACH ROW BEGIN
    -- Ensure the admin_id in drivers remains the same
    UPDATE driver
    SET A_id = OLD.A_id
    WHERE A_id = OLD.A_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `Bm_id` int(11) NOT NULL,
  `B_id` int(11) DEFAULT NULL,
  `P_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `B_id` int(11) NOT NULL,
  `B_model` varchar(20) NOT NULL,
  `B_reg_no` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`B_id`, `B_model`, `B_reg_no`) VALUES
(1, 'Sajha Yatayat', 'Ba12Ka98'),
(2, 'Nepal Yatayat', 'Ka34Kha78'),
(3, 'Kasthamandap', 'Ba23Ka73'),
(4, 'Valley Yatayat', 'Kha54ka60'),
(5, 'Sajha Yatayat', 'Cha43Kha80');

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `D_id` int(11) NOT NULL,
  `D_fName` varchar(20) NOT NULL,
  `D_sName` varchar(20) NOT NULL,
  `D_address` varchar(30) NOT NULL,
  `D_license_no` varchar(20) NOT NULL,
  `D_pic_path` varchar(20) DEFAULT NULL,
  `A_id` int(11) DEFAULT NULL,
  `B_id` int(11) DEFAULT NULL,
  `S_id` int(11) DEFAULT NULL,
  `R_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`D_id`, `D_fName`, `D_sName`, `D_address`, `D_license_no`, `D_pic_path`, `A_id`, `B_id`, `S_id`, `R_id`) VALUES
(28, 'Ram', 'Lal', 'Naranthan', '120000000001', NULL, 14, 1, 7, 1),
(29, 'Pogo', 'Lal', 'Naranthan', '120000000002', NULL, 14, 2, 8, 2),
(30, 'Sano', 'Manjiro', 'Gokarna', '120000000003', NULL, 14, 3, 9, 3),
(31, 'Yami', 'Dancho', 'BlackBull', '120000000004', NULL, 14, 2, 10, 3),
(32, 'Nova', 'Chrono', 'Clovers Kingdom', '120000000005', NULL, 14, 4, 11, 4),
(33, 'Yuno', 'Grinberryall', 'Spade Kingdom', '120000000006', NULL, 14, 2, 12, 5),
(34, 'Secre', 'kilt', 'Heart Kingdom', '120000000007', NULL, 14, 5, 13, 2),
(37, 'Orochimaru', 'Sanin', 'Hidden Leaf', '120000000010', NULL, 14, 1, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `d_phone_nos`
--

CREATE TABLE `d_phone_nos` (
  `D_phone_no_id` int(11) NOT NULL,
  `D_phone_no` varchar(10) NOT NULL,
  `D_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `d_phone_nos`
--

INSERT INTO `d_phone_nos` (`D_phone_no_id`, `D_phone_no`, `D_id`) VALUES
(26, '9823820000', 28),
(27, '9823820002', 29),
(28, '9823820003', 30),
(29, '9823820004', 31),
(30, '9823820005', 32),
(31, '9823820006', 33),
(32, '9823820007', 34),
(33, '9823820008', NULL),
(34, '9823820009', NULL),
(35, '9823820010', 37);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `L_id` int(11) NOT NULL,
  `L_longitude` varchar(40) NOT NULL,
  `L_latitude` varchar(40) NOT NULL,
  `B_id` int(11) DEFAULT NULL,
  `P_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`L_id`, `L_longitude`, `L_latitude`, `B_id`, `P_id`) VALUES
(10, '85.3180416', '27.7086208', 2, NULL),
(11, '85.26662000', '27.73139000', 1, NULL),
(12, '85.3539071000', '27.74029000', 3, NULL),
(13, '85.34646000', '27.70939000', 4, NULL),
(14, '85.31523700', '27.70634500', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loginadmin`
--

CREATE TABLE `loginadmin` (
  `La_id` int(11) NOT NULL,
  `La_username` varchar(40) NOT NULL,
  `La_gmail` varchar(40) NOT NULL,
  `La_password` varchar(255) NOT NULL,
  `La_role` varchar(20) DEFAULT 'Admin',
  `A_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loginadmin`
--

INSERT INTO `loginadmin` (`La_id`, `La_username`, `La_gmail`, `La_password`, `La_role`, `A_id`) VALUES
(1, 'superadmin', 'superadmin@gmail.com', '$2y$10$ATnxI5oJL5/Q.U1y0JcQNucyF7fIA8CFOovlaxLqCnL1GQnDPPava', 'SuperAdmin', NULL),
(14, 'adminone@gmail.com', 'adminone@gmail.com', '$2y$10$6yrInksASU0hlAbNBt/Xxej8DcgveHWRRoToA6H5a0lwb9SobaFgu', 'Admin', 14);

-- --------------------------------------------------------

--
-- Table structure for table `loginregister`
--

CREATE TABLE `loginregister` (
  `Lr_id` int(11) NOT NULL,
  `Lr_username` varchar(30) NOT NULL,
  `Lr_gmail` varchar(30) NOT NULL,
  `Lr_password` varchar(225) NOT NULL,
  `Lr_user` varchar(10) NOT NULL DEFAULT 'Passenger',
  `D_id` int(11) DEFAULT NULL,
  `P_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loginregister`
--

INSERT INTO `loginregister` (`Lr_id`, `Lr_username`, `Lr_gmail`, `Lr_password`, `Lr_user`, `D_id`, `P_id`) VALUES
(24, 'binatolight@gmail.com', 'binatolight@gmail.com', '$2y$10$wUZ5/ri4UFnAuJOOTFXmoudw6PNUYznxCLPvk7V2Gf53TCM4ga7oC', 'Passenger', NULL, 14),
(28, 'ramlal@gmail.com', 'ramlal@gmail.com', '$2y$10$C1gMdkOtEvlX4Hi.HwVdye9SJzpsZx0laevEPBRS6gKaTrJJ8oyPS', 'Driver', 28, NULL),
(29, 'pogolal@gmail.com', 'pogolal@gmail.com', '$2y$10$JiGZ3l0U0zuiGmyH.n6ku.uV8czPevdBXwsNDBbBDzygMtnzpEDnW', 'Driver', 29, NULL),
(30, 'sanomanjiro@gmail.com', 'sanomanjiro@gmail.com', '$2y$10$B6Vt/jOzk5kN66RCkv/xPesCbdo7jTcjbH4j0WgxmEzTo5iStfH4G', 'Driver', 30, NULL),
(31, 'yamidancho@gmail.com', 'yamidancho@gmail.com', '$2y$10$tlFWEOKnIkZiHZZbxAichOTAloKM2o1C2mjOZfEb3GQsQkSWGl9fq', 'Driver', 31, NULL),
(32, 'novachrono@gmail.com', 'novachrono@gmail.com', '$2y$10$wb6s1/EdDn6oxYedqYmAm.z/ruQzcjBPh2hhu7p4YYHN4W7.aYzam', 'Driver', 32, NULL),
(33, 'yunogrinberryall@gmail.com', 'yunogrinberryall@gmail.com', '$2y$10$/sKk74fdZtyUzJAIlW9VF.2CBdrGe1PQ23dFlhlcLfUPNcEU1uURi', 'Driver', 33, NULL),
(34, 'secrekilt@gmail.com', 'secrekilt@gmail.com', '$2y$10$3BFG4jPFVJl50XLMq6kVousHz3ei6XajwKpsE5tA4hvhcHalHhzFW', 'Driver', 34, NULL),
(35, 'gohanson@gmail.com', 'gohanson@gmail.com', '$2y$10$IR7N.3eqQaixcNL0QnqNreU13rDMLqaIUYGVocxYa/cdFASILKYha', 'Driver', NULL, NULL),
(36, 'paintendo@gmail.com', 'paintendo@gmail.com', '$2y$10$Rvik83PI7rOknGfePuCj9O4XDWVWl0pqCWW8uXlZLh2HNyMZ/PcHi', 'Driver', NULL, NULL),
(37, 'orochimarusanin@gmail.com', 'orochimarusanin@gmail.com', '$2y$10$J4jJtjZtGtl6PbMWxGpY/.MGuXnynfDPp.kSrwh2h5Cr1//MB/CPq', 'Driver', 37, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `passenger`
--

CREATE TABLE `passenger` (
  `P_id` int(11) NOT NULL,
  `P_fName` varchar(20) NOT NULL,
  `P_sName` varchar(20) NOT NULL,
  `P_gmail` varchar(30) NOT NULL,
  `P_pic_path` varchar(30) NOT NULL,
  `B_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passenger`
--

INSERT INTO `passenger` (`P_id`, `P_fName`, `P_sName`, `P_gmail`, `P_pic_path`, `B_id`) VALUES
(14, 'Binato', 'Light', 'binatolight@gmail.com', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `p_phone_nos`
--

CREATE TABLE `p_phone_nos` (
  `P_phone_no_id` int(11) NOT NULL,
  `P_phone_no` varchar(10) NOT NULL,
  `P_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_phone_nos`
--

INSERT INTO `p_phone_nos` (`P_phone_no_id`, `P_phone_no`, `P_id`) VALUES
(13, '9823820860', 14),
(14, '9823829861', NULL),
(15, '9841000001', NULL),
(16, '9823820123', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `R_id` int(11) NOT NULL,
  `R_name` varchar(40) NOT NULL,
  `R_start` varchar(20) NOT NULL,
  `R_end` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`R_id`, `R_name`, `R_start`, `R_end`) VALUES
(1, 'Budhanilkantha', 'Naranthan', 'Ratnapark'),
(2, 'Tokha', 'Hepali Height', 'Saibaba Chowk'),
(3, 'Gokarneshwor', 'Gokarna', 'Kalanki'),
(4, 'Kathmandu MN', 'Gausala', 'kamalpokhari'),
(5, 'Kirtipur', 'Ratnapark', 'Kirtipur');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `S_id` int(11) NOT NULL,
  `S_day` varchar(10) NOT NULL,
  `S_sTime` time NOT NULL,
  `S_eTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`S_id`, `S_day`, `S_sTime`, `S_eTime`) VALUES
(7, 'Sunday', '02:00:00', '03:00:00'),
(8, 'Sunday', '04:00:00', '05:00:00'),
(9, 'Sunday', '05:00:00', '06:00:00'),
(10, 'Sunday', '06:00:00', '07:00:00'),
(11, 'Sunday', '06:30:00', '07:30:00'),
(12, 'Tuesday', '02:00:00', '03:00:00'),
(13, 'Tuesday', '04:00:00', '05:00:00'),
(14, 'Tuesday', '05:00:00', '06:00:00'),
(15, 'Tuesday', '06:00:00', '07:00:00'),
(16, 'Tuesday', '06:30:00', '07:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`A_id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`Bm_id`),
  ADD KEY `B_id` (`B_id`),
  ADD KEY `P_id` (`P_id`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`B_id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`D_id`),
  ADD KEY `fk_a_id` (`A_id`),
  ADD KEY `fk_b_id` (`B_id`),
  ADD KEY `fk_r_id` (`R_id`),
  ADD KEY `fk_s_id` (`S_id`);

--
-- Indexes for table `d_phone_nos`
--
ALTER TABLE `d_phone_nos`
  ADD PRIMARY KEY (`D_phone_no_id`),
  ADD KEY `d_phone_nos_ibfk_1` (`D_id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`L_id`),
  ADD KEY `location_ibfk_1` (`B_id`),
  ADD KEY `location_ibfk_2` (`P_id`);

--
-- Indexes for table `loginadmin`
--
ALTER TABLE `loginadmin`
  ADD PRIMARY KEY (`La_id`),
  ADD KEY `fk_la_admin` (`A_id`);

--
-- Indexes for table `loginregister`
--
ALTER TABLE `loginregister`
  ADD PRIMARY KEY (`Lr_id`),
  ADD KEY `loginregister_ibfk_1` (`D_id`),
  ADD KEY `loginregister_ibfk_2` (`P_id`);

--
-- Indexes for table `passenger`
--
ALTER TABLE `passenger`
  ADD PRIMARY KEY (`P_id`),
  ADD KEY `passenger_ibfk_1` (`B_id`);

--
-- Indexes for table `p_phone_nos`
--
ALTER TABLE `p_phone_nos`
  ADD PRIMARY KEY (`P_phone_no_id`),
  ADD KEY `p_phone_nos_ibfk_1` (`P_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`R_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`S_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `A_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `Bm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `B_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `D_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `d_phone_nos`
--
ALTER TABLE `d_phone_nos`
  MODIFY `D_phone_no_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `L_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `loginadmin`
--
ALTER TABLE `loginadmin`
  MODIFY `La_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `loginregister`
--
ALTER TABLE `loginregister`
  MODIFY `Lr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `passenger`
--
ALTER TABLE `passenger`
  MODIFY `P_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `p_phone_nos`
--
ALTER TABLE `p_phone_nos`
  MODIFY `P_phone_no_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `R_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `S_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`B_id`) REFERENCES `bus` (`B_id`),
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`P_id`) REFERENCES `passenger` (`P_id`);

--
-- Constraints for table `driver`
--
ALTER TABLE `driver`
  ADD CONSTRAINT `fk_a_id` FOREIGN KEY (`A_id`) REFERENCES `admin` (`A_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_b_id` FOREIGN KEY (`B_id`) REFERENCES `bus` (`B_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_r_id` FOREIGN KEY (`R_id`) REFERENCES `route` (`R_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_id` FOREIGN KEY (`S_id`) REFERENCES `schedule` (`S_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `d_phone_nos`
--
ALTER TABLE `d_phone_nos`
  ADD CONSTRAINT `d_phone_nos_ibfk_1` FOREIGN KEY (`D_id`) REFERENCES `driver` (`D_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`B_id`) REFERENCES `bus` (`B_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `location_ibfk_2` FOREIGN KEY (`P_id`) REFERENCES `passenger` (`P_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `loginadmin`
--
ALTER TABLE `loginadmin`
  ADD CONSTRAINT `fk_la_admin` FOREIGN KEY (`A_id`) REFERENCES `admin` (`A_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `loginregister`
--
ALTER TABLE `loginregister`
  ADD CONSTRAINT `loginregister_ibfk_1` FOREIGN KEY (`D_id`) REFERENCES `driver` (`D_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `loginregister_ibfk_2` FOREIGN KEY (`P_id`) REFERENCES `passenger` (`P_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `passenger`
--
ALTER TABLE `passenger`
  ADD CONSTRAINT `passenger_ibfk_1` FOREIGN KEY (`B_id`) REFERENCES `bus` (`B_id`) ON UPDATE CASCADE;

--
-- Constraints for table `p_phone_nos`
--
ALTER TABLE `p_phone_nos`
  ADD CONSTRAINT `p_phone_nos_ibfk_1` FOREIGN KEY (`P_id`) REFERENCES `passenger` (`P_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

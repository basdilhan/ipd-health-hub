-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 11, 2025 at 06:11 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ipdhealthhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE IF NOT EXISTS `appointment` (
  `AppointmentID` int NOT NULL AUTO_INCREMENT,
  `appointmentNo` int NOT NULL,
  `RoomNo` int NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `HospitalID` int NOT NULL,
  `DID` int NOT NULL,
  `PID` int NOT NULL,
  `ScheduleID` int NOT NULL,
  PRIMARY KEY (`AppointmentID`),
  KEY `HospitalID` (`HospitalID`,`DID`,`PID`),
  KEY `ScheduleID` (`ScheduleID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`AppointmentID`, `appointmentNo`, `RoomNo`, `Date`, `Time`, `HospitalID`, `DID`, `PID`, `ScheduleID`) VALUES
(4, 2, 20, '2025-02-15', '16:45:00', 1, 1, 11, 1),
(5, 3, 20, '2025-02-15', '16:45:00', 1, 1, 12, 1),
(6, 1, 2, '2025-03-12', '15:30:00', 1, 1, 20, 6),
(7, 2, 2, '2025-03-12', '15:30:00', 1, 1, 23, 6),
(8, 1, 20, '2025-02-26', '21:00:00', 1, 1, 24, 5),
(9, 1, 7, '2025-03-19', '16:00:00', 2, 7, 25, 7),
(10, 2, 7, '2025-03-19', '16:00:00', 2, 7, 26, 7),
(11, 3, 7, '2025-03-19', '16:00:00', 2, 7, 27, 7),
(12, 4, 7, '2025-03-19', '16:00:00', 2, 7, 28, 7),
(13, 5, 7, '2025-03-19', '16:00:00', 2, 7, 29, 7),
(14, 6, 7, '2025-03-19', '16:00:00', 2, 7, 30, 7),
(15, 7, 7, '2025-03-19', '16:00:00', 2, 7, 31, 7),
(16, 8, 7, '2025-03-19', '16:00:00', 2, 7, 32, 7),
(17, 4, 20, '2025-02-15', '16:45:00', 1, 1, 41, 1),
(19, 2, 20, '2025-02-26', '21:00:00', 1, 1, 43, 5),
(20, 3, 20, '2025-02-26', '21:00:00', 1, 1, 44, 5),
(21, 4, 20, '2025-02-26', '21:00:00', 1, 1, 45, 5),
(22, 5, 20, '2025-02-26', '21:00:00', 1, 1, 46, 5),
(23, 6, 20, '2025-02-26', '21:00:00', 1, 1, 47, 5),
(24, 1, 12, '2025-02-20', '16:00:00', 2, 3, 48, 4),
(25, 5, 20, '2025-02-15', '16:45:00', 1, 1, 49, 1),
(26, 1, 17, '2025-03-31', '16:24:00', 3, 2, 50, 34),
(27, 7, 20, '2025-02-26', '21:00:00', 1, 1, 53, 5),
(28, 1, 12, '2025-03-07', '18:30:00', 2, 5, 54, 8),
(29, 2, 12, '2025-03-07', '18:30:00', 2, 5, 55, 8),
(30, 2, 17, '2025-03-31', '16:24:00', 3, 2, 56, 34),
(31, 2, 12, '2025-02-20', '16:00:00', 2, 3, 57, 4),
(32, 3, 12, '2025-02-20', '16:00:00', 2, 3, 58, 4);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
CREATE TABLE IF NOT EXISTS `doctor` (
  `DID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Speciality` varchar(500) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `TeleNo` varchar(10) NOT NULL,
  PRIMARY KEY (`DID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`DID`, `Name`, `Speciality`, `Email`, `TeleNo`) VALUES
(1, 'Muhunthan', 'chest physician', 'muhunthan12@gmail.com', '0711237084'),
(2, 'Pulindu Ranhiru', 'Neuro Surgeon', 'samudu104@gmail.com', '0761243988'),
(3, 'Hemamali', 'eye surgeon', 'hemamali123@gmail.com', '0789798071'),
(4, 'Ajith Jayasekara', 'psychiatrist', 'ajithjay12@gmail.com', '0777365142'),
(5, 'Aruna De Silva', 'peadiatrician', 'arunasilva1@gmail.com', '0756759366'),
(6, 'Ashoka disanayake', 'speech therapist', 'ashokadisanayake123@gmail.com', '0768967380'),
(7, 'B A K Pieris', 'gynaecologist', 'bakpieris22@gmail.com', '0778473925'),
(8, 'B V Hasheni', 'physician', 'bvhasheni1@gmail.com', '0746752344'),
(9, 'Chandima Munamale', 'counselor', 'chandimamunamale1@gmail.com', '0773930888'),
(10, 'Gihan Piyasiri', 'Orthopedic', 'gihanpiyasiri22@gmail.com', '0702947833'),
(11, 'Harsha Samarasinghe', 'cardiologist', 'harshasamarasinghe31@gmail.com', '0749832458'),
(12, 'Muditha Weerakkody', 'endocrinologist', 'mudithaweerakkody312@gmail.com', '0703878375'),
(13, 'Ranjani Gamage', 'dental surgeon', 'ranjanigamage123@gmail.com', '0702989544'),
(14, 'Ridma Rasanjana', 'audiologist', 'ridmarasanjana23@gmail.com', '0787598546'),
(15, 'Ranga Weerakkody', 'nephrologist', 'rangaweerakkody113@gmail.com', '0775644721'),
(16, 'imesh', 'eye surgeon', 'waimesh2002@gmail.com', '0773478732');

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

DROP TABLE IF EXISTS `hospital`;
CREATE TABLE IF NOT EXISTS `hospital` (
  `HospitalID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Location` varchar(500) NOT NULL,
  PRIMARY KEY (`HospitalID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`HospitalID`, `Name`, `Location`) VALUES
(1, 'Asiri Hospital ', 'Galle'),
(2, 'Co-operative ', 'Galle'),
(3, 'Ruhuna Hospital', 'Galle'),
(4, 'Asiri Hospital', 'Matara');

-- --------------------------------------------------------

--
-- Table structure for table `hospitaladmin`
--

DROP TABLE IF EXISTS `hospitaladmin`;
CREATE TABLE IF NOT EXISTS `hospitaladmin` (
  `AdminID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `TeleNo` int NOT NULL,
  `Email` varchar(30) NOT NULL,
  PRIMARY KEY (`AdminID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hospitaladmin`
--

INSERT INTO `hospitaladmin` (`AdminID`, `Name`, `Password`, `TeleNo`, `Email`) VALUES
(1, 'samudu', 'abc1234', 769798081, 'samudu104@gmail.com'),
(2, 'imesh', 'imesh1234', 761243988, 'waimesh2002@gmail.com'),
(3, 'Dahami Vidanagama', 'dahami123', 711237084, 'dinunimavidanagama@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `PID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Age` tinyint NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `TeleNo` varchar(10) NOT NULL,
  PRIMARY KEY (`PID`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PID`, `Name`, `Email`, `Age`, `Gender`, `TeleNo`) VALUES
(1, 'John Doe', 'johndoe@example.com', 30, 'Male', '1234567890'),
(10, 'samudu dilhan', 'samudu104@gmail.com', 22, 'Male', '0769798081'),
(11, 'samudu dilhan', 'samudu104@gmail.com', 22, 'Male', '0769798081'),
(12, 'samudu dilhan', 'samudu104@gmail.com', 22, 'Male', '0769798081'),
(13, 'pulindu', 'pulindu123@gmail.com', 19, 'Male', '0761243988'),
(20, 'pasindu', 'pasindu123@gmail.com', 23, 'Male', '0768798091'),
(21, 'Venusha', 'Venusha123@gmail.com', 23, 'Male', '0771245988'),
(22, 'Venusha', 'Venusha123@gmail.com', 23, 'Male', '0771245988'),
(23, 'Manel', 'samudu104@gmail.com', 40, 'Female', '0773878732'),
(24, 'Nethma', 'nethma123@gmail.com', 21, 'Female', '0771245989'),
(25, 'Sithumi', 'Sithumi123@gmail.com', 23, 'Female', '0769778081'),
(26, 'Manel', 'samudu104@gmail.com', 40, 'Female', '0773878732'),
(27, 'imesh', 'waimesh2002@gmail.com', 22, 'Male', '0773478732'),
(28, 'imesh', 'waimesh2002@gmail.com', 22, 'Male', '0773478732'),
(32, 'Amal', 'Amal123@gmail.com', 22, 'Male', '0761243988'),
(33, 'Amal', 'Amal123@gmail.com', 22, 'Male', '0761243988'),
(34, 'dahami', 'dahami123@gmail.com', 21, 'Female', '0762334098'),
(35, 'dahami', 'dahami123@gmail.com', 21, 'Female', '0762334098'),
(36, 'Manel', 'samudu104@gmail.com', 40, 'Female', '0773878732'),
(37, 'Ashen', 'Ashen123@gmail.com', 22, 'Male', '0765657643'),
(38, 'Ashen', 'Ashen123@gmail.com', 22, 'Male', '0765657643'),
(39, 'Pasindu', 'pasindu123@gmail.com', 22, 'Male', '0768798091'),
(40, 'Pasindu', 'pasindu123@gmail.com', 22, 'Male', '0768798091'),
(41, 'Namal', 'Namal123@gmail.com', 22, 'Male', '0768798091'),
(42, 'dahami', 'dinunimavidanagama@gmail.com', 22, 'Female', '0711237084'),
(43, 'dahami', 'dinunimavidanagama@gmail.com', 22, 'Female', '0711237084'),
(44, 'dahami', 'dinunimavidanagama@gmail.com', 22, 'Female', '0711237084'),
(45, 'dahami', 'dinunimavidanagama@gmail.com', 22, 'Female', '0711237084'),
(46, 'dahami', 'dinunimavidanagama@gmail.com', 22, 'Female', '0711237084'),
(47, 'dahami', 'dinunimavidanagama@gmail.com', 22, 'Female', '0711237084'),
(48, 'mahela', 'Mahela123@gmail.com', 21, 'Male', '0756787987'),
(49, 'mahela', 'Mahela123@gmail.com', 21, 'Male', '0756787987'),
(50, 'samudu dilhan', 'samudu104@gmail.com', 22, 'Male', '0769798081'),
(51, 'dahami', 'dinunimavidanagama@gmail.com', 22, 'Female', '0711237084'),
(52, 'Thilini', 'mktwdharmasena@gmail.com', 33, 'Female', '0711237084'),
(53, 'dahami', 'dinunimavidanagama@gmail.com', 22, 'Female', '0711237084'),
(54, 'samudu dilhan', 'samudu104@gmail.com', 22, 'Male', '0769798081'),
(55, 'samudu dilhan', 'samudu104@gmail.com', 22, 'Male', '0769798081'),
(56, 'dahami', 'dinunimavidanagama@gmail.com', 23, 'Female', '0711237084'),
(57, 'samudu dilhan', 'samudu104@gmail.com', 22, 'Male', '0769798081'),
(58, 'dahami', 'dinunimavidanagama@gmail.com', 23, 'Male', '0711237084'),
(59, 'samudu dilhan', 'samudu104@gmail.com', 23, 'Male', '0769798081');

-- --------------------------------------------------------

--
-- Table structure for table `patient-room`
--

DROP TABLE IF EXISTS `patient-room`;
CREATE TABLE IF NOT EXISTS `patient-room` (
  `PID` int NOT NULL,
  `RoomID` int NOT NULL,
  `StartDate` date NOT NULL,
  KEY `PID` (`PID`),
  KEY `PID_2` (`PID`,`RoomID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `patient-room`
--

INSERT INTO `patient-room` (`PID`, `RoomID`, `StartDate`) VALUES
(13, 1, '2025-03-03'),
(33, 3, '2025-03-06'),
(34, 4, '2025-03-05'),
(35, 2, '2025-03-05'),
(36, 1, '2025-03-06'),
(40, 4, '2025-03-22');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `PayID` int NOT NULL AUTO_INCREMENT,
  `Amount` int NOT NULL,
  `ExpireDate` varchar(10) NOT NULL,
  `CardNum` varchar(20) NOT NULL,
  `HolderName` varchar(50) NOT NULL,
  `CVV` int NOT NULL,
  `AppointmentID` int NOT NULL,
  PRIMARY KEY (`PayID`),
  KEY `AppointmentID` (`AppointmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PayID`, `Amount`, `ExpireDate`, `CardNum`, `HolderName`, `CVV`, `AppointmentID`) VALUES
(2, 3721, '0000-00-00', '4564 5646 4654 6546', 'samudu', 564, 17),
(3, 3000, '0000-00-00', '5465 4545 4654 6556', 'dahami', 345, 23),
(4, 3000, '2025-10', '1234 5678 2435 5546', 'mahela', 123, 25),
(5, 5000, '2025-02', '1313 2131 3131 3111', 'samudu', 123, 26),
(6, 3000, '2025-03', '1313 3231 2312 3131', 'sam', 233, 28),
(7, 3000, '2025-12', '1323 1232 3232 1323', 'sam', 123, 29),
(8, 5000, '2025-02', '2312 3232 3232 3232', 'dahami', 123, 30),
(9, 3000, '2025-06', '1213 1321 3213 2132', 'sam', 306, 32);

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist`
--

DROP TABLE IF EXISTS `pharmacist`;
CREATE TABLE IF NOT EXISTS `pharmacist` (
  `PharmacistID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Location` varchar(500) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `TeleNo` varchar(10) NOT NULL,
  `Password` varchar(20) NOT NULL,
  PRIMARY KEY (`PharmacistID`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pharmacist`
--

INSERT INTO `pharmacist` (`PharmacistID`, `Name`, `Location`, `Email`, `TeleNo`, `Password`) VALUES
(13, 'CityPharamacy', 'gallle', 'citypharmacy123@gmail.com', '0711237084', 'city1234'),
(12, 'newloyd', 'karapitiya', 'newloyd12@gmail.com', '0761243923', 'sam1234'),
(14, 'Crystol Pharamacy', 'Wakwella', 'crystol1235@gmail.com', '0779880231', 'crystol1234@'),
(15, 'samudupharmacy', 'Gonapinuwala', 'samudu104@gmail.com', '0769798081', 'samudu1234');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

DROP TABLE IF EXISTS `prescription`;
CREATE TABLE IF NOT EXISTS `prescription` (
  `PreID` int NOT NULL AUTO_INCREMENT,
  `Date` datetime NOT NULL,
  `Image` mediumblob NOT NULL,
  `PID` int NOT NULL,
  `PharmacistID` int NOT NULL,
  PRIMARY KEY (`PreID`),
  KEY `PID` (`PID`),
  KEY `PharmacistID` (`PharmacistID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`PreID`, `Date`, `Image`, `PID`, `PharmacistID`) VALUES
(4, '2025-03-08 00:00:00', 0x70686f746f732f53637265656e73686f74202831292e706e67, 38, 14),
(3, '2025-03-05 00:00:00', 0x70686f746f732f53637265656e73686f7420323032342d31302d3130203230343333322e706e67, 22, 13),
(5, '2025-03-11 00:00:00', 0x70686f746f732f5465616c20616e64205768697465204d6f6465726e204d6f6e74686c79205461736b2047616e74742047726170682e706e67, 52, 12),
(6, '2025-03-29 00:00:00', 0x70686f746f732f53637265656e73686f74202832292e706e67, 59, 14);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `RoomID` int NOT NULL AUTO_INCREMENT,
  `Availability` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `RoomNumber` int NOT NULL,
  `HospitalID` int NOT NULL,
  PRIMARY KEY (`RoomID`),
  KEY `HospitalID` (`HospitalID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RoomID`, `Availability`, `RoomNumber`, `HospitalID`) VALUES
(1, 'Occupied', 25, 1),
(2, 'Occupied', 30, 2),
(3, 'Occupied', 22, 4),
(4, 'Occupied', 25, 1),
(5, 'Available', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE IF NOT EXISTS `schedule` (
  `ScheduleID` int NOT NULL AUTO_INCREMENT,
  `RoomNo` int NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `MaxAppointments` int NOT NULL,
  `HospitalID` int NOT NULL,
  `DID` int NOT NULL,
  PRIMARY KEY (`ScheduleID`),
  KEY `HospitalID` (`HospitalID`,`DID`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`ScheduleID`, `RoomNo`, `Date`, `Time`, `MaxAppointments`, `HospitalID`, `DID`) VALUES
(4, 12, '2025-02-20', '16:00:00', 17, 2, 3),
(5, 20, '2025-02-26', '21:00:00', 12, 1, 1),
(6, 2, '2025-03-12', '15:30:00', 18, 1, 1),
(7, 7, '2025-03-19', '16:00:00', 22, 2, 7),
(8, 12, '2025-03-07', '18:30:00', 23, 2, 5),
(9, 9, '2025-03-14', '16:00:00', 15, 2, 13),
(10, 3, '2025-03-06', '15:30:00', 20, 4, 15),
(11, 6, '2025-03-20', '17:00:00', 25, 3, 11),
(12, 10, '2025-03-09', '16:00:00', 15, 3, 9),
(13, 10, '2025-03-09', '16:00:00', 15, 3, 9),
(14, 6, '2025-03-20', '17:00:00', 25, 3, 11),
(23, 25, '2025-03-25', '11:10:00', 30, 1, 2),
(32, 25, '2025-03-20', '12:20:00', 30, 3, 16),
(33, 25, '2025-03-20', '14:00:00', 25, 2, 16),
(34, 17, '2025-03-31', '16:24:00', 0, 3, 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`AppointmentID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2021 at 03:46 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insurance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ADMIN_ID` char(9) NOT NULL,
  `ADMIN_HIREDATE` date NOT NULL,
  `ADMIN_NAME` varchar(30) NOT NULL,
  `ADMIN_PASSWORD` binary(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ADMIN_ID`, `ADMIN_HIREDATE`, `ADMIN_NAME`, `ADMIN_PASSWORD`) VALUES
('admin2021', '2021-06-25', 'Amirul Adli Fahmi', 0x098f6bcd4621d373cade4e832627b4f6);

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `CAR_NO` varchar(10) NOT NULL,
  `CAR_CONDITIONS` varchar(10) NOT NULL,
  `CAR_MODEL` varchar(20) NOT NULL,
  `CAR_MANUFACTURED` date NOT NULL,
  `CLIENT_IC` char(12) NOT NULL,
  `ADMIN_ID` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `choose`
--

CREATE TABLE `choose` (
  `CLIENT_IC` char(12) NOT NULL,
  `INSURANCE_ID` char(9) NOT NULL,
  `CHOOSE_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `CLIENT_IC` char(12) NOT NULL,
  `CLIENT_NAME` varchar(50) NOT NULL,
  `CLIENT_PHONE_NO` char(11) NOT NULL,
  `CLIENT_ADDRESS` varchar(50) NOT NULL,
  `CLIENT_PASSWORD` binary(16) NOT NULL,
  `EMP_ID` char(9) NOT NULL,
  `ADMIN_ID` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`CLIENT_IC`, `CLIENT_NAME`, `CLIENT_PHONE_NO`, `CLIENT_ADDRESS`, `CLIENT_PASSWORD`, `EMP_ID`, `ADMIN_ID`) VALUES
('000413140977', 'AMIRUL ADLI FAHMI', '01121196596', 'Lot 7339, JALAN HAJI NORDIN OFF', 0x098f6bcd4621d373cade4e832627b4f6, 'emp2021', 'admin2021');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EMP_ID` char(9) NOT NULL,
  `EMP_PASSWORD` binary(16) NOT NULL,
  `EMP_NAME` varchar(50) NOT NULL,
  `EMP_POSITION` varchar(20) NOT NULL,
  `EMP_SALARY` decimal(9,2) NOT NULL,
  `EMP_BONUS` decimal(9,2) NOT NULL,
  `ADMIN_ID` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EMP_ID`, `EMP_PASSWORD`, `EMP_NAME`, `EMP_POSITION`, `EMP_SALARY`, `EMP_BONUS`, `ADMIN_ID`) VALUES
('emp2021', 0x74657374000000000000000000000000, 'Amirul Adli', 'Admin', '1000.00', '300.00', 'admin2021');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_policy`
--

CREATE TABLE `insurance_policy` (
  `INSURANCE_ID` char(9) NOT NULL,
  `INSURANCE_NAME` varchar(40) NOT NULL,
  `INSURANCE_LIMIT` decimal(9,2) NOT NULL,
  `INSURANCE_DURATION` varchar(10) NOT NULL,
  `INSURANCE_COVERAGE` decimal(9,2) NOT NULL,
  `ADMIN_ID` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `CLIENT_IC` char(12) NOT NULL,
  `INSURANCE_ID` char(9) NOT NULL,
  `PAYMENT_STATUS` varchar(8) NOT NULL,
  `PAYMENT_DUE` date NOT NULL,
  `PAYMENT_AMOUNT` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `updating`
--

CREATE TABLE `updating` (
  `EMP_ID` char(9) NOT NULL,
  `INSURANCE_ID` char(9) NOT NULL,
  `UPDATE_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ADMIN_ID`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`CAR_NO`),
  ADD KEY `ADMIN_ID` (`ADMIN_ID`),
  ADD KEY `CLIENT_IC` (`CLIENT_IC`);

--
-- Indexes for table `choose`
--
ALTER TABLE `choose`
  ADD PRIMARY KEY (`CLIENT_IC`,`INSURANCE_ID`),
  ADD KEY `INSURANCE_ID` (`INSURANCE_ID`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`CLIENT_IC`),
  ADD KEY `ADMIN_ID` (`ADMIN_ID`),
  ADD KEY `EMP_ID` (`EMP_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EMP_ID`),
  ADD KEY `ADMIN_ID` (`ADMIN_ID`);

--
-- Indexes for table `insurance_policy`
--
ALTER TABLE `insurance_policy`
  ADD PRIMARY KEY (`INSURANCE_ID`),
  ADD KEY `ADMIN_ID` (`ADMIN_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`CLIENT_IC`,`INSURANCE_ID`),
  ADD KEY `INSURANCE_ID` (`INSURANCE_ID`);

--
-- Indexes for table `updating`
--
ALTER TABLE `updating`
  ADD PRIMARY KEY (`EMP_ID`,`INSURANCE_ID`),
  ADD KEY `INSURANCE_ID` (`INSURANCE_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `car_ibfk_1` FOREIGN KEY (`ADMIN_ID`) REFERENCES `admin` (`ADMIN_ID`),
  ADD CONSTRAINT `car_ibfk_2` FOREIGN KEY (`CLIENT_IC`) REFERENCES `client` (`CLIENT_IC`);

--
-- Constraints for table `choose`
--
ALTER TABLE `choose`
  ADD CONSTRAINT `choose_ibfk_1` FOREIGN KEY (`CLIENT_IC`) REFERENCES `client` (`CLIENT_IC`),
  ADD CONSTRAINT `choose_ibfk_2` FOREIGN KEY (`INSURANCE_ID`) REFERENCES `insurance_policy` (`INSURANCE_ID`);

--
-- Constraints for table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`ADMIN_ID`) REFERENCES `admin` (`ADMIN_ID`),
  ADD CONSTRAINT `client_ibfk_2` FOREIGN KEY (`EMP_ID`) REFERENCES `employee` (`EMP_ID`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`ADMIN_ID`) REFERENCES `admin` (`ADMIN_ID`);

--
-- Constraints for table `insurance_policy`
--
ALTER TABLE `insurance_policy`
  ADD CONSTRAINT `insurance_policy_ibfk_1` FOREIGN KEY (`ADMIN_ID`) REFERENCES `admin` (`ADMIN_ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`CLIENT_IC`) REFERENCES `client` (`CLIENT_IC`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`INSURANCE_ID`) REFERENCES `insurance_policy` (`INSURANCE_ID`);

--
-- Constraints for table `updating`
--
ALTER TABLE `updating`
  ADD CONSTRAINT `updating_ibfk_1` FOREIGN KEY (`EMP_ID`) REFERENCES `employee` (`EMP_ID`),
  ADD CONSTRAINT `updating_ibfk_2` FOREIGN KEY (`INSURANCE_ID`) REFERENCES `insurance_policy` (`INSURANCE_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

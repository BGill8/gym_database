-- phpMyAdmin SQL Dump
-- version 5.2.2-1.el9.remi
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 04, 2025 at 09:44 AM
-- Server version: 10.11.11-MariaDB-log
-- PHP Version: 8.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs340_deleong`
--

-- --------------------------------------------------------

--
-- Table structure for table `Member`
--

CREATE TABLE `Member` (
  `MemberID` int(11) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `MembershipTypeID` int(11) DEFAULT NULL,
  `ClassID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Member`
--

INSERT INTO `Member` (`MemberID`, `FirstName`, `LastName`, `Email`, `Phone`, `Address`, `DateOfBirth`, `MembershipTypeID`, `ClassID`) VALUES
(301, 'Alice', 'Johnson', 'alice.johnson@email.com', '5551112222', '123 Maple St', '1990-01-15', 100, 401),
(302, 'Bob', 'Smith', 'bob.smith@email.com', '5552223333', '456 Oak Ave', '1985-03-22', 101, 402),
(303, 'Carol', 'White', 'carol.white@email.com', '5553334444', '789 Pine Rd', '1992-07-08', 102, 403),
(304, 'David', 'Brown', 'david.brown@email.com', '5554445555', '321 Elm St', '1980-11-02', 103, 404),
(305, 'Eva', 'Davis', 'eva.davis@email.com', '5555556666', '654 Cedar Ln', '1995-05-17', 104, 405),
(306, 'Frank', 'Miller', 'frank.miller@email.com', '5556667777', '987 Birch Blvd', '1988-09-10', 105, 406),
(307, 'Grace', 'Wilson', 'grace.wilson@email.com', '5557778888', '135 Spruce Ct', '1993-12-01', 106, 407),
(308, 'Hank', 'Moore', 'hank.moore@email.com', '5558889999', '246 Aspen Dr', '1982-04-14', 107, 408),
(309, 'Ivy', 'Taylor', 'ivy.taylor@email.com', '5559990000', '357 Willow Way', '1991-08-29', 108, 409),
(310, 'Jack', 'Anderson', 'jack.anderson@email.com', '5550001111', '468 Redwood Ln', '1987-06-05', 109, 410);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Member`
--
ALTER TABLE `Member`
  ADD PRIMARY KEY (`MemberID`),
  ADD KEY `MembershipTypeID` (`MembershipTypeID`),
  ADD KEY `FK_Member_ClassID` (`ClassID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Member`
--
ALTER TABLE `Member`
  ADD CONSTRAINT `FK_Member_ClassID` FOREIGN KEY (`ClassID`) REFERENCES `FitnessClass` (`ClassID`),
  ADD CONSTRAINT `Member_ibfk_1` FOREIGN KEY (`MembershipTypeID`) REFERENCES `MembershipType` (`MembershipTypeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

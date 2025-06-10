-- Group #3 Gabriel de Leon, James Nguyen, Stanley Eng, Brandon Gill
-- phpMyAdmin SQL Dump
-- version 5.2.2-1.el9.remi
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 09, 2025 at 10:43 PM
-- Server version: 10.11.11-MariaDB-log
-- PHP Version: 8.4.8

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
-- Table structure for table `Equipment`
--

CREATE TABLE `Equipment` (
  `EquipmentID` int(11) NOT NULL,
  `EquipmentName` varchar(255) DEFAULT NULL,
  `EquipmentType` varchar(255) DEFAULT NULL,
  `PurchaseDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Equipment`
--

INSERT INTO `Equipment` (`EquipmentID`, `EquipmentName`, `EquipmentType`, `PurchaseDate`) VALUES
(501, 'Treadmill X100', 'Cardio', '2022-01-05'),
(502, 'Rowing Machine Z', 'Cardio', '2021-12-20'),
(503, 'Dumbbell Set A', 'Strength', '2022-03-11'),
(504, 'Bench Press B2', 'Strength', '2021-09-17'),
(505, 'Spin Bike Pro', 'Cardio', '2022-07-23'),
(506, 'Kettlebells K4', 'Strength', '2023-02-14'),
(507, 'Yoga Mats Y1', 'Flexibility', '2021-06-30'),
(508, 'Resistance Bands R3', 'Flexibility', '2023-01-08'),
(509, 'Punching Bag P5', 'Boxing', '2022-05-12'),
(510, 'Elliptical E9', 'Cardio', '2022-10-19');

-- --------------------------------------------------------

--
-- Table structure for table `FitnessClass`
--

CREATE TABLE `FitnessClass` (
  `ClassID` int(11) NOT NULL,
  `ClassName` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL,
  `MaxCapacity` int(11) DEFAULT NULL,
  `StaffID` int(11) DEFAULT NULL,
  `Rating` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `FitnessClass`
--

INSERT INTO `FitnessClass` (`ClassID`, `ClassName`, `Description`, `StartTime`, `EndTime`, `MaxCapacity`, `StaffID`, `Rating`) VALUES
(401, 'Yoga Basics', 'Beginner yoga class', '08:00:00', '09:00:00', 20, 206, 4.50),
(402, 'HIIT Blast', 'High intensity interval training', '09:30:00', '10:15:00', 25, 202, 4.70),
(403, 'Zumba Dance', 'Fun dance-based workout', '10:30:00', '11:30:00', 30, 201, 4.80),
(404, 'Strength Training', 'Full body lifting workout', '12:00:00', '13:00:00', 15, 205, 4.60),
(405, 'Spin Class', 'High-energy cycling class', '13:30:00', '14:15:00', 20, 210, 4.40),
(406, 'Pilates', 'Core strength and flexibility', '14:30:00', '15:15:00', 18, 206, 4.90),
(407, 'Boxing Basics', 'Intro to boxing', '15:30:00', '16:30:00', 12, 210, 4.30),
(408, 'Aqua Fit', 'Water-based workout', '17:00:00', '18:00:00', 20, 209, 4.50),
(409, 'Mobility Flow', 'Stretching and movement', '18:30:00', '19:15:00', 20, 207, 4.20),
(410, 'Evening Yoga', 'Relaxing yoga session', '19:30:00', '20:30:00', 25, 206, 4.60);

--
-- Triggers `FitnessClass`
--
DELIMITER $$
CREATE TRIGGER `check_start_and_end_time_fc_insert` BEFORE INSERT ON `FitnessClass` FOR EACH ROW BEGIN
    IF NEW.StartTime >= NEW.EndTime THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Start time must be before end time.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_start_and_end_time_fc_update` BEFORE UPDATE ON `FitnessClass` FOR EACH ROW BEGIN
    IF NEW.StartTime >= NEW.EndTime THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Start time must be before end time.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_invalid_capacity_update` BEFORE UPDATE ON `FitnessClass` FOR EACH ROW BEGIN
    DECLARE enrolled_count INT;

    IF NEW.MaxCapacity < OLD.MaxCapacity THEN
        SELECT COUNT(*) INTO enrolled_count
        FROM MemberClass
        WHERE ClassID = OLD.ClassID;

        IF NEW.MaxCapacity < enrolled_count THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot reduce capacity: more members are already enrolled than the new capacity allows.';
        END IF;
    END IF;
END
$$
DELIMITER ;

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
  `MembershipTypeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Member`
--

INSERT INTO `Member` (`MemberID`, `FirstName`, `LastName`, `Email`, `Phone`, `Address`, `DateOfBirth`, `MembershipTypeID`) VALUES
(301, 'Alice', 'Johnson', 'alice.johnson@email.com', '5551112222', '123 Maple St', '1990-01-15', 100),
(302, 'Bob', 'Smith', 'bob.smith@email.com', '5552223333', '456 Oak Ave', '1985-03-22', 101),
(303, 'Carol', 'White', 'carol.white@email.com', '5553334444', '789 Pine Rd', '1992-07-08', 102),
(304, 'David', 'Brown', 'david.brown@email.com', '5554445555', '321 Elm St', '1980-11-02', 103),
(305, 'Eva', 'Davis', 'eva.davis@email.com', '5555556666', '654 Cedar Ln', '1995-05-17', 104),
(306, 'Frank', 'Miller', 'frank.miller@email.com', '5556667777', '987 Birch Blvd', '1988-09-10', 105),
(307, 'Grace', 'Wilson', 'grace.wilson@email.com', '5557778888', '135 Spruce Ct', '1993-12-01', 106),
(308, 'Hank', 'Moore', 'hank.moore@email.com', '5558889999', '246 Aspen Dr', '1982-04-14', 107),
(309, 'Ivy', 'Taylor', 'ivy.taylor@email.com', '5559990000', '357 Willow Way', '1991-08-29', 108),
(310, 'Jack', 'Anderson', 'jack.anderson@email.com', '5550001111', '468 Redwood Ln', '1987-06-05', 109);

--
-- Triggers `Member`
--
DELIMITER $$
CREATE TRIGGER `check_dob_before_insert` BEFORE INSERT ON `Member` FOR EACH ROW BEGIN
  IF NEW.DateOfBirth > CURDATE() THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Date of Birth cannot be in the future.';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_dob_before_update` BEFORE UPDATE ON `Member` FOR EACH ROW BEGIN
  IF NEW.DateOfBirth > CURDATE() THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Date of Birth cannot be in the future.';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_membership_downgrade_with_classes` BEFORE UPDATE ON `Member` FOR EACH ROW BEGIN
    DECLARE class_count INT;
    DECLARE benefit_text TEXT;

    IF NEW.MembershipTypeID <> OLD.MembershipTypeID THEN
        SELECT COUNT(*) INTO class_count
        FROM MemberClass
        WHERE MemberID = OLD.MemberID;

        IF class_count > 0 THEN
            SELECT Benefits INTO benefit_text
            FROM MembershipType
            WHERE MembershipTypeID = NEW.MembershipTypeID;

            IF NOT (benefit_text LIKE '%Class%' OR benefit_text LIKE '%All%') THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Cannot change to a membership without class access while enrolled.';
            END IF;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MemberClass`
--

CREATE TABLE `MemberClass` (
  `MemberID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `MemberClass`
--

INSERT INTO `MemberClass` (`MemberID`, `ClassID`) VALUES
(302, 401),
(302, 402),
(302, 407),
(303, 403),
(303, 407),
(303, 408),
(303, 410),
(304, 404),
(304, 405),
(304, 406),
(304, 409),
(305, 405),
(305, 409),
(305, 410),
(306, 401),
(306, 406),
(306, 408),
(308, 402),
(308, 403),
(308, 404);

--
-- Triggers `MemberClass`
--
DELIMITER $$
CREATE TRIGGER `prevent_class_over_enrollment` BEFORE INSERT ON `MemberClass` FOR EACH ROW BEGIN
    DECLARE current_count INT;
    DECLARE max_capacity INT;

    SELECT COUNT(*) INTO current_count
    FROM MemberClass
    WHERE ClassID = NEW.ClassID;

    SELECT MaxCapacity INTO max_capacity
    FROM FitnessClass
    WHERE ClassID = NEW.ClassID;

    IF current_count >= max_capacity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot enroll: class is at full capacity.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_enrollment_without_class_membership` BEFORE INSERT ON `MemberClass` FOR EACH ROW BEGIN
    DECLARE benefit_text TEXT;

    SELECT mt.Benefits INTO benefit_text
    FROM Member m
    JOIN MembershipType mt ON m.MembershipTypeID = mt.MembershipTypeID
    WHERE m.MemberID = NEW.MemberID;

    IF NOT (benefit_text LIKE '%Class%' OR benefit_text LIKE '%All%') THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot enroll: membership does not allow class participation.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MembershipType`
--

CREATE TABLE `MembershipType` (
  `MembershipTypeID` int(11) NOT NULL,
  `TypeName` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Duration` int(11) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Benefits` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `MembershipType`
--

INSERT INTO `MembershipType` (`MembershipTypeID`, `TypeName`, `Description`, `Duration`, `Price`, `Benefits`) VALUES
(100, 'Basic', 'Access to gym facilities only', 1, 29.99, 'Gym Access'),
(101, 'Silver', 'Gym + 2 classes per week', 3, 69.99, 'Gym, Classes'),
(102, 'Gold', 'Gym + unlimited classes', 6, 99.99, 'Gym, All Classes'),
(103, 'Platinum', 'All access + guest passes', 12, 149.99, 'All Access, Guest Passes'),
(104, 'Student', 'Discounted rate for students', 3, 39.99, 'Gym, Classes'),
(105, 'Family', 'Membership for family of 4', 12, 199.99, 'Gym, All Classes, Pool'),
(106, 'Senior', 'Discounted senior plan', 6, 49.99, 'Gym, Wellness Programs'),
(107, 'Corporate', 'Company-sponsored access', 12, 89.99, 'All Access'),
(108, 'Trial', 'One-week trial plan', 0, 0.00, 'Limited Access'),
(109, 'Weekend', 'Access on weekends only', 12, 19.99, 'Weekend Gym Access');

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE `Staff` (
  `StaffID` int(11) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `Position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Staff`
--

INSERT INTO `Staff` (`StaffID`, `FirstName`, `LastName`, `Email`, `Phone`, `Position`) VALUES
(201, 'Rachel', 'Green', 'rgreen@gym.com', '5551234567', 'Instructor'),
(202, 'Ross', 'Geller', 'rgeller@gym.com', '5552345678', 'Trainer'),
(203, 'Monica', 'Geller', 'mgeller@gym.com', '5553456789', 'Manager'),
(204, 'Chandler', 'Bing', 'cbing@gym.com', '5554567890', 'Receptionist'),
(205, 'Joey', 'Tribbiani', 'jtribbiani@gym.com', '5555678901', 'Trainer'),
(206, 'Phoebe', 'Buffay', 'pbuffay@gym.com', '5556789012', 'Yoga Instructor'),
(207, 'Janice', 'Litman', 'janice@gym.com', '5557890123', 'Nutritionist'),
(208, 'Gunther', 'Smith', 'gunther@gym.com', '5558901234', 'Barista'),
(209, 'Emily', 'Waltham', 'ewaltham@gym.com', '5559012345', 'Swimming Coach'),
(210, 'Mike', 'Hannigan', 'mhannigan@gym.com', '5550123456', 'Boxing Coach');

-- --------------------------------------------------------

--
-- Table structure for table `Workout`
--

CREATE TABLE `Workout` (
  `WorkoutID` int(11) NOT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `EquipmentID` int(11) DEFAULT NULL,
  `WorkoutDate` date DEFAULT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL,
  `TotalCaloriesBurned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Workout`
--

INSERT INTO `Workout` (`WorkoutID`, `MemberID`, `EquipmentID`, `WorkoutDate`, `StartTime`, `EndTime`, `TotalCaloriesBurned`) VALUES
(601, 301, 501, '2025-05-20', '07:00:00', '07:30:00', 300),
(602, 302, 502, '2025-05-20', '08:00:00', '08:45:00', 400),
(603, 303, 503, '2025-05-21', '06:30:00', '07:15:00', 350),
(604, 304, 504, '2025-05-21', '09:00:00', '09:45:00', 380),
(605, 305, 505, '2025-05-22', '10:00:00', '10:30:00', 320),
(606, 306, 506, '2025-05-22', '11:00:00', '11:30:00', 310),
(608, 308, 508, '2025-05-23', '13:00:00', '13:30:00', 250),
(609, 309, 509, '2025-05-24', '14:00:00', '14:30:00', 330),
(610, 310, 510, '2025-05-24', '15:00:00', '15:45:00', 370);

--
-- Triggers `Workout`
--
DELIMITER $$
CREATE TRIGGER `check_start_and_end_time_workout_insert` BEFORE INSERT ON `Workout` FOR EACH ROW BEGIN
    IF NEW.StartTime >= NEW.EndTime THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Start time must be before end time.';
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Equipment`
--
ALTER TABLE `Equipment`
  ADD PRIMARY KEY (`EquipmentID`);

--
-- Indexes for table `FitnessClass`
--
ALTER TABLE `FitnessClass`
  ADD PRIMARY KEY (`ClassID`),
  ADD KEY `FitnessClass_ibfk_1` (`StaffID`);

--
-- Indexes for table `Member`
--
ALTER TABLE `Member`
  ADD PRIMARY KEY (`MemberID`),
  ADD KEY `Member_ibfk_1` (`MembershipTypeID`);

--
-- Indexes for table `MemberClass`
--
ALTER TABLE `MemberClass`
  ADD PRIMARY KEY (`MemberID`,`ClassID`),
  ADD KEY `MemberClass_ibfk_2` (`ClassID`);

--
-- Indexes for table `MembershipType`
--
ALTER TABLE `MembershipType`
  ADD PRIMARY KEY (`MembershipTypeID`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`StaffID`);

--
-- Indexes for table `Workout`
--
ALTER TABLE `Workout`
  ADD PRIMARY KEY (`WorkoutID`),
  ADD KEY `Workout_ibfk_1` (`MemberID`),
  ADD KEY `Workout_ibfk_2` (`EquipmentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Equipment`
--
ALTER TABLE `Equipment`
  MODIFY `EquipmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=511;

--
-- AUTO_INCREMENT for table `FitnessClass`
--
ALTER TABLE `FitnessClass`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT for table `Member`
--
ALTER TABLE `Member`
  MODIFY `MemberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT for table `MembershipType`
--
ALTER TABLE `MembershipType`
  MODIFY `MembershipTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `Staff`
--
ALTER TABLE `Staff`
  MODIFY `StaffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `Workout`
--
ALTER TABLE `Workout`
  MODIFY `WorkoutID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=612;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `FitnessClass`
--
ALTER TABLE `FitnessClass`
  ADD CONSTRAINT `FitnessClass_ibfk_1` FOREIGN KEY (`StaffID`) REFERENCES `Staff` (`StaffID`) ON UPDATE CASCADE;

--
-- Constraints for table `Member`
--
ALTER TABLE `Member`
  ADD CONSTRAINT `Member_ibfk_1` FOREIGN KEY (`MembershipTypeID`) REFERENCES `MembershipType` (`MembershipTypeID`) ON UPDATE CASCADE;

--
-- Constraints for table `MemberClass`
--
ALTER TABLE `MemberClass`
  ADD CONSTRAINT `MemberClass_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `Member` (`MemberID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `MemberClass_ibfk_2` FOREIGN KEY (`ClassID`) REFERENCES `FitnessClass` (`ClassID`) ON UPDATE CASCADE;

--
-- Constraints for table `Workout`
--
ALTER TABLE `Workout`
  ADD CONSTRAINT `Workout_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `Member` (`MemberID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Workout_ibfk_2` FOREIGN KEY (`EquipmentID`) REFERENCES `Equipment` (`EquipmentID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

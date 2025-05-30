CREATE TABLE MembershipType (
    MembershipTypeID INT PRIMARY KEY,
    TypeName VARCHAR(255),
    Description TEXT,
    Duration INT, -- Duration in months
    Price DECIMAL(10,2),
    Benefits TEXT
);

CREATE TABLE Staff (
    StaffID INT PRIMARY KEY,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    Email VARCHAR(255),
    Phone VARCHAR(50),
    Position VARCHAR(100)
);
CREATE TABLE Member (
    MemberID INT PRIMARY KEY,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    Email VARCHAR(255),
    Phone VARCHAR(50),
    Address VARCHAR(255),
    DateOfBirth DATE,
    MembershipTypeID INT,
    FOREIGN KEY (MembershipTypeID) REFERENCES MembershipType(MembershipTypeID)
);
CREATE TABLE FitnessClass (
    ClassID INT PRIMARY KEY,
    ClassName VARCHAR(255),
    Description TEXT,
    StartTime TIME,
    EndTime TIME,
    MaxCapacity INT,
    StaffID INT,
    Rating DECIMAL(3,2),
    FOREIGN KEY (StaffID) REFERENCES Staff(StaffID)
);
CREATE TABLE Equipment (
    EquipmentID INT PRIMARY KEY,
    EquipmentName VARCHAR(255),
    EquipmentType VARCHAR(255),
    PurchaseDate DATE
);
CREATE TABLE Workout (
    WorkoutID INT PRIMARY KEY,
    MemberID INT,
    EquipmentID INT,
    WorkoutDate DATE,
    StartTime TIME,
    EndTime TIME,
    TotalCaloriesBurned INT,
    FOREIGN KEY (MemberID) REFERENCES Member(MemberID),
    FOREIGN KEY (EquipmentID) REFERENCES Equipment(EquipmentID)
);


INSERT INTO MembershipType (MembershipTypeID, TypeName, Description, Duration, Price, Benefits) VALUES
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
INSERT INTO Staff (StaffID, FirstName, LastName, Email, Phone, Position) VALUES
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
INSERT INTO Member (MemberID, FirstName, LastName, Email, Phone, Address, DateOfBirth, MembershipTypeID) VALUES
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
INSERT INTO FitnessClass (ClassID, ClassName, Description, StartTime, EndTime, MaxCapacity, StaffID, Rating) VALUES
(401, 'Yoga Basics', 'Beginner yoga class', '08:00:00', '09:00:00', 20, 206, 4.5),
(402, 'HIIT Blast', 'High intensity interval training', '09:30:00', '10:15:00', 25, 202, 4.7),
(403, 'Zumba Dance', 'Fun dance-based workout', '10:30:00', '11:30:00', 30, 201, 4.8),
(404, 'Strength Training', 'Full body lifting workout', '12:00:00', '13:00:00', 15, 205, 4.6),
(405, 'Spin Class', 'High-energy cycling class', '13:30:00', '14:15:00', 20, 210, 4.4),
(406, 'Pilates', 'Core strength and flexibility', '14:30:00', '15:15:00', 18, 206, 4.9),
(407, 'Boxing Basics', 'Intro to boxing', '15:30:00', '16:30:00', 12, 210, 4.3),
(408, 'Aqua Fit', 'Water-based workout', '17:00:00', '18:00:00', 20, 209, 4.5),
(409, 'Mobility Flow', 'Stretching and movement', '18:30:00', '19:15:00', 20, 207, 4.2),
(410, 'Evening Yoga', 'Relaxing yoga session', '19:30:00', '20:30:00', 25, 206, 4.6);
INSERT INTO Equipment (EquipmentID, EquipmentName, EquipmentType, PurchaseDate) VALUES
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
INSERT INTO Workout (WorkoutID, MemberID, EquipmentID, WorkoutDate, StartTime, EndTime, TotalCaloriesBurned) VALUES
(601, 301, 501, '2025-05-20', '07:00:00', '07:30:00', 300),
(602, 302, 503, '2025-05-20', '08:00:00', '08:45:00', 400),
(603, 303, 502, '2025-05-21', '06:30:00', '07:15:00', 350),
(604, 304, 504, '2025-05-21', '09:00:00', '09:45:00', 380),
(605, 305, 505, '2025-05-22', '10:00:00', '10:30:00', 320),
(606, 306, 506, '2025-05-22', '11:00:00', '11:30:00', 310),
(607, 307, 507, '2025-05-23', '12:00:00', '12:30:00', 200),
(608, 308, 508, '2025-05-23', '13:00:00', '13:30:00', 250),
(609, 309, 509, '2025-05-24', '14:00:00', '14:30:00', 330),
(610, 310, 510, '2025-05-24', '15:00:00', '15:45:00', 370);

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

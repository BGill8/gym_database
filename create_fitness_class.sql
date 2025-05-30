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

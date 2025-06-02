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

CREATE TABLE MembershipType (
    MembershipTypeID INT PRIMARY KEY,
    TypeName VARCHAR(255),
    Description TEXT,
    Duration INT, -- Duration in months
    Price DECIMAL(10,2),
    Benefits TEXT
);

<?php
session_start();
require_once "../config.php";
 
// Define variables and initialize with empty values
$ClassName = $Description = $StartTime = $EndTime = $MaxCapacity = $StaffID = $Rating = "";
$ClassName_err = $Description_err = $StartTime_err = $EndTime_err = $MaxCapacity_err = $StaffID_err = $Rating_err = "";

// Get ClassID from URL
if(isset($_GET["ClassID"]) && !empty(trim($_GET["ClassID"]))){
    $_SESSION["ClassID"] = $_GET["ClassID"];
}

$ClassID = $_SESSION["ClassID"] ?? null;

// Form default values - load current class data
if($ClassID && $_SERVER["REQUEST_METHOD"] != "POST"){
    $sql1 = "SELECT * FROM FitnessClass WHERE ClassID = $ClassID";
    $result1 = mysqli_query($link, $sql1);
    
    if($result1 && mysqli_num_rows($result1) > 0){
        $row = mysqli_fetch_array($result1);
        $ClassName = $row['ClassName'];
        $Description = $row['Description'];
        $StartTime = $row['StartTime'];
        $EndTime = $row['EndTime'];
        $MaxCapacity = $row['MaxCapacity'];
        $StaffID = $row['StaffID'];
        $Rating = $row['Rating'];
    } else {
        header("location: ../utilities/error.php");
        exit();
    }
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate Class Name
    $ClassName = trim($_POST["ClassName"]);
    if(empty($ClassName)){
        $ClassName_err = "Please enter a class name.";
    }
    
    // Validate Description
    $Description = trim($_POST["Description"]);
    if(empty($Description)){
        $Description_err = "Please enter a class description.";
    }
    
    // Validate Start Time
    $StartTime = trim($_POST["StartTime"]);
    if(empty($StartTime)){
        $StartTime_err = "Please enter start time.";
    }
    
    // Validate End Time
    $EndTime = trim($_POST["EndTime"]);
    if(empty($EndTime)){
    }
    
    // Validate Max Capacity
    $MaxCapacity = trim($_POST["MaxCapacity"]);
    if(empty($MaxCapacity)){
        $MaxCapacity_err = "Please enter maximum capacity.";
    } elseif(!is_numeric($MaxCapacity) || $MaxCapacity <= 0){
        $MaxCapacity_err = "Please enter a valid capacity number.";
    } else {
        // Check if new capacity is less than current enrollments
        $enrollment_check = "SELECT COUNT(*) as enrolled FROM MemberClass WHERE ClassID = $ClassID";
        $enrollment_result = mysqli_query($link, $enrollment_check);
        $enrollment_row = mysqli_fetch_array($enrollment_result);
        if($MaxCapacity < $enrollment_row['enrolled']) {
            $MaxCapacity_err = "Capacity cannot be less than current enrollments (" . $enrollment_row['enrolled'] . ")";
        }
    }
    
    // Validate Staff ID (Instructor)
    $StaffID = trim($_POST["StaffID"]);
    if(empty($StaffID)){
        $StaffID_err = "Please select an instructor.";
    }
    
    // Validate Rating (optional)
    $Rating = trim($_POST["Rating"]);
    if(!empty($Rating) && (!is_numeric($Rating) || $Rating < 0 || $Rating > 5)){
        $Rating_err = "Rating must be between 0 and 5.";
    }

    // Check input errors before updating database
    if(empty($ClassName_err) && empty($Description_err) && empty($StartTime_err) && empty($EndTime_err) && 
       empty($MaxCapacity_err) && empty($StaffID_err) && empty($Rating_err)){
        
        // Prepare rating value (NULL if empty)
        $ratingValue = empty($Rating) ? "NULL" : $Rating;
        
        // Update statement
        $sql = "UPDATE FitnessClass SET ClassName='$ClassName', Description='$Description', StartTime='$StartTime', 
                EndTime='$EndTime', MaxCapacity=$MaxCapacity, StaffID=$StaffID, Rating=$ratingValue 
                WHERE ClassID=$ClassID";
    
        if(mysqli_query($link, $sql)){
            // Records updated successfully. Redirect to classes page
            header("location: viewAllClasses.php");
            exit();
        } else{
            echo "<center><h2>Error when updating fitness class: " . mysqli_error($link) . "</center></h2>";
        }
    }
    
    // Close connection
    mysqli_close($link);
}

// Check if ClassID exists
if(!$ClassID) {
    header("location: ../utilities/error.php");
    exit();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Fitness Class - Benny's Iron Dam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h3>Update Fitness Class - ID = <?php echo $ClassID; ?></h3>
                    </div>
                    <p>Please edit the input values and submit to update.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?ClassID=" . $ClassID; ?>" method="post">
                        <div class="form-group <?php echo (!empty($ClassName_err)) ? 'has-error' : ''; ?>">
                            <label>Class Name</label>
                            <input type="text" name="ClassName" class="form-control" value="<?php echo $ClassName; ?>">
                            <span class="help-block"><?php echo $ClassName_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($Description_err)) ? 'has-error' : ''; ?>">
                            <label>Description</label>
                            <textarea name="Description" class="form-control" rows="3"><?php echo $Description; ?></textarea>
                            <span class="help-block"><?php echo $Description_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($StartTime_err)) ? 'has-error' : ''; ?>">
                            <label>Start Time</label>
                            <input type="time" name="StartTime" class="form-control" value="<?php echo $StartTime; ?>">
                            <span class="help-block"><?php echo $StartTime_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($EndTime_err)) ? 'has-error' : ''; ?>">
                            <label>End Time</label>
                            <input type="time" name="EndTime" class="form-control" value="<?php echo $EndTime; ?>">
                            <span class="help-block"><?php echo $EndTime_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($MaxCapacity_err)) ? 'has-error' : ''; ?>">
                            <label>Maximum Capacity</label>
                            <input type="number" name="MaxCapacity" class="form-control" min="1" max="100" value="<?php echo $MaxCapacity; ?>">
                            <span class="help-block"><?php echo $MaxCapacity_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($StaffID_err)) ? 'has-error' : ''; ?>">
                            <label>Instructor</label>
                            <select name="StaffID" class="form-control">
                                <option value="">Select Instructor</option>
                                <?php
                                $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                                if (!$conn) {
                                    die('Could not connect: ' . mysqli_error());
                                }
                                // Only show staff members who are instructors/coaches
                                $sql = "SELECT StaffID, FirstName, LastName, Position FROM Staff 
                                        WHERE Position LIKE '%Instructor%' OR Position LIKE '%Coach%' 
                                        ORDER BY FirstName, LastName";
                                $result = mysqli_query($conn, $sql);
                                if (!$result) {
                                    die("Query to show instructors failed");
                                }
                                while($row = mysqli_fetch_array($result)) {
                                    $selected = ($StaffID == $row['StaffID']) ? 'selected' : '';
                                    echo "<option value='{$row['StaffID']}' $selected>{$row['FirstName']} {$row['LastName']} ({$row['Position']})</option>";
                                }
                                mysqli_free_result($result);
                                mysqli_close($conn);
                                ?>
                            </select>
                            <span class="help-block"><?php echo $StaffID_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($Rating_err)) ? 'has-error' : ''; ?>">
                            <label>Rating</label>
                            <input type="number" name="Rating" class="form-control" min="0" max="5" step="0.1" value="<?php echo $Rating; ?>" placeholder="e.g., 4.5">
                            <span class="help-block"><?php echo $Rating_err;?></span>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="viewAllClasses.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
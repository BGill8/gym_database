<?php
//Group #3 Gabriel de Leon, James Nguyen, Stanley Eng, Brandon Gill
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$ClassName = $Description = $StartTime = $EndTime = $MaxCapacity = $StaffID = $Rating = "";
$ClassName_err = $Description_err = $StartTime_err = $EndTime_err = $MaxCapacity_err = $StaffID_err = $Rating_err = "";
 
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
        $EndTime_err = "Please enter end time.";
    }
    
    // Validate Max Capacity
    $MaxCapacity = trim($_POST["MaxCapacity"]);
    if(empty($MaxCapacity)){
        $MaxCapacity_err = "Please enter maximum capacity.";
    } elseif(!is_numeric($MaxCapacity) || $MaxCapacity <= 0){
        $MaxCapacity_err = "Please enter a valid capacity number.";
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

    // Check input errors before inserting in database
    if(empty($ClassName_err) && empty($Description_err) && empty($StartTime_err) && 
       empty($EndTime_err) && empty($MaxCapacity_err) && empty($StaffID_err) && empty($Rating_err)){
        
        // Prepare rating value (NULL if empty)
        $ratingValue = empty($Rating) ? "NULL" : $Rating;
        
        // Insert statement
        $sql = "INSERT INTO FitnessClass (ClassName, Description, StartTime, EndTime, MaxCapacity, StaffID, Rating) 
                VALUES ('$ClassName', '$Description', '$StartTime', '$EndTime', $MaxCapacity, $StaffID, $ratingValue)";
         
        if(mysqli_query($link, $sql)){
            // Records created successfully. Redirect to classes page
            header("location: viewAllClasses.php");
            exit();
        } else{
            echo "<center><h4>Error while creating new class: " . mysqli_error($link) . "</h4></center>";
            $ClassID_err = "Enter a unique Class ID.";
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Fitness Class - Benny's Iron Dam</title>
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
                        <h2>Create New Fitness Class</h2>
                    </div>
                    <p>Please fill this form and submit to add a fitness class to the gym database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        
                        <div class="form-group <?php echo (!empty($ClassName_err)) ? 'has-error' : ''; ?>">
                            <label>Class Name</label>
                            <input type="text" name="ClassName" class="form-control" value="<?php echo $ClassName; ?>" placeholder="e.g., Yoga Basics, HIIT Blast">
                            <span class="help-block"><?php echo $ClassName_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($Description_err)) ? 'has-error' : ''; ?>">
                            <label>Description</label>
                            <textarea name="Description" class="form-control" rows="3" placeholder="Describe the class..."><?php echo $Description; ?></textarea>
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
                            <input type="number" name="MaxCapacity" class="form-control" min="1" max="100" value="<?php echo $MaxCapacity; ?>" placeholder="e.g., 20">
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
                            <label>Initial Rating (Optional)</label>
                            <input type="number" name="Rating" class="form-control" min="0" max="5" step="0.1" value="<?php echo $Rating; ?>" placeholder="e.g., 4.5">
                            <span class="help-block"><?php echo $Rating_err;?></span>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../index.php" class="btn btn-default">Cancel</a>
                        <a href="viewAllClasses.php" class="btn btn-info">View All Classes</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
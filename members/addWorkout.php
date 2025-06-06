<?php
session_start();
ob_start();
$MemberID = $_SESSION["MemberID"] = $_GET["MemberID"] ?? $_SESSION["MemberID"] ?? "";
$LastName = $_SESSION["LastName"] = $_GET["LastName"] ?? $_SESSION["LastName"] ?? "";

// Include config file
require_once "../config.php";
?>

<?php 
// Define variables and initialize with empty values
$WorkoutID = $EquipmentID = $WorkoutDate = $StartTime = $EndTime = $TotalCaloriesBurned = "";
$WorkoutID_err = $EquipmentID_err = $WorkoutDate_err = $StartTime_err = $EndTime_err = $TotalCaloriesBurned_err = "";
$SQL_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Workout ID
    $WorkoutID = trim($_POST["WorkoutID"]);
    if(empty($WorkoutID)){
        $WorkoutID_err = "Please enter a workout ID.";
    } elseif(!ctype_digit($WorkoutID)){
        $WorkoutID_err = "Please enter a positive integer value for Workout ID.";
    }
    
    // Validate Equipment (optional)
    $EquipmentID = trim($_POST["EquipmentID"]);
    // Equipment is optional, so no validation needed if empty
    
    // Validate Workout Date
    $WorkoutDate = trim($_POST["WorkoutDate"]);
    if(empty($WorkoutDate)){
        $WorkoutDate_err = "Please select a workout date.";
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
    
    // Validate Calories Burned
    $TotalCaloriesBurned = trim($_POST["TotalCaloriesBurned"]);
    if(empty($TotalCaloriesBurned)){
        $TotalCaloriesBurned_err = "Please enter calories burned.";
    } elseif(!is_numeric($TotalCaloriesBurned) || $TotalCaloriesBurned < 0){
        $TotalCaloriesBurned_err = "Please enter a valid number of calories.";
    }
    
    // Validate the Member ID
    if(empty($MemberID)){
        $SQL_err = "No Member ID provided.";     
    }

    // Check input errors before inserting in database
    if(empty($WorkoutID_err) && empty($EquipmentID_err) && empty($WorkoutDate_err) && 
       empty($StartTime_err) && empty($EndTime_err) && empty($TotalCaloriesBurned_err) && empty($SQL_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Workout (WorkoutID, MemberID, EquipmentID, WorkoutDate, StartTime, EndTime, TotalCaloriesBurned) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            $equipmentParam = empty($EquipmentID) ? null : $EquipmentID;
            mysqli_stmt_bind_param($stmt, 'iiisssi', $param_WorkoutID, $param_MemberID, $param_EquipmentID, 
                $param_WorkoutDate, $param_StartTime, $param_EndTime, $param_TotalCaloriesBurned);
            
            // Set parameters
            $param_WorkoutID = $WorkoutID;
            $param_MemberID = $MemberID;
            $param_EquipmentID = $equipmentParam;
            $param_WorkoutDate = $WorkoutDate;
            $param_StartTime = $StartTime;
            $param_EndTime = $EndTime;
            $param_TotalCaloriesBurned = $TotalCaloriesBurned;
        
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to view workouts page
                header("location: viewWorkouts.php?MemberID=" . $MemberID . "&LastName=" . $LastName);
                exit();
            } else{
                // Error
                $SQL_err = mysqli_error($link);
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }   
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Workout - Benny's Iron Dam</title>
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
                <div class="col-md-10">
                    <div class="page-header">
                        <h3>Add a Workout</h3>
                        <h4><?php echo $LastName; ?> - Member ID = <?php echo $MemberID; ?></h4>
                    </div>
                
<?php
echo $SQL_err;
?>    

    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
        <div class="form-group <?php echo (!empty($WorkoutID_err)) ? 'has-error' : ''; ?>">
            <label>Workout ID</label>
            <input type="number" name="WorkoutID" class="form-control" value="<?php echo $WorkoutID; ?>">
            <span class="help-block"><?php echo $WorkoutID_err; ?></span>
        </div>
        
        <div class="form-group <?php echo (!empty($EquipmentID_err)) ? 'has-error' : ''; ?>">
            <label>Equipment (Optional)</label>
            <select name="EquipmentID" class="form-control">
                <option value="">No Equipment</option>
                <?php
                $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                if (!$conn) {
                    die('Could not connect: ' . mysqli_error());
                }
                $sql = "SELECT EquipmentID, EquipmentName, EquipmentType FROM Equipment ORDER BY EquipmentType, EquipmentName";
                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    die("Query to show equipment failed");
                }
                $num_row = mysqli_num_rows($result);    

                for($i=0; $i<$num_row; $i++) {
                    $equipment = mysqli_fetch_row($result);
                    $selected = ($EquipmentID == $equipment[0]) ? 'selected' : '';
                    echo "<option value='$equipment[0]' $selected>$equipment[1] ($equipment[2])</option>";
                }
                mysqli_free_result($result);
                mysqli_close($conn);
                ?>
            </select>    
            <span class="help-block"><?php echo $EquipmentID_err; ?></span>
        </div>
        
        <div class="form-group <?php echo (!empty($WorkoutDate_err)) ? 'has-error' : ''; ?>">
            <label>Workout Date</label>
            <input type="date" name="WorkoutDate" class="form-control" value="<?php echo $WorkoutDate ?: date('Y-m-d'); ?>">
            <span class="help-block"><?php echo $WorkoutDate_err; ?></span>
        </div>
        
        <div class="form-group <?php echo (!empty($StartTime_err)) ? 'has-error' : ''; ?>">
            <label>Start Time</label>
            <input type="time" name="StartTime" class="form-control" value="<?php echo $StartTime; ?>">
            <span class="help-block"><?php echo $StartTime_err; ?></span>
        </div>
        
        <div class="form-group <?php echo (!empty($EndTime_err)) ? 'has-error' : ''; ?>">
            <label>End Time</label>
            <input type="time" name="EndTime" class="form-control" value="<?php echo $EndTime; ?>">
            <span class="help-block"><?php echo $EndTime_err; ?></span>
        </div>
        
        <div class="form-group <?php echo (!empty($TotalCaloriesBurned_err)) ? 'has-error' : ''; ?>">
            <label>Total Calories Burned</label>
            <input type="number" name="TotalCaloriesBurned" class="form-control" min="0" max="2000" value="<?php echo $TotalCaloriesBurned; ?>">
            <span class="help-block"><?php echo $TotalCaloriesBurned_err; ?></span>
        </div>
        
        <div>
            <input type="submit" class="btn btn-success pull-left" value="Add Workout">    
            &nbsp;
            <a href="viewWorkouts.php?MemberID=<?php echo $MemberID; ?>&LastName=<?php echo $LastName; ?>" class="btn btn-primary">Back to Workouts</a>
            &nbsp;
            <a href="../index.php" class="btn btn-default">Back to Members</a>
        </div>
    </form>
</body>
</html>
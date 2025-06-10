<?php
//Group #3 Gabriel de Leon, James Nguyen, Stanley Eng, Brandon Gill
session_start();
// Include config file
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Workouts - Benny's Iron Dam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 80%;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">View Member Workouts</h2>
                        <a href="addWorkout.php?MemberID=<?php echo $_GET['MemberID']; ?>&LastName=<?php echo $_GET['LastName']; ?>" class="btn btn-success pull-right">Add Workout</a>
                    </div>
<?php

// Check existence of id parameter before processing further
if(isset($_GET["MemberID"]) && !empty(trim($_GET["MemberID"]))){
    $_SESSION["MemberID"] = $_GET["MemberID"];
}
if(isset($_GET["LastName"]) && !empty(trim($_GET["LastName"]))){
    $_SESSION["LastName"] = $_GET["LastName"];
}

if(isset($_SESSION["MemberID"])){
    
    // Prepare a select statement to get workouts with equipment details
    $sql = "SELECT w.WorkoutID, w.WorkoutDate, w.StartTime, w.EndTime, w.TotalCaloriesBurned, 
                   e.EquipmentName, e.EquipmentType
            FROM Workout w 
            LEFT JOIN Equipment e ON w.EquipmentID = e.EquipmentID 
            WHERE w.MemberID = ? 
            ORDER BY w.WorkoutDate DESC, w.StartTime DESC";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_MemberID);      
        // Set parameters
        $param_MemberID = $_SESSION["MemberID"];
        $LastName = $_SESSION["LastName"];

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            echo "<h4>Workouts for " . $LastName . " &nbsp;&nbsp;&nbsp; Member ID = " . $param_MemberID . "</h4><p>";
            if(mysqli_num_rows($result) > 0){
                echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Workout ID</th>";
                            echo "<th>Date</th>";
                            echo "<th>Start Time</th>";
                            echo "<th>End Time</th>";
                            echo "<th>Equipment Used</th>";
                            echo "<th>Equipment Type</th>";
                            echo "<th>Calories Burned</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";                            
                // Output data of each row
                while($row = mysqli_fetch_array($result)){
                    echo "<tr>";
                    echo "<td>" . $row['WorkoutID'] . "</td>";
                    echo "<td>" . $row['WorkoutDate'] . "</td>";
                    echo "<td>" . $row['StartTime'] . "</td>";
                    echo "<td>" . $row['EndTime'] . "</td>";
                    echo "<td>" . ($row['EquipmentName'] ?? 'No Equipment') . "</td>";
                    echo "<td>" . ($row['EquipmentType'] ?? 'N/A') . "</td>";
                    echo "<td>" . ($row['TotalCaloriesBurned'] ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";                            
                echo "</table>";                
                mysqli_free_result($result);
                
                // Display workout statistics
                echo "<div class='row'>";
                echo "<div class='col-md-6'>";
                echo "<h4>Workout Statistics</h4>";
                
                // Get workout stats
                $stats_sql = "SELECT 
                                COUNT(*) as total_workouts,
                                SUM(TotalCaloriesBurned) as total_calories,
                                AVG(TotalCaloriesBurned) as avg_calories,
                                MAX(TotalCaloriesBurned) as max_calories
                              FROM Workout WHERE MemberID = ?";
                
                if($stats_stmt = mysqli_prepare($link, $stats_sql)){
                    mysqli_stmt_bind_param($stats_stmt, "i", $param_MemberID);
                    if(mysqli_stmt_execute($stats_stmt)){
                        $stats_result = mysqli_stmt_get_result($stats_stmt);
                        if($stats_row = mysqli_fetch_array($stats_result)){
                            echo "<p><strong>Total Workouts:</strong> " . $stats_row['total_workouts'] . "</p>";
                            echo "<p><strong>Total Calories Burned:</strong> " . ($stats_row['total_calories'] ?? 0) . "</p>";
                            echo "<p><strong>Average Calories per Workout:</strong> " . number_format($stats_row['avg_calories'] ?? 0, 1) . "</p>";
                            echo "<p><strong>Best Workout (Calories):</strong> " . ($stats_row['max_calories'] ?? 0) . "</p>";
                        }
                        mysqli_free_result($stats_result);
                    }
                    mysqli_stmt_close($stats_stmt);
                }
                echo "</div>";
                echo "</div>";
                
            } else {
                echo "<p class='lead'><em>No workouts recorded yet.</em></p>";
            }
        } else{
            // URL doesn't contain valid id parameter. Redirect to error page
            header("location: ../utilities/error.php");
            exit();
        }
    }     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: ../utilities/error.php");
    exit();
}
?>                                         
    <p><a href="../index.php" class="btn btn-primary">Back to Members</a></p>
    </div>
   </div>        
  </div>
</div>
</body>
</html>
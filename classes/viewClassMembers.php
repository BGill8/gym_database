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
    <title>View Class Members - Benny's Iron Dam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 90%;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
        .class-info {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
                        <h2 class="pull-left">Class Enrollment</h2>
                        <a href="viewAllClasses.php" class="btn btn-primary pull-right">Back to All Classes</a>
                    </div>
                    
                    <?php
                    // Check existence of ClassID parameter
                    if(isset($_GET["ClassID"]) && !empty(trim($_GET["ClassID"]))){
                        $_SESSION["ClassID"] = $_GET["ClassID"];
                    }
                    if(isset($_GET["ClassName"]) && !empty(trim($_GET["ClassName"]))){
                        $_SESSION["ClassName"] = $_GET["ClassName"];
                    }

                    if(isset($_SESSION["ClassID"])){
                        $ClassID = $_SESSION["ClassID"];
                        $ClassName = $_SESSION["ClassName"] ?? "Unknown Class";
                        
                        // Get class details
                        $class_sql = "SELECT fc.*, s.FirstName, s.LastName, s.Position 
                                      FROM FitnessClass fc 
                                      LEFT JOIN Staff s ON fc.StaffID = s.StaffID 
                                      WHERE fc.ClassID = $ClassID";
                        $class_result = mysqli_query($link, $class_sql);
                        
                        if($class_result && mysqli_num_rows($class_result) > 0) {
                            $class_info = mysqli_fetch_array($class_result);
                            
                            // Display class information
                            echo "<div class='class-info'>";
                            echo "<div class='row'>";
                            echo "<div class='col-md-8'>";
                            echo "<h3>" . $class_info['ClassName'] . " <small>(Class ID: " . $ClassID . ")</small></h3>";
                            echo "<p><strong>Description:</strong> " . $class_info['Description'] . "</p>";
                            echo "<p><strong>Time:</strong> " . date('g:i A', strtotime($class_info['StartTime'])) . " - " . date('g:i A', strtotime($class_info['EndTime'])) . "</p>";
                            echo "<p><strong>Instructor:</strong> " . $class_info['FirstName'] . " " . $class_info['LastName'] . " (" . $class_info['Position'] . ")</p>";
                            echo "</div>";
                            echo "<div class='col-md-4'>";
                            echo "<p><strong>Capacity:</strong> " . $class_info['MaxCapacity'] . " members</p>";
                            if($class_info['Rating']) {
                                $rating = round($class_info['Rating'], 1);
                                $stars = str_repeat('★', floor($rating)) . str_repeat('☆', 5 - floor($rating));
                                echo "<p><strong>Rating:</strong> <span style='color: #f39c12;'>$stars</span> $rating/5</p>";
                            } else {
                                echo "<p><strong>Rating:</strong> <em>No rating yet</em></p>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        
                        // Get enrolled members
                        $sql = "SELECT m.MemberID, m.FirstName, m.LastName, m.Email, m.Phone, mt.TypeName
                                FROM Member m 
                                LEFT JOIN MembershipType mt ON m.MembershipTypeID = mt.MembershipTypeID
                                INNER JOIN MemberClass mc ON m.MemberID = mc.MemberID 
                                WHERE mc.ClassID = $ClassID
                                ORDER BY m.MemberID";

                        $result = mysqli_query($link, $sql);
                        
                        if($result){
                            $enrolledCount = mysqli_num_rows($result);
                            $capacity = $class_info['MaxCapacity'] ?? 0;
                            $fillRate = $capacity > 0 ? round(($enrolledCount / $capacity) * 100, 1) : 0;
                            
                            echo "<h4>Enrolled Members ($enrolledCount/$capacity - $fillRate% full)</h4>";
                            
                            if($enrolledCount > 0){
                                echo "<table class='table table-bordered table-striped'>";
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th width=10%>Member ID</th>";
                                            echo "<th width=20%>Name</th>";
                                            echo "<th width=25%>Email</th>";
                                            echo "<th width=15%>Phone</th>";
                                            echo "<th width=20%>Membership Type</th>";
                                            echo "<th width=10%>Actions</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                            echo "<td>" . $row['MemberID'] . "</td>";
                                            echo "<td>" . $row['FirstName'] . " " . $row['LastName'] . "</td>";
                                            echo "<td>" . $row['Email'] . "</td>";
                                            echo "<td>" . $row['Phone'] . "</td>";
                                            echo "<td>" . ($row['TypeName'] ?? 'No Membership') . "</td>";
                                            echo "<td>";
                                            echo "<a href='../members/viewWorkouts.php?MemberID=". $row['MemberID']."&LastName=".$row['LastName']."' title='View Member Workouts' data-toggle='tooltip'><span class='glyphicon glyphicon-list-alt'></span></a>";
                                            echo "<a href='../classes/deleteMemberClass.php?MemberID=" . $row['MemberID'] . "&ClassID=" . $ClassID . "' title='Unenroll Member' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                echo "</table>";
                                
                                // Show enrollment status
                                if($enrolledCount >= $capacity) {
                                    echo "<div class='alert alert-danger'><strong>Class Full:</strong> This class has reached maximum capacity.</div>";
                                } elseif($enrolledCount >= $capacity * 0.8) {
                                    echo "<div class='alert alert-warning'><strong>Nearly Full:</strong> This class is " . $fillRate . "% full.</div>";
                                } else {
                                    echo "<div class='alert alert-info'><strong>Availability:</strong> " . ($capacity - $enrolledCount) . " spots remaining.</div>";
                                }
                                
                                mysqli_free_result($result);
                            } else {
                                echo "<p class='lead'><em>No members are currently enrolled in this class.</em></p>";
                                echo "<div class='alert alert-info'>This class is available for new enrollments!</div>";
                            }
                        } else {
                            echo "ERROR: Could not execute enrollment query. <br>" . mysqli_error($link);
                        }
                        
                    } else {
                        // URL doesn't contain ClassID parameter. Redirect to error page
                        header("location: ../utilities/error.php");
                        exit();
                    }
                    
                    // Close connection
                    mysqli_close($link);
                    ?>
                    
                    <p>
                        <a href="addMemberClass.php" class= "btn btn-success">Enroll Member</a>
                        <a href="viewAllClasses.php" class="btn btn-primary">Back to All Classes</a>
                        <a href="../index.php" class="btn btn-default">Back to Dashboard</a>
                    </p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
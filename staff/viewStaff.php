<?php
session_start();
// Include config file
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Staff - Benny's Iron Dam</title>
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
        .section-header {
            margin-top: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
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
                        <h2 class="pull-left">Staff Directory</h2>
                        <a href="createStaff.php" class="btn btn-success pull-right">Add New Staff</a>
                    </div>
                    
                    <?php
                    // Attempt select all staff query execution
                    $sql = "SELECT StaffID, FirstName, LastName, Email, Phone, Position FROM Staff ORDER BY StaffID ASC";
                    $result = mysqli_query($link, $sql);
                    
                    if($result){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=8%>Staff ID</th>";
                                        echo "<th width=15%>First Name</th>";
                                        echo "<th width=15%>Last Name</th>";
                                        echo "<th width=20%>Email</th>";
                                        echo "<th width=12%>Phone</th>";
                                        echo "<th width=15%>Position</th>";
                                        echo "<th width=15%>Actions</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['StaffID'] . "</td>";
                                        echo "<td>" . $row['FirstName'] . "</td>";
                                        echo "<td>" . $row['LastName'] . "</td>";
                                        echo "<td>" . $row['Email'] . "</td>";
                                        echo "<td>" . $row['Phone'] . "</td>";
                                        echo "<td>" . $row['Position'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='updateStaff.php?StaffID=". $row['StaffID'] ."' title='Update Staff' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='deleteStaff.php?StaffID=". $row['StaffID'] ."' title='Delete Staff' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No staff members found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not execute $sql. <br>" . mysqli_error($link);
                    }
                    
                    // Display Staff Statistics
                    echo "<h2 class='section-header'>Staff Statistics</h2>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-6'>";
                    echo "<h3>Staff by Position</h3>";
                    $sql2 = "SELECT Position, COUNT(*) as Count FROM Staff GROUP BY Position ORDER BY Position ASC";
                    $result2 = mysqli_query($link, $sql2);
                    if($result2){
                        if(mysqli_num_rows($result2) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Position</th>";
                                        echo "<th>Count</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result2)){
                                    echo "<tr>";
                                        echo "<td>" . $row['Position'] . "</td>";
                                        echo "<td>" . $row['Count'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result2);
                        } else{
                            echo "<p class='lead'><em>No staff data found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not execute staff statistics query. <br>" . mysqli_error($link);
                    }
                    echo "</div>";
                    
                    // Display Class Instructors
                    echo "<div class='col-md-6'>";
                    echo "<h3>Class Instructors</h3>";
                    $sql3 = "SELECT s.FirstName, s.LastName, s.Position, COUNT(fc.ClassID) as ClassesTeaching 
                            FROM Staff s 
                            LEFT JOIN FitnessClass fc ON s.StaffID = fc.StaffID 
                            WHERE s.Position LIKE '%Instructor%' OR s.Position LIKE '%Coach%' 
                            GROUP BY s.StaffID 
                            ORDER BY ClassesTeaching DESC";
                    $result3 = mysqli_query($link, $sql3);
                    if($result3){
                        if(mysqli_num_rows($result3) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Name</th>";
                                        echo "<th>Position</th>";
                                        echo "<th>Classes</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result3)){
                                    echo "<tr>";
                                        echo "<td>" . $row['FirstName'] . " " . $row['LastName'] . "</td>";
                                        echo "<td>" . $row['Position'] . "</td>";
                                        echo "<td>" . $row['ClassesTeaching'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result3);
                        } else{
                            echo "<p class='lead'><em>No instructors found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not execute instructor query. <br>" . mysqli_error($link);
                    }
                    echo "</div>";
                    echo "</div>";
                    
                    // Close connection
                    mysqli_close($link);
                    ?>
                    
                    <p><a href="../index.php" class="btn btn-primary">Back to Dashboard</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
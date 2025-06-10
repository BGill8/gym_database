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
    <title>View All Classes - Benny's Iron Dam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 95%;
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
        .rating-stars {
            color: #f39c12;
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
                        <h2 class="pull-left">Fitness Classes Directory</h2>
                        <a href="createFitnessClass.php" class="btn btn-success pull-right">Add New Class</a>
                    </div>
                    
                    <?php
                    // Attempt select all classes query execution
                    $sql = "SELECT fc.ClassID, fc.ClassName, fc.Description, fc.StartTime, fc.EndTime, 
                                   fc.MaxCapacity, fc.Rating, s.FirstName, s.LastName, s.Position,
                                   COUNT(mc.MemberID) as EnrolledMembers
                            FROM FitnessClass fc 
                            LEFT JOIN Staff s ON fc.StaffID = s.StaffID 
                            LEFT JOIN MemberClass mc ON fc.ClassID = mc.ClassID
                            GROUP BY fc.ClassID
                            ORDER BY fc.ClassID ASC";
                    $result = mysqli_query($link, $sql);
                    
                    if($result){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=6%>Class ID</th>";
                                        echo "<th width=15%>Class Name</th>";
                                        echo "<th width=20%>Description</th>";
                                        echo "<th width=8%>Time</th>";
                                        echo "<th width=8%>Capacity</th>";
                                        echo "<th width=15%>Instructor</th>";
                                        echo "<th width=8%>Rating</th>";
                                        echo "<th width=8%>Enrolled</th>";
                                        echo "<th width=12%>Actions</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['ClassID'] . "</td>";
                                        echo "<td><strong>" . $row['ClassName'] . "</strong></td>";
                                        echo "<td>" . substr($row['Description'], 0, 60) . "...</td>";
                                        echo "<td>" . date('g:i A', strtotime($row['StartTime'])) . "<br>to<br>" . date('g:i A', strtotime($row['EndTime'])) . "</td>";
                                        echo "<td>" . $row['MaxCapacity'] . "</td>";
                                        echo "<td>" . $row['FirstName'] . " " . $row['LastName'] . "<br><small>(" . $row['Position'] . ")</small></td>";
                                        
                                        // Display rating with stars
                                        if($row['Rating']) {
                                            $rating = round($row['Rating'], 1);
                                            $stars = str_repeat('★', floor($rating)) . str_repeat('☆', 5 - floor($rating));
                                            echo "<td><span class='rating-stars'>$stars</span><br>$rating/5</td>";
                                        } else {
                                            echo "<td><em>No rating</em></td>";
                                        }
                                        
                                        // Display enrollment with capacity warning
                                        $enrolled = $row['EnrolledMembers'];
                                        $capacity = $row['MaxCapacity'];
                                        $enrollmentClass = '';
                                        if($enrolled >= $capacity) {
                                            $enrollmentClass = 'text-danger';
                                        } elseif($enrolled >= $capacity * 0.8) {
                                            $enrollmentClass = 'text-warning';
                                        }
                                        echo "<td class='$enrollmentClass'><strong>$enrolled/$capacity</strong></td>";
                                        
                                        echo "<td>";
                                            echo "<a href='updateFitnessClass.php?ClassID=". $row['ClassID'] ."' title='Update Class' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='deleteFitnessClass.php?ClassID=". $row['ClassID'] ."' title='Delete Class' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                            echo "<a href='viewClassMembers.php?ClassID=". $row['ClassID'] ."&ClassName=".urlencode($row['ClassName'])."' title='View Members' data-toggle='tooltip'><span class='glyphicon glyphicon-user'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No fitness classes found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not execute $sql. <br>" . mysqli_error($link);
                    }
                    
                    // Display Class Statistics
                    echo "<h2 class='section-header'>Class Statistics</h2>";
                    echo "<div class='row'>";
                    
                    // Popular Classes
                    echo "<div class='col-md-6'>";
                    echo "<h3>Most Popular Classes</h3>";
                    $sql2 = "SELECT fc.ClassName, COUNT(mc.MemberID) as Members, fc.MaxCapacity,
                                    ROUND((COUNT(mc.MemberID) / fc.MaxCapacity) * 100, 1) as FillRate
                             FROM FitnessClass fc 
                             LEFT JOIN MemberClass mc ON fc.ClassID = mc.ClassID
                             GROUP BY fc.ClassID 
                             ORDER BY Members DESC 
                             LIMIT 5";
                    $result2 = mysqli_query($link, $sql2);
                    if($result2){
                        if(mysqli_num_rows($result2) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Class Name</th>";
                                        echo "<th>Members</th>";
                                        echo "<th>Fill Rate</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result2)){
                                    echo "<tr>";
                                        echo "<td>" . $row['ClassName'] . "</td>";
                                        echo "<td>" . $row['Members'] . "/" . $row['MaxCapacity'] . "</td>";
                                        echo "<td>" . $row['FillRate'] . "%</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result2);
                        } else{
                            echo "<p class='lead'><em>No enrollment data found.</em></p>";
                        }
                    }
                    echo "</div>";
                    
                    // Highest Rated Classes
                    echo "<div class='col-md-6'>";
                    echo "<h3>Highest Rated Classes</h3>";
                    $sql3 = "SELECT ClassName, Rating FROM FitnessClass 
                             WHERE Rating IS NOT NULL 
                             ORDER BY Rating DESC 
                             LIMIT 5";
                    $result3 = mysqli_query($link, $sql3);
                    if($result3){
                        if(mysqli_num_rows($result3) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Class Name</th>";
                                        echo "<th>Rating</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result3)){
                                    $rating = round($row['Rating'], 1);
                                    $stars = str_repeat('★', floor($rating)) . str_repeat('☆', 5 - floor($rating));
                                    echo "<tr>";
                                        echo "<td>" . $row['ClassName'] . "</td>";
                                        echo "<td><span class='rating-stars'>$stars</span> $rating/5</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result3);
                        } else{
                            echo "<p class='lead'><em>No rated classes found.</em></p>";
                        }
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
<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Benny's Iron Dam - Gym Management</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
	<style type="text/css">
        .wrapper{
            width: 90%;
            margin:0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
        .section-header {
            margin-top: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .nav-buttons {
            margin-bottom: 20px;
        }
        .nav-buttons .btn {
            margin-right: 10px;
            margin-bottom: 10px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
		 $('.selectpicker').selectpicker();
    </script>
</head>
<body>
    <?php
        // Include config file
        require_once "config.php";
	?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
		    <div class="page-header clearfix">
		     <h1>Benny's Iron Dam - Gym Management System</h1> 
                       <p>Project includes CRUD operations for gym management. In this system you can:
				<ol> 	<li>CREATE new members, staff, and fitness classes</li>
					<li>RETRIEVE all workouts and classes for members</li>
                                        <li>UPDATE member, staff, and class records</li>
					<li>DELETE member, staff, and class records</li>
				</ol>
				
		    <!-- Navigation Buttons -->
		    <div class="nav-buttons">
		        <a href="members/createMember.php" class="btn btn-success">Add New Member</a>
		        <a href="staff/createStaff.php" class="btn btn-info">Add New Staff</a>
		        <a href="classes/createFitnessClass.php" class="btn btn-warning">Add New Class</a>
		        <a href="classes/viewAllClasses.php" class="btn btn-primary">View All Classes</a>
		        <a href="staff/viewStaff.php" class="btn btn-default">View Staff</a>
		    </div>
		    
		    <h2 class="section-header">Member Directory</h2>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select all members query execution
                    $sql = "SELECT m.MemberID, m.FirstName, m.LastName, m.Email, m.Phone, mt.TypeName 
                            FROM Member m 
                            LEFT JOIN MembershipType mt ON m.MembershipTypeID = mt.MembershipTypeID 
                            ORDER BY m.LastName, m.FirstName";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=8%>Member ID</th>";
                                        echo "<th width=15%>First Name</th>";
                                        echo "<th width=15%>Last Name</th>";
                                        echo "<th width=20%>Email</th>";
                                        echo "<th width=12%>Phone</th>";
                                        echo "<th width=15%>Membership Type</th>";
                                        echo "<th width=15%>Actions</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['MemberID'] . "</td>";
                                        echo "<td>" . $row['FirstName'] . "</td>";
                                        echo "<td>" . $row['LastName'] . "</td>";
										echo "<td>" . $row['Email'] . "</td>";
										echo "<td>" . $row['Phone'] . "</td>";
										echo "<td>" . ($row['TypeName'] ?? 'No Membership') . "</td>";								
                                        echo "<td>";
                                            echo "<a href='members/viewWorkouts.php?MemberID=". $row['MemberID']."&LastName=".$row['LastName']."' title='View Workouts' data-toggle='tooltip'><span class='glyphicon glyphicon-list-alt'></span></a>";
                                            echo "<a href='members/updateMember.php?MemberID=". $row['MemberID'] ."' title='Update Member' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='members/deleteMember.php?MemberID=". $row['MemberID'] ."' title='Delete Member' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                            echo "<a href='members/addWorkout.php?MemberID=". $row['MemberID']."&LastName=".$row['LastName']."' title='Add Workout' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No members found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not execute $sql. <br>" . mysqli_error($link);
                    }
					
					echo "<h2 class='section-header'>Gym Statistics</h2>";
					
					// Display Equipment Stats
					echo "<div class='row'>";
					echo "<div class='col-md-6'>";
					echo "<h3>Equipment Inventory</h3>";
                    $sql2 = "SELECT EquipmentType, COUNT(*) as Count FROM Equipment GROUP BY EquipmentType";
                    if($result2 = mysqli_query($link, $sql2)){
                        if(mysqli_num_rows($result2) > 0){
							echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Equipment Type</th>";
                                        echo "<th>Count</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result2)){
                                    echo "<tr>";
                                        echo "<td>" . $row['EquipmentType'] . "</td>";
                                        echo "<td>" . $row['Count'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result2);
                        } else{
                            echo "<p class='lead'><em>No equipment found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not execute equipment query. <br>" . mysqli_error($link);
                    }
                    echo "</div>";
                    
                    // Display Membership Stats
                    echo "<div class='col-md-6'>";
                    echo "<h3>Membership Statistics</h3>";
                    $sql3 = "SELECT mt.TypeName, COUNT(m.MemberID) as MemberCount, mt.Price 
                            FROM MembershipType mt 
                            LEFT JOIN Member m ON mt.MembershipTypeID = m.MembershipTypeID 
                            GROUP BY mt.MembershipTypeID 
                            ORDER BY MemberCount DESC";
                    if($result3 = mysqli_query($link, $sql3)){
                        if(mysqli_num_rows($result3) > 0){
							echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Membership Type</th>";
                                        echo "<th>Members</th>";
                                        echo "<th>Price</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result3)){
                                    echo "<tr>";
                                        echo "<td>" . $row['TypeName'] . "</td>";
                                        echo "<td>" . $row['MemberCount'] . "</td>";
                                        echo "<td>$" . number_format($row['Price'], 2) . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result3);
                        } else{
                            echo "<p class='lead'><em>No membership types found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not execute membership query. <br>" . mysqli_error($link);
                    }
                    echo "</div>";
                    echo "</div>";
					
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
            </div>        
        </div>
    </div>
</body>
</html>
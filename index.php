<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gym Management System</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
	<style type="text/css">
        .wrapper{
            width: 70%;
            margin:0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
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
		     <h2> Gym Management System </h2> 
                       <p> Project includes CRUD operations. In this website you can:
				<ol> 	<li> CREATE new members and staff </li>
					<li> RETRIEVE all workouts and classes for a member</li>
                                        <li> UPDATE member and staff records</li>
					<li> DELETE member and staff records </li>
				</ol>
		       <h2 class="pull-left">Member Details</h2>
                        <a href="createMember.php" class="btn btn-success pull-right">Add New Member</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Query for Members instead of Students
                    $sql = "SELECT MemberID, FirstName, LastName, Email, Phone FROM Member";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=8%>Member ID</th>";
                                        echo "<th width=10%>First Name</th>";
                                        echo "<th width=10%>Last Name</th>";
                                        echo "<th width=15%>Email</th>";
                                        echo "<th width=10%>Phone</th>";
                                        echo "<th width=10%>Action</th>";
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
                                        echo "<td>";
                                            echo "<a href='viewWorkouts.php?MemberID=". $row['MemberID']."&LastName=".$row['LastName']."' title='View Workouts' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='updateMember.php?MemberID=". $row['MemberID'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='deleteMember.php?MemberID=". $row['MemberID'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No members were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. <br>" . mysqli_error($link);
                    }
					echo "<br> <h2> Equipment Stats </h2> <br>";
					
                    // Select Equipment Stats instead of Course stats
                    $sql2 = "SELECT EquipmentID, EquipmentName, EquipmentType FROM Equipment";
                    if($result2 = mysqli_query($link, $sql2)){
                        if(mysqli_num_rows($result2) > 0){
                            echo "<div class='col-md-4'>";
							echo "<table width=30% class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=20%>Equipment ID</th>";
                                        echo "<th width = 40%>Name</th>";
                                        echo "<th width = 20%>Type</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result2)){
                                    echo "<tr>";
                                        echo "<td>" . $row['EquipmentID'] . "</td>";
                                        echo "<td>" . $row['EquipmentName'] . "</td>";
                                        echo "<td>" . $row['EquipmentType'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result2);
                        } else{
                            echo "<p class='lead'><em>No equipment records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql2. <br>" . mysqli_error($link);
                    }
					
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
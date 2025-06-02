<?php
	session_start();
    // Include config file
    require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Projects</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
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
                        <h2 class="pull-left">View Projects</h2>
						<a href="addProject.php" class="btn btn-success pull-right">Add Project</a>
                    </div>
<?php

// Check existence of id parameter before processing further
if(isset($_GET["Ssn"]) && !empty(trim($_GET["Ssn"]))){
	$_SESSION["Ssn"] = $_GET["Ssn"];
}
if(isset($_GET["Lname"]) && !empty(trim($_GET["Lname"]))){
	$_SESSION["Lname"] = $_GET["Lname"];
}

if(isset($_SESSION["Ssn"]) ){
	
    // Prepare a select statement
    $sql = "SELECT P.Pname, P.Pnumber, WO.Hours, WO.Essn FROM PROJECT P, WORKS_ON WO WHERE WO.Essn = ? AND WO.Pno = P.Pnumber";

	//$sql = "SELECT Essn, Pno, Hours From WORKS_ON WHERE Essn = ? ";   
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_Ssn);      
        // Set parameters
       $param_Ssn = $_SESSION["Ssn"];
	   $Lname = $_SESSION["Lname"];

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
			echo"<h4> Projects for ".$Lname." &nbsp      SSN =".$param_Ssn."</h4><p>";
			if(mysqli_num_rows($result) > 0){
				echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th width = 20%>Project Number</th>";
                            echo "<th>Project Name</th>";
                            echo "<th>Hours</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";							
				// output data of each row
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td>" . $row['Pnumber'] . "</td>";
                        echo "<td>" . $row['Pname'] . "</td>";
                        echo "<td>" . $row['Hours'] . "</td>";
    
                        echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";				
				mysqli_free_result($result);
			} else {
				echo "No Projects. ";
			}
//				mysqli_free_result($result);
        } else{
			// URL doesn't contain valid id parameter. Redirect to error page
            header("location: error.php");
            exit();
        }
    }     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>					                 					
	<p><a href="index.php" class="btn btn-primary">Back</a></p>
    </div>
   </div>        
  </div>
</div>
</body>
</html>
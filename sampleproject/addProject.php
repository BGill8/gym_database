<?php
	session_start();
	ob_start();
	$Ssn = $_SESSION["Ssn"];
	$Lname = $_SESSION["Lname"];
	// Include config file
	require_once "config.php";

?>


<?php 
	// Define variables and initialize with empty values
	$Pno = "";
	$Pno_err = $Ssn_err = $Hours_err = "" ;
	$SQL_err="";
 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Validate Project number
		$Pno = trim($_POST["Pno"]);
		if(empty($Pno)){
			$Pno_err = "Please select a project.";
		} 
    
		// Validate Hours
		$Hours = trim($_POST["Hours"]);
		if(empty($Hours)){
			$Hours_err = "Please enter hours (1-40)";     
		}
	
		// Validate the SSN
		if(empty($Ssn)){
			$Ssn_err = "No SSN.";     
		}


    // Check input errors before inserting in database
		if(empty($Ssn_err) && empty($Hours_err) && empty($Pno_err) ){
        // Prepare an insert statement
			$sql = "INSERT INTO WORKS_ON (Essn, Pno, Hours) VALUES (?, ?, ?)";


        	if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, 'sii', $param_Ssn, $param_Pno, $param_Hours);
            
				// Set parameters
				$param_Ssn = $Ssn;
				$param_Pno = $Pno;
				$param_Hours = $Hours;
        
            // Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
               // Records created successfully. Redirect to landing page
				//    header("location: index.php");
				//	exit();
				} else{
					// Error
					echo "Error";
					//exit();
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
    <title>Company DB</title>
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
                        <h3>Add a Project </h3>
						<h4><?php echo $Lname;?> SSN = <?php echo $Ssn;?></h4>
                    </div>
				
<?php
	echo $SQL_err;		
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if (!$conn) {
		die('Could not connect: ' . mysqli_error());
	}
	$sql = "SELECT Pnumber, Pname FROM PROJECT";
	$result = mysqli_query($conn, $sql);
	if (!$result) {
		die("Query to show fields from table failed");
	}
	$num_row = mysqli_num_rows($result);	
?>	

	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
		<div class="form-group <?php echo (!empty($Ssn_err)) ? 'has-error' : ''; ?>">
            <label>Project number & name</label>
			<select name="Pno" class="form-control">
			<?php

				for($i=0; $i<$num_row; $i++) {
					$Pnos=mysqli_fetch_row($result);
					echo "<option value='$Pnos[0]' >".$Pnos[0]."  ".$Pnos[1]."</option>";
				}
			?>
			</select>	
            <span class="help-block"><?php echo $Pno_err;?></span>
		</div>
		<div class="form-group <?php echo (!empty($Hours_err)) ? 'has-error' : ''; ?>">
			<label>Hours </label>
			<input type="number" name="Hours" class="form-control" min="1" max="80" value="">
			<span class="help-block"><?php echo $Hours_err;?></span>
		</div>
		<div>
			<input type="submit" class="btn btn-success pull-left" value="Add Project">	
			&nbsp;
			<a href="viewProjects.php" class="btn btn-primary">List Projects</a>

		</div>
	</form>
<?php		
	mysqli_free_result($result);
	mysqli_close($conn);
?>
</body>

</html>

	
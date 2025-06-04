<?php
	session_start();	
// Include config file
	require_once "config.php";
 
// Define variables and initialize with empty values
// Note: You can not update SSN 
$Lname = $Fname = $Salary = $Bdate = $Address = $Sex = $Dno = $Super_ssn = "";
$Lname_err = $Fname_err = $Address_err = $Sex_err = $Salary_err = $Dno_err = "" ;
// Form default values

if(isset($_GET["Ssn"]) && !empty(trim($_GET["Ssn"]))){
	$_SESSION["Ssn"] = $_GET["Ssn"];

    // Prepare a select statement
    $sql1 = "SELECT * FROM EMPLOYEE WHERE Ssn = ?";
  
    if($stmt1 = mysqli_prepare($link, $sql1)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt1, "s", $param_Ssn);      
        // Set parameters
       $param_Ssn = trim($_GET["Ssn"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt1)){
            $result1 = mysqli_stmt_get_result($stmt1);
			if(mysqli_num_rows($result1) > 0){

				$row = mysqli_fetch_array($result1);

				$Lname = $row['Lname'];
				$Fname = $row['Fname'];
				$Salery = $row['Salary'];
				$Bdate = $row['Bdate'];
				$Address = $row['Address'];
				$Sex = $row['Sex'];
				$Dno = $row['Dno'];
				$Super_ssn = $row['Super_ssn'];
			}
		}
	}
}
 
// Post information about the employee when the form is submitted
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // the id is hidden and can not be changed
    $Ssn = $_SESSION["Ssn"];
    // Validate form data this is similar to the create Employee file
    // Validate name
    $Fname = trim($_POST["Fname"]);

    if(empty($Fname)){
        $Fname_err = "Please enter a first name.";
    } elseif(!filter_var($Fname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Fname_err = "Please enter a valid first name.";
    } 
    $Lname = trim($_POST["Lname"]);
    if(empty($Lname)){
        $Lname_err = "Please enter a last name.";
    } elseif(!filter_var($Lname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Lname_err = "Please enter a valid last name.";
    }  
    // Validate Address
    $Address = trim($_POST["Address"]);
    if(empty($Address)){
        $Address_err = "Please enter Address.";     
    }
	
	// Validate Salary
    $Salary = trim($_POST["Salary"]);
    if(empty($Salary)){
        $Salary_err = "Please enter salary.";    	
	}
	
	// Validate Department Number
    $Dno = trim($_POST["Dno"]);
    if(empty($Dno)){
        $Dno_err = "Please enter department number.";    	
	}

    // Check input errors before inserting into database
    if(empty($Fname_err) && empty($Lname_err) && empty($Address_err) && empty($Salary_err) && empty($Dno_err)){
        // Prepare an update statement
        $sql = "UPDATE EMPLOYEE SET Fname=?, Lname=?, Address=?, Salary = ?, Dno = ? WHERE Ssn=?";
    
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssdis", $param_Fname, $param_Lname,$param_Address, $param_Salary,$param_Dno, $param_Ssn);
            
            // Set parameters
            $param_Fname = $Fname;
			$param_Lname = $Lname;            
			$param_Address = $Address;
            $param_Salary = $Salary;
            $param_Dno = $Dno;
            $param_Ssn = $Ssn;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "<center><h2>Error when updating</center></h2>";
            }
        }        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else {

    // Check existence of sID parameter before processing further
	// Form default values

	if(isset($_GET["Ssn"]) && !empty(trim($_GET["Ssn"]))){
		$_SESSION["Ssn"] = $_GET["Ssn"];

		// Prepare a select statement
		$sql1 = "SELECT * FROM EMPLOYEE WHERE Ssn = ?";
  
		if($stmt1 = mysqli_prepare($link, $sql1)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt1, "s", $param_Ssn);      
			// Set parameters
			$param_Ssn = trim($_GET["Ssn"]);

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt1)){
				$result1 = mysqli_stmt_get_result($stmt1);
				if(mysqli_num_rows($result1) == 1){

					$row = mysqli_fetch_array($result1);

					$Lname = $row['Lname'];
					$Fname = $row['Fname'];
					$Salary = $row['Salary'];
					$Bdate = $row['Bdate'];
					$Address = $row['Address'];
					$Sex = $row['Sex'];
					$Dno = $row['Dno'];
					$Super_ssn = $row['Super_ssn'];
				} else{
					// URL doesn't contain valid id. Redirect to error page
					header("location: error.php");
					exit();
				}                
			} else{
				echo "Error in SSN while updating";
			}		
		}
			// Close statement
			mysqli_stmt_close($stmt1);
        
			// Close connection
			mysqli_close($link);
	}  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
	}	
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
                <div class="col-md-12">
                    <div class="page-header">
                        <h3>Update Record for SSN =  <?php echo $_GET["Ssn"]; ?> </H3>
                    </div>
                    <p>Please edit the input values and submit to update.
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
						<div class="form-group <?php echo (!empty($Fname_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="Fname" class="form-control" value="<?php echo $Fname; ?>">
                            <span class="help-block"><?php echo $Fname_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($Lname_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="Lname" class="form-control" value="<?php echo $Lname; ?>">
                            <span class="help-block"><?php echo $Lname_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($Address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <input type="text" name="Address" class="form-control" value="<?php echo $Address; ?>">
                            <span class="help-block"><?php echo $Address_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Salary_err)) ? 'has-error' : ''; ?>">
                            <label>Salary</label>
                            <input type="text" name="Salary" class="form-control" value="<?php echo $Salary; ?>">
                            <span class="help-block"><?php echo $Salary_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($Dno_err)) ? 'has-error' : ''; ?>">
                            <label>Department Number</label>
                            <input type="number" min="1" max="20" name="Dno" class="form-control" value="<?php echo $Dno; ?>">
                            <span class="help-block"><?php echo $Dno_err;?></span>
                        </div>						
                        <input type="hidden" name="Ssn" value="<?php echo $Ssn; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
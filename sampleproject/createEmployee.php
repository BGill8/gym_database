<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Ssn = $Lname = $Fname = $Salary = $Bdate = $Bdate1 = $Address = $Sex = $Dno = $Super_ssn = "";
$Ssn_err = $Lname_err = $Fname_err = $Address_err = $Sex_err = $Salary_err = $Dno_err =$Bdate_err= "" ;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate First name
    $Fname = trim($_POST["Fname"]);
    if(empty($Fname)){
        $Fname_err = "Please enter a Fname.";
    } elseif(!filter_var($Fname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Fname_err = "Please enter a valid Fname.";
    } 
    // Validate Last name
    $Lname = trim($_POST["Lname"]);
    if(empty($Lname)){
        $Lname_err = "Please enter a Lname.";
    } elseif(!filter_var($Lname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Lname_err = "Please enter a valid Lname.";
    } 
 
    // Validate SSN
    $Ssn = trim($_POST["Ssn"]);
    if(empty($Ssn)){
        $Ssn_err = "Please enter SSN.";     
    } elseif(!ctype_digit($Ssn)){
        $Ssn_err = "Please enter a positive integer value of SSN.";
    } 
    // Validate Salary
    $Salary = trim($_POST["Salary"]);
    if(empty($Salary)){
        $Salary_err = "Please enter Salary.";     
    }
	// Validate Address
    $Address = trim($_POST["Address"]);
    if(empty($Address)){
        $Address_err = "Please enter Address.";     
    }
	// Validate Sex
    $Sex = trim($_POST["Sex"]);
    if(empty($Sex)){
        $Sex_err = "Please enter Sex.";     
    }
	// Validate Birthdate
    $Bdate = trim($_POST["Bdate"]);

    if(empty($Bdate)){
        $Bdate_err = "Please enter birthdate.";     
    }	

	// Validate Department
    $Dno = trim($_POST["Dno"]);
    if(empty($Dno)){
        $Dno_err = "Please enter a department number.";     		
	}
    // Check input errors before inserting in database
    if(empty($Ssn_err) && empty($Lname_err) && empty($Salary_err) 
				&& empty($Dno_err)&& empty($Address_err) && empty($Sex_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO EMPLOYEE (Ssn, Fname, Lname, Address, Salary, Sex, Bdate, Dno) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isssdssi", $param_Ssn, $param_Fname, $param_Lname, 
				$param_Address, $param_Salary, $param_Sex, $param_Bdate, $param_Dno);
            
            // Set parameters
			$param_Ssn = $Ssn;
            $param_Lname = $Lname;
			$param_Fname = $Fname;
			$param_Address = $Address;
			$param_Sex = $Sex;
			$param_Bdate = $Bdate;
            $param_Salary = $Salary;
            $param_Dno = $Dno;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
				    header("location: index.php");
					exit();
            } else{
                echo "<center><h4>Error while creating new employee</h4></center>";
				$Ssn_err = "Enter a unique Ssn.";
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
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add an Employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group <?php echo (!empty($Ssn_err)) ? 'has-error' : ''; ?>">
                            <label>SSN</label>
                            <input type="text" name="Ssn" class="form-control" value="<?php echo $Ssn; ?>">
                            <span class="help-block"><?php echo $Ssn_err;?></span>
                        </div>
                 
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
						<div class="form-group <?php echo (!empty($Sex_err)) ? 'has-error' : ''; ?>">
                            <label>Sex</label>
                            <input type="text" name="Sex" class="form-control" value="<?php echo $Sex; ?>">
                            <span class="help-block"><?php echo $Sex_err;?></span>
                        </div>
						                  
						<div class="form-group <?php echo (!empty($Bdate_err)) ? 'has-error' : ''; ?>">
                            <label>Birth date</label>
                            <input type="date" name="Bdate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            <span class="help-block"><?php echo $Bdate_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Dno_err)) ? 'has-error' : ''; ?>">
                            <label>Dno</label>
                            <input type="number" min ="1" max ="20" name="Dno" class="form-control" value="<?php echo $Dno; ?>">
                            <span class="help-block"><?php echo $Dno_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
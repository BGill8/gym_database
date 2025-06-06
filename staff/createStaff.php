<?php
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$FirstName = $LastName = $Email = $Phone = $Position = "";
$FirstName_err = $LastName_err = $Email_err = $Phone_err = $Position_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate First Name
    $FirstName = trim($_POST["FirstName"]);
    if(empty($FirstName)){
        $FirstName_err = "Please enter a first name.";
    } elseif(!filter_var($FirstName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $FirstName_err = "Please enter a valid first name.";
    }
    
    // Validate Last Name
    $LastName = trim($_POST["LastName"]);
    if(empty($LastName)){
        $LastName_err = "Please enter a last name.";
    } elseif(!filter_var($LastName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $LastName_err = "Please enter a valid last name.";
    }
    
    // Validate Email
    $Email = trim($_POST["Email"]);
    if(empty($Email)){
        $Email_err = "Please enter an email.";
    } elseif(!filter_var($Email, FILTER_VALIDATE_EMAIL)){
        $Email_err = "Please enter a valid email address.";
    }
    
    // Validate Phone
    $Phone = trim($_POST["Phone"]);
    if(empty($Phone)){
        $Phone_err = "Please enter a phone number.";
    }
    
    // Validate Position
    $Position = trim($_POST["Position"]);
    if(empty($Position)){
        $Position_err = "Please select a position.";
    }

    // Check input errors before inserting in database
    if(empty($FirstName_err) && empty($LastName_err) && empty($Email_err) && 
       empty($Phone_err) && empty($Position_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Staff (FirstName, LastName, Email, Phone, Position) 
                VALUES ('$FirstName', '$LastName', '$Email', '$Phone', '$Position')";
         
        if(mysqli_query($link, $sql)){
            // Records created successfully. Redirect to staff page
            header("location: viewStaff.php");
            exit();
        } else{
            echo "<center><h4>Error while creating new staff member: " . mysqli_error($link) . "</h4></center>";
            $StaffID_err = "Enter a unique Staff ID.";
        }
    }
    

    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Staff - Benny's Iron Dam</title>
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
                        <h2>Create New Staff Member</h2>
                    </div>
                    <p>Please fill this form and submit to add a staff member to the gym database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        
                        <div class="form-group <?php echo (!empty($FirstName_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="FirstName" class="form-control" value="<?php echo $FirstName; ?>">
                            <span class="help-block"><?php echo $FirstName_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($LastName_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="LastName" class="form-control" value="<?php echo $LastName; ?>">
                            <span class="help-block"><?php echo $LastName_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($Email_err)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <input type="email" name="Email" class="form-control" value="<?php echo $Email; ?>">
                            <span class="help-block"><?php echo $Email_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($Phone_err)) ? 'has-error' : ''; ?>">
                            <label>Phone</label>
                            <input type="text" name="Phone" class="form-control" value="<?php echo $Phone; ?>">
                            <span class="help-block"><?php echo $Phone_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($Position_err)) ? 'has-error' : ''; ?>">
                            <label>Position</label>
                            <select name="Position" class="form-control">
                                <option value="">Select Position</option>
                                <option value="Instructor" <?php echo ($Position == 'Instructor') ? 'selected' : ''; ?>>Instructor</option>
                                <option value="Trainer" <?php echo ($Position == 'Trainer') ? 'selected' : ''; ?>>Trainer</option>
                                <option value="Manager" <?php echo ($Position == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                                <option value="Receptionist" <?php echo ($Position == 'Receptionist') ? 'selected' : ''; ?>>Receptionist</option>
                                <option value="Yoga Instructor" <?php echo ($Position == 'Yoga Instructor') ? 'selected' : ''; ?>>Yoga Instructor</option>
                                <option value="Nutritionist" <?php echo ($Position == 'Nutritionist') ? 'selected' : ''; ?>>Nutritionist</option>
                                <option value="Swimming Coach" <?php echo ($Position == 'Swimming Coach') ? 'selected' : ''; ?>>Swimming Coach</option>
                                <option value="Boxing Coach" <?php echo ($Position == 'Boxing Coach') ? 'selected' : ''; ?>>Boxing Coach</option>
                                <option value="Barista" <?php echo ($Position == 'Barista') ? 'selected' : ''; ?>>Barista</option>
                            </select>
                            <span class="help-block"><?php echo $Position_err;?></span>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../index.php" class="btn btn-default">Cancel</a>
                        <a href="viewStaff.php" class="btn btn-info">View All Staff</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
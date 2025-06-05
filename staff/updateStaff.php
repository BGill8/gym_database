<?php
session_start();
require_once "../config.php";
 
// Define variables and initialize with empty values
$FirstName = $LastName = $Email = $Phone = $Position = "";
$FirstName_err = $LastName_err = $Email_err = $Phone_err = $Position_err = "";

// Get StaffID from URL
if(isset($_GET["StaffID"]) && !empty(trim($_GET["StaffID"]))){
    $_SESSION["StaffID"] = $_GET["StaffID"];
}

$StaffID = $_SESSION["StaffID"] ?? null;

// Form default values - load current staff data
if($StaffID && $_SERVER["REQUEST_METHOD"] != "POST"){
    $sql1 = "SELECT * FROM Staff WHERE StaffID = $StaffID";
    $result1 = mysqli_query($link, $sql1);
    
    if($result1 && mysqli_num_rows($result1) > 0){
        $row = mysqli_fetch_array($result1);
        $FirstName = $row['FirstName'];
        $LastName = $row['LastName'];
        $Email = $row['Email'];
        $Phone = $row['Phone'];
        $Position = $row['Position'];
    } else {
        header("location: ../utilities/error.php");
        exit();
    }
}

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

    // Check input errors before updating database
    if(empty($FirstName_err) && empty($LastName_err) && empty($Email_err) && empty($Phone_err) && empty($Position_err)){
        
        // Update statement
        $sql = "UPDATE Staff SET FirstName='$FirstName', LastName='$LastName', Email='$Email', Phone='$Phone', Position='$Position' WHERE StaffID=$StaffID";
    
        if(mysqli_query($link, $sql)){
            // Records updated successfully. Redirect to staff page
            header("location: viewStaff.php");
            exit();
        } else{
            echo "<center><h2>Error when updating staff member: " . mysqli_error($link) . "</center></h2>";
        }
    }
    
    // Close connection
    mysqli_close($link);
}

// Check if StaffID exists
if(!$StaffID) {
    header("location: ../utilities/error.php");
    exit();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Staff - Benny's Iron Dam</title>
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
                        <h3>Update Record for Staff ID = <?php echo $StaffID; ?></h3>
                    </div>
                    <p>Please edit the input values and submit to update.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?StaffID=" . $StaffID; ?>" method="post">
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
                        <a href="viewStaff.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
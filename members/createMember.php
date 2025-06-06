<?php
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$FirstName = $LastName = $Email = $Phone = $Address = $DateOfBirth = $MembershipTypeID = "";
$FirstName_err = $LastName_err = $Email_err = $Phone_err = $Address_err = $DateOfBirth_err = $MembershipTypeID_err = "";
 
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
    
    // Validate Address
    $Address = trim($_POST["Address"]);
    if(empty($Address)){
        $Address_err = "Please enter an address.";
    }
    
    // Validate Date of Birth
    $DateOfBirth = trim($_POST["DateOfBirth"]);
    if(empty($DateOfBirth)){
        $DateOfBirth_err = "Please enter date of birth.";
    }
    
    // Validate Membership Type
    $MembershipTypeID = trim($_POST["MembershipTypeID"]);
    if(empty($MembershipTypeID)){
        $MembershipTypeID_err = "Please select a membership type.";
    }

    // Check input errors before inserting in database
    if(empty($FirstName_err) && empty($LastName_err) && empty($Email_err) && 
       empty($Phone_err) && empty($Address_err) && empty($DateOfBirth_err) && empty($MembershipTypeID_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Member (FirstName, LastName, Email, Phone, Address, DateOfBirth, MembershipTypeID) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssi", $param_FirstName, $param_LastName, 
                $param_Email, $param_Phone, $param_Address, $param_DateOfBirth, $param_MembershipTypeID);
            
            // Set parameters
            $param_FirstName = $FirstName;
            $param_LastName = $LastName;
            $param_Email = $Email;
            $param_Phone = $Phone;
            $param_Address = $Address;
            $param_DateOfBirth = $DateOfBirth;
            $param_MembershipTypeID = $MembershipTypeID;
            
            // Attempt to execute the prepared statement
            if (!mysqli_stmt_execute($stmt)) {
                echo "<center><h4 style='color:red;'>SQL Error: " . mysqli_stmt_error($stmt) . "</h4></center>";
            } else {
                    header("location: ../index.php");
                exit();
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
    <title>Create Member - Benny's Iron Dam</title>
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
                        <h2>Create New Member</h2>
                    </div>
                    <p>Please fill this form and submit to add a member to the gym database.</p>
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
                        
                        <div class="form-group <?php echo (!empty($Address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <input type="text" name="Address" class="form-control" value="<?php echo $Address; ?>">
                            <span class="help-block"><?php echo $Address_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($DateOfBirth_err)) ? 'has-error' : ''; ?>">
                            <label>Date of Birth</label>
                            <input type="date" name="DateOfBirth" class="form-control" value="<?php echo $DateOfBirth; ?>">
                            <span class="help-block"><?php echo $DateOfBirth_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($MembershipTypeID_err)) ? 'has-error' : ''; ?>">
                            <label>Membership Type</label>
                            <select name="MembershipTypeID" class="form-control">
                                <option value="">Select Membership Type</option>
                                <?php
                                $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                                if (!$conn) {
                                    die('Could not connect: ' . mysqli_error());
                                }
                                $sql = "SELECT MembershipTypeID, TypeName, Price FROM MembershipType ORDER BY TypeName";
                                $result = mysqli_query($conn, $sql);
                                if (!$result) {
                                    die("Query to show membership types failed");
                                }
                                while($row = mysqli_fetch_array($result)) {
                                    $selected = ($MembershipTypeID == $row['MembershipTypeID']) ? 'selected' : '';
                                    echo "<option value='{$row['MembershipTypeID']}' $selected>{$row['TypeName']} - \${$row['Price']}</option>";
                                }
                                mysqli_free_result($result);
                                mysqli_close($conn);
                                ?>
                            </select>
                            <span class="help-block"><?php echo $MembershipTypeID_err;?></span>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
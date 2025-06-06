<?php
session_start();
require_once "../config.php";

// Get StaffID from URL or session
if(isset($_GET["StaffID"]) && !empty(trim($_GET["StaffID"]))){
    $_SESSION["StaffID"] = $_GET["StaffID"];
}

$staffName = "";
$StaffID = $_SESSION["StaffID"] ?? null;

if ($StaffID) {
    // Get staff name for display
    $sql_name = "SELECT FirstName, LastName, Position FROM Staff WHERE StaffID = $StaffID";
    $result_name = mysqli_query($link, $sql_name);
    if($result_name && mysqli_num_rows($result_name) > 0) {
        $row_name = mysqli_fetch_array($result_name);
        $staffName = $row_name['FirstName'] . " " . $row_name['LastName'] . " (" . $row_name['Position'] . ")";
    }
}

// Handle POST request (delete confirmation)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $StaffID) {
    
    // Check if staff member teaches any classes
    $class_check = "SELECT COUNT(*) as class_count FROM FitnessClass WHERE StaffID = $StaffID";
    $class_result = mysqli_query($link, $class_check);
    $class_row = mysqli_fetch_array($class_result);
    
    if($class_row['class_count'] > 0) {
        echo "<div class='alert alert-warning'>Cannot delete staff member: This staff member is currently assigned to teach " . $class_row['class_count'] . " fitness class(es). Please reassign or delete these classes first.</div>";
    } else {
        // Safe to delete - no classes assigned
        $sql = "DELETE FROM Staff WHERE StaffID = $StaffID";
        $result = mysqli_query($link, $sql);
        
        if ($result) {
            // Success - redirect to staff page
            header("location: viewStaff.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error deleting staff member: " . mysqli_error($link) . "</div>";
        }
    }
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
    <title>Delete Staff - Benny's Iron Dam</title>
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
                        <h1>Delete Staff Record</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?StaffID=" . $StaffID; ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <p>Are you sure you want to delete the record for Staff ID <?php echo $StaffID; ?>
                            <?php if(!empty($staffName)) echo " - " . $staffName; ?>?</p>
                            <p><strong>Note:</strong> Staff members who are teaching classes cannot be deleted. You must reassign their classes first.</p>
                            <br>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="viewStaff.php" class="btn btn-default">No</a>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
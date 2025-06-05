<?php
session_start();
require_once "../config.php";

// Get MemberID from URL or session
if(isset($_GET["MemberID"]) && !empty(trim($_GET["MemberID"]))){
    $_SESSION["MemberID"] = $_GET["MemberID"];
}

$memberName = "";
$MemberID = $_SESSION["MemberID"] ?? null;

if ($MemberID) {
    // Get member name for display
    $sql_name = "SELECT FirstName, LastName FROM Member WHERE MemberID = $MemberID";
    $result_name = mysqli_query($link, $sql_name);
    if($result_name && mysqli_num_rows($result_name) > 0) {
        $row_name = mysqli_fetch_array($result_name);
        $memberName = $row_name['FirstName'] . " " . $row_name['LastName'];
    }
}

// Handle POST request (delete confirmation)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $MemberID) {
    
    // Delete in proper order to avoid foreign key constraints
    
    // 1. Delete workout records
    $sql1 = "DELETE FROM Workout WHERE MemberID = $MemberID";
    mysqli_query($link, $sql1);
    
    // 2. Delete class registrations
    $sql2 = "DELETE FROM MemberClass WHERE MemberID = $MemberID";
    mysqli_query($link, $sql2);
    
    // 3. Delete the member
    $sql3 = "DELETE FROM Member WHERE MemberID = $MemberID";
    $result = mysqli_query($link, $sql3);
    
    if ($result) {
        // Success - redirect to main page
        header("location: ../index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error deleting member: " . mysqli_error($link) . "</div>";
    }
}

// Check if MemberID exists
if(!$MemberID) {
    header("location: ../utilities/error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Member - Benny's Iron Dam</title>
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
                        <h1>Delete Member Record</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?MemberID=" . $MemberID; ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <p>Are you sure you want to delete the record for Member ID <?php echo $MemberID; ?>
                            <?php if(!empty($memberName)) echo " - " . $memberName; ?>?</p>
                            <p><strong>Warning:</strong> This will also delete all workout records and class registrations for this member.</p>
                            <br>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="../index.php" class="btn btn-default">No</a>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
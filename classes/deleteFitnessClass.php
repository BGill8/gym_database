<?php
session_start();
require_once "../config.php";

// Get ClassID from URL or session
if(isset($_GET["ClassID"]) && !empty(trim($_GET["ClassID"]))){
    $_SESSION["ClassID"] = $_GET["ClassID"];
}

$className = "";
$enrollmentCount = 0;
$ClassID = $_SESSION["ClassID"] ?? null;

if ($ClassID) {
    // Get class details for display
    $sql_class = "SELECT fc.ClassName, fc.Description, s.FirstName, s.LastName, s.Position 
                  FROM FitnessClass fc 
                  LEFT JOIN Staff s ON fc.StaffID = s.StaffID 
                  WHERE fc.ClassID = $ClassID";
    $result_class = mysqli_query($link, $sql_class);
    if($result_class && mysqli_num_rows($result_class) > 0) {
        $row_class = mysqli_fetch_array($result_class);
        $className = $row_class['ClassName'];
        $instructorName = $row_class['FirstName'] . " " . $row_class['LastName'];
    }
    
    // Get enrollment count
    $sql_enrollment = "SELECT COUNT(*) as enrolled FROM MemberClass WHERE ClassID = $ClassID";
    $result_enrollment = mysqli_query($link, $sql_enrollment);
    if($result_enrollment) {
        $row_enrollment = mysqli_fetch_array($result_enrollment);
        $enrollmentCount = $row_enrollment['enrolled'];
    }
}

// Handle POST request (delete confirmation)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $ClassID) {
    
    // Delete in proper order to avoid foreign key constraints
    
    // 1. Delete all member enrollments for this class
    $sql1 = "DELETE FROM MemberClass WHERE ClassID = $ClassID";
    mysqli_query($link, $sql1);
    
    // 2. Delete the fitness class
    $sql2 = "DELETE FROM FitnessClass WHERE ClassID = $ClassID";
    $result = mysqli_query($link, $sql2);
    
    if ($result) {
        // Success - redirect to classes page
        header("location: viewAllClasses.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error deleting fitness class: " . mysqli_error($link) . "</div>";
    }
}

// Check if ClassID exists
if(!$ClassID) {
    header("location: ../utilities/error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Fitness Class - Benny's Iron Dam</title>
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
                        <h1>Delete Fitness Class</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?ClassID=" . $ClassID; ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <p>Are you sure you want to delete the fitness class:</p>
                            <p><strong>Class ID:</strong> <?php echo $ClassID; ?></p>
                            <p><strong>Class Name:</strong> <?php echo $className; ?></p>
                            <?php if(isset($instructorName)): ?>
                            <p><strong>Instructor:</strong> <?php echo $instructorName; ?></p>
                            <?php endif; ?>
                            
                            <?php if($enrollmentCount > 0): ?>
                            <div class="alert alert-warning" style="margin-top: 15px;">
                                <strong>Warning:</strong> This class currently has <?php echo $enrollmentCount; ?> enrolled member(s). 
                                Deleting this class will also remove all member enrollments.
                            </div>
                            <?php else: ?>
                            <p><em>No members are currently enrolled in this class.</em></p>
                            <?php endif; ?>
                            
                            <br>
                            <input type="submit" value="Yes, Delete Class" class="btn btn-danger">
                            <a href="viewAllClasses.php" class="btn btn-default">No, Keep Class</a>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<?php
//Group #3 Gabriel de Leon, James Nguyen, Stanley Eng, Brandon Gill
session_start();
ob_start();
$ClassID = $_SESSION["ClassID"] = $_GET["ClassID"] ?? $_SESSION["ClassID"] ?? "";

// Include config file
require_once "../config.php";
?>

<?php 
$SQL_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate Member ID
    $MemberID = trim($_POST["MemberID"]);
    if(empty($MemberID)){
        $MemberID_err = "Please select a member.";
    }
    
    // Validate the Class ID
    if(empty($ClassID)){
        $SQL_err = "No Class ID provided.";     
    }

    // Check input errors before inserting in database
    if(empty($MemberID_err) && empty($SQL_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO MemberClass (MemberID, ClassID) 
                VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, 'ii', $param_MemberID, $param_ClassID);
            
            // Set parameters
            $param_MemberID = $MemberID;
            $param_ClassID = $ClassID;
        
            if (!mysqli_stmt_execute($stmt)) {
                $SQL_err = "<center><h4 style='color:red;'>SQL Error: " . mysqli_stmt_error($stmt) . "</h4></center>";
            } else {
                header("location: viewClassMembers.php?ClassID=$ClassID");
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
    <title>Enroll Member - Benny's Iron Dam</title>
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
                        <h3>Enroll Member </h3>
                        <h4><?php?> Class ID = <?php echo $ClassID; ?></h4>
                    </div>
                
<?php
echo $SQL_err;
?>    

    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
        
        <div class="form-group <?php echo (!empty($MemberID_err)) ? 'has-error' : ''; ?>">
            <label>Members</label>
            <select name="MemberID" class="form-control">
                <?php
                $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                if (!$conn) {
                    die('Could not connect: ' . mysqli_error());
                }
                $sql = "SELECT MemberID, FirstName, LastName FROM Member WHERE MemberID NOT IN (SELECT MemberID FROM MemberClass WHERE ClassID = $ClassID) ORDER BY MemberID ASC";
                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    die("Query to show members failed");
                }
                $num_row = mysqli_num_rows($result);    

                for($i=0; $i<$num_row; $i++) {
                    $member = mysqli_fetch_row($result);
                    $selected = ($MemberID == $member[0]) ? 'selected' : '';
                    echo "<option value='$member[0]' $selected>$member[1] $member[2]</option>";
                }
                mysqli_free_result($result);
                mysqli_close($conn);
                ?>
            </select>    
            <span class="help-block"><?php echo $MemberID_err; ?></span>
        </div>
        
        <div>
            <input type="submit" class="btn btn-success pull-left" value="Enroll Member">    
            &nbsp;
            <a href="viewClassMembers.php?ClassID=<?php echo $ClassID; ?>" class="btn btn-primary">Back to Members in the class</a>
            &nbsp;
            <a href="../index.php" class="btn btn-default">Back to Members</a>
        </div>
    </form>
</body>
</html>
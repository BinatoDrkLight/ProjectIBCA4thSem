<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
<?php
    include_once('../../All/allHeader.php'); 
?>
    <div class="profileColH">
        <div class="profilePicH">

        </div>
        <div class="profileCateH">
            <h1>Passenger</h1>
        </div>
    </div>

    <?php
        //Connect to database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        //Prepared statement function.
        include_once('../../All/allPreparedStatement.php');

        //Params
        $lr_id = $_SESSION['Lr_id'];
        $queryProfile = "SELECT CONCAT(P_fName, ' ', P_sName) AS P_full_name, P_gmail, P_phone_no, Lr_user
                        FROM loginregister AS lr 
                        LEFT JOIN passenger AS p 
                        ON lr.P_id = p.P_id
                        LEFT JOIN p_phone_nos AS ph
                        ON p.P_id = ph.P_id
                        WHERE Lr_id = ?;";
        $typeProfile = "s";
        $paramsProfile = [$lr_id];

        //Call prepared statement function
        $resArrProfile = preparedStmt($queryProfile, $con, $typeProfile, $paramsProfile);
        $stmtProfile = $resArrProfile['stmt'];

        //Get result from query
        $resultProfileData = mysqli_stmt_get_result($stmtProfile);

        //Check if anything is retrieved.
        if($row = mysqli_fetch_assoc($resultProfileData)){
            $fullNameD = $row['P_full_name'];
            $gmailD = $row['P_gmail'];
            $phoneD = $row['P_phone_no'];
            $userD = $row['Lr_user'];
        }
        $con->close();
        mysqli_stmt_close($stmtProfile);
    ?>

    <div class="profileInfoH">
        <div class="profileInfoLeftH">
            <p>Name: <?php echo $fullNameD ?> </p>
            <p>Age: 20</p>
            <p>Gmail: <?php echo $gmailD ?> </p>
            <p>Phone no: <?php echo $phoneD ?> </p>
            <p>User: <?php echo $userD ?> </p>
        </div>
        <button class="profileMoreH">More</button><br><br>
        <button class="profileLogoutH"><a href="../../All/login/loginH.php">Logout</a></button>
    </div> 
</body>
</html>
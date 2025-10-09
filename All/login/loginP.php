<?php
    session_start();
    //Initialize variable.
    $usernameP = $passwordP = "";

    //If submitted using Log In button.
    if(isset($_POST['submitH'])){
        //If submitted using post method.
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usernameP = $_POST['usernameH'];
            $passwordP = $_POST['passwordH']; 
        
            //Connect to database.
            include_once('../../All/allDatabaseConnection.php');
            $con = dbConnection();

            //Prepare Statement function
            include_once('../../All/allPreparedStatement.php');

            //Validate if the username and email is correct.
            $queryUser = "SELECT lr.Lr_id, lr.Lr_password, lr.Lr_user, d.B_id
                        FROM loginregister AS lr
                        LEFT JOIN p_phone_nos AS p
                        ON lr.P_id = p.P_id
                        LEFT JOIN driver AS d
                        ON d.D_id = lr.D_id
                        WHERE (lr.Lr_gmail = ? OR p.P_phone_no = ?);";
            $typesUser = "ss";
            $paramsUser = [$usernameP, $usernameP];

            // Call the preparedStmt function
            $resArrUser = preparedStmt($queryUser, $con, $typesUser, $paramsUser);
            $stmtUser = $resArrUser['stmt'];

            $resultUserData = mysqli_stmt_get_result($stmtUser);
            //Check if anything is retrieved.
            if($row = mysqli_fetch_assoc($resultUserData)){
                $lrId = $row['Lr_id'];
                $hashPassD = $row['Lr_password'];
                $lrUser = $row['Lr_user'];
                $bId = $row['B_id'];

                //Compare the passwords
                if(password_verify($passwordP, $hashPassD)){
                    $_SESSION['Lr_id'] = $lrId;
                    $con->close();
                    mysqli_stmt_close($stmtUser);
                    if($lrUser === "Passenger"){
                        header("Location: ../../Passenger/home/home.php");
                        exit();
                    } elseif ($lrUser === "Driver"){
                        $_SESSION['B_id'] = $bId;
                        header("Location: ../../Driver/track/trackH.php");
                        exit();
                    }
                }
            }
            $con->close();
            mysqli_stmt_close($stmtUser);
            $_SESSION['logError'] = "Invalid Credentials";
            header("Location: loginH.php");
        }  
    }
 
?>
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
            $queryUser = "SELECT La_id, La_password, La_gmail, La_role, A_id
                        FROM loginadmin
                        WHERE (La_gmail = ?);";
            $typesUser = "s";
            $paramsUser = [$usernameP];

            // Call the preparedStmt function
            $resArrUser = preparedStmt($queryUser, $con, $typesUser, $paramsUser);
            $stmtUser = $resArrUser['stmt'];

            $resultUserData = mysqli_stmt_get_result($stmtUser);
            //Check if anything is retrieved.
            if($row = mysqli_fetch_assoc($resultUserData)){
                $la_id = $row['La_id'];
                $hashPassD = $row['La_password'];
                $la_role = $row['La_role'];
                $a_id = $row['A_id'];
                // $bId = $row['B_id'];

                //Compare the passwords
                if(password_verify($passwordP, $hashPassD)){
                    $con->close();
                    mysqli_stmt_close($stmtUser);
                    if($la_role === "Admin"){
                        $_SESSION['A_id'] = $a_id;
                        header("Location: ../welcomeAdmin/welcomeAdminH.php");
                        exit();
                    } elseif ($la_role === "SuperAdmin"){
                        $_SESSION['La_id'] = $la_id;
                        header("Location: ../../SuperAdmin/welcomeSuperAdmin/welcomeSuperAdminH.php");
                        exit();
                    }
                }
            }
            $con->close();
            mysqli_stmt_close($stmtUser);
            header("Location: loginAdminH.php");
            $_SESSION['logError'] = "Invalid Credentials";
        }  
    }
 
?>
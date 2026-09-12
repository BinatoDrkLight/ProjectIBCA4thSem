<?php
    session_start();

    //Initialize variable.
    $fullNameP = $secondNameP = $gmailP = $passwordP = $rePasswordP = 
    $phoneP = $isValid = $hashPass = "";

    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $firstNameP = trim($_POST['firstNameH'] ?? '');
        $secondNameP = trim($_POST['secondNameH'] ?? '');
        $gmailP = trim($_POST['gmailH'] ?? '');
        $passwordP = $_POST['passwordH'] ?? '';
        $rePasswordP = $_POST['rePasswordH'] ?? '';
        $phoneP = trim($_POST['phoneH'] ?? '');

        $isValid = true;

        //Connect to database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        //Prepared statement function
        include_once('../../All/allPreparedStatement.php');

        //Validate firstname.
        if(!preg_match("/^[a-zA-Z]{3,}/", $firstNameP)){
            $_SESSION['firstNameError'] = "Invalid first Name.";
            $isValid = false;
        }

        //Validate secondname.
        if(!preg_match("/^[a-zA-Z]{3,}/", $secondNameP)){
            $_SESSION['secondNameError'] = "Invalid second name.";
            $isValid = false;
        }            

        //Validate gmail.
        if(!filter_var($gmailP, FILTER_VALIDATE_EMAIL)){
            $_SESSION['gmailError'] = "Invalid gmail.";
            $isValid = false;
        } else {
            // Prepared statement for checking existing gmail
            $stmtGmailRes = preparedStmt("SELECT Lr_gmail FROM loginregister WHERE Lr_gmail = ?", $con, "s", [$gmailP]);
            $stmtGmail = $stmtGmailRes['stmt'];
            $resGmail = mysqli_stmt_get_result($stmtGmail);
            if(mysqli_num_rows($resGmail) > 0) {
                $_SESSION['gmailError'] = "Gmail already in use.";
                $isValid = false;
            }
            mysqli_stmt_close($stmtGmail);
        }

        //Validate password.
        if(!preg_match("/^(?=(.*[a-zA-Z]))(?=(.*\d))(?=(.*\w)).{8,}$/", $passwordP)){
            $_SESSION['passwordError'] = "Use proper password";
            $isValid = false;
        }
        
        //Check if passwords match.
        if($passwordP !== $rePasswordP || empty($passwordP)){
            $_SESSION['rePasswordError'] = "Passwords not match";
            $isValid = false;
        }

        //Validate phone number.
        if(!preg_match("/^9[\d]{9}$/", $phoneP)){
            $_SESSION['phoneError'] = "Invalid phone number";
            $isValid = false;
        } else {
            // Prepared statement for checking existing phone
            $stmtPhoneRes = preparedStmt("SELECT P_phone_no FROM p_phone_nos WHERE P_phone_no = ?", $con, "s", [$phoneP]);
            $stmtPhone = $stmtPhoneRes['stmt'];
            $resPhone = mysqli_stmt_get_result($stmtPhone);
            if(mysqli_num_rows($resPhone) > 0) {
                $_SESSION['phoneError'] = "Phone no already in use";
                $isValid = false;
            }
            mysqli_stmt_close($stmtPhone);
        }

        //If validated.
        if($isValid){                
            // Query to insert into passenger table
            $queryPassenger = "INSERT INTO passenger(P_fName, P_sName, P_gmail) VALUES (?, ?, ?);";
            $typesPassenger = "sss";
            $paramsPassenger = [$firstNameP, $secondNameP, $gmailP];

            $resArrPassenger = preparedStmt($queryPassenger, $con, $typesPassenger, $paramsPassenger);
            $stmtPassenger = $resArrPassenger['stmt'];
            $resultPassenger = $resArrPassenger['res'];

            if (!$resultPassenger) {
                mysqli_stmt_close($stmtPassenger);
                $con->close();
                die("Couldn't insert into passenger table.");
            }

            // Get the inserted ID
            $p_id = mysqli_insert_id($con);
            mysqli_stmt_close($stmtPassenger);

            // Query to insert phone no in p_phone_nos table
            $queryPhone = "INSERT INTO p_phone_nos(P_id, P_phone_no) VALUES (?, ?);";
            $typesPhone = "is";
            $paramsPhone = [$p_id, $phoneP];

            $resArrPhone = preparedStmt($queryPhone, $con, $typesPhone, $paramsPhone);
            $stmtPhone = $resArrPhone['stmt'];
            $resultPhone = $resArrPhone['res'];

            if(!$resultPhone){
                mysqli_stmt_close($stmtPhone);
                $con->close();
                die("Couldn't insert into phone table.");
            }
            mysqli_stmt_close($stmtPhone);
            
            // Create hash password
            $hashPass = password_hash($passwordP, PASSWORD_DEFAULT);

            // Query to insert into loginregister table
            $queryLoginRegister = "INSERT INTO loginregister(Lr_username, Lr_gmail, Lr_password, P_id) VALUES (?, ?, ?, ?);";
            $typesLoginRegister = "sssi";
            $paramsLoginRegister = [$gmailP, $gmailP, $hashPass, $p_id];

            $resArrLoginRegister = preparedStmt($queryLoginRegister, $con, $typesLoginRegister, $paramsLoginRegister);
            $stmtLoginRegister = $resArrLoginRegister['stmt'];
            $resultLoginRegister = $resArrLoginRegister['res'];

            if(!$resultLoginRegister){
                mysqli_stmt_close($stmtLoginRegister);
                $con->close();
                die("Couldn't insert into loginregister table.");
            }

            mysqli_stmt_close($stmtLoginRegister);
            $con->close();
            header("Location: ../../All/login/loginH.php");
            exit();
        } else {
            $con->close();
            header("Location: registerPassengerH.php");
            exit();
        }
    }
?>
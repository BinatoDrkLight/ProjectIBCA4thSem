<?php
    session_start();

    //Initialize variable.
    $fullNameP = $secondNameP = $gmailP = $passwordP = $rePasswordP = 
    $phoneP = $isValid = $hashPass = "";


    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $firstNameP = $_POST['firstNameH'];
        $secondNameP = $_POST['secondNameH'];
        $gmailP = $_POST['gmailH'];
        $passwordP = $_POST['passwordH'];
        $rePasswordP = $_POST['rePasswordH'];
        $phoneP = $_POST['phoneH'];

        $isValid = true;

        //Connect to database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        //Query to select if gmail already exists in database.
        $sqlGmail = "SELECT Lr_gmail FROM loginregister where Lr_gmail = '$gmailP'";
        //Execute the query and store it in a variable.
        $resGmail = mysqli_query($con, $sqlGmail);


        //Query to select if gmail already exists in database.
        $sqlPhone = "SELECT P_phone_no FROM p_phone_nos where P_phone_no = '$phoneP'";
        //Execute the query and store it in a variable.
        $resPhone = mysqli_query($con, $sqlPhone);

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
        } 
        //Check if gmail already exists.
            elseif(mysqli_num_rows($resGmail) > 0) {
                $_SESSION['gmailError'] = "Gmail already in use.";
                $isValid = false;
        }

        //Validate password.
        if(!preg_match("/^(?=(.*[a-zA-Z]))(?=(.*\d))(?=(.*\w)).{8,}$/", $passwordP)){
            $_SESSION['passwordError'] = "Use proper password";
            $isValid = false;
        }
        
        //Check if passwords match.
        if(!($passwordP == $rePasswordP) || ($passwordP == "" || $rePasswordP == "")){
            $_SESSION['rePasswordError'] = "Passwords not match";
            $isValid = false;
        }

        //Validate phone number.
        if(!preg_match("/^9[\d]{9}/", $phoneP)){
            $_SESSION['phoneError'] = "Invalid phone number";
            $isValid = false;
        }
        //Check if password already exists.
            elseif(mysqli_num_rows($resPhone) > 0) {
                $_SESSION['phoneError'] = "Phone no already in use";
                $isValid = false;
        }

        //If validated.
        if($isValid){                
            //Prepared statement function
            Include_once('../../All/allPreparedStatement.php');
            $con = dbConnection();

            // Query to insert into passenger table
            $queryPassenger = "INSERT INTO passenger(P_fName, P_sName, P_gmail) VALUES (?, ?, ?);";
            $typesPassenger = "sss";
            $paramsPassenger = [$firstNameP, $secondNameP, $gmailP];

            // Call the preparedStmt function
            $resArrPassenger = preparedStmt($queryPassenger, $con, $typesPassenger, $paramsPassenger);
            $stmtPassenger = $resArrPassenger['stmt'];
            $resultPassenger = $resArrPassenger['res'];

            // Check execution result
            if (!$resultPassenger) {
                echo "Couldn't insert into passenger table";
                mysqli_stmt_close($stmtPassenger);
                die;
            } else {
                echo "Insertion in passenger table successful";
            }

            // Get the inserted ID
            $p_id = mysqli_insert_id($con);
            // Close the statement
            mysqli_stmt_close($stmtPassenger);
            //-----------------------------------------------------------------------------------------------------------------------

            //Query to insert phone no in p_phone_nos table
            $queryPhone = "INSERT INTO p_phone_nos(P_id, P_phone_no) VALUES (?, ?);";
            $typesPhone = "is";
            $paramsPhone = [$p_id, $phoneP];

            //Call the preparedStmt function
            $resArrPhone = preparedStmt($queryPhone, $con, $typesPhone, $paramsPhone);
            $stmtPhone = $resArrPhone['stmt'];
            $resultPhone = $resArrPhone['res'];

            //Check if seccess
            if(!$resultPhone){
                echo "Couldn't insert into phone table";
                mysqli_stmt_close($stmtPhone);
                die;
            } else {
                echo "Insertion in phone table successful";
            }
            mysqli_stmt_close($stmtPhone);

           //-----------------------------------------------------------------------------------------------------------------------
            
            //Create hash password
            $hashPass = password_hash($passwordP, PASSWORD_DEFAULT);
            //Query to insert into loginregister table
            $queryLoginRegister = "INSERT INTO loginregister(Lr_username, Lr_gmail, Lr_password, P_id) VALUES (?, ?, ?, ?);";
            $typesLoginRegister = "sssi";
            $paramsLoginRegister = [$gmailP, $gmailP, $hashPass, $p_id];

            //Call the preparedStmt function
            $resArrLoginRegister = preparedStmt($queryLoginRegister, $con, $typesLoginRegister, $paramsLoginRegister);
            $stmtLoginRegister = $resArrLoginRegister['stmt'];
            $resultLoginRegister = $resArrLoginRegister['res'];

            //Check if success
            if(!$resultLoginRegister){
                echo "Couldn't insert into the loginregister table";
                mysqli_stmt_close($stmtLoginRegister);
                die;
            } else {
                echo "Insertion to loginregister successful! <br>";
                mysqli_stmt_close($stmtLoginRegister);
                $con->close();
                header("Location: ../../All/login/loginH.php");
            }
        } else {
            // mysqli_stmt_close($stmtLoginRegister);
            $con->close();
            header("Location: registerPassengerH.php");
        }
    }
 
?>
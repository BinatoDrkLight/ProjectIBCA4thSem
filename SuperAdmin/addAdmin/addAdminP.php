<?php
    session_start();

    //Initialize variable.
    $firstNameP = $secondNameP = $gmailP = $passwordP = $rePasswordP = $regAdmAddressP = $regAdmAreaP = $isValid = $hashPass = "";
    $hashPass = password_hash('Sup@rAdm1n23', PASSWORD_DEFAULT);

    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $firstNameP = $_POST['firstNameH'];
        $secondNameP = $_POST['secondNameH'];
        $gmailP = $_POST['gmailH'];
        $passwordP = $_POST['passwordH'];
        $rePasswordP = $_POST['rePasswordH'];
        $regAdmAddressP = $_POST['addressH'];
        $regAdmAreaP = $_POST['areaH'];

        $isValid = true;

        //Connect to database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        //Query to select if gmail already exists in database.
        $sqlGmail = "SELECT La_gmail FROM loginadmin where La_gmail = '$gmailP'";
        //Execute the query and store it in a variable.
        $resGmail = mysqli_query($con, $sqlGmail);

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

        //Check the address length
        if(strlen($regAdmAddressP) > 20){
            $_SESSION['addressError'] = "Length doesn't match";
            $isValid = false;
        }

        //Check the area length
        if(strlen($regAdmAreaP) > 40){
            $_SESSION['areaError'] = "Length doesn't match";
            $isValid = false;
        }

        //If validated.
        if($isValid){                
            //Prepared statement function
            Include_once('../../All/allPreparedStatement.php');
            $con = dbConnection();

            // Query to insert into admin table
            $queryAdmin = "INSERT INTO admin(A_fName, A_sName, A_address, A_area) VALUES (?, ?, ?, ?);";
            $typesAdmin = "ssss";
            $paramsAdmin = [$firstNameP, $secondNameP, $regAdmAddressP, $regAdmAreaP];

            // Call the preparedStmt function
            $resArrAdmin = preparedStmt($queryAdmin, $con, $typesAdmin, $paramsAdmin);
            $stmtAdmin = $resArrAdmin['stmt'];
            $resultAdmin = $resArrAdmin['res'];

            // Check execution result
            if (!$resultAdmin) {
                echo "Couldn't insert into admin table";
                mysqli_stmt_close($stmtAdmin);
                die;
            } else {
                echo "Insertion in admin table successful";
            }

            // Get the inserted ID
            $a_id = mysqli_insert_id($con);
            // Close the statement
            mysqli_stmt_close($stmtAdmin);
            //-----------------------------------------------------------------------------------------------------------------------
            
            //Create hash password
            $hashPass = password_hash($passwordP, PASSWORD_DEFAULT);

            //Query to insert into loginregister table
            $queryLoginRegister = "INSERT INTO loginadmin(La_username, La_gmail, La_password, A_id) VALUES (?, ?, ?, ?);";
            $typesLoginRegister = "sssi";
            $paramsLoginRegister = [$gmailP, $gmailP, $hashPass, $a_id];

            //Call the preparedStmt function
            $resArrLoginRegister = preparedStmt($queryLoginRegister, $con, $typesLoginRegister, $paramsLoginRegister);
            $stmtLoginRegister = $resArrLoginRegister['stmt'];
            $resultLoginRegister = $resArrLoginRegister['res'];

            //Check if success
            if(!$resultLoginRegister){
                echo "Couldn't insert into the loginadmin table";
                mysqli_stmt_close($stmtLoginRegister);
                die;
            } else {
                echo "Insertion to loginadmin successful! <br>";
                mysqli_stmt_close($stmtLoginRegister);
                $con->close();
                header("Location: ../manageAdmin/manageAdminH.php");
            }
        } else {
            $con->close();
            header("Location: ./addAdminH.php");
        }
    }
 
?>
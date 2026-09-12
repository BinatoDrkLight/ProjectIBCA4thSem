<?php
    session_start();

    //Initialize variable.
    $firstNameP = $secondNameP = $gmailP = $passwordP = $rePasswordP = $regAdmAddressP = $regAdmAreaP = $isValid = $hashPass = "";

    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $firstNameP = trim($_POST['firstNameH'] ?? '');
        $secondNameP = trim($_POST['secondNameH'] ?? '');
        $gmailP = trim($_POST['gmailH'] ?? '');
        $passwordP = $_POST['passwordH'] ?? '';
        $rePasswordP = $_POST['rePasswordH'] ?? '';
        $regAdmAddressP = trim($_POST['addressH'] ?? '');
        $regAdmAreaP = trim($_POST['areaH'] ?? '');

        $isValid = true;

        //Connect to database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        //Prepared statement helper
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
            // Prepared statement check to prevent SQL injection
            $checkGmailStmt = preparedStmt("SELECT La_gmail FROM loginadmin WHERE La_gmail = ?", $con, "s", [$gmailP]);
            $resCheckGmail = mysqli_stmt_get_result($checkGmailStmt['stmt']);
            if(mysqli_num_rows($resCheckGmail) > 0) {
                $_SESSION['gmailError'] = "Gmail already in use.";
                $isValid = false;
            }
            mysqli_stmt_close($checkGmailStmt['stmt']);
        }

        //Validate password.
        if(!preg_match("/^(?=(.*[a-zA-Z]))(?=(.*\d))(?=(.*\w)).{8,}$/", $passwordP)){
            $_SESSION['passwordError'] = "Password must be at least 8 characters with letters and numbers.";
            $isValid = false;
        }
        
        //Check if passwords match.
        if($passwordP !== $rePasswordP || empty($passwordP)){
            $_SESSION['rePasswordError'] = "Passwords do not match.";
            $isValid = false;
        }

        //Check the address length
        if(strlen($regAdmAddressP) > 30 || strlen($regAdmAddressP) < 2){
            $_SESSION['addressError'] = "Invalid address length.";
            $isValid = false;
        }

        //Check the area length
        if(strlen($regAdmAreaP) > 40 || strlen($regAdmAreaP) < 2){
            $_SESSION['areaError'] = "Invalid area length.";
            $isValid = false;
        }

        //If validated.
        if($isValid){                
            // Query to insert into admin table
            $queryAdmin = "INSERT INTO admin(A_fName, A_sName, A_address, A_area) VALUES (?, ?, ?, ?);";
            $typesAdmin = "ssss";
            $paramsAdmin = [$firstNameP, $secondNameP, $regAdmAddressP, $regAdmAreaP];

            $resArrAdmin = preparedStmt($queryAdmin, $con, $typesAdmin, $paramsAdmin);
            $stmtAdmin = $resArrAdmin['stmt'];
            $resultAdmin = $resArrAdmin['res'];

            if (!$resultAdmin) {
                mysqli_stmt_close($stmtAdmin);
                $con->close();
                die("Couldn't insert into admin table.");
            }

            // Get the inserted ID
            $a_id = mysqli_insert_id($con);
            mysqli_stmt_close($stmtAdmin);
            
            // Create hash password
            $hashPass = password_hash($passwordP, PASSWORD_DEFAULT);

            // Query to insert into loginadmin table
            $queryLoginRegister = "INSERT INTO loginadmin(La_username, La_gmail, La_password, A_id) VALUES (?, ?, ?, ?);";
            $typesLoginRegister = "sssi";
            $paramsLoginRegister = [$gmailP, $gmailP, $hashPass, $a_id];

            $resArrLoginRegister = preparedStmt($queryLoginRegister, $con, $typesLoginRegister, $paramsLoginRegister);
            $stmtLoginRegister = $resArrLoginRegister['stmt'];
            $resultLoginRegister = $resArrLoginRegister['res'];

            if(!$resultLoginRegister){
                mysqli_stmt_close($stmtLoginRegister);
                $con->close();
                die("Couldn't insert into the loginadmin table.");
            }

            mysqli_stmt_close($stmtLoginRegister);
            $con->close();
            header("Location: ../manageAdmin/manageAdminH.php");
            exit();
        } else {
            $con->close();
            header("Location: ./addAdminH.php");
            exit();
        }
    }
?>
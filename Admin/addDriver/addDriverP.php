<?php
    session_start();
    $a_id = $_SESSION['A_id'];

    //Initialize variable.
    $firstNameP = $secondNameP = $gmailP = $passwordP = $rePasswordP = $phoneP = $regDriAddressP 
    = $regDriLicenseP = $regBusModelP = $regRegNoP = $regDriRouteNameP = $regRouteStartP = $regRouteEndP 
    = $scheduleDayP = $scheduleStTimeP = $scheduleEndTimeP = $d_id = $s_id = $r_id = $b_id
    = $isValid = $hashPass = "";


    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $firstNameP = $_POST['firstNameH'];
        $secondNameP = $_POST['secondNameH'];
        $gmailP = $_POST['gmailH'];
        $passwordP = $_POST['passwordH'];
        $rePasswordP = $_POST['rePasswordH'];
        $phoneP = $_POST['phoneH'];
        $regDriAddressP = $_POST['addressH'];
        $regDriLicenseP = $_POST['licenseH'];
        $regBusModelP = $_POST['busModelH'];
        $regRegNoP = $_POST['busRegNoH'];
        $regDriRouteNameP = $_POST['routeNameH'];
        $regRouteStartP = $_POST['routeStartH'];
        $regRouteEndP = $_POST['routeEndH'];
        $scheduleDayP = $_POST['scheduleDayH'];
        $scheduleStTimeP = $_POST['scheduleStTimeH'];
        $scheduleEndTimeP = $_POST['scheduleEndTimeH'];

        $roleP = "Driver";

        $isValid = true;

        //Include function to connect database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        include_once('../../All/allPreparedStatement.php');

        //----------------------------------------------------------------Validate driver data ------------------------------------------------------------
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
            else {
                // Check if gmail exists in loginregister table
                $checkGmailQuery = "SELECT Lr_gmail FROM loginregister where Lr_gmail = ?";
                $checkGmailParams = [$gmailP];
                $checkGmailTypes = "s";

                $resCheckArrGmail = preparedStmt($checkGmailQuery, $con, $checkGmailTypes, $checkGmailParams);
                //Get data as object using mysqli_stmt_get_result()
                $resCheckGmailData = mysqli_stmt_get_result($resCheckArrGmail['stmt']);

                //Check if any data is returned
                if (mysqli_num_rows($resCheckGmailData) > 0) {
                    $_SESSION['gmailError'] = "Gmail already exists.";
                    $isValid = false;
                }
                mysqli_stmt_close($resCheckArrGmail['stmt']);
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
         if(!preg_match("/^9[\d]{9}$/", $phoneP)){
            $_SESSION['phoneError'] = "Invalid phone number";
            $isValid = false;
        }
        //Check if password already exists.
            else{
                $checkPhoneQuery = "SELECT D_phone_no FROM d_phone_nos WHERE D_phone_no = ?";
                $checkPhoneParams = [$phoneP];
                $checkPhoneTypes = "s";

                //Call preparedStmt
                $resCheckArrPhone = preparedStmt($checkPhoneQuery, $con, $checkPhoneTypes, $checkPhoneParams);
                $resCheckPhoneData = mysqli_stmt_get_result($resCheckArrPhone['stmt']);

                if(mysqli_num_rows($resCheckPhoneData) > 0){
                    $_SESSION['phoneError'] = "Phone no already in use";
                    $isValid = false;
                }
                mysqli_stmt_close($resCheckArrPhone['stmt']);
            }

        //Check the address length
        if((strlen($regDriAddressP) > 20) || (strlen($regDriAddressP) <= 3)){
            $_SESSION['addressError'] = "Length doesn't match";
            $isValid = false;
        }

        // Validate Driver License Number Format
        if (!preg_match('/^\d{12}$/', $regDriLicenseP)) {
            $_SESSION['licenseError'] = "Invalid Driver License Number format.";
            $isValid = false;
        } else {
            // Check if Driver License Exists in 'driver' table
            $checkDriverQuery = "SELECT D_license_no FROM driver WHERE D_license_no = ?";
            $checkDriverParams = [$regDriLicenseP];
            $checkDriverTypes = "s";

            //Call preparedStmt()
            $resCheckArrDriver = preparedStmt($checkDriverQuery, $con, $checkDriverTypes, $checkDriverParams);
            $resCheckDriverData = mysqli_stmt_get_result($resCheckArrDriver['stmt']);

            if (mysqli_num_rows($resCheckDriverData) > 0) {
                $_SESSION['licenseError'] = "Driver License already exists.";
                $isValid = false;
            }
            mysqli_stmt_close($resCheckArrDriver['stmt']);
        }

        //----------------------------------------------------------------Validate bus data ------------------------------------------------------------

        //Check Bus Model
        if(!preg_match("/^[a-zA-Z0-9\s]{3,50}$/", $regBusModelP)){
            $_SESSION['busModelError'] = "Invalid Bus Model";
            $isValid = false;
        } else {
            //Check if Model exists in  bus table
            $checkModelQuery = "SELECT B_model FROM bus WHERE B_model = ?";
            $checkModelParams = [$regBusModelP];
            $checkModelTypes = 's';

            $resCheckArrModel = preparedStmt($checkModelQuery, $con, $checkModelTypes, $checkModelParams);
            $resCheckModelData = mysqli_stmt_get_result($resCheckArrModel['stmt']);

            if(mysqli_num_rows($resCheckModelData) == 0){
                $_SESSION['busModelError'] = "Bus Model Doesn't exists";
                $isValid = false;
            }
            mysqli_stmt_close($resCheckArrModel['stmt']);
        }

        // Validate Bus Registration Number Format
        // if (!preg_match('/^[A-Z]{1}[A-Z]{2}\d{4}$/', $regRegNoP)) {
        if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $regRegNoP)) {
        $_SESSION['busRegError'] = "Invalid Bus Registration No.";
        $isValid = false;
        } else {
            // Check if Bus Registration Exists in 'bus' table
            $checkBusQuery = "SELECT B_id, B_reg_no FROM bus WHERE B_reg_no = ?";
            $checkBusParams = [$regRegNoP];
            $checkBusTypes = "s";

            $resCheckArrBus = preparedStmt($checkBusQuery, $con, $checkBusTypes, $checkBusParams);
            $resCheckBusData = mysqli_stmt_get_result($resCheckArrBus['stmt']);

            if (mysqli_num_rows($resCheckBusData) == 0) {
            $_SESSION['busRegError'] = "Bus Reg No doesn't exists.";
            $isValid = false;
            } else {
                while($row = mysqli_fetch_assoc($resCheckBusData)){
                    $b_id = $row['B_id'];
                }
            }
            mysqli_stmt_close($resCheckArrBus['stmt']);
        }

        //----------------------------------------------------------------Validate route data ------------------------------------------------------------

        //Check route name
        if(!preg_match("/^[a-zA-Z\s]{3,}$/", $regDriRouteNameP)){
            $_SESSION['routeNameError'] = "Invalid route name length.";
            $isValid = false;
        }

        //Check route start name
        if(!preg_match("/^[a-zA-Z\s]{3,}$/", $regRouteStartP)){
            $_SESSION['startRouteError'] = "length doesn't match.";
            $isValid = false;
        }

        //Check route end name
        if(!preg_match("/^[a-zA-Z\s]{3,}$/", $regRouteEndP)){   
            $_SESSION['endRouteError'] = "length doesn't match.";
            $isValid = false;
        }

        //Check if Route combo exists
        if($isValid){
            $checkRouteQuery = "SELECT  R_id FROM route 
                                                WHERE R_name = ? AND R_start = ? AND R_end = ?";
            $checkRouteParams = [$regDriRouteNameP, $regRouteStartP, $regRouteEndP];
            $checkRouteTypes = 'sss';

            $regCheckArrRoute = preparedStmt($checkRouteQuery, $con, $checkRouteTypes, $checkRouteParams);
            $regCheckRouteData = mysqli_stmt_get_result($regCheckArrRoute['stmt']);

            if(mysqli_num_rows($regCheckRouteData ) == 0){
                $_SESSION['routeComboError'] = "Route combination doesn't exists";
                $isValid = false;
            } else {
                while($row = mysqli_fetch_assoc($regCheckRouteData)){
                    $r_id = $row['R_id'];
                }
            }
            mysqli_stmt_close($regCheckArrRoute['stmt']);
        }

        //----------------------------------------------------------------Validate schedule data ------------------------------------------------------------

        //Check if schedule start time is empty.
        if(!$scheduleStTimeP){
            $_SESSION['scheduleStError'] = "Start time is empty.";
            $isValid = false;
            } 

        //Validate schedule day.
        if(!$scheduleEndTimeP){
            $_SESSION['scheduleEndError'] = "End time is empty.";
            $isValid = false;
        }

        if($scheduleDayP && $scheduleStTimeP && $scheduleEndTimeP ){
            //Check if the schedule already exists
            $checkScheduleQuery = "SELECT S_id 
                                                    FROM schedule
                                                    WHERE S_day = ? AND S_sTime = ? AND S_eTime = ?";
            $checkScheduleTypes = "sss";
            $checkScheduleParams = [$scheduleDayP, $scheduleStTimeP, $scheduleEndTimeP];

            //Call the preparedStmt function
            $resCheckArrSchedule = preparedStmt($checkScheduleQuery, $con, $checkScheduleTypes, $checkScheduleParams);
            $resCheckScheduleData = mysqli_stmt_get_result($resCheckArrSchedule['stmt']);

            //Check if seccess
            if(mysqli_num_rows($resCheckScheduleData) == 0){
                echo "Schedule doesn't exists in schedule table";
                $isValid = false;
                $_SESSION['scheduleExistsError'] = "These schedule doesn't Exists" . $d_id;
            } else {
               while($row = mysqli_fetch_assoc($resCheckScheduleData)){
                $s_id = $row['S_id'];
               }
                echo $s_id;
            }
            mysqli_stmt_close($resCheckArrSchedule['stmt']);
        }

        //---------------------------------------------------------------- Inserting data into data base ------------------------------------------------------------


        //If validated.
        if($isValid){                
            // Query to insert into driver table
            $insertDriverQuery = "INSERT INTO driver(D_fName, D_sName, D_address, D_license_no, A_id, B_id, S_id, R_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
            $insertDriverTypes = "ssssiiii";
            $insertDriverParams = [$firstNameP, $secondNameP, $regDriAddressP, $regDriLicenseP, $a_id, $b_id, $s_id, $r_id];

            // Call the preparedStmt function
            $resInsertArrDriver = preparedStmt($insertDriverQuery, $con, $insertDriverTypes, $insertDriverParams);

            // Check execution result
            if (!$resInsertArrDriver['res']) {
                echo "Couldn't insert into driver table";
                mysqli_stmt_close($resInsertArrDriver['stmt']);
                mysqli_close($con);
                die;
            } else {
                echo "Insertion in driver table successful";
            }

            // Get the inserted ID
            $d_id = mysqli_insert_id($con);
            echo $d_id;

            mysqli_stmt_close($resInsertArrDriver['stmt']);
            //-----------------------------------------------------------------------------------------------------------------------
            
            //Query to insert phone no in d_phone_nos table
            $insertPhoneQuery = "INSERT INTO d_phone_nos(D_phone_no, D_id) VALUES (?, ?);";
            $insertPhoneTypes = "si";
            $insertPhoneParams = [$phoneP, $d_id];

            //Call the preparedStmt function
            $resInsertArrPhone = preparedStmt($insertPhoneQuery, $con, $insertPhoneTypes, $insertPhoneParams);

            //Check if seccess
            if(!$resInsertArrPhone['res']){
                echo "Couldn't insert into d_phone_nos table";
                mysqli_stmt_close($resInsertArrPhone['stmt']);
                mysqli_close($con);
                die;
            } else {
                echo "Insertion in d_phone_nos table successful";
            }
            mysqli_stmt_close($resInsertArrPhone['stmt']);

            //-----------------------------------------------------------------------------------------------------------------------

            //Create hash password
            $hashPass = password_hash($passwordP, PASSWORD_DEFAULT);
            //Query to insert into loginregister table
            $insertLoginRegisterQuery = "INSERT INTO loginregister(Lr_username, Lr_gmail, Lr_password, Lr_user, D_id) VALUES (?, ?, ?, ?, ?);";
            $insertLoginRegisterTypes = "ssssi";
            $insertLoginRegisterParams = [$gmailP, $gmailP, $hashPass, $roleP, $d_id];

            //Call the preparedStmt function
            $resInsertArrLoginRegister = preparedStmt($insertLoginRegisterQuery, $con, $insertLoginRegisterTypes, $insertLoginRegisterParams);

            //Check if success
            if(!$resInsertArrLoginRegister['res']){
                echo "Couldn't insert into the loginregister table";
                mysqli_stmt_close($resInsertArrLoginRegister['stmt']);
                mysqli_close($con);
                die;
            } else {
                echo "Insertion to loginregister successful! <br>";
                mysqli_stmt_close($resInsertArrLoginRegister['stmt']);
                mysqli_close($con);
                header("Location: ../manageDriver/manageDriverH.php");
                exit();
            }
        } else {
            mysqli_close($con);
            header("Location: ./addDriverH.php");
            exit();
        }
    }
?>
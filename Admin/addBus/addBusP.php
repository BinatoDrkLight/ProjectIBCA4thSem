<?php
    session_start();

    //Initialize variable.
    $regBusModelP = $regRegNoP = $isValid = "";

    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $regBusModelP = $_POST['busModelH'];
        $regRegNoP = $_POST['busRegNoH'];

        $isValid = true;

        //Include function to connect database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        include_once('../../All/allPreparedStatement.php');

        //Check Bus Model
        if(!preg_match("/^[a-zA-Z0-9]{3,50}$/", $regBusModelP)){
            $_SESSION['busModelError'] = "Invalid Bus Model";
            $isValid = false;
        }

        // Validate Bus Registration Number Format
        if (!preg_match('/^[A-Z]{1}[A-Z]{2}\d{4}$/', $regRegNoP)) {
        $_SESSION['busRegError'] = "Invalid Bus Registration No.";
        $isValid = false;
        } else {
            // Check if Bus Registration Exists in 'bus' table
            $checkBusQuery = "SELECT B_reg_no FROM bus WHERE B_reg_no = ?";
            $checkBusParams = [$regRegNoP];
            $checkBusTypes = "s";

            $resArrBus = preparedStmt($checkBusQuery, $con, $checkBusTypes, $checkBusParams);
            $resBusData = mysqli_stmt_get_result($resArrBus['stmt']);

            if (mysqli_num_rows($resBusData) > 0) {
            $_SESSION['busRegError'] = "Bus Reg No already exists.";
            $isValid = false;
            }
            mysqli_stmt_close($resArrBus['stmt']);
        }

        //If validated.
        if($isValid){
            //Query to insert bus details in bus table
            $checkBusQuery = "INSERT INTO bus(B_model, B_reg_no) VALUES (?, ?);";
            $checkBusTypes = "ss";
            $checkBusParams = [$regBusModelP, $regRegNoP];

            //Call the preparedStmt function
            $resArrBus = preparedStmt($checkBusQuery, $con, $checkBusTypes, $checkBusParams);

            //Check if seccess
            if(!$resArrBus['res']){
                echo "Couldn't insert into bus table";
                mysqli_stmt_close($resArrBus['stmt']);
                mysqli_close($con);
                die;
            } else {
                echo "Insertion in bus table successful";
                mysqli_stmt_close($resArrBus['stmt']);
                mysqli_close($con);
                header("Location: ../manageDriver/manageDriverH.php");
                exit();
            }
        } else {
            mysqli_close($con);
            header("Location: ./addBusH.php");
            exit();
        }
    }
?>
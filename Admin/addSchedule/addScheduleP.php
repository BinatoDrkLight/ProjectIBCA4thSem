<?php
    session_start();

    //Initialize variable.
    $scheduleDayP = $scheduleStTimeP = $scheduleEndTimeP = $isValid = "";

    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $scheduleDayP = $_POST['scheduleDayH'];
        $scheduleStTimeP = $_POST['scheduleStTimeH'];
        $scheduleEndTimeP = $_POST['scheduleEndTimeH'];

        $isValid = true;

        //Include function to connect database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        include_once('../../All/allPreparedStatement.php');

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
            $resArrCheckSchedule = preparedStmt($checkScheduleQuery, $con, $checkScheduleTypes, $checkScheduleParams);
            $resCheckScheduleData = mysqli_stmt_get_result($resArrCheckSchedule['stmt']);

            //Check if success
            if(mysqli_num_rows($resCheckScheduleData) > 0){
                $isValid = false;
                $_SESSION['scheduleExistsError'] = "This schedule already exists in records.";
            }
            mysqli_stmt_close($resArrCheckSchedule['stmt']);
        }

        //If validated.
        if($isValid){                
            //Query to insert schedule in schedule table
            $insertScheduleQuery = "INSERT INTO schedule(S_day, S_sTime, S_eTime) VALUES (?, ?, ?);";
            $insertScheduleTypes = "sss";
            $insertScheduleParams = [$scheduleDayP, $scheduleStTimeP, $scheduleEndTimeP];

            //Call the preparedStmt function
            $resArrInsertSchedule = preparedStmt($insertScheduleQuery, $con, $insertScheduleTypes, $insertScheduleParams);

            //Check if success
            if(!$resArrInsertSchedule['res']){
                mysqli_stmt_close($resArrInsertSchedule['stmt']);
                mysqli_close($con);
                die("Couldn't insert into schedule table.");
            } else {
                mysqli_stmt_close($resArrInsertSchedule['stmt']);
                mysqli_close($con);
                header("Location: ../manageDriver/manageDriverH.php");
                exit();
            }
        } else {
            mysqli_close($con);
            header("Location: ./addScheduleH.php");
            exit();
        }
    }
?>
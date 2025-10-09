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

            //Check if seccess
            if(mysqli_num_rows($resCheckScheduleData) > 0){
                echo "Schedule already exists in schedule table";
                $isValid = false;
                $_SESSION['scheduleExistsError'] = "These schedule already Exists";
            } else {
                echo "Schedule not found in schedule table";
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

            //Check if seccess
            if(!$resArrInsertSchedule['res']){
                echo "Couldn't insert into schedule table";
                mysqli_stmt_close($resArrInsertSchedule['stmt']);
                mysqli_close($con);
                die;
            } else {
                echo "Insertion in schedule table successful";
                header("Location: ../manageDriver/manageDriverH.php");
                exit();
            }
             mysqli_stmt_close($resArrInsertSchedule['stmt']);
        } else {
            mysqli_close($con);
            header("Location: ./addScheduleH.php");
            exit();
        }
    }
?>
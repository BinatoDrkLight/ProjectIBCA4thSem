<?php
    session_start();
    // $laIId = $_SESSION['La_id'];

    //Initialize variable.
    $regRouteNameP = $regRouteStartP = $regRouteEndP= $isValid = "";


    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $regRouteNameP = $_POST['routeNameH'];
        $regRouteStartP = $_POST['routeStartH'];
        $regRouteEndP = $_POST['routeEndH'];

        $isValid = true;

        //Include function to connect database.
        include_once('../../All/allDatabaseConnection.php');
        $con = dbConnection();

        include_once('../../All/allPreparedStatement.php');

        //Check route name
        if(!preg_match("/^[a-zA-Z\s]{3,}$/", $regRouteNameP)){
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

        if($regRouteNameP && $regRouteStartP && $regRouteEndP ){
            //Check if the route already exists
            $checkRouteQuery = "SELECT R_id 
                                FROM route 
                                WHERE R_name = ? AND R_start = ? AND R_end = ?";
            $checkRouteTypes = "sss";
            $checkRouteParams = [$regRouteNameP, $regRouteStartP, $regRouteEndP];

            //Call the preparedStmt function
            $resArrCheckRoute = preparedStmt($checkRouteQuery, $con, $checkRouteTypes, $checkRouteParams);
            $resCheckRouteData = mysqli_stmt_get_result($resArrCheckRoute['stmt']);

            //Check if seccess
            if(mysqli_num_rows($resCheckRouteData) > 0){
                echo "Route already exists in route table";
                $isValid = false;
                $_SESSION['routeExistsError'] = "These route already Exists";
            } else {
                echo "Route not found in route table";
            }
            mysqli_stmt_close($resArrCheckRoute['stmt']);
        }

        //If validated.
        if($isValid){                
            //Query to insert route details in route table
            $insertRouteQuery = "INSERT INTO route(R_name, R_start, R_end) VALUES (?, ?, ?);";
            $insertRouteTypes = "sss";
            $insertRouteParams = [$regRouteNameP, $regRouteStartP, $regRouteEndP];

            //Call the preparedStmt function
            $resArrInsertRoute = preparedStmt($insertRouteQuery, $con, $insertRouteTypes, $insertRouteParams);

            //Check if seccess
            if(!$resArrInsertRoute['res']){
                echo "Couldn't insert into route table";
                mysqli_stmt_close($resArrInsertRoute['stmt']);
                mysqli_close($con);
                die;
            } else {
                echo "Insertion in route table successful";
                header("Location: ../manageDriver/manageDriverH.php");
                exit();
            }
            mysqli_stmt_close($resArrInsertRoute['stmt']);
        } else {
            mysqli_close($con);
            header("Location: ./addRouteH.php");
            exit();
        }
    }
?>
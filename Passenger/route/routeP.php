<?php
    //Database connection
    include_once('../../All/allDatabaseConnection.php');
    $con = dbConnection();

    //Prepared statement
    include_once('../../All/allPreparedStatement.php');

    //Params
    $queryRoute = "SELECT r.R_name, b.B_model, r.R_id, b.B_id, b.B_reg_no, r.R_start, r.R_end, l.L_id, l.L_longitude, l.L_latitude
                            FROM route AS r
                            LEFT JOIN driver AS d
                            ON r.R_id = d.R_id 
                            LEFT JOIN bus AS b
                            ON d.B_id = b.B_id
                            LEFT JOIN location AS l
                            ON b.B_id = l.B_id
                            WHERE ? = ?";
    $typeRoute = "ii";
    $paramsRoute = ['1', '1'];

    //Call prepared statement function
    $resArrRoute = preparedStmt($queryRoute, $con, $typeRoute, $paramsRoute);
    $stmtRoute = $resArrRoute['stmt'];

    //Get result from query
    $resultRouteData = mysqli_stmt_get_result($stmtRoute);
    $dataRouteD = [];

    //Check if anything is retrieved.
    while($row = mysqli_fetch_assoc($resultRouteData)){
    $dataRouteD[] = $row;
    }
    echo json_encode($dataRouteD);
    $con->close();
    mysqli_stmt_close($stmtRoute);
?>
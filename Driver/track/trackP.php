<?php
session_start();
$bId = $_SESSION['B_id'];

header('Content-Type: application/json');

include_once("../../All/allDatabaseConnection.php");
include_once("../../All/allPreparedStatement.php");

if (isset($_POST['latitude'], $_POST['longitude']) && 
    is_numeric($_POST['latitude']) && is_numeric($_POST['longitude'])) {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Call dbConnection
    $conCheck = dbConnection();

    // Check if location already exists
    $checkQuery = "SELECT * FROM location WHERE B_id = ?";
    $checkTypes = "i";
    $checkParams = [$bId];

    // Call preparedStmt
    $resCheck = preparedStmt($checkQuery, $conCheck, $checkTypes, $checkParams);
    if ($resCheck && isset($resCheck['stmt'])) {
        $stmtCheck = $resCheck['stmt'];
        $resCheckData = mysqli_stmt_get_result($stmtCheck);

        // If location exists, update it
        if ($resCheckData->num_rows > 0) {
            $updateQuery = "UPDATE location SET L_longitude = ?, L_latitude = ? WHERE B_id = ?";
            $updateTypes = "ddi";
            $updateParams = [$longitude, $latitude, $bId];
            $resUpdate = preparedStmt($updateQuery, $conCheck, $updateTypes, $updateParams);

            if ($resUpdate && isset($resUpdate['res'])) {
                echo json_encode(["status" => "success", "message" => "Location updated successfully."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update location.", "error" => mysqli_error($conCheck)]);
            }
            if (isset($resUpdate['stmt'])) {
                mysqli_stmt_close($resUpdate['stmt']);
            }
        } else {
            // If no existing location, insert a new one
            $insertQuery = "INSERT INTO location(L_longitude, L_latitude, B_id) VALUES (?, ?, ?)";
            $insertTypes = "ddi";
            $insertParams = [$longitude, $latitude, $bId];
            $resInsert = preparedStmt($insertQuery, $conCheck, $insertTypes, $insertParams);

            if ($resInsert && isset($resInsert['res'])) {
                echo json_encode(["status" => "success", "message" => "Location inserted successfully."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to insert location.", "error" => mysqli_error($conCheck)]);
            }
            if (isset($resInsert['stmt'])) {
                mysqli_stmt_close($resInsert['stmt']);
            }
        }

        // Close the check statement
        if (isset($resCheck['stmt'])) {
            mysqli_stmt_close($resCheck['stmt']);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Query execution failed.", "error" => mysqli_error($conCheck)]);
    }

    // Close the database connection
    $conCheck->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid parameters."]);
}
?>

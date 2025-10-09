<?php
session_start();
header('Content-Type: application/json');

// include your DB connection
include_once("../../All/allDatabaseConnection.php");
$con = dbConnection();

$sql = "SELECT L_longitude AS longitude, L_latitude AS latitude, B_id AS id, L_id AS l_id FROM location";
$result = mysqli_query($con, $sql);
$busData = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $busData[] = $row;
    }
}

echo json_encode([
    "status" => "success",
    "message" => "sent successfylly",
    "busData" => $busData
]);

?>
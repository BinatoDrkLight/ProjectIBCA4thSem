<?php
session_start();
header('Content-Type: application/json');

include_once("../../All/allDatabaseConnection.php");
$con = dbConnection();

$sql = "SELECT l.L_longitude AS longitude, l.L_latitude AS latitude, l.B_id AS id, l.L_id AS l_id, b.B_model AS model, b.B_reg_no AS reg_no 
        FROM location AS l
        LEFT JOIN bus AS b ON l.B_id = b.B_id";
$result = mysqli_query($con, $sql);
$busData = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $busData[] = $row;
    }
}

$con->close();

echo json_encode([
    "status" => "success",
    "message" => "sent successfully",
    "busData" => $busData
]);
?>
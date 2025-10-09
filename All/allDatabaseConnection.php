<?php
function dbConnection(){
    $con = mysqli_connect('localhost', 'root', '', 'trackie');
        if(!$con){
            echo "Couldn't connect to databse <br>";die;
        } 
        return $con;
    }
?>

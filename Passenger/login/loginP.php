<?php
    session_start();
    //Initialize variable.
    $usernameP = $passwordP = "";

    //If submitted using Log In button.
    if(isset($_POST['submitH'])){
        //If submitted using post method.
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usernameP = $_POST['usernameH'];
            $passwordP = $_POST['passwordH']; 
        
            //Connect to database.
            $con = mysqli_connect('localhost', 'root', '', 'trackie');
            if(!$con){
                echo "Couldn't connect to databse <br>";die;
            } else {
                echo "Connection successful <br>";
            }

            //Validate if the username and email is correct.
            $sqlUser = "SELECT gmailDB, passwordDB, phoneDB FROM registerPassenger WHERE 
            ((gmailDB = '$usernameP' && passwordDB = '$passwordP') OR (phoneDB = '$usernameP' && passwordDB = '$passwordP'));";
            $res = mysqli_query($con, $sqlUser);

            //Check if anything is retrieved.
            if(mysqli_num_rows($res) > 0){
                header("Location: ../home/home.php");
            } else {
                header("Location: loginH.php");
                $_SESSION['logError'] = "Invalid credentials";
            }
        }  
    }
 
?>
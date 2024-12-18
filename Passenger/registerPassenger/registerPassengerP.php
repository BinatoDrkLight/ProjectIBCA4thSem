<?php
    session_start();

    //Initialize variable.
    $fullNameP = $secondNameP = $gmailP = $passwordP = $rePasswordP = 
    $phoneP = $isValid = "";


    //If submitted using post method.
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $firstNameP = $_POST['firstNameH'];
        $secondNameP = $_POST['secondNameH'];
        $gmailP = $_POST['gmailH'];
        $passwordP = $_POST['passwordH'];
        $rePasswordP = $_POST['rePasswordH'];
        $phoneP = $_POST['phoneH'];

        $isValid = true;

        //Connect to database.
        $con = mysqli_connect('localhost', 'root', '', 'trackie');
        if(!$con){
            echo "Couldn't connect to databse <br>";die;
        } else {
            echo "Connection successful <br>";
        }

        //Query to select if gmail already exists in database.
        $sqlGmail = "SELECT gmailDB FROM registerPassenger where gmailDB = '$gmailP'";
        //Execute the query and store it in a variable.
        $resGmail = mysqli_query($con, $sqlGmail);


        //Query to select if gmail already exists in database.
        $sqlPhone = "SELECT phoneDB FROM registerPassenger where phoneDB = '$phoneP'";
        //Execute the query and store it in a variable.
        $resPhone = mysqli_query($con, $sqlPhone);

        //Validate firstname.
        if(!preg_match("/^[a-zA-Z]{3,}/", $firstNameP)){
            $_SESSION['firstNameError'] = "Invalid first Name.";
            $isValid = false;
        }

        //Validate secondname.
        if(!preg_match("/^[a-zA-Z]{3,}/", $secondNameP)){
            $_SESSION['secondNameError'] = "Invalid second name.";
            $isValid = false;
        }            

        //Validate gmail.
        if(!filter_var($gmailP, FILTER_VALIDATE_EMAIL)){
            $_SESSION['gmailError'] = "Invalid gmail.";
            $isValid = false;
        } 
        //Check if gmail already exists.
            elseif(mysqli_num_rows($resGmail) > 0) {
                $_SESSION['gmailError'] = "Gmail already in use.";
                $isValid = false;
        }

        //Validate password.
        if(!preg_match("/^(?=(.*[a-zA-Z]))(?=(.*\d))(?=(.*\w)).{8,}$/", $passwordP)){
            $_SESSION['passwordError'] = "Use proper password";
            $isValid = false;
        }
        
        //Check if passwords match.
        if(($passwordP != $rePasswordP) || ($passwordP == "" || $rePasswordP == "")){
            $_SESSION['rePasswordError'] = "Passwords not match";
            $isValid = false;
        }

        //Validate phone number.
        if(!preg_match("/^9[\d]{9}/", $phoneP)){
            $_SESSION['phoneError'] = "Invalid phone number";
            $isValid = false;
        }
        //Check if password already exists.
            elseif(mysqli_num_rows($resPhone) > 0) {
                $_SESSION['phoneError'] = "Phone no already in use";
                $isValid = false;
        }

        //If validated.
        if($isValid){                
            //Insert into database.
            $sql = "INSERT INTO registerPassenger(firstNameDB, secondNameDB, gmailDB, passwordDB, phoneDB) VALUES 
                    ('$firstNameP', '$secondNameP', '$gmailP', '$passwordP', '$phoneP');";

            $res = mysqli_query($con, $sql);
            if(!$res){
                echo "Couldn't insert into the table";die;
            } else {
                echo "Insertion successful! <br>";
                header("Location: ../home/home.php");
            }
            $con->close();
        } else {
            header("Location: registerPassengerH.php");
        }
    }
 
?>
<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Passenger</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="registerPassenger.css">
</head>
<body>
    <h2 class="regPasLogoH">Trackie<sup>BDL</sup></h2>
    <h1 class="regPasHeaH">Register</h1>

    <alignCenter>
        <form action="registerPassengerP.php" method="post" class="regPasForm">
            <div class="regPasNames">
                <div class="regErrorH">
                    <input type="text" name="firstNameH" class="regPasFname" placeholder="First Name">
                    <error>
                        <?php
                        if(isset($_SESSION['firstNameError'])){
                            echo "<br><e>".$_SESSION['firstNameError']."</e>";
                        }
                        ?>
                    </error>
                </div>
                
                <div class="regErrorH">
                <input type="text" name="secondNameH" class="regPasSname" placeholder="Second Name">
                    <error>
                        <?php
                        if(isset($_SESSION['secondNameError'])){
                            echo "<br><e>".$_SESSION['secondNameError']."</e>";
                            }
                        ?>
                    </error>
                </div>
            </div>
        
            <div class="regErrorH">
                <input type="text" name="gmailH" class="regPasGmail" placeholder="Gmail">
                <error>
                    <?php
                            if(isset($_SESSION['gmailError'])){
                                echo "<br><e>".$_SESSION['gmailError']."</e>";
                            }
                    ?>
                </error>
            </div>
            
            <div class="regPasPasses">
                <div class="regErrorH">
                    <input type="text" name="passwordH" class="regPasPassL" placeholder="Password">
                    <error>
                        <?php
                        if(isset($_SESSION['passwordError'])){
                            echo "<br><e>".$_SESSION['passwordError']."</e>";
                        }
                        ?>
                    </error>
                </div>
                
                <div class="regErrorH">
                <input type="text" name="rePasswordH" class="regPasPassR" placeholder="Re-password">
                    <error>
                        <?php
                        if(isset($_SESSION['rePasswordError'])){
                            echo "<br><e>".$_SESSION['rePasswordError']."</e>";
                        }
                        ?>
                    </error>
                </div>
            </div>

            <div class="regErrorH">
            <input type="text" name="phoneH" class="regPasPhone" placeholder="Phone no">
                <error>
                    <?php
                        if(isset($_SESSION['phoneError'])){
                            echo "<br><e>".$_SESSION['phoneError']."</e>";
                            session_unset();
                            session_destroy();
                        }
                    ?>
                </error>
            </div>
            
            <input type="submit" name="registerH" class="regPasSign" value="Sign Up">
            <p class="regPasAlre">Already <a href="../login/loginH.php">Signed up</a>?</p>
        </form>
    </alignCenter>
</body>
</html>

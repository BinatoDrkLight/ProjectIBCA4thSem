<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="addAdmin.css">
</head>
<body>
    <h2 class="regPasLogoH">Trackie<sup>BDL</sup></h2>
    <h1 class="regPasHeaH">Register Admins</h1>

    <alignCenter>
        <form action="addAdminP.php" method="post" class="regAdmForm">
            <div class="regAdmNames">
                <div class="regErrorH">
                    <input type="text" name="firstNameH" class="regAdmFname" placeholder="First Name">
                    <error>
                        <?php
                        if(isset($_SESSION['firstNameError'])){
                            echo "<br><e>".$_SESSION['firstNameError']."</e>";
                        }
                        ?>
                    </error>
                </div>
                
                <div class="regErrorH">
                <input type="text" name="secondNameH" class="regAdmSname" placeholder="Second Name">
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
                <input type="text" name="gmailH" class="regAdmGmail" placeholder="Gmail">
                <error>
                    <?php
                            if(isset($_SESSION['gmailError'])){
                                echo "<br><e>".$_SESSION['gmailError']."</e>";
                            }
                    ?>
                </error>
            </div>
            
            <div class="regAdmPasses">
                <div class="regErrorH">
                    <input type="text" name="passwordH" class="regAdmPassL" placeholder="Password">
                    <error>
                        <?php
                        if(isset($_SESSION['passwordError'])){
                            echo "<br><e>".$_SESSION['passwordError']."</e>";
                        }
                        ?>
                    </error>
                </div>
                
                <div class="regErrorH">
                <input type="text" name="rePasswordH" class="regAdmPassR" placeholder="Re-password">
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
                <input type="text" name="addressH" class="regAdmAddress" placeholder="Address">
                <error>
                    <?php
                            if(isset($_SESSION['addressError'])){
                                echo "<br><e>".$_SESSION['addressError']."</e>";
                                // session_unset();
                            }
                    ?>
                </error>
            </div>

            <div class="regErrorH">
                <input type="text" name="areaH" class="regAdmArea" placeholder="Area">
                <error>
                    <?php
                            if(isset($_SESSION['areaError'])){
                                echo "<br><e>".$_SESSION['areaError']."</e>";
                            }
                            session_unset();
                            session_destroy();
                    ?>
                </error>
            </div>

            
            <input type="submit" name="registerH" class="regAdmSign" value="Sign Up">
            <!-- <p class="regPasAlre">Already <a href="../login/loginH.php">Signed up</a>?</p> -->
        </form>
    </alignCenter>
</body>
</html>

<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as passenger</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="registerPassenger.css">
</head>
<body>
    <h2 class="regPasLogoH">Trackie<sup>BDL</sup></h2>
    <h1 class="regPasHeaH">Register</h1>

    <div class="form-align-center">
        <form action="registerPassengerP.php" method="post" class="regPasForm">
            <div class="regPasNames">
                <div class="regErrorH">
                    <input type="text" name="firstNameH" class="regPasFname" placeholder="First Name" required>
                    <div class="form-error">
                        <?php
                        if(isset($_SESSION['firstNameError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['firstNameError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['firstNameError']);
                        }
                        ?>
                    </div>
                </div>
                
                <div class="regErrorH">
                    <input type="text" name="secondNameH" class="regPasSname" placeholder="Second Name" required>
                    <div class="form-error">
                        <?php
                        if(isset($_SESSION['secondNameError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['secondNameError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['secondNameError']);
                        }
                        ?>
                    </div>
                </div>
            </div>
        
            <div class="regErrorH">
                <input type="email" name="gmailH" class="regPasGmail" placeholder="Email" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['gmailError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['gmailError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['gmailError']);
                        }
                    ?>
                </div>
            </div>
            
            <div class="regPasPasses">
                <div class="regErrorH">
                    <input type="password" name="passwordH" class="regPasPassL" placeholder="Password" required>
                    <div class="form-error">
                        <?php
                        if(isset($_SESSION['passwordError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['passwordError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['passwordError']);
                        }
                        ?>
                    </div>
                </div>
                
                <div class="regErrorH">
                    <input type="password" name="rePasswordH" class="regPasPassR" placeholder="Re-password" required>
                    <div class="form-error">
                        <?php
                        if(isset($_SESSION['rePasswordError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['rePasswordError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['rePasswordError']);
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="regErrorH">
                <input type="tel" name="phoneH" class="regPasPhone" placeholder="Phone no" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['phoneError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['phoneError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['phoneError']);
                        }
                    ?>
                </div>
            </div>
            
            <input type="submit" name="registerH" class="regPasSign" value="Sign Up">
            <p class="regPasAlre">Already <a href="../../All/login/loginH.php">Signed up</a>?</p>
        </form>
    </div>
</body>
</html>

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

    <div class="form-align-center">
        <form action="addAdminP.php" method="post" class="regAdmForm">
            <div class="regAdmNames">
                <div class="regErrorH">
                    <input type="text" name="firstNameH" class="regAdmFname" placeholder="First Name" required>
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
                    <input type="text" name="secondNameH" class="regAdmSname" placeholder="Second Name" required>
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
                <input type="email" name="gmailH" class="regAdmGmail" placeholder="Email / Gmail" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['gmailError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['gmailError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['gmailError']);
                        }
                    ?>
                </div>
            </div>
            
            <div class="regAdmPasses">
                <div class="regErrorH">
                    <input type="password" name="passwordH" class="regAdmPassL" placeholder="Password" required>
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
                    <input type="password" name="rePasswordH" class="regAdmPassR" placeholder="Re-password" required>
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
                <input type="text" name="addressH" class="regAdmAddress" placeholder="Address" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['addressError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['addressError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['addressError']);
                        }
                    ?>
                </div>
            </div>

            <div class="regErrorH">
                <input type="text" name="areaH" class="regAdmArea" placeholder="Area" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['areaError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['areaError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['areaError']);
                        }
                    ?>
                </div>
            </div>

            <input type="submit" name="registerH" class="regAdmSign" value="Sign Up">
        </form>
    </div>
</body>
</html>

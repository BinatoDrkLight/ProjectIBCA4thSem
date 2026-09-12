<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Driver</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="../allAdd/allAdd.css">
    <link rel="stylesheet" href="addDriver.css">
</head>
<body>
    <h2 class="regLogoH">Trackie<sup>BDL</sup></h2>
    <h1 class="regHeaH">Register Drivers</h1>

    <div class="form-align-center">
        <form action="addDriverP.php" method="post" class="regForm">
            <!-- ...................................................................................................Driver details ..................................................................................................... -->
            <div class="regDriNames">
                <div class="regErrorH">
                    <input type="text" name="firstNameH" class="regDriFname" placeholder="First Name" required>
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
                    <input type="text" name="secondNameH" class="regDriSname" placeholder="Second Name" required>
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
                <input type="email" name="gmailH" class="regDriGmail" placeholder="Email / Gmail" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['gmailError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['gmailError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['gmailError']);
                        }
                    ?>
                </div>
            </div>
            
            <div class="regDriPasses">
                <div class="regErrorH">
                    <input type="password" name="passwordH" class="regDriPassL" placeholder="Password" required>
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
                    <input type="password" name="rePasswordH" class="regDriPassR" placeholder="Re-password" required>
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
                <input type="tel" name="phoneH" class="regDriPhone" placeholder="Phone no" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['phoneError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['phoneError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['phoneError']);
                        }
                    ?>
                </div>
            </div>

            <div class="regErrorH">
                <input type="text" name="addressH" class="regDriAddress" placeholder="Address" required>
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
                <input type="text" name="licenseH" class="regDriLicense" placeholder="License No" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['licenseError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['licenseError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['licenseError']);
                        }
                    ?>
                </div>
            </div>

            <!-- ...................................................................................................Route details ..................................................................................................... -->

            <div class="regErrorH">
                <input type="text" name="routeNameH" class="regRouteName" placeholder="Route Name" required>
                <div class="form-error">
                    <?php
                        if(isset($_SESSION['routeNameError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['routeNameError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['routeNameError']);
                        }
                    ?>
                </div>
            </div>

            <div class="regRoutes">
                <div class="regErrorH">
                    <input type="text" name="routeStartH" class="regRouteStart" placeholder="Start Route" required>
                    <div class="form-error">
                        <?php
                        if(isset($_SESSION['startRouteError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['startRouteError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['startRouteError']);
                        }
                        ?>
                    </div>
                </div>
                
                <div class="regErrorH">
                    <input type="text" name="routeEndH" class="regRouteEnd" placeholder="End Route" required>
                    <div class="form-error">
                        <?php
                        if(isset($_SESSION['endRouteError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['endRouteError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['endRouteError']);
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="regErrorH">
                <div class="form-error">
                    <?php
                    if(isset($_SESSION['routeComboError'])){
                        echo "<span class='error-msg'>".htmlspecialchars($_SESSION['routeComboError'], ENT_QUOTES, 'UTF-8')."</span>";
                        unset($_SESSION['routeComboError']);
                    }
                    ?> 
                </div>
            </div>

            <!-- ...................................................................................................Bus details ..................................................................................................... -->

            <div class="regBusInfos">
                <div class="regErrorH">
                    <input type="text" name="busModelH" class="regBusModel" placeholder="Bus Model" required>
                    <div class="form-error">
                        <?php
                        if(isset($_SESSION['busModelError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['busModelError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['busModelError']);
                        }
                        ?>
                    </div>
                </div>
                
                <div class="regErrorH">
                    <input type="text" name="busRegNoH" class="regRegNo" placeholder="Bus Reg No" required>
                    <div class="form-error">
                        <?php
                        if(isset($_SESSION['busRegError'])){
                            echo "<span class='error-msg'>".htmlspecialchars($_SESSION['busRegError'], ENT_QUOTES, 'UTF-8')."</span>";
                            unset($_SESSION['busRegError']);
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- ...................................................................................................Schedule details ..................................................................................................... -->

            <div class="regErrorH">
                <select name="scheduleDayH" class="regSchDay" required>
                    <option value="Sunday">Sunday</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                </select>
            </div><br>
            
            <div class="regSchTimes">
                <div class="regErrorH">
                    <input type="time" name="scheduleStTimeH" class="regSchStTime" required>
                    <div class="form-error">
                        <?php
                            if(isset($_SESSION['scheduleStError'])){
                                echo "<span class='error-msg'>".htmlspecialchars($_SESSION['scheduleStError'], ENT_QUOTES, 'UTF-8')."</span>";
                                unset($_SESSION['scheduleStError']);
                            }
                        ?>
                    </div>
                </div>

                <div class="regErrorH">
                    <input type="time" name="scheduleEndTimeH" class="regSchEndTime" required>
                    <div class="form-error">
                        <?php
                            if(isset($_SESSION['scheduleEndError'])){
                                echo "<span class='error-msg'>".htmlspecialchars($_SESSION['scheduleEndError'], ENT_QUOTES, 'UTF-8')."</span>";
                                unset($_SESSION['scheduleEndError']);
                            }
                        ?>
                    </div>        
                </div>
            </div>
 
            <div class="regErrorH">
                <div class="form-error">
                    <?php
                    if(isset($_SESSION['scheduleExistsError'])){
                        echo "<span class='error-msg'>".htmlspecialchars($_SESSION['scheduleExistsError'], ENT_QUOTES, 'UTF-8')."</span>";
                        unset($_SESSION['scheduleExistsError']);
                    }
                    ?> 
                </div>
            </div>

            <?php 
                foreach($_SESSION as $key => $value){
                    if($key != 'A_id' && $key != 'La_id'){
                        unset($_SESSION[$key]);
                    }
                }
            ?>
            
            <input type="submit" name="registerDriverH" class="regAdd" value="Register Driver">
        </form>
    </div>
</body>
</html>

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

    <alignCenter>
        <form action="addDriverP.php" method="post" class="regForm">
            <!-- ...................................................................................................Driver details ..................................................................................................... -->
            <div class="regDriNames">
                <div class="regErrorH">
                    <input type="text" name="firstNameH" class="regDriFname" placeholder="First Name">
                    <error>
                        <?php
                        if(isset($_SESSION['firstNameError'])){
                            echo "<br><e>".$_SESSION['firstNameError']."</e>";
                        }
                        ?>
                    </error>
                </div>
                
                <div class="regErrorH">
                <input type="text" name="secondNameH" class="regDriSname" placeholder="Second Name">
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
                <input type="text" name="gmailH" class="regDriGmail" placeholder="Gmail">
                <error>
                    <?php
                        if(isset($_SESSION['gmailError'])){
                            echo "<br><e>".$_SESSION['gmailError']."</e>";
                        }
                    ?>
                </error>
            </div>
            
            <div class="regDriPasses">
                <div class="regErrorH">
                    <input type="text" name="passwordH" class="regDriPassL" placeholder="Password">
                    <error>
                        <?php
                        if(isset($_SESSION['passwordError'])){
                            echo "<br><e>".$_SESSION['passwordError']."</e>";
                        }
                        ?>
                    </error>
                </div>
                
                <div class="regErrorH">
                <input type="text" name="rePasswordH" class="regDriPassR" placeholder="Re-password">
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
            <input type="text" name="phoneH" class="regDriPhone" placeholder="Phone no">
                <error>
                    <?php
                        if(isset($_SESSION['phoneError'])){
                            echo "<br><e>".$_SESSION['phoneError']."</e>";
                        }
                    ?>
                </error>
            </div>

            <div class="regErrorH">
                <input type="text" name="addressH" class="regDriAddress" placeholder="Address">
                <error>
                    <?php
                            if(isset($_SESSION['addressError'])){
                                echo "<br><e>".$_SESSION['addressError']."</e>";
                            }
                    ?>
                </error>
            </div>

            <div class="regErrorH">
                <input type="text" name="licenseH" class="regDriLicense" placeholder="License No">
                <error>
                    <?php
                            if(isset($_SESSION['licenseError'])){
                                echo "<br><e>".$_SESSION['licenseError']."</e>";
                            }
                    ?>
                </error>
            </div>

            <!-- ...................................................................................................Route details ..................................................................................................... -->

            <div class="regErrorH">
                <input type="text" name="routeNameH" class="regRouteName" placeholder="Route Name">
                <error>
                    <?php
                            if(isset($_SESSION['routeNameError'])){
                                echo "<br><e>".$_SESSION['routeNameError']."</e>";
                            }
                    ?>
                </error>
            </div>

            <div class="regRoutes">
                <div class="regErrorH">
                    <input type="text" name="routeStartH" class="regRouteStart" placeholder="Start Route">
                    <error>
                        <?php
                        if(isset($_SESSION['startRouteError'])){
                            echo "<br><e>".$_SESSION['startRouteError']."</e>";
                        }
                        ?>
                    </error>
                </div>
                
                <div class="regErrorH">
                <input type="text" name="routeEndH" class="regRouteEnd" placeholder="End Route">
                    <error>
                        <?php
                        if(isset($_SESSION['endRouteError'])){
                            echo "<br><e>".$_SESSION['endRouteError']."</e>";
                            }
                        ?>
                    </error>
                </div>
            </div>

            <div class="regErrorH">
                <error>
                <?php
                    if(isset($_SESSION['routeComboError'])){
                        echo "<br><e>".$_SESSION['routeComboError']."</e>";
                    }
                    ?> 
                </error>
            </div>

            <!-- ...................................................................................................Bus details ..................................................................................................... -->

            <div class="regBusInfos">
                <div class="regErrorH">
                    <input type="text" name="busModelH" class="regBusModel" placeholder="Bus Model">
                    <error>
                        <?php
                        if(isset($_SESSION['busModelError'])){
                            echo "<br><e>".$_SESSION['busModelError']."</e>";
                        }
                        ?>
                    </error>
                </div>
                
                <div class="regErrorH">
                <input type="text" name="busRegNoH" class="regRegNo" placeholder="Bus Reg No">
                    <error>
                        <?php
                        if(isset($_SESSION['busRegError'])){
                            echo "<br><e>".$_SESSION['busRegError']."</e>";
                            }
                        ?>
                    </error>
                </div>
            </div>

            <!-- ...................................................................................................Schedule details ..................................................................................................... -->

            <div class="regErrorH">
                <select name="scheduleDayH" class="regSchDay">
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
                    <input type="time" name="scheduleStTimeH" class="regSchStTime" placeholder="Start Time">
                    <error>
                        <?php
                            if(isset($_SESSION['scheduleStError'])){
                                echo "<br><e>".$_SESSION['scheduleStError']."</e>";
                            }
                        ?>
                    </error>
                </div>

                <div class="regErrorH">
                    <input type="time" name="scheduleEndTimeH" class="regSchEndTime" placeholder="Start Time">
                    <error>
                        <?php
                            if(isset($_SESSION['scheduleEndError'])){
                                echo "<br><e>".$_SESSION['scheduleEndError']."</e>";
                            }
                        ?>
                    </error>        
                </div>
            </div>
 
            <div class="regErrorH">
                <error>
                <?php
                    if(isset($_SESSION['scheduleExistsError'])){
                        echo "<br><e>".$_SESSION['scheduleExistsError']."</e>";
                    }
                    ?> 
                </error>
            </div>


           <?php 
                foreach($_SESSION as $key => $value){
                    if($key != 'A_id'){
                        unset($_SESSION[$key]);
                    }
                }
           ?>
            
            <input type="submit" name="registerDriverH" class="regAdd" value="Sign Up">
            <!-- <p class="regPasAlre">Already <a href="../login/loginH.php">Signed up</a>?</p> -->
        </form>
    </alignCenter>
</body>
</html>

<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="../allAdd/allAdd.css">
    <link rel="stylesheet" href="addSchedule.css">
</head>
<body>
    <h2 class="regLogoH">Trackie<sup>BDL</sup></h2>
    <h1 class="regHeaH">Add Schedule</h1>

    <alignCenter>
        <form action="addScheduleP.php" method="post" class="regForm">
            <br><br>
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
                    if($key != 'La_id'){
                        unset($_SESSION[$key]);
                    }
                }
            ?>
            
            <input type="submit" name="registerScheduleH" class="regAdd" value="Add Schedule">
            <!-- <p class="regPasAlre">Already <a href="../login/loginH.php">Signed up</a>?</p> -->
        </form>
    </alignCenter>
</body>
</html>

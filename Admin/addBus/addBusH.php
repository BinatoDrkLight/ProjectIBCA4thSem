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
    <link rel="stylesheet" href="../allAdd/allAdd.css">
    <link rel="stylesheet" href="addBus.css">
</head>
<body>
    <h2 class="regLogoH">Trackie<sup>BDL</sup></h2>
    <h1 class="regHeaH">Add Bus</h1>

    <alignCenter>
        <form action="addBusP.php" method="post" class="regForm">           
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

            <?php 
                foreach($_SESSION as $key => $value){
                    if($key != 'La_id'){
                        unset($_SESSION[$key]);
                    }
                }
            ?>
            
            <input type="submit" name="registerBusH" class="regAdd" value="Add Bus">
            <!-- <p class="regPasAlre">Already <a href="../login/loginH.php">Signed up</a>?</p> -->
        </form>
    </alignCenter>
</body>
</html>

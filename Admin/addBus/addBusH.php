<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bus</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="../allAdd/allAdd.css">
    <link rel="stylesheet" href="addBus.css">
</head>
<body>
    <h2 class="regLogoH">Trackie<sup>BDL</sup></h2>
    <h1 class="regHeaH">Add Bus</h1>

    <div class="form-align-center">
        <form action="addBusP.php" method="post" class="regForm">           
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
                    <input type="text" name="busRegNoH" class="regRegNo" placeholder="Bus Reg No (e.g. BA12KA3456)" required>
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

            <?php 
                foreach($_SESSION as $key => $value){
                    if($key != 'La_id' && $key != 'A_id'){
                        unset($_SESSION[$key]);
                    }
                }
            ?>
            
            <input type="submit" name="registerBusH" class="regAdd" value="Add Bus">
        </form>
    </div>
</body>
</html>

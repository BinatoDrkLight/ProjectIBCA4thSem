<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Route</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="../allAdd/allAdd.css">
    <link rel="stylesheet" href="addRoute.css">
</head>
<body>
    <h2 class="regLogoH">Trackie<sup>BDL</sup></h2>
    <h1 class="regHeaH">Add Route</h1>

    <alignCenter>
        <form action="addRouteP.php" method="post" class="regForm">
            <br><br>
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
                    if(isset($_SESSION['routeExistsError'])){
                        echo "<br><e>".$_SESSION['routeExistsError']."</e>";
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
            <input type="submit" name="registerRouteH" class="regAdd" value="Add Route">
            <!-- <p class="regPasAlre">Already <a href="../login/loginH.php">Signed up</a>?</p> -->
        </form>
    </alignCenter>
</body>
</html>

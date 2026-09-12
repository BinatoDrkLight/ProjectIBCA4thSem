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

    <div class="form-align-center">
        <form action="addRouteP.php" method="post" class="regForm">
            <br><br>
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
                    if(isset($_SESSION['routeExistsError'])){
                        echo "<span class='error-msg'>".htmlspecialchars($_SESSION['routeExistsError'], ENT_QUOTES, 'UTF-8')."</span>";
                        unset($_SESSION['routeExistsError']);
                    }
                    ?> 
                </div>
            </div>
           <?php 
                foreach($_SESSION as $key => $value){
                    if($key != 'La_id' && $key != 'A_id'){
                        unset($_SESSION[$key]);
                    }
                }
           ?>
            <input type="submit" name="registerRouteH" class="regAdd" value="Add Route">
        </form>
    </div>
</body>
</html>

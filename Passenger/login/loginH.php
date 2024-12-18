<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <form action="loginP.php" method="post" class="loginH">
        <h2 class="logLogoH">Trackie<sup>BDL</sup></h2>
        <h1 class="logHeaH">Log In</h1>
    
        <input type="text" name="usernameH" placeholder="Username" class="logUserH"><br><br>
        <input type="text" name="passwordH" placeholder="Password" class="logPassH"><br>
            <error>
                <?php
                    if(isset($_SESSION['logError'])){
                        echo "<br><e style='margin-top: -.8rem;'>".$_SESSION['logError']."</e>";
                        session_unset();
                        session_destroy();
                    }
                ?>
            </error>
        <br>
        <input type="submit" name="submitH" class="logBtnH" value="Log In">
        <p class="logRegH">Don't have an account? <a href="../registerPassenger/registerPassengerH.php"> register</a>!</p>
    </form>
</body>
</html>
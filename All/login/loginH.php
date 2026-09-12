<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../allCss.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <form action="loginP.php" method="post" class="loginH">
        <h2 class="logLogoH">Trackie<sup>BDL</sup></h2>
        <h1 class="logHeaH">Log In</h1>
    
        <input type="text" name="usernameH" placeholder="Username" class="logUserH" required><br><br>
        <input type="password" name="passwordH" placeholder="Password" class="logPassH" required><br>
        <div class="form-error">
            <?php
                if(isset($_SESSION['logError'])){
                    echo "<span class='error-msg'>".htmlspecialchars($_SESSION['logError'], ENT_QUOTES, 'UTF-8')."</span>";
                    unset($_SESSION['logError']);
                }
            ?>
        </div>
        <br>
        <input type="submit" name="submitH" class="logBtnH" value="Log In">
        <a href="../../Admin/loginAdmins/loginAdminH.php"> <p class="logAdminH">Login as admin? </p></a>
        <p class="logRegH">Don't have an account? <a href="../../Passenger/registerPassenger/registerPassengerH.php"> register</a>!</p>
        <br>
        <p class="logGuestH">Continue as <a href="guestLogin.php">Guest.</a></p>
    </form>
</body>
</html>
<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="loginAdmin.css">
</head>
<body>
    <form action="loginAdminP.php" method="post" class="loginH">
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
        <a href="../../All/login/loginH.php"> <p class="logRegH">Login as user?</p> </a>
    </form>
</body>
</html>
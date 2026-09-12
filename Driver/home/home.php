<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="../../All/allMenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="home.css">
    <title>Driver Home</title>
</head>
<body>
    <div>
        <div class="background">
            <?php include '../../All/allLogo.php' ?>
        </div>
        <?php
            include_once('../../All/allHeader.php');
            include_once('driverMenu.php');
        ?>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
<?php
    include_once('../../All/allHeader.php');
?>
    <div class="profileColH">
        <div class="profilePicH">

        </div>
        <div class="profileCateH">
            <h1>Passenger</h1>
        </div>
    </div>
    <div class="profileInfoH">
        <div class="profileInfoLeftH">
            <p>Name: Binesh Bahadur Adhikari</p>
            <p>Age: 20</p>
            <p>Gmail: binesh2adhikari@gmail.com</p>
            <p>Phone no: 9823820865</p>
            <p>User: Passenger</p>

        </div>
        <!-- <div class="profileInfoRightH">
            <p>Bus Company:</p>
            <p>Bus No:</p>
            <p>Color:</p>
            <p>Time:</p>
            <p>Route:</p>
        </div> -->
        <button class="profileMoreH">More</button><br><br>
        <button class="profileLogoutH"><a href="../home/home.php">Logout</a></button>
    </div> 
</body>
</html>
<?php
    session_start();
    if (!isset($_SESSION['Lr_id'])) {
        header("Location: ../../All/login/loginH.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../../All/allCss.css">
    <link rel="stylesheet" href="../../All/allMenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <h1><?php echo (!empty($_SESSION['is_guest'])) ? 'Guest User' : 'Passenger'; ?></h1>
        </div>
    </div>

    <?php
        $isGuest = !empty($_SESSION['is_guest']);
        $fullNameD = $gmailD = $phoneD = $userD = "N/A";

        if ($isGuest) {
            $fullNameD = $_SESSION['guest_name'] ?? 'Guest Passenger';
            $gmailD = $_SESSION['guest_email'] ?? 'guest@trackie.local';
            $phoneD = $_SESSION['guest_phone'] ?? 'N/A';
            $userD = 'Guest Passenger';
        } else {
            // Connect to database for registered passenger
            include_once('../../All/allDatabaseConnection.php');
            $con = dbConnection();

            include_once('../../All/allPreparedStatement.php');

            $lr_id = $_SESSION['Lr_id'];
            $queryProfile = "SELECT CONCAT(p.P_fName, ' ', p.P_sName) AS P_full_name, p.P_gmail, ph.P_phone_no, lr.Lr_user
                            FROM loginregister AS lr 
                            LEFT JOIN passenger AS p 
                            ON lr.P_id = p.P_id
                            LEFT JOIN p_phone_nos AS ph
                            ON p.P_id = ph.P_id
                            WHERE lr.Lr_id = ?;";
            $typeProfile = "s";
            $paramsProfile = [$lr_id];

            $resArrProfile = preparedStmt($queryProfile, $con, $typeProfile, $paramsProfile);
            $stmtProfile = $resArrProfile['stmt'];

            $resultProfileData = mysqli_stmt_get_result($stmtProfile);

            if($row = mysqli_fetch_assoc($resultProfileData)){
                $fullNameD = $row['P_full_name'] ?? 'N/A';
                $gmailD = $row['P_gmail'] ?? 'N/A';
                $phoneD = $row['P_phone_no'] ?? 'N/A';
                $userD = $row['Lr_user'] ?? 'Passenger';
            }
            mysqli_stmt_close($stmtProfile);
            $con->close();
        }
    ?>

    <div class="profileInfoH">
        <div class="profileInfoLeftH">
            <p>Name: <?php echo htmlspecialchars($fullNameD, ENT_QUOTES, 'UTF-8'); ?> </p>
            <p>Gmail: <?php echo htmlspecialchars($gmailD, ENT_QUOTES, 'UTF-8'); ?> </p>
            <p>Phone no: <?php echo htmlspecialchars($phoneD, ENT_QUOTES, 'UTF-8'); ?> </p>
            <p>User: <?php echo htmlspecialchars($userD, ENT_QUOTES, 'UTF-8'); ?> </p>
        </div>
        <button class="profileMoreH" type="button">More</button><br><br>
        <?php if ($isGuest): ?>
            <a href="../registerPassenger/registerPassengerH.php" class="profileLogoutBtn"><button class="profileLogoutH" type="button">Register</button></a>
        <?php else: ?>
            <a href="../../All/logout.php" class="profileLogoutBtn"><button class="profileLogoutH" type="button">Logout</button></a>
        <?php endif; ?>
    </div> 
</body>
</html>
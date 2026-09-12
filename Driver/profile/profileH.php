<?php
    session_start();
    if (!isset($_SESSION['Lr_id']) || ($_SESSION['user_role'] ?? '') !== 'Driver') {
        header("Location: ../../All/login/loginH.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Profile</title>
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
        <div class="profilePicH"></div>
        <div class="profileCateH">
            <h1>Driver</h1>
        </div>
    </div>

<?php
    $fullNameD = $gmailD = $addressD = $licenseD = "N/A";

    include_once('../../All/allDatabaseConnection.php');
    $con = dbConnection();
    include_once('../../All/allPreparedStatement.php');

    $lr_id = $_SESSION['Lr_id'];
    $queryProfile = "SELECT CONCAT(d.D_fName, ' ', d.D_sName) AS D_full_name,
                            lr.Lr_gmail,
                            d.D_address,
                            d.D_license_no
                     FROM loginregister AS lr
                     LEFT JOIN driver AS d ON lr.D_id = d.D_id
                     WHERE lr.Lr_id = ?;";

    $resArrProfile = preparedStmt($queryProfile, $con, "s", [$lr_id]);
    $stmtProfile = $resArrProfile['stmt'];
    $resultProfileData = mysqli_stmt_get_result($stmtProfile);

    if ($row = mysqli_fetch_assoc($resultProfileData)) {
        $fullNameD  = $row['D_full_name']  ?? 'N/A';
        $gmailD     = $row['Lr_gmail']     ?? 'N/A';
        $addressD   = $row['D_address']    ?? 'N/A';
        $licenseD   = $row['D_license_no'] ?? 'N/A';
    }
    mysqli_stmt_close($stmtProfile);
    $con->close();
?>

    <div class="profileInfoH">
        <div class="profileInfoLeftH">
            <p>Name: <?php echo htmlspecialchars($fullNameD, ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Gmail: <?php echo htmlspecialchars($gmailD, ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Address: <?php echo htmlspecialchars($addressD, ENT_QUOTES, 'UTF-8'); ?></p>
            <p>License No: <?php echo htmlspecialchars($licenseD, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <button class="profileMoreH" type="button">More</button><br><br>
        <a href="../../All/logout.php" class="profileLogoutBtn">
            <button class="profileLogoutH" type="button">Logout</button>
        </a>
    </div>
</body>
</html>

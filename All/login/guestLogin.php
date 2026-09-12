<?php
session_start();
session_regenerate_id(true);

// Generate a random guest passenger ID
$randomGuestId = rand(50000, 99999);

// Populate session so the guest can perform all passenger activities
$_SESSION['is_guest'] = true;
$_SESSION['P_id'] = $randomGuestId;
$_SESSION['Lr_id'] = "guest_" . $randomGuestId;
$_SESSION['user_role'] = 'Passenger';
$_SESSION['guest_name'] = 'Guest Passenger #' . $randomGuestId;
$_SESSION['guest_email'] = 'guest' . $randomGuestId . '@trackie.local';
$_SESSION['guest_phone'] = '980' . rand(1000000, 9999999);

// Redirect to Passenger Home page
header("Location: ../../Passenger/home/home.php");
exit();
?>

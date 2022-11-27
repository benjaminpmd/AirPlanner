<?php
$page_title = "Portail de réservation";
$page_date = "23 Octobre 2022";
$page_canonical = "/booking.php";
$bg_path = "/img/booking_bg.jpg";

include "./include/header.inc.php";

if ($session->is_logged() && $user->is_pilot()) {
    include "./include/booking.inc.php";
}
else {
    include "./include/connection.inc.php";
}

include "./include/footer.inc.php";
?>
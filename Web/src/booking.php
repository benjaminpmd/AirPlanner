<?php
$page_title = "Portail de réservation";
$page_date = "23 Octobre 2022";
$page_canonical = "/booking.php";

include "./include/header.inc.php";

if ($session->is_logged() && $user->is_pilot()) {
    include "./include/booking.inc.php";
}
else {
    include "./include/connection.inc.php";
}

include "./include/footer.inc.php";
?>
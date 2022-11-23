<?php
$page_title = "Réservation";
$page_date = "23 Novembre 2022";
$page_canonical = "/book.php";

include "./include/header.inc.php";

if ($session->is_logged() && $user->is_pilot()) {
    include "./include/booking.inc.php";
}
else {
    include "./include/connection.inc.php";
}

include "./include/footer.inc.php";
?>
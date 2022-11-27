<?php
$page_title = "Réservation";
$page_date = "23 Novembre 2022";
$page_canonical = "/book.php";
$bg_path = "/img/booking_bg.jpg";

include "./include/header.inc.php";

if ($session->is_logged() && $user->is_pilot()) {
    include "./include/book.inc.php";
}
else {
    include "./include/connection.inc.php";
}

include "./include/footer.inc.php";
?>
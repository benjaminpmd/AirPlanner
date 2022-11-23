<?php
$page_title = "Portail de réservation";
$page_date = "23 Octobre 2022";
$page_canonical = "/booking.php";

include "./include/header.inc.php";

if ($session->is_logged() && $user->is_pilot()) {
    include "./include/booking.inc.php";
}
else {
    echo "<h2>Veuillez vous connecter</h2>\n";
}

include "./include/footer.inc.php";
?>
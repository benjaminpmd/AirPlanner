<?php
$page_title = "Saisie de vol";
$page_date = "26 Novembre 2022";
$page_canonical = "/record.php";

include "./include/header.inc.php";

if ($session->is_logged() && $user->is_pilot()) {
    include "./include/record.inc.php";
}
else {
    include "./include/connection.inc.php";
}

include "./include/footer.inc.php";
?>
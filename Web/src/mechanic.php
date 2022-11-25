<?php
$page_title = "Portail Mécanicien";
$page_date = "26 Novembre 2022";
$page_canonical = "/mechanic.php";

include "./include/header.inc.php";

if ($session->is_logged() && $user->is_mechanic()) {
    include "./include/mechanic.inc.php";
}
else {
    include "./include/connection.inc.php";
}

include "./include/footer.inc.php";
?>

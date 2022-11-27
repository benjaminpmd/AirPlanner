<?php
$page_title = "Mon compte";
$page_date = "23 Octobre 2022";
$page_canonical = "/account.php";
$bg_path = "/img/account_bg.jpg";

include "./include/header.inc.php";

if ($session->is_logged() && $user->is_pilot()) {
    include "./include/account.inc.php";
}
else {
    include "./include/connection.inc.php";
}

include "./include/footer.inc.php";
?>

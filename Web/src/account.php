<?php
$page_title = "Mon compte";
$page_date = "23 Octobre 2022";
$page_canonical = "/account.php";

include "./include/header.inc.php";
?>
    <section>
        <h2 class="text-center text-2xl">Page en cours de construction</h2>
        <article>
            <h3><?php echo $_SESSION["user_id"] ?></h3>
        </article>
    </section>
<?php
include "./include/footer.inc.php";
?>
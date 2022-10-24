<?php
$page_title = "Inscription";
$page_date = "17 Octobre 2022";
$page_canonical = "/register.php";

include_once "./include/utils.inc.php";

include "./include/header.inc.php";
?>
<main class="min-h-screen">
    <section class="text-center">
    <h2 class="text-2xl p-4">Inscription sur le portail du club</h2>
    <form 
            action="/register.php"
            method="post"
            class="flex flex-col text-left rounded-xl max-w-xl m-auto p-5 bg-gray-200 dark:bg-gray-700"
        >
            <label class="text-center mt-2 text-red-600 dark:text-red-500">
            <?php
                if (!$valid_credentials) {
                    echo "\t\t\t\t\tIdentifiant ou mot de passe incorrect.\n";
                }
            ?>
            </label>




            <label class="p-2 text-xl">
                Prénom
            </label>
            <input
                type="text"
                name="user-firstname"
                class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
                <?php
                    if(isset($_POST["user-firstname"]) && !empty($_POST["user-firstname"])) {
                        echo "\t\t\t\tvalue=\"".$_POST["user-firstname"]."\"\n";
                    }
                ?>
            />

            <label class="p-2 text-xl">
                Nom
            </label>
            <input
                type="text"
                name="user-lastname"
                class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
                <?php
                    if(isset($_POST["user-lastname"]) && !empty($_POST["user-lastname"])) {
                        echo "\t\t\t\tvalue=\"".$_POST["user-lastname"]."\"\n";
                    }
                ?>
            />

            <label class="p-2 text-xl">
                Mot de passe
            </label>
            <input 
                type="password"
                name="user-password"
                class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
                <?php
                    if(isset($_POST["user-password"]) && !empty($_POST["user-password"])) {
                        echo "\t\t\t\tvalue=\"".$_POST["user-password"]."\"\n";
                    }
                ?>
            />

            <label class="p-2 text-xl">
                Confirmez le Mot de passe
            </label>
            <input 
                type="password"
                name="user-password-confirmation"
                class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
                <?php
                    if(isset($_POST["user-password-confirmation"]) && !empty($_POST["user-password-confirmation"])) {
                        echo "\t\t\t\tvalue=\"".$_POST["user-password-confirmation"]."\"\n";
                    }
                ?>
            />

            <input
                type="submit"
                class="cursor-pointer mx-2 mt-4 p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600"
                value="S'inscrire"
            />
        </form>
    </section>
</main>
<?php
include "./include/footer.inc.php";
?>
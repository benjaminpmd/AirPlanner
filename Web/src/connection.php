<?php
$page_title = "Connexion";
$page_date = "17 Octobre 2022";
$page_canonical = "/connection.php";

include "./include/header.inc.php";
include_once "./include/utils.inc.php";

?>
<main class="min-h-screen text-center">
        <h2 class="text-2xl p-4">Connectez vous</h2>
        <form 
            action="/connection.php"
            method="post"
            class="flex flex-col text-left rounded-xl max-w-xl m-auto p-5 bg-gray-200 dark:bg-gray-700"
        >
            <label 
                for="user-id"
                class="p-2 text-xl"
            >
                Identifiant
            </label>
            <input
                type="number"
                name="user-id"
                class="p-2 m-2 max-w-[200px] rounded-full bg-gray-400 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 duration-300 outline-none"
                <?php
                    if(isset($_POST["user-id"]) && !empty($_POST["user-id"])) {
                        echo "\t\t\t\tvalue=\"".$_POST["user-id"]."\"\n";
                    }
                ?>
            />
            <label
                for="user-password"
                class="p-2 text-xl"
            >
                Mot de passe
            </label>
            <input 
                type="password"
                name="user-password"
                class="p-2 m-2 rounded-full border-2 border-gray-700 dark:border-gray-500 bg-gray-400 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 duration-300 outline-none"
                <?php
                    if(isset($_POST["user-password"]) && !empty($_POST["user-password"])) {
                        echo "\t\t\t\tvalue=\"".$_POST["user-password"]."\"\n";
                    }
                ?>
            />
            <input
                type="submit"
                class=" cursor-pointer mx-2 mt-4 p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600"
                value="Se connecter"
            />
            <label class="text-center mt-2 text-red-600 dark:text-red-500">
            <?php
            if (!$valid_credentials) {
                echo "\t\t\t\t\tIdentifiant ou mot de passe incorrect.\n";
            }
            ?>
            </label>
        </form>
    </section>
    <section>
    <h2 class="text-2xl p-4">Un problème ? Pas encore inscrit ?</h2>
    </section>
</main>
<?php
include "./include/footer.inc.php";
?>
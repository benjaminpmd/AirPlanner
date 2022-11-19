<?php
$page_title = "Connexion";
$page_date = "17 Octobre 2022";
$page_canonical = "/connection.php";

include "./include/header.inc.php";
include_once "./include/utils.inc.php";

?>
    <section>
        <h2 class="text-2xl text-center">Connectez vous</h2>
        <article class="m-4">
            <form 
                action="/login.php"
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
                <label 
                    class="p-2 text-xl"
                >
                    Adresse email
                </label>
                <input
                    type="email"
                    name="login-email"
                    class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
                    <?php
                        if(isset($_POST["login-email"]) && !empty($_POST["login-email"])) {
                            echo "\t\t\t\tvalue=\"".$_POST["login-email"]."\"\n";
                        }
                    ?>
                />
                <label
                    class="p-2 text-xl"
                >
                    Mot de passe
                </label>
                <input 
                    type="password"
                    name="login-password"
                    class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
                    <?php
                        if(isset($_POST["login-password"]) && !empty($_POST["login-password"])) {
                            echo "\t\t\t\tvalue=\"".$_POST["login-password"]."\"\n";
                        }
                    ?>
                />
                <input
                    type="submit"
                    class="cursor-pointer mx-2 mt-4 p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600"
                    value="Se connecter"
                />
            </form>
        </article>
    </section>
    <section>
        <h2 class="text-2xl p-4 text-center">Un problème ? Pas encore inscrit ?</h2>
        <article class="m-4" id="reset-password">
            <h3 class="text-xl p-2 text-center">Mot de passe perdu ?</h3>
            <form 
                action="/login.php#reset-password"
                method="post"
                class="flex flex-col text-left rounded-xl max-w-xl m-auto p-5 bg-gray-200 dark:bg-gray-700"
            >
            <label class="text-center mt-2 text-xl">
                <?php
                if (isset($password_reset) && !empty($password_reset) && $password_reset) {
                    echo $password_reset;
                }
                ?>
            </label>
            <label 
                class="p-2 text-xl"
            >
                Adresse email
            </label>
            <input
                type="email"
                name="reset-email"
                class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
            />
            <input
                type="submit"
                class="cursor-pointer mx-2 mt-4 p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600"
                value="Obtenir un nouveau mot de passe"
            />
        </form>
        </article>
        <article class="p-4 text-center">
            <h3 class="text-xl p-2">Pour s'inscrire, c'est par ici !</h3>
            <a
                href="/register.php"
                class="cursor-pointer mx-2 mt-4 p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600"
            >
                S'enregistrer sur le portail du club
            </a>
        </article>
    </section>
<?php
include "./include/footer.inc.php";
?>
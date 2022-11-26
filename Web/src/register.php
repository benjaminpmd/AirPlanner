<?php
$page_title = "Inscription";
$page_date = "17 Octobre 2022";
$page_canonical = "/register.php";

include_once "./include/utils.inc.php";

include_once "./include/functions.inc.php";

include "./include/header.inc.php";
?>

    <section class="text-center">
      <h2 class="text-2xl p-4 pt-0">Inscription sur le portail du club</h2>
      <div class="p-4 pt-0">
        <form action="/register.php" method="post" class="flex flex-col text-left rounded-xl max-w-xl m-auto p-5 bg-gray-200 dark:bg-gray-700">
          <label class="text-center mt-2 text-xl">
            <?php
            if ($register_message) {
              echo $register_message;
            }
            ?>
          </label>

          <?php
          foreach (get_register_elements() as $item) {
            echo "\t\t\t\t<label class=\"p-2 text-xl\">" . $item["name"] . "</label>\n";
            echo "\t\t\t<input type=\"" . $item["type"] . "\" name=\"" . $item["id_name"] . "\" class=\"p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none\"";
            if (isset($_POST[$item["id_name"]]) && !empty($_POST[$item["id_name"]])) {
              echo " value=\"" . $_POST[$item["id_name"]] . "\"";
            }
            echo ">\n";
          }
          ?>

          <input type="submit" class="cursor-pointer mx-2 mt-4 p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600" value="S'inscrire" />
        </form>
      </div>
    </section>
<?php
include "./include/footer.inc.php";
?>
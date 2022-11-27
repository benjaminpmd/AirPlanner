<?php
include "./include/functions.inc.php";
include_once "./classes/User.class.php";

$complete_op_message = complete_operation();

$create_op_message = create_operation($user->get_user_id());

$aircrafts = get_aircrafts();

$operations = get_operations($user->get_user_id());

?>
<section class="mx-10 md:m-auto md:max-w-3xl p-5 rounded-xl items-center object-center bg-gray-200 dark:bg-gray-700">
  <h2 class="text-center text-3xl">Appareils en maintenance</h2>
  <?php
    if ($complete_op_message != "") echo "<h3 class=\"text-center text-xl p-3\">$complete_op_message</h3>\n";
    else if ($create_op_message != "") echo "<h3 class=\"text-center text-xl p-3\">$create_op_message</h3>\n";
  ?>
  <?php
    foreach($operations as $key => $operation) {
      echo "<form><label>".$operation['aircraft_reg']."</label><input type=\"hidden\" name=\"type\" value=\"complete-operation\" /><input type=\"hidden\" name=\"registration\" value=\"".$operation['aircraft_reg']."\" /><input type=\"text\" name=\"description\" class=\"p-2 m-2 w-full rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none\" /><input type=\"submit\" value=\"Terminer l'opération\" class=\"p-2 m-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600 cursor-pointer\" /></form>\n";
    }
  ?>

  <article class="text-center border-t-2 border-slate-400 dark:border-slate-500">
    <h3 class="text-xl">Prévoir une nouvelle opération</h3>
    <form>
      <input type="hidden" name="type" value="new-operation" />
      <select name="registration" class="input-value">
        <?php
        foreach($aircrafts as $key => $aircraft) {
          $in_service = true;
          foreach($operations as $key => $operation) {
            if ($operation["aircraft_reg"] == $aircraft["registration"]) $in_service = false;
          }

          if ($in_service) {
            echo "<option value=\"".$aircraft["registration"]."\">".$aircraft["registration"]."</option>\n";
          }
        }
        ?>
      </select>

      <input type="submit" value="Bloquer l'appareil" class="input-submit" />
    </form>
  </article>
</section>





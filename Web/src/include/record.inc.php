<?php
include "./include/functions.inc.php";
include_once "./classes/User.class.php";

$message = save_flight_record();

$flight = get_current_flight($user->get_user_id());
$flight_exist = false;

if (count($flight) > 0) {
  $flight_exist = true;
}
?>

<section class="mx-10 md:m-auto md:max-w-3xl p-5 rounded-xl items-center object-center bg-gray-200 dark:bg-gray-700">
  <h2 class="text-center text-3xl">
    <?php
    if ($flight_exist) {
      echo "Enregistrement d'un vol avec l'appareil ".$flight["registration"];
    }
    else echo "Aucun vol à saisir pour le moment";
    ?>
  </h2>
  <?php
  if ($message != "") echo "<h3 class=\"text-center text-xl p-3\">$message</h3>\n";
  ?>
  <form class="grid grid-cols-1 md:grid-cols-4 items-center">

    <input type="hidden" name="registration" value="<?php if($flight_exist) echo $flight["registration"]; ?>" />
    
    <label>Terrain de départ</label>
    <input
      type="text"
      name="departure"
      maxlength="4"
      required
      class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
    />
    
    <label>Terrain d'arrivée</label>
    <input 
      type="text" 
      name="arrival"
      maxlength="4"
      required
      class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
    />
    
    <label>Compteur de départ</label>
    <input 
      type="text"
      readonly="readonly"
      name="departure-counter" 
      required
      value="<?php if($flight_exist) echo float_to_time($flight["aircraft_counter"]) ?>"
      class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
    />
    
    <label>Compteur d'arrivée</label>
    <input 
      type="text" 
      name="arrival-counter"
      required
      class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
    />
    
    <label class="md:col-span-3">Mouvements (1 décollage = 1 mouvement, 1 atterrissage = 1 mouvement)</label>
    <input 
      type="number" 
      name="movements"
      required
      min="2"
      value="2"
      class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
    />


    <label>Ajout carburant</label>
    <input 
      type="number" 
      name="added-fuel"
      class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
    />

    <label class="md:col-span-4">Remarques</label>
    <input 
      type="text" 
      name="description"
      class="p-2 m-2 md:col-span-4 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none"
    />

    <input type="submit" value="enregistrer" class="p-2 m-2 md:col-span-4 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600 cursor-pointer" />
  </form>
</section>
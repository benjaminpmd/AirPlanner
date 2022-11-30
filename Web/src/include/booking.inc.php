<?php
include "./include/functions.inc.php";
include_once "./classes/User.class.php";

$allowed_aircrafts = get_allowed_aircrafts($user->get_user_id());
$aircrafts = get_aircrafts();
$instructors = get_instructors();
$operations = get_all_operations();
?>
<section class="mx-8 md:m-auto md:max-w-3xl p-5 rounded-xl items-center object-center bg-gray-200 dark:bg-gray-700">
  <h2 class="text-center text-2xl">Flotte</h2>
  <table class="m-auto">
    <thead>
      <tr class="border-b-2 border-slate-400 dark:border-slate-500">
        <th class="p-2">Immatriculation</th>
        <th class="p-2">Type</th>
        <th class="p-2 hidden md:table-cell">Distance franchissable</th>
        <th class="p-2 hidden md:table-cell">Nuit</th>
        <th class="p-2 hidden md:table-cell">IFR</th>
        <th class="p-2 hidden md:table-cell">Pas Variable</th>
        <th class="p-2 hidden md:table-cell">Train Rentrant</th>
        <th class="p-2"></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($aircrafts as $key => $aircraft) {
        
        foreach ($aircraft as $key => $value) {
          if ($value == 't') {
            $aircraft[$key] = "Oui";
          } else if ($value == 'f') {
            $aircraft[$key] = "Non";
          }
        }
        
        $is_allowed = false;

        foreach ($allowed_aircrafts as $key => $a_aircraft) {
          if ($a_aircraft["registration"] == $aircraft["registration"]) {
            $is_allowed = true;
          }
        }

        echo "<tr class=\"border-b-2 border-slate-300 dark:border-slate-600 text-center\">
                <th class=\"p-2\">" . $aircraft["registration"] . "</th>
                <td class=\"p-2\">" . $aircraft["aircraft_type"] . "</td>
                <td class=\"p-0 hidden md:p-2 md:table-cell\">" . $aircraft["aircraft_range"] . " km</td>
                <td class=\"p-0 hidden md:p-2 md:table-cell\">" . $aircraft["night_qualified"] . "</td>
                <td class=\"p-0 hidden md:p-2 md:table-cell\">" . $aircraft["ifr_qualified"] . "</td>
                <td class=\"p-0 hidden md:p-2 md:table-cell\">" . $aircraft["has_vpp"] . "</td>
                <td class=\"p-0 hidden md:p-2 md:table-cell\">" . $aircraft["has_rg"] . "</td>
                <td class=\"p-2\"><a class=\"p-2 rounded-full duration-300";
        if (!$is_allowed) {
          echo " bg-red-300 dark:bg-red-700 hover:bg-red-400 dark:hover:bg-red-600 cursor-not-allowed\" aria-disabled=\"true\"";
        }
        else {
          echo " bg-sky-300 dark:bg-sky-700 hover:bg-sky-400 dark:hover:bg-sky-600\"";
        }
        echo " disallow href=\"book.php?registration=" . $aircraft["registration"] . "\">Réserver</a></td></tr>\n";
      }
      ?>
    </tbody>
  </table>
</section>

<section class="m-8 md:mx-auto md:max-w-3xl p-5 rounded-xl items-center object-center bg-gray-200 dark:bg-gray-700">
  <h2 class="text-center text-2xl">Instructeurs</h2>
  <table class="m-auto">
    <thead>
      <tr class="border-b-2 border-slate-400 dark:border-slate-500">
        <th class="p-2">Prénom</th>
        <th class="p-2">Nom</th>
        <th class="p-2">Code</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($instructors as $key => $instructor) {
        foreach ($instructor as $key => $value) {
          if ($value == 't') {
            $aircraft[$key] = "Oui";
          } else if ($value == 'f') {
            $aircraft[$key] = "Non";
          }
        }
        echo "<tr class=\"border-b-2 border-slate-300 dark:border-slate-600 text-center\">
                        <th class=\"p-2\">" . $instructor["first_name"] . "</th>
                        <td class=\"p-2\">" . $instructor["last_name"] . "</td>
                        <td class=\"p-2\">" . $instructor["fi_code"] . "</td>
                    </tr>\n";
      }
      ?>
    </tbody>
  </table>
</section>
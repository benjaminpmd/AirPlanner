<?php
include "./include/functions.inc.php";
include_once "./classes/User.class.php";

$pilot_data = $user->get_pilot_data();

$message = null;

if ($pilot_data["rib"] && $_POST["recharge-amount"]) {
  $res = update_balance($user->get_user_id(), $_POST["recharge-amount"]);
  $message = $res["message"];
  $pilot_data["balance"] = intval($pilot_data["balance"]) + $res["amount"];
}
?>

<section class="p-5 mx-auto flex flex-col">
  <h2 class="text-center text-3xl p-2">Bonjour <?php echo $user->get_first_name() . " " . $user->get_last_name(); ?></h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-14 md:mx-20">
    <article class="flex flex-col text-left rounded-xl w-full p-5 bg-gray-200 dark:bg-gray-700">
      <h3 class="text-center text-xl p-2">Mes informations personnelles</h3>
      <form action="/account.php" method="GET" class="flex flex-col text-left">

        <label class="input-label">Prénom</label>
        <input type="text" name="user-firstname" value="<?php echo $user->get_first_name(); ?>" readonly class="input-value" />

        <label class="input-label">Nom</label>
        <input type="text" name="user-lastname" value="<?php echo $user->get_last_name(); ?>" readonly class="input-value" />

        <label class="input-label">Date de naissance</label>
        <input type="date" name="user-birthdate" value="<?php echo $pilot_data["birth_date"]; ?>" readonly class="input-value" />

        <label class="input-label">Adresse email</label>
        <input type="email" name="user-email" value="<?php echo $user->get_email(); ?>" readonly class="input-value" />

        <label class="input-label">Adresse postale</label>
        <input type="text" name="user-address" value="<?php echo $pilot_data["pilot_address"]; ?>" readonly class="input-value" />

        <label class="input-label">Ville</label>
        <input type="text" name="user-city" value="<?php echo $pilot_data["city"]; ?>" readonly class="input-value" />

        <label class="input-label">Code postale</label>
        <input type="number" name="user-postal-code" value="<?php echo intval($pilot_data["postal_code"]); ?>" readonly class="input-value" />
      </form>
    </article>

    <article class="flex flex-col text-left rounded-xl p-5 bg-gray-200 dark:bg-gray-700">
      <h3 class="text-center text-xl p-2">Mon profil club</h3>
      <ul class="text-xl">
        <li>Heures de vol : <?php echo $pilot_data["pilot_counter"]; ?></li>
        <li>Montant compte aéroclub : <?php echo $pilot_data["balance"]; ?></li>
        <li>Contribution club payée le <?php echo $pilot_data["contribution_date"]; ?></li>
        <li>Dernière visite médicale le <?php echo $pilot_data["medical_check_date"]; ?></li>
        <li>Élève : <?php echo $user->is_student() ? "Oui" : "Non"; ?></li>
        <li>Instructeur : <?php echo $user->is_fi() ? "Oui" : "Non"; ?></li>
        <li>Qualifié(e) VFR Nuit : <?php echo ($pilot_data["night_qualified"] == "t") ? "Oui" : "Non"; ?></li>
        <li>Qualifié(e) IFR : <?php echo ($pilot_data["ifr_qualified"] == "t") ? "Oui" : "Non"; ?></li>
        <li>Qualifié(e) Pas variable : <?php echo ($pilot_data["vpp_qualified"] == "t") ? "Oui" : "Non"; ?></li>
        <li>Qualifié(e) Trains rentrants : <?php echo ($pilot_data["rg_qualified"] == "t") ? "Oui" : "Non"; ?></li>
      </ul>
    </article>
    <article class="flex flex-col text-left rounded-xl p-5 bg-gray-200 dark:bg-gray-700" id="balance">
      <h3 class="text-center text-xl p-2">Alimenter mon compte</h3>
      <?php 
      if ($message) {
        echo "<p class=\"text-center p-2\">$message</hp>";
      }
      if (!$pilot_data["rib"]) {
        echo "<p class=\"text-red-600 dark:text-red-400 text-center\">Veuillez fournir un rib afin de pouvoir alimenter votre compte en ligne</p>\n";
      }
      else {
        echo '<form action="/account.php#balance" method="POST" class="flex flex-col text-left"><label class="input-label">Montant à recharger</label><input type="number" min="1" name="recharge-amount" class="input-value" /><input type="submit" class="cursor-pointer mx-2 mt-4 p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600" value="Payer" /></form>';
      }
      ?>
    </article>
  </div>
</section>
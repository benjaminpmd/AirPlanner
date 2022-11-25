<section class="p-5 mx-auto flex flex-col">
  <h2 class="text-center text-2xl p-2">Bonjour <?php echo $_SESSION["user_first_name"] . " " . $_SESSION["user_last_name"]; ?></h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-14 md:mx-20">
    <article class="flex flex-col text-left rounded-xl w-full p-5 bg-gray-200 dark:bg-gray-700">
      <h3 class="text-center text-xl">Mes informations personnelles</h3>
      <form action="/account.php" method="post" class="flex flex-col text-left">

        <label class="p-2 text-xl">Prénom</label>
        <input type="text" name="user-firstname" value="<?php echo $_SESSION["user_first_name"]; ?>" class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none" />

        <label class="p-2 text-xl">Nom</label>
        <input type="text" name="user-lastname" value="<?php echo $_SESSION["user_last_name"]; ?>" class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none" />

        <label class="p-2 text-xl">Date de naissance</label>
        <input type="date" name="user-birthdate" value="<?php echo $_SESSION["pilot"]["birth_date"]; ?>" class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none" />

        <label class="p-2 text-xl">Adresse email</label>
        <input type="email" name="user-email" value="<?php echo $_SESSION["user_email"]; ?>" class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none" />

        <label class="p-2 text-xl">Adresse postale</label>
        <input type="text" name="user-address" value="<?php echo $_SESSION["pilot"]["pilot_address"]; ?>" class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none" />

        <label class="p-2 text-xl">Ville</label>
        <input type="text" name="user-city" value="<?php echo $_SESSION["pilot"]["city"]; ?>" class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none" />

        <label class="p-2 text-xl">Code postale</label>
        <input type="number" name="user-postal-code" value="<?php echo intval($_SESSION["pilot"]["postal_code"]); ?>" class="p-2 m-2 rounded-full border-2 border-gray-500 dark:border-gray-500 hover:border-gray-600 hover:dark:border-gray-400 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 duration-300 outline-none" />

        <input type="submit" class="cursor-pointer mx-2 mt-4 p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600" value="Enregistrer" />
      </form>
    </article>

    <article class="flex flex-col text-left rounded-xl p-5 bg-gray-200 dark:bg-gray-700">
      <h3 class="text-center text-xl">Mon profil club</h3>
      <ul>
        <li>Heures de vol : <?php echo $_SESSION["pilot"]["pilot_counter"]; ?></li>
        <li>Montant compte aéroclub : <?php echo $_SESSION["pilot"]["balance"]; ?></li>
        <li>Contribution club payée le <?php echo $_SESSION["pilot"]["contribution_date"]; ?></li>
        <li>Dernière visite médicale le <?php echo $_SESSION["pilot"]["medical_check_date"]; ?></li>
        <li>Élève : </li>
        <li>(si élève) Instructeur référant : </li>
        <li>Instructeur : </li>
        <li>Qualifié(e) VFR Nuit : <?php echo $_SESSION["pilot"]["night_qualified"] ? "Non" : "Oui"; ?></li>
        <li>Qualifié(e) IFR : <?php echo $_SESSION["pilot"]["ifr_qualified"] ? "Non" : "Oui"; ?></li>
        <li>Qualifié(e) Pas variable : <?php echo $_SESSION["pilot"]["vpp_qualified"] ? "Non" : "Oui"; ?></li>
        <li>Qualifié(e) Trains rentrants : <?php echo $_SESSION["pilot"]["rg_qualified"] ? "Non" : "Oui"; ?></li>
      </ul>
    </article>
  </div>
</section>
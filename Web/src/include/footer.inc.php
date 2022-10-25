<?php
include_once './include/utils.inc.php';
?>
</main>
<footer class="pb-2 flex flex-col content-center items-center text-center bg-gray-300 dark:bg-gray-900">
  
  <div class="w-5/6">
    <div class="w-full grid grid-cols-1 md:grid-cols-5">

      <div class="text-left col-span-2 flex flex-col h-full my-5 mr-10">
        <p class="text-5xl"><span class="text-blue-500">Air</span><span class="text-white">Plan</span><span class="text-red-500">ner</span></p>
        <p class="opacity-70"><?php echo WEBSITE_NAME; ?> simplifie la gestion d'aéroclubs en proposant un système de réservation tout en main.</p>
        <p class="opacity-70">Ce projet est réalisé dans le cadre du projet des UE Base de données et Réseau de la licence informatique de Cergy Paris Université.</p>
      </div>
      
      <div class="text-left flex flex-col h-full mt-5">
        <p class="font-bold text-xl opacity-70">Navigation</p>
        <ul>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL; ?>">Accueil</a></li>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL . "/booking.php"; ?>">Réservation</a></li>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL . "/account.php"; ?>">Compte</a></li>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL . "/about.php"; ?>">A propos</a></li>
        </ul>
      </div>

      <div class="text-left flex flex-col h-full mt-5">
        <p class="font-bold text-xl opacity-70">Connection</p>
        <ul>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL . "/connection.php"; ?>">Connection</a></li>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL . "/register.php"; ?>">Inscription</a></li>
        </ul>
        <p class="font-bold text-xl opacity-70 mt-5">Administration</p>
        <ul>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL . "/mechanic.php"; ?>">Portail Mécanicien</a></li>
        </ul>
      </div>

      <div class="text-left flex flex-col h-full mt-5">
        <p class="font-bold text-xl opacity-70">Réseaux</p>
        <ul>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="https://github.com/benjaminpmd/AC-Solutions">Github</a></li>
        </ul>
        <p class="font-bold text-xl opacity-80 mt-5">Réalisation</p>
        <ul>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="https://benjaminp.dev">Benjamin P</a></li>
          <li><a class="md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="https://github.com/Evafltry">Eva F</a></li>
          <li>Xuming M</li>
        </ul>
      </div>

    </div>
  </div>

  <div class="border-[1px] w-5/6 m-3 border-gray-500 dark:border-gray-500"></div>

  <div class="my-0 text-left flex">
    <a class="text-gray-700 dark:text-gray-300 mx-5 md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL . "/terms-of-service.php" ?>">Conditions d'utilisation</a>
    <a class="text-gray-700 dark:text-gray-300 mx-5 md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out" href="<?php echo WEBSITE_URL . "/privacy-policy.php" ?>">Politique de confidentialité</a>
    <p class="text-gray-700 dark:text-gray-300 mx-5 ml-10">Tous droits réservés</p>
  </div>
</footer>
</body>

</html>
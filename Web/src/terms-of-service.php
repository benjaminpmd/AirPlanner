<?php
$page_title = "Conditions d'utilisation";
$page_date = "17 Octobre 2022";
$page_canonical = "/terms-of-service.php";

include "./include/header.inc.php";
?>
<section class="mx-10 md:m-auto md:max-w-3xl p-5 text-center rounded-xl items-center object-center bg-gray-200 dark:bg-gray-700">
  <h2 class="text-center text-2xl p-5">Conditions d'utilisation</h2>
  <h3 class="text-center text-2xl p-5">Informations du site</h3>
  <p>AirPlanner a été créé afin de gérer des réservations de vols à partir d'informations dans la base de données.
    AirPlanner peut les modifier et les mettre à jour à tout moment sans préavis.</p>
  <p>Ce site étant un projet réalisé dans un cadre universitaire, les services offerts restent fictifs. Les auteurs d'AirPlanner ne peuvent être tenu responsable de tout usage déplacé des services simulés.</p>

  <h3 class="text-center text-2xl p-5">Confidentialité</h3>
  <p>AirPlanner protège vos données entrées sur le site. Veuillez consulter <a href="<?php echo WEBSITE_URL . "/privacy-policy.php"; ?>">la politique de confidentialité</a>
    pour plus d'informations concernant nos termes de confidentialité</p>

  <h3 class="text-center text-2xl p-5">Propriété intellectuelle</h3>
  <h4 class="text-center text-2xl p-5">Contenus du site</h4>
  <p>Toutes les informations, photos, bases de données contenus sur ce site internet sont la propriété d'AirPlanner ou des
    documents libres de droits et d'utilisation.Ces informations, documents ou éléments sont soumis aux lois protégeant
    le droit d'auteur dès lors qu'elles sont mises à la disposition du public sur ce site internet.
    Aucune licence, ni aucun droit autre que celui de consulter le site internet, n'est conféré à quiconque au regard
    des droits de propriété intellectuelle. </p>

  <h4 class="text-center text-2xl p-5">Base de données</h4>
  <p>Les éventuelles bases de données mises à votre disposition sont la propriété de la Société qui a la qualité
    de producteur de bases de données. Il vous est interdit d'extraire ou de réutiliser une partie qualitativement ou
    quantitativement substantielle des bases de données y compris à des fins privées.</p>

  <h3 class="text-center text-2xl p-5">Gestion des accès et mots de passe</h3>
  <p>Lors de la création d'un nouveau compte, un mail vous est envoyé à l'adresse email associée au compte.
    Ce mail contient votre mot de passe que vous pouvez ensuite changer par la suite.
    AirPlanner vous permet également de recréer un mot de passe dans le cas de la perte de ce dernier, et ce
    grâce à votre adresse email.
  </p>
  <h3 class="text-center text-2xl p-5">Gestion du porte monnaie</h3>
  <p>Afin de reserver les vols, il est nécessaire de rentrer de l'argent dans le site sous forme de remplissage d'un
    porte monnaie du site. AirPlanner s'engage à ne pas utiliser cet argent autre que pour les achats faits par l'utilisateur.
    AirPlanner n'est aucunement responsable en cas d'erreur de saisie de montant ou de mauvaise utilisation de cette fonctionnalité.
  </p>
</section>
<?php
include "./include/footer.inc.php";
?>
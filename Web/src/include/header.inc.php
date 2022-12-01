<?php
# initial declarations
declare(strict_types=1);
include_once "./include/utils.inc.php";
include_once "./classes/Session.class.php";
include_once "./classes/User.class.php";

$login_message = $session->login();

$session->disconnect();

$password_reset_message = $user->reset_password();

$register_message = $user->register();

$is_logged = $session->is_logged();

# checking if the page title exist
if (!isset($page_title) || empty($page_title)) {
  $page_title = WEBSITE_NAME;
}

if ($page_title != WEBSITE_NAME) {
  $meta_title = $page_title . " — " . WEBSITE_NAME;
} else {
  $meta_title = $page_title;
}

# checking if the page description exist
if (!isset($page_description) || empty($page_description)) {
  $page_description = WEBSITE_NAME . " est un outil destiné à la gestion d'aéroclubs, plus particulièrement dans la gestion de la réservations des appareils.";
}

# checking if the page date exist
if (!isset($page_date) || empty($page_date)) {
  $page_date = date('d-m-y');
}

# checking if the page date exist
if (!isset($page_canonical) || empty($page_canonical)) {
  $page_canonical = "/";
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <meta name="author" content="Xuming M. &amp; Eva F. &amp; Benjamin P." />
  <meta name="title" content="<?php echo $meta_title; ?>" />
  <meta name="name" content="<?php echo $meta_title; ?>" />
  <meta name="description" content="<?php echo $page_description; ?>" />
  <meta name="date" content="<?php echo $page_date ?>" />
  <meta name="location" content="CY Cergy Paris Université" />
  <link rel="canonical" href="<?php echo WEBSITE_URL . $page_canonical; ?>" />

  <!-- Facebook Meta Tags -->
  <meta property="og:locale" content="fr_FR" />
  <meta property="og:url" content="<?php echo WEBSITE_URL . $page_canonical; ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="AirPlanner" />
  <meta property="og:title" content="<?php echo $meta_title; ?>" />
  <meta property="og:description" content="<?php echo $page_description; ?>" />
  <meta property="og:image" content="<?php echo WEBSITE_URL . "/img/favicon.ico"; ?>" />

  <!-- Twitter Meta Tags -->
  <meta name="twitter:url" content="<?php echo WEBSITE_URL . $page_canonical; ?>" />
  <meta name="twitter:card" content="summary_small_image" />
  <meta name="twitter:title" content="<?php echo $meta_title; ?>" />
  <meta name="twitter:description" content="<?php echo $page_description; ?>" />
  <meta name="twitter:image" content="<?php echo WEBSITE_URL . "/img/favicon.ico"; ?>" />

  <title><?php echo $meta_title; ?></title>
  <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
  <script src="./js/index.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&amp;display=swap" rel="stylesheet" />
  <link href="./css/global.css" rel="stylesheet" />
</head>

<body class="font-sans bg-gray-50 dark:bg-gray-800 text-black dark:text-white antialiased bg-cover" style="background-image:  url('<?php if(isset($bg_path)) echo $bg_path; else echo "/img/home_bg.jpg"; ?>');">
  <header class="bg-gray-50/60 dark:bg-gray-500/60 duration-300 ease-in-out">
    <div class="top-0 z-40 flex-none mx-auto w-full">
      <div class="mx-auto w-full md:flex md:justify-between backdrop-blur bg-gray-400/70 dark:bg-gray-900">
        <div class="flex justify-between">
          <a class="flex items-center" href="/">
            <span class="self-center flex ml-2 text-2xl font-extrabold text-gray-900 whitespace-nowrap dark:text-white items-center"><img src="/img/favicon.ico" class="w-9 h-9 items-center" alt="AirPlanner logo" /><?php echo WEBSITE_NAME; ?></span>
          </a>
          <div class="flex items-center md:hidden">
            <button class="rounded-full text-sm p-4 inline-flex items-center" data-aw-toggle-color-scheme>
              <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" class="fill-black dark:fill-white">
                <path d="M12 22q-.825 0-1.412-.587Q10 20.825 10 20h4q0 .825-.587 1.413Q12.825 22 12 22Zm-4-3v-2h8v2Zm.25-3q-1.725-1.025-2.737-2.75Q4.5 11.525 4.5 9.5q0-3.125 2.188-5.312Q8.875 2 12 2q3.125 0 5.312 2.188Q19.5 6.375 19.5 9.5q0 2.025-1.012 3.75-1.013 1.725-2.738 2.75Zm.6-2h6.3q1.125-.8 1.738-1.975.612-1.175.612-2.525 0-2.3-1.6-3.9T12 4Q9.7 4 8.1 5.6T6.5 9.5q0 1.35.613 2.525Q7.725 13.2 8.85 14ZM12 14Z" />
              </svg>
            </button>
            <button class="ml-1.5 rounded-full text-sm p-4 inline-flex items-center transition" data-aw-toggle-menu>
              <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" class="fill-black dark:fill-white">
                <path d="M3 18v-2h18v2Zm0-5v-2h18v2Zm0-5V6h18v2Z" />
              </svg>
            </button>
          </div>
        </div>
        <nav class="items-center w-full md:w-auto hidden md:flex h-screen md:h-auto" id="menu">
          <ul class="flex flex-col pt-1 md:pt-0 md:flex-row md:self-center collapse w-full md:w-auto collapsed text-xl md:text-base">
            <?php
            foreach (get_routes() as $route) {
              if ($route["header"]) {
                if (($is_logged && $route["logged"]) || (!$is_logged && $route["not_logged"])) {
                  echo "\t\t\t\t\t\t<li>";
                  echo "\t\t\t\t\t\t\t<a class=\"block p-4 md:text-center md:hover:text-blue-800 dark:md:hover:text-blue-300 transition duration-300 ease-in-out\" href=\"" . $route["ref"] . "\">";
                  echo "\t\t\t\t\t\t\t" . $route["title"] . "</a></li>";
                }
              }
            }
            ?>

          </ul>
          <div class="md:self-center flex items-center mb-4 md:mb-0 collapse collapsed">
            <div class="hidden items-center mr-3 md:flex">
              <button class="text-gray-500 dark:text-gray-400 text-sm p-4 inline-flex items-center" aria-label="Toggle between Dark and Light mode" data-aw-toggle-color-scheme>
                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" class="fill-black dark:fill-white">
                  <path d="M12 22q-.825 0-1.412-.587Q10 20.825 10 20h4q0 .825-.587 1.413Q12.825 22 12 22Zm-4-3v-2h8v2Zm.25-3q-1.725-1.025-2.737-2.75Q4.5 11.525 4.5 9.5q0-3.125 2.188-5.312Q8.875 2 12 2q3.125 0 5.312 2.188Q19.5 6.375 19.5 9.5q0 2.025-1.012 3.75-1.013 1.725-2.738 2.75Zm.6-2h6.3q1.125-.8 1.738-1.975.612-1.175.612-2.525 0-2.3-1.6-3.9T12 4Q9.7 4 8.1 5.6T6.5 9.5q0 1.35.613 2.525Q7.725 13.2 8.85 14ZM12 14Z" />
                </svg>
              </button>
            </div>
          </div>
        </nav>
      </div>
    </div>
    <h1 class="p-10 pb-20 text-5xl text-center font-bold"><?php echo $page_title; ?></h1>
  </header>
  <main class="min-h-screen bg-gray-50/60 dark:bg-gray-500/60 duration-300 ease-in-out pb-10">
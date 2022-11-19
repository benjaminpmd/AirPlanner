<?php

/**
 * File containing specific functions used in the header, footer or for a specific purpose.
 * 
 * @author Xuming M, Eva F, Benjamin P
 * @version 1.0.0
 * @since 17/10/2022
 */

session_start();

// name of the website
define("WEBSITE_NAME", "AirPlanner");

// name of the website
define("WEBSITE_NAME_URL", "airplanner");

// URL of the website
define("WEBSITE_URL", "https://".WEBSITE_NAME_URL.".benjaminpmd.fr");



// connection string of the database
define("CONNECTION_STRING", "host=" . getenv("HOST") . " port=" . getenv("PORT") . " dbname=" . getenv("DBNAME") . " user=" . getenv("USER") . " password=" . getenv("PASSWORD"));

/**
 * Function called to get all the routes, their ref and a boolean  to indicate their presence in the nav header.
 * 
 * @return array of routes [title, ref, header, logged, not_logged]
 */
function get_routes(): array {
    return [
        [
            "title" => "Accueil",
            "ref"   => "/",
            "header" => true,
            "logged" => true,
            "not_logged" => true,
        ],
        [
            "title" => "Connexion",
            "ref"   => "/login.php",
            "header" => true,
            "logged" => false,
            "not_logged" => true,
        ],
        [
            "title" => "S'enregistrer",
            "ref"   => "/register.php",
            "header" => true,
            "logged" => false,
            "not_logged" => true,
        ],
        [
            "title" => "Réservation",
            "ref"   => "/booking.php",
            "header" => true,
            "logged" => true,
            "not_logged" => false,
        ],
        [
            "title" => "Mon compte",
            "ref"   => "/account.php",
            "header" => true,
            "logged" => true,
            "not_logged" => false,
        ],
        [
            "title" => "Se déconnecter",
            "ref"   => "/index.php?disconnect=true",
            "header" => true,
            "logged" => true,
            "not_logged" => false,
        ],
        [
            "title" => "Conditions d'utilisation",
            "ref"   => "/terms-of-service.php",
            "header" => false,
            "logged" => false,
            "not_logged" => false,
        ],
        [
            "title" => "Politique de confidentialité",
            "ref"   => "/privacy-policy.php",
            "header" => false,
            "logged" => false,
            "not_logged" => false,
        ],
        [
            "title" => "A propos",
            "ref"   => "/about.php",
            "header" => false,
            "logged" => true,
            "not_logged" => true,
        ],
    ];
}

function get_register_elements(): array {
    return [
        [
            "name" => "Prénom",
            "id_name" => "register-firstname",
            "type" => "text"
        ],
        [
            "name" => "Nom",
            "id_name" => "register-lastname",
            "type" => "text"
        ],
        [
            "name" => "Date de naissance",
            "id_name" => "register-birthdate",
            "type" => "date"
        ],
        [
            "name" => "Adresse email",
            "id_name" => "register-email",
            "type" => "email"
        ],
        [
            "name" => "Téléphone portable",
            "id_name" => "register-phone",
            "type" => "tel"
        ],
        [
            "name" => "Adresse postale",
            "id_name" => "register-address",
            "type" => "text"
        ],
        [
            "name" => "Ville",
            "id_name" => "register-city",
            "type" => "text"
        ],
        [
            "name" => "Code postale",
            "id_name" => "register-postal-code",
            "type" => "number"
        ]
    ];
}


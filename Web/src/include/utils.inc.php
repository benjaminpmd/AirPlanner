<?php
/**
 * File containing specific functions used in the header, footer or for a specific purpose.
 * 
 * @author Xuming M, Eva F, Benjamin P
 * @version 1.0.0
 * @since 17/10/2022
 */


// URL of the website
define("WEBSITE_URL", "https://ac-solutions.benjaminp.dev");

/**
 * Function called to get all the routes, their ref and a boolean  to indicate their presence in the nav header.
 * 
 * @return array of routes [title, ref, header]
 */
function get_routes(): array {
    return [
        [
            "title" => "Accueil",
            "ref"   => "/",
            "header" => true
        ],
        [
            "title" => "A propos",
            "ref"   => "/about.php",
            "header" => true
        ],
        [
            "title" => "Connexion",
            "ref"   => "/connexion.php",
            "header" => true
        ],
        [
            "title" => "Conditions d'utilisation",
            "ref"   => "/terms-of-service.php",
            "header" => false
        ],
        [
            "title" => "Politique de confidentialité",
            "ref"   => "/privacy-policy.php",
            "header" => false
        ],
    ];
}
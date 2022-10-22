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

// connection string of the database
define("CONNECTION_STRING", "host=" . getenv("HOST") . " port=" . getenv("PORT") . " dbname=" . getenv("DBNAME") . " user=" . getenv("USER") . " password=" . getenv("PASSWORD"));

/**
 * Function called to get all the routes, their ref and a boolean  to indicate their presence in the nav header.
 * 
 * @return array of routes [title, ref, header]
 */
function get_routes(): array
{
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
            "ref"   => "/connection.php",
            "header" => true
        ],
        [
            "title" => "S'enregistrer",
            "ref"   => "/register.php",
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

/**
 * Function to check if credentials of a user are valid or not.
 * ## TODO: implement mechanic search
 * 
 * @param string $user_id the ID of an user.
 * @param string $user_password the password of a user.
 * @return boolean if the credentials are correct or not.
 */
function valid_credentials(string $user_id, string $user_password): array|bool {
    // creating the query first
    $query = "SELECT user_id FROM users WHERE (user_id=" . $user_id . ") AND (password='" . $user_password . "');";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);
    // getting the results of the query
    $result = pg_query($connection, $query);
    // close the connection
    pg_close($connection);
    // return if the credentials are correct or not
    return pg_fetch_array($result);
}

/**
 * Function to check if a user is logged in or not. If the user is logged, an array containing all of its informations is returned.
 * 
 * @param string $uid the token of the session.
 * @param int the supposed ID of the user.
 * @return array|bool an array of informations about the user if he is logged, false if the session is expired or does not exist
 */
function get_user(string $uid, string $user_id): array|bool {
    $remove_old_session_query = "DELETE FROM site_sessions WHERE (user_id=" . $user_id . ") AND (uid='" . $uid . "');";
    // creating the query first
    $query = "SELECT * FROM site_sessions WHERE (user_id=" . $user_id . ") AND (uid='" . $uid . "');";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);
    // getting the results of the query
    pg_query($connection, $remove_old_session_query);
    $result = pg_query($connection, $query);
    // close the connection
    pg_close($connection);
    // return if the credentials are correct or not
    return $result;
}

function create_session(string $user_id): string {
    $uid = uniqid();
    $query = "INSERT INTO site_sessions(uid, user_id, expiration_time) VALUES ('".$uid."', ".$user_id.", NOW() + INTERVAL '24 hours');";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);
    // getting the results of the query
    pg_exec($connection, $query);
    // close the connection
    pg_close($connection);
    // return if the credentials are correct or not
    return $uid;
}

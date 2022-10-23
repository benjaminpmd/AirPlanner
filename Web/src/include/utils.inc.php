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
            "header" => true,
            "logged" => true,
            "not_logged" => true,
        ],
        [
            "title" => "Connexion",
            "ref"   => "/connection.php",
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

/**
 * Function to check if credentials of a user are valid or not.
 * ## TODO: implement mechanic search
 * 
 * @param string $user_id the ID of an user.
 * @param string $user_password the password of a user.
 * @return boolean if credentials are correct or not. 
 */
function valid_credentials(string $user_id, string $user_password): bool {
    // creating the query
    $query = "SELECT password FROM users WHERE (user_id=" . $user_id . ");";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);
    // getting the results of the query
    $result = pg_query($connection, $query);
    $result_array = pg_fetch_array($result);
    if ($result_array) {
        if (password_verify($user_password, $result_array[0])) {
            // close the connection
            pg_close($connection);
            // return if the credentials are correct or not
            return true;
        }
    }
    // close the connection
    pg_close($connection);
    // return if the credentials are correct or not
    return false;
}

/**
 * Function to check if session details are correct or not. If the user is logged, an array containing all of its informations is returned.
 * 
 * @param string $uid the token of the session.
 * @param int the supposed ID of the user.
 * @return boolean if the session exist.
 */
function valid_session(string $uid, string $user_id): bool {
    $remove_old_session_query = "DELETE FROM site_sessions WHERE (expiration_time < NOW());";
    // creating the query first
    $query = "SELECT user_id FROM site_sessions WHERE (user_id=" . $user_id . ") AND (uid='" . $uid . "');";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);
    // removing the old sessions
    pg_query($connection, $remove_old_session_query);
    // checking if a session exist
    $result = pg_query($connection, $query);
    // if the result is not null close the connection and return true
    $result_array = pg_fetch_array($result);
    if ($result_array) {
        pg_close($connection);
        return true;
    }
    // close the connection
    pg_close($connection);
    // return if the credentials are correct or not
    return false;
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

function delete_session(string $uid, string $user_id) {
    $query = "DELETE FROM site_sessions WHERE (uid='".$uid."') AND (user_id=".$user_id.");";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);
    // getting the results of the query
    pg_exec($connection, $query);
    // close the connection
    pg_close($connection);
}


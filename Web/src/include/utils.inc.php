<?php

/**
 * File containing specific functions used in the header, footer or for a specific purpose.
 * 
 * @author Xuming M, Eva F, Benjamin P
 * @version 1.0.0
 * @since 17/10/2022
 */

// name of the website
define("WEBSITE_NAME", "AirPlanner");

// name of the website
define("WEBSITE_NAME_URL", "airplanner");

// URL of the website
define("WEBSITE_URL", "https://".WEBSITE_NAME_URL.".benjaminp.dev");



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
 * #################################################################################################
 * SESSIONS MANAGEMENT
 * #################################################################################################
 */

/**
 * Function to create a session.
 * 
 * @param string $user_id the ID of the user.
 * @return string a unique ID for the session.
 */
function create_session(string $user_id): string {
    // create the unique ID for the session
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

/**
 * Function to delete a session.
 * 
 * @param string $uid the UID of the session.
 * @param string $user_id the ID of the user.
 */
function delete_session(string $uid, string $user_id) {
    $query = "DELETE FROM site_sessions WHERE (uid='".$uid."') AND (user_id=".$user_id.");";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);
    // getting the results of the query
    pg_exec($connection, $query);
    // close the connection
    pg_close($connection);
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

/**
 * Function that check if a user is logged or not.
 * 
 * @return boolean if the user is logged or not.
 */
function is_logged(): bool {
    if ((isset($_COOKIE["uid"]) && !empty($_COOKIE["uid"])) && (isset($_COOKIE["user_id"]) && !empty($_COOKIE["user_id"]))) {
        return valid_session($_COOKIE["uid"], $_COOKIE["user_id"]);
    }
    return false;
}

/**
 * Function that disconnect the user if this is requested by the query parameters and if the user is logged.
 * 
 * @param bool $is_logged the status whether the user is logged or not.
 * @param bool the status of whether the user is still log or not.
 */
function disconnect(bool $is_logged) {
    if (isset($_GET["disconnect"]) && !empty($_GET["disconnect"])) {
        if (($is_logged) && ($_GET["disconnect"] == "true")) {
            delete_session($_COOKIE["uid"], $_COOKIE["user_id"]);
            return false;
        }
    }
    return $is_logged;
}



/**
 * #################################################################################################
 * ACCOUNT ACCESS MANAGEMENT
 * #################################################################################################
 */

/**
 * Function to check if credentials of a user are valid or not.
 * 
 * @param string $user_email the email of an user.
 * @param string $user_password the password of a user.
 * @return boolean if credentials are correct or not. 
 */
function valid_credentials(string $user_email, string $user_password): bool | string {
    // creating the query
    $query = "SELECT password, user_id FROM users WHERE (email='" . $user_email . "');";
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
            return $result_array[1];
        }
    }
    // close the connection
    pg_close($connection);
    // return if the credentials are correct or not
    return false;
}

/**
 * Function used to check if a user is trying to connect and if the credentials are correct.
 * 
 * @return boolean value indicating if the credentials are correct or not.
 */
function check_credentials(): bool {
    // checking if credentials are passed as post
    if ((isset($_POST["login-email"]) && !empty($_POST["login-email"])) && (isset($_POST["login-password"]) && !empty($_POST["login-password"]))) {

        // making a query on the database to request a user with the ID and the corresponding password
        $user_id = valid_credentials($_POST["login-email"], $_POST["login-password"]);

        // checking the the result is equal to false
        if ($user_id) {

            // if not, then the result is an array containing the id of the user
            // creating an unique ID for the session
            $uid = create_session($user_id);

            // setting cookies with the uid and the user ID
            setcookie("uid", $uid, time() + 86400);
            setcookie("user_id", $user_id, time() + 86400);

            // redirecting the user to the dashboard
            header("Location: /booking.php");

            // exiting
            exit();

            // return that the credentials are correct
            return true;
        } else {

            // else the credentials are not correct
            return false;
        }
    }

    // if no then the user is not trying to connect
    return true;
}

/**
 * Function called that reset the password of a user the post query request it
 * 
 * @return bool|string false if the password is not reset, a message if a reset attempt was made
 */
function reset_password(): string | bool {

    if (isset($_POST["reset-email"]) && !empty($_POST["reset-email"])) {
        // generate a new password
        $new_password = uniqid();
        // hash the password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        // create the query to update the database
        $new_password_query = "UPDATE users SET password='" . $hashed_password . "' WHERE (email='" . $_POST["reset-email"] . "');";
        // create the query to get infos about the user
        $get_user_query = "SELECT email, first_name, last_name FROM users WHERE (email='" . $_POST["reset-email"] . "');";
        
        // connect to the db
        $connection = pg_connect(CONNECTION_STRING);
        // update the password
        pg_query($connection, $new_password_query);
        // get the informations about the user
        $user = pg_fetch_row(pg_query($connection, $get_user_query));
        // close the connection
        pg_close($connection);

        // if the user exist, it means an account is associated with it
        if ($user) {
            // mailing the password part
            $to = $user[0];
            $subject = "Réinitialisation de votre mot de passe - " . WEBSITE_NAME;
            $message = "Bonjour " . $user[1] . " " . $user[2] .",\nVous avez demandez une réinitialisation de votre mot de passe.\nVotre nouveau mot de passe est : " . $new_password . "\n\nCordialement\nL'équipe " . WEBSITE_NAME;
            $headers = "From: " . WEBSITE_NAME . " <noreply@" . WEBSITE_NAME_URL . ".benjaminp.dev>"       . "\r\n" .
                        "X-Mailer: PHP/" . phpversion();

            mail($to, $subject, $message, $headers);
            return "Un email vous a été envoyé.";
        }
        
        return "Aucun compte n'est associé à cette adresse email.";
    }
    return false;
}


/**
 * Function called that reset the password of a user the post query request it
 * 
 * @return bool|string false if the password is not reset, a message if a reset attempt was made
 */
function register(): string | bool {
    $ids = ["register-firstname", "register-lastname", "register-birthday", "register-email", "register-phone", "register-address", "register-city", "register-postal-code"];
    
    foreach($ids as $id) {
        if (!isset($_POST[$id]) || empty($_POST[$id])) {
            return false;
        }
    }
    // generate a password
    $new_password = uniqid();
    
    // hash the password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    
    // create a query to check if the email is already used or not
    $existing_user_query = "SELECT user_id FROM users WHERE (email='" . $_POST["register-email"] . "');";
    
    // create the query to insert the user
    $new_user_query = "INSERT INTO users(first_name, last_name, email, phone, password) VALUES ('".$_POST["register-firstname"]."', '".$_POST["register-lastname"]."', '".$_POST["register-email"]."', '".$_POST["register-phone"]."', '".$hashed_password ."');";
    
    // create the query to get infos about the user, especially ID
    $get_user_query = "SELECT user_id, email, first_name, last_name FROM users WHERE (email='" . $_POST["register-email"] . "');";
    
    // connect to the db
    $connection = pg_connect(CONNECTION_STRING);
    // update the password
    $email_already_used = pg_fetch_row(pg_query($connection, $existing_user_query));
    if (!$email_already_used) {
        // create the new user
        pg_query($connection, $new_user_query);

        // get the informations about the user
        $user = pg_fetch_row(pg_query($connection, $get_user_query));
        
        // if the user exist, it means an account is associated with it
        if ($user) {
            // create the query to insert the pilot
            $new_pilot_query = "INSERT INTO pilots(first_name, last_name, email, phone, password) VALUES ('".$_POST["register-firstname"]."', '".$_POST["register-lastname"]."', '".$_POST["register-email"]."', '".$_POST["register-phone"]."', '".$hashed_password ."');";

            //pg_query($connection, $new_pilot_query);

            // close the connection
            pg_close($connection);
            
            // mailing the password part
            $to = $user[1];
            $subject = "Bienvenue chez AirPlanner";
            $message = "Bonjour " . $user[2] . " " . $user[3] .",\n
            Vous venez de vous inscrire sur la plateforme " . WEBSITE_NAME . ". Merci beaucoup de votre confiance ! \n
            Voici votre mot de passe : " . $new_password . "\n
            Vous pouvez le modifier à tout moment en vous connectant sur le site internet.\n\n
            Cordialement\n
            L'équipe " . WEBSITE_NAME;
            $headers = "From: " . WEBSITE_NAME . " <noreply@" . WEBSITE_NAME_URL . ".benjaminp.dev>"       . "\r\n" .
                        "X-Mailer: PHP/" . phpversion();
            mail($to, $subject, $message, $headers);
            return "Votre inscription est complète, un email vous a été envoyé.";
        }
        return "Une erreur est survenue, veuillez réessayer ultérieurement.";
    }
    return "Cette adresse email est déjà associée à un compte.";
}
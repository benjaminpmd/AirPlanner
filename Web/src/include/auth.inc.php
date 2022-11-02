<?php
include_once "./include/utils.inc.php";
include_once "./include/mail.inc.php";

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
            $toAddress = $user[0];
            $toName = $user[1] . " " . $user[2];
            $subject = "Réinitialisation de votre mot de passe - " . WEBSITE_NAME;
            $content = "<html><h2 style=\"font-size: large;\">Réinitialisation de votre mot de passe</h2>\n<h3>Bonjour " . $user[1] . " " . $user[2] ."</h3>\n<p>Vous avez demandez une réinitialisation de votre mot de passe.</p><p>Votre nouveau mot de passe est : " . $new_password . "</p>\n<p>Vous pouvez le modifier à tout moment en vous connectant sur le <a href=\"https://" . WEBSITE_NAME_URL . ".benjaminpmd.fr\">site internet</a>.</p>\n<p></p>\n<p>Cordialement</p>\n<p>L'équipe " . WEBSITE_NAME . "</p></html>";

            $is_sent = send_mail($toAddress, $toName, $subject, $content);
            
            if ($is_sent) {
                return "Un email vous a été envoyé.";
            }
            else return "Une erreur est survenue, veuillez réessayer plus tard.";
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
            // mailing the password part
            $toAddress = $user[0];
            $toName = $user[1] . " " . $user[2];
            $subject = "Bienvenue sur la plateforme " . WEBSITE_NAME;
            $content = "<h2>Bienvenue sur la plateforme " . WEBSITE_NAME . "</h2>\n<h3>Bonjour " . $user[1] . " " . $user[2] ."</h3>\n<p>Votre incripstion sur la plateforme est complète !</p><p>Votre mot de passe est : " . $new_password . "</p>\n<p>Vous pouvez le modifier à tout moment en vous connectant sur le <a href=\"https://" . WEBSITE_NAME_URL . ".benjaminpmd.fr\">site internet</a>.</p>\n<p></p>\n<p>Cordialement</p>\n<p>L'équipe " . WEBSITE_NAME . "</p>";

            $is_sent = send_mail($toAddress, $toName, $subject, $content);
            
            if ($is_sent) {
                return "Un email vous a été envoyé.";
            }
            else return "Une erreur est survenue, veuillez réessayer plus tard.";
        }
        return "Une erreur est survenue, veuillez réessayer ultérieurement.";
    }
    return "Cette adresse email est déjà associée à un compte.";
}
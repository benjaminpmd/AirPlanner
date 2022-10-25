<?php

include_once "./include/utils.inc.php";

/**
 * Function used to check if a user is trying to connect and if the credentials are correct.
 * 
 * @return boolean value indicating if the credentials are correct or not.
 */
function check_credentials(): bool {
    // checking if credentials are passed as post
    if ((isset($_POST["user-id"]) && !empty($_POST["user-id"])) && (isset($_POST["user-password"]) && !empty($_POST["user-password"]))) {

        // making a query on the database to request a user with the ID and the corresponding password
        $res = valid_credentials($_POST["user-id"], $_POST["user-password"]);

        // checking the the result is equal to false
        if ($res) {

            // if not, then the result is an array containing the id of the user
            // creating an unique ID for the session
            $uid = create_session($_POST["user-id"]);

            // setting cookies with the uid and the user ID
            setcookie("uid", $uid, time() + 86400);
            setcookie("user_id", $_POST["user-id"], time() + 86400);

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


function disconnect(bool $is_logged) {
    if (isset($_GET["disconnect"]) && !empty($_GET["disconnect"])) {
        if (($is_logged) && ($_GET["disconnect"] == "true")) {
            delete_session($_COOKIE["uid"], $_COOKIE["user_id"]);
            return false;
        }
    }
    return $is_logged;
}

function reset_password(): string | bool {
    if (isset($_POST["user-id-pr"]) && !empty($_POST["user-id-pr"]) && isset($_POST["reset-password"]) && !empty($_POST["reset-password"])) {
        // generate a new password
        $new_password = uniqid();
        // hash the password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        // create the query to update the database
        $new_password_query = "UPDATE users SET password='" . $hashed_password . "' WHERE (user_id=" . $_POST["user-id-pr"] . ");";
        // create the query to get infos about the user
        $get_user_query = "SELECT email, first_name, last_name FROM users WHERE (user_id=" . $_POST["user-id-pr"] . ");";
        
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
            return "Un mail vous a été envoyé.";
        }
        
        return "Aucun compte n'est associé à cet identifiant.";
    }
    return false;
}

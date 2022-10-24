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
            setcookie("uid", $uid, time()+86400);
            setcookie("user_id", $_POST["user-id"], time()+86400);
            
            // redirecting the user to the dashboard
            header("Location: /booking.php");
            
            // exiting
            exit();
            
            // return that the credentials are correct
            return true;
        }
        else {

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
    if (isset($_POST["user-id-pr"]) && !empty($_POST["user-id-pr"]) && isset($_POST["user-birthday-pr"]) && !empty($_POST["user-birthday-pr"]) && isset($_POST["reset-password"]) && !empty($_POST["reset-password"])) {
        $new_password = uniqid();
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $connection = pg_connect(CONNECTION_STRING);
        $query = "UPDATE user JOIN";
        pg_close($connection);
        return "Votre nouveau mot de passe est <strong>" . $new_password . "</strong>\n";
    }
    return false;
}
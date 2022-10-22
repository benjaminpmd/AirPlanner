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
            $uid = create_session($res[0]);

            // setting cookies with the uid and the user ID
            setcookie("uid", $uid, time()+86400);
            setcookie("user_id", $res[0], time()+86400);
            
            // redirecting the user to the dashboard
            header("Location: /dashboard.php");
            
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
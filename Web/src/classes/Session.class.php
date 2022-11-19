<?php
include_once "./include/utils.inc.php";
include_once "./include/mail.inc.php";

class Session {

    public function __construct() {}

    /**
     * Function that check if a user is logged or not.
     * 
     * @return boolean if the user is logged or not.
     */
    function is_logged(): bool {
        // if the session is active, return true
        if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
            return true;
        }
        // else return false
        return false;
    }

    /**
     * Function that disconnect the user if this is requested by the query parameters and if the user is logged.
     */
    function disconnect(): void {
        if (isset($_GET["disconnect"]) && !empty($_GET["disconnect"])) {
            if (($_GET["disconnect"] == "true") && $this->is_logged()) {
                unset($_SESSION["user_id"]);
            }
        }
    }
}
?>
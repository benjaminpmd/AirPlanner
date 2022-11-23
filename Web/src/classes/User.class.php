<?php
class User {

    public function __construct() {}

    /**
     * Function used to check if a user is trying to connect and if the credentials are correct.
     * 
     * @return boolean value indicating if the credentials are correct or not.
     */
    function login(): bool {
        // checking if credentials are passed as post
        if ((isset($_POST["login-email"]) && !empty($_POST["login-email"])) && (isset($_POST["login-password"]) && !empty($_POST["login-password"]))) {

            // creating the query
            $query = "SELECT password, user_id, last_name, first_name, email, phone FROM users WHERE (email='" . $_POST["login-email"] . "');";

            // connecting to the database
            $connection = pg_connect(CONNECTION_STRING);

            // getting the results of the query
            $result = pg_query($connection, $query);

            // fetch the result of the query
            $result_array = pg_fetch_array($result);

            // free the result
            pg_free_result($result);

            if ($result_array) {

                if (password_verify($_POST["login-password"], $result_array[0])) {

                    $_SESSION["user_id"] = $result_array[1];
                    $_SESSION["user_last_name"] = $result_array[2];
                    $_SESSION["user_first_name"] = $result_array[3];
                    $_SESSION["user_email"] = $result_array[4];
                    $_SESSION["user_phone"] = $result_array[5];

                    $query_pilot = "SELECT * FROM pilots WHERE (pilot_id=" . $result_array[1] . ");";
                    $query_mechanic = "SELECT * FROM mechanics WHERE (mechanic_id=" . $result_array[1] . ");";

                    // getting the results of the query
                    $result = pg_query($connection, $query_pilot);

                    // fetch the result of the query
                    $result_data = pg_fetch_all($result);

                    // free the result
                    pg_free_result($result);

                    if (isset($result_data[0]) && !empty($result_data[0])) {
                        $_SESSION["pilot"] = $result_data[0];
                        $_SESSION["is_pilot"] = true;
                        $_SESSION["is_mechanic"] = false;

                        // close the connection
                        pg_close($connection);

                        // redirecting the user to the dashboard
                        header("Location: /booking.php");

                    } else {
                        // getting the results of the query
                        $result = pg_query($connection, $query_mechanic);

                        // fetch the result of the query
                        $result_data = pg_fetch_all($result);

                        // free the result
                        pg_free_result($result);

                        $_SESSION["mechanic"] = $result_data[0];
                        $_SESSION["is_pilot"] = false;
                        $_SESSION["is_mechanic"] = true;

                        // close the connection
                        pg_close($connection);

                        // redirecting the user to the dashboard
                        header("Location: /mechanic.php");
                    }

                    // exiting
                    exit();

                    return true;
                }
            }
            // close the connection
            pg_close($connection);
            return false;
        }
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
            $result = pg_query($connection, $get_user_query);

            $user = pg_fetch_row($result);

            // free the result
            pg_free_result($result);

            // close the connection
            pg_close($connection);

            // if the user exist, it means an account is associated with it
            if ($user) {

                // mailing the password part
                $toAddress = $user[0];
                $toName = $user[1] . " " . $user[2];
                $subject = "Réinitialisation de votre mot de passe - " . WEBSITE_NAME;
                $content = "<html><h2 style=\"font-size: large;\">Réinitialisation de votre mot de passe</h2>\n<h3>Bonjour " . $user[1] . " " . $user[2] . "</h3>\n<p>Vous avez demandez une réinitialisation de votre mot de passe.</p><p>Votre nouveau mot de passe est : " . $new_password . "</p>\n<p>Vous pouvez le modifier à tout moment en vous connectant sur le <a href=\"https://" . WEBSITE_NAME_URL . ".benjaminpmd.fr\">site internet</a>.</p>\n<p></p>\n<p>Cordialement</p>\n<p>L'équipe " . WEBSITE_NAME . "</p></html>";

                $is_sent = send_mail($toAddress, $toName, $subject, $content);

                if ($is_sent) {
                    return "Un email vous a été envoyé.";
                } else return "Une erreur est survenue, veuillez réessayer plus tard.";
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
    public function register(): string | bool {
        $ids = ["register-firstname", "register-lastname", "register-birthdate", "register-email", "register-phone", "register-address", "register-city", "register-postal-code"];

        foreach ($ids as $id) {
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
        $new_user_query = "INSERT INTO users(first_name, last_name, email, phone, password) VALUES ('" . $_POST["register-firstname"] . "', '" . $_POST["register-lastname"] . "', '" . $_POST["register-email"] . "', '" . $_POST["register-phone"] . "', '" . $hashed_password . "');";

        // create the query to get infos about the user, especially ID
        $get_user_query = "SELECT user_id FROM users WHERE (email='" . $_POST["register-email"] . "');";

        // connect to the db
        $connection = pg_connect(CONNECTION_STRING);

        // update the password
        $result = pg_query($connection, $existing_user_query);

        // if a value is fetch, then the email is already used.
        $email_already_used = pg_fetch_row($result);

        pg_free_result($result);

        // in the case of the email unused
        if (!$email_already_used) {
            // create the new user
            pg_query($connection, $new_user_query);

            // get the informations about the user
            $user = pg_fetch_row(pg_query($connection, $get_user_query));

            // if the user exist, it means an account is associated with it
            if ($user) {

                $new_pilot_query = "INSERT INTO pilots(pilot_id, birth_date, pilot_address, city, postal_code) VALUES (" . $user[0] . ", '" . $_POST["register-birthdate"] . "', '" . $_POST["register-address"] . "', '" . $_POST["register-city"] . "', '" . $_POST["register-postal-code"] . "');";
                pg_query($connection, $new_pilot_query);

                // close the connection to the database
                pg_close(($connection));


                // mailing the password part
                $toAddress = $_POST["register-email"];
                $toName = $_POST["register-firstname"] . " " . $_POST["register-lastname"];
                $subject = "Bienvenue sur la plateforme " . WEBSITE_NAME;
                $content = "<h2>Bienvenue sur la plateforme " . WEBSITE_NAME . "</h2>\n<h3>Bonjour " . $_POST["register-firstname"] . " " . $_POST["register-lastname"] . "</h3>\n<p>Votre incripstion sur la plateforme est complète !</p><p>Votre mot de passe est : " . $new_password . "</p>\n<p>Vous pouvez le modifier à tout moment en vous connectant sur le <a href=\"https://" . WEBSITE_NAME_URL . ".benjaminpmd.fr\">site internet</a>.</p>\n<p></p>\n<p>Cordialement</p>\n<p>L'équipe " . WEBSITE_NAME . "</p>";

                $is_sent = send_mail($toAddress, $toName, $subject, $content);

                if ($is_sent) {
                    return "Un email vous a été envoyé.";
                } else {
                    return "Une erreur est survenue, veuillez réessayer plus tard.";
                }
            }
            // close the connection to the database
            pg_close(($connection));
            return "Une erreur est survenue, veuillez réessayer ultérieurement.";
        }
        pg_close($connection);
        return "Cette adresse email est déjà associée à un compte.";
    }


    function is_pilot(): bool {
        if (isset($_SESSION["is_pilot"]) && !empty($_SESSION["is_pilot"])) {
            return $_SESSION["is_pilot"];
        }
        else return false;
    }

    function is_mechanic(): bool {
        if (isset($_SESSION["is_mechanic"]) && !empty($_SESSION["is_mechanic"])) {
            return $_SESSION["is_mechanic"];
        }
        else return false;
    }
}
?>
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
    
    // check if disconnection is requested in the params
    if (isset($_GET["disconnect"]) && !empty($_GET["disconnect"])) {
      
      // if the disconnection is requested and the user is logged in disconnect.
      if (($_GET["disconnect"] == "true") && $this->is_logged()) {
        unset($_SESSION["user_id"]);
      }
    }
  }
  /**
   * Function used to check if a user is trying to connect and if the credentials are correct.
   * 
   * @return boolean value indicating if the credentials are correct or not.
   */
  function login(): bool {
    
    // checking if credentials are passed as post
    if ((isset($_POST["login-email"]) && !empty($_POST["login-email"])) && (isset($_POST["login-password"]) && !empty($_POST["login-password"]))) {
      
      // creating the query
      $query = "SELECT password, user_id, ((SELECT u.email FROM pilots AS p JOIN users AS u ON u.user_id = p.pilot_id WHERE u.email = '" . $_POST["login-email"] . "')='" . $_POST["login-email"] . "') as is_pilot, ((SELECT u.email FROM mechanics AS m JOIN users AS u ON u.user_id = m.mechanic_id WHERE u.email = '" . $_POST["login-email"] . "')='" . $_POST["login-email"] . "') as is_mechanic FROM users WHERE (email='" . $_POST["login-email"] . "');";
      
      // connecting to the database
      $connection = pg_connect(CONNECTION_STRING);
      
      // getting the results of the query
      $result = pg_query($connection, $query);
      
      // fetch the result of the query
      $result_array = pg_fetch_array($result);
      
      // free the result
      pg_free_result($result);

      // close the connection
      pg_close($connection);
      
      // check if the returned array is false or not
      if ($result_array) {
        
        // if not false, then check the password provided
        if (password_verify($_POST["login-password"], $result_array["password"])) {
          
          // if the password is OK, save the user ID in the session
          $_SESSION["user_id"] = $result_array["user_id"];

          // the third element of the array indicates if it is a pilot or not
          // redirecting the pilot to the dashboard
          if ($result_array["is_pilot"]) header("Location: /booking.php");

          // if not a pilot, it means he's a mechanic
          // redirecting the mechanic to the dashboard
          else header("Location: /mechanic.php");
          
          // exiting
          exit();
          return ""; 
        }
        else return "Email ou mot de passe incorrect";
      }
      else return "Email ou mot de passe incorrect";
    }
    return "";
  }
}

$session = new Session();

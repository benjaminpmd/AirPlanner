<?php
include_once "./classes/Session.class.php";

/**
 * Class used to store user data.
 * 
 * @version 1.0.0
 * @author Benjamin PAUMARD
 */
class User
{
  private string $user_id;
  private string $email;
  private string $phone;
  private string $first_name;
  private string $last_name;
  private bool $is_pilot = false;
  private bool $is_mechanic = false;
  private bool $is_student = false;
  private bool $is_fi = false;
  private array $pilot_data = [];
  private array $mechanic_data = [];

  public function __construct(string $user_id = "null") {
    $this->user_id = $user_id;
    if ($user_id != "null") {
      $this->fetch_user();
      $this->fetch_pilot();
      $this->fetch_mechanic();
    }
  }

  public function get_user_id(): string {
    return $this->user_id;
  }

  public function set_user_id(string $user_id) {
    $this->user_id = $user_id;
  }

  public function get_email(): string {
    return $this->email;
  }

  public function get_phone(): string {
    return $this->phone;
  }

  public function get_first_name(): string {
    return $this->first_name;
  }

  public function get_last_name(): string {
    return $this->last_name;
  }

  public function is_pilot(): bool {
    return $this->is_pilot;
  }

  public function is_mechanic(): bool {
    return $this->is_mechanic;
  }


  public function is_student() {
    return $this->is_student;
  }

  public function is_fi() {
    return $this->is_fi;
  }


  public function get_pilot_data(): array {
    return $this->pilot_data;
  }

  public function get_mechanic_data(): array {
    return $this->mechanic_data;
  }

  function fetch_user(): void {
    $query = "SELECT email, phone, first_name, last_name, ((SELECT student_id FROM students WHERE student_id=".$this->user_id.")=".$this->user_id.") AS is_student, ((SELECT fi_id FROM instructors WHERE fi_id=".$this->user_id.")=".$this->user_id.") AS is_fi FROM users WHERE (user_id=" . $this->user_id . ");";
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

    if ($result_array) {
      $this->email = $result_array["email"];
      $this->phone = $result_array["phone"];
      $this->first_name = $result_array["first_name"];
      $this->last_name = $result_array["last_name"];
      $this->is_student = ($result_array["is_student"] == "t");
      $this->is_fi = ($result_array["is_fi"] == "t");
    }
  }

  function fetch_pilot(): void {
    $query = "SELECT * FROM pilots WHERE pilot_id=" . $this->user_id . ";";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);

    // getting the results of the query
    $result = pg_query($connection, $query);

    // fetch the result of the query
    $result_array = pg_fetch_all($result);

    // free the result
    pg_free_result($result);

    pg_close($connection);

    if ($result_array) {
      $this->pilot_data = $result_array[0];
      $this->is_pilot = true;
    } 
    else $this->is_pilot = false;
  }

  function fetch_mechanic(): void {
    $query = "SELECT * FROM mechanics WHERE mechanic_id=" . $this->user_id . ";";
    // connecting to the database
    $connection = pg_connect(CONNECTION_STRING);

    // getting the results of the query
    $result = pg_query($connection, $query);

    // fetch the result of the query
    $result_array = pg_fetch_all($result);

    // free the result
    pg_free_result($result);

    pg_close($connection);

    if ($result_array) {
      $this->mechanic_data = $result_array[0];
      $this->is_mechanic = true;
    } 
    else $this->is_mechanic = false;
  }

  /**
   * Function called that reset the password of a user the post query request it
   * 
   * @return bool|string false if the password is not reset, a message if a reset attempt was made
   */
  function reset_password() {
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
        $content = "<html><h2 style=\"font-size: large;\">Réinitialisation de votre mot de passe</h2>\n<h3>Bonjour " . $user[1] . " " . $user[2] . "</h3>\n<p>Vous avez demandez une réinitialisation de votre mot de passe.</p><p>Votre nouveau mot de passe est : " . $new_password . "</p>\n<p>Vous pouvez le modifier à tout moment en vous connectant sur le <a href=\"https://" . WEBSITE_NAME_URL . ".benjaminpmd.fr\">site internet</a>.</p>\n<p></p>\n<p>Cordialement</p>\n<p>L'équipe " . WEBSITE_NAME . "</p><img src=\"https://" . WEBSITE_NAME_URL . ".benjaminpmd.fr/img/favicon.ico\" alt=\"".WEBSITE_NAME." logo\" /></html>";
        $is_sent = send_mail($toAddress, $toName, $subject, $content);
        
        // confirm to the user that a mail have been sent
        if ($is_sent) return "Un email vous a été envoyé.";
        // an error occurred, notify it to the user
        else return "Une erreur est survenue, veuillez réessayer plus tard.";
      }
      // no account exist for this email
      return "Aucun compte n'est associé à cette adresse email.";
    }
    // return false, the user has not requested a password reset
    return false;
  }

  /**
   * Function called that reset the password of a user the post query request it
   * 
   * @return bool|string false if the password is not reset, a message if a reset attempt was made
   */
  public function register(): string {
    // keys for the registering 
    $ids = ["register-firstname", "register-lastname", "register-birthdate", "register-email", "register-phone", "register-address", "register-city", "register-postal-code"];
    // check for each key if a value has been provided
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
      
      if (!is_numeric($_POST["register-phone"])) {
        pg_close($connection);
        return "Téléphone incorrect, assurez vous qu'il soit de la forme 0102030405.";
      }

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
        $content = "<h2>Bienvenue sur la plateforme " . WEBSITE_NAME . "</h2>\n<h3>Bonjour " . $_POST["register-firstname"] . " " . $_POST["register-lastname"] . "</h3>\n<p>Votre incripstion sur la plateforme est complète !</p><p>Votre mot de passe est : " . $new_password . "</p>\n<p>Vous pouvez le modifier à tout moment en vous connectant sur le <a href=\"https://" . WEBSITE_NAME_URL . ".benjaminpmd.fr\">site internet</a>.</p>\n<p></p>\n<p>Cordialement</p>\n<p>L'équipe " . WEBSITE_NAME . "</p><img src=\"https://" . WEBSITE_NAME_URL . ".benjaminpmd.fr/img/favicon.ico\" alt=\"".WEBSITE_NAME." logo\" />";
        $is_sent = send_mail($toAddress, $toName, $subject, $content);
        
        if ($is_sent) return "Un email vous a été envoyé.";
        else return "Une erreur est survenue, veuillez réessayer plus tard.";
      }
      // close the connection to the database
      pg_close($connection);
      return "Une erreur est survenue, veuillez réessayer ultérieurement.";
    }
    pg_close($connection);
    return "Cette adresse email est déjà associée à un compte.";
  }
}

$user = null;
if ($session->is_logged()) {
  $user = new User($_SESSION["user_id"]);
}
else {
  $user = new User();
}

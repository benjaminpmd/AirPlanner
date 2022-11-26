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
  private array $pilot_data = [];
  private array $mechanic_data = [];

  public function __construct(string $user_id) {
    $this->user_id = $user_id;
    $this->fetch_user();
    $this->fetch_pilot();
    $this->fetch_mechanic();
  }

  public function get_user_id(): string {
    return $this->user_id;
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

  public function get_pilot_data(): array {
    return $this->pilot_data;
  }

  public function get_mechanic_data(): array {
    return $this->mechanic_data;
  }

  function fetch_user(): void {
    $query = "SELECT email, phone, first_name, last_name FROM users WHERE (user_id=" . $this->user_id . ");";
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
}

$user = null;
if ($session->is_logged()) {
  $user = new User($_SESSION["user_id"]);
}

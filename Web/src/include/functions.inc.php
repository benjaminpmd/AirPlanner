<?php

/**
 * Convert a float into a time.
 * 
 * @param x the float to convert.
 * @return string the converted time. 
 */
function float_to_time(float $x): string {
  return sprintf('%02d:%02d', (int) $x, fmod($x, 1) * 60);
}

/**
 * Convert a time into a float.
 * 
 * @param time the time to convert.
 * @return float the converted float. 
 */
function time_to_float(string $time): float {
  if (empty($time)) {
    return 0;
  }
  $parts = explode(':', $time);
  return $parts[0] + floor(($parts[1] / 60) * 100) / 100;
}

/**
 * Get a list of the fleet in the database.
 * 
 * @return array of aircraft.
 */
function get_aircrafts(): array {
  $query = "SELECT * FROM aircrafts;";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_all($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // return the result
  return $result_array;
}

/**
 * Get a list of all the instructors in the database.
 * 
 * @return array of instructors data.
 */
function get_instructors(): array {
  $query = "SELECT * FROM instructors JOIN users ON (user_id = fi_id);";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_all($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // return the result
  return $result_array;
}

/**
 * Get all ongoing operations for a mechanic.
 * 
 * @return array of operation data.
 */
function get_operations(string $mechanic_id): array {
  $query = "SELECT * FROM operations WHERE mechanic_id = $mechanic_id AND (op_date IS NULL OR op_date > CURRENT_DATE);";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_all($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // if the result array is null return an empty array
  if (!$result_array) {
    return [];
  }
  // return the resulting array
  return $result_array;
}

/**
 * Get all ongoing operations.
 * 
 * @return array of operation data.
 */
function get_all_operations(): array {
  $query = "SELECT aircraft_reg FROM operations;";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_all($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // if the result array is null return an empty array
  if (!$result_array) {
    return [];
  }
  // return the resulting array
  return $result_array;
}

/**
 * Get the current flight for a pilot.
 * 
 * @param user_id the flight currently in progress for the user.
 * @return array containing the flight details.
 */
function get_current_flight(string $pilot_id): array {
  // create the query
  $query = "SELECT * FROM flights AS f JOIN aircrafts AS a ON a.registration = f.aircraft_reg WHERE (f.pilot_id = $pilot_id) AND (f.in_progress=true);";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_array($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // if the result array is null return an empty array
  if (!$result_array) {
    return [];
  }
  // return the resulting array
  return $result_array;
}

/**
 * Get the flights of an aircraft for a specific date.
 * 
 * @param date selected to check for the flights.
 * @param registration the registration of the aircraft.
 * @return array containing flights details.
 */
function get_flights_per_date(string $date, string $registration): array {
  // create the query
  $query = "SELECT * FROM flights WHERE flight_date='$date' AND aircraft_reg='$registration' ORDER BY start_time;";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_all($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // if the result array is null return an empty array
  if (!$result_array) {
    return [];
  }
  // return the resulting array
  return $result_array;
}

/**
 * Save the flight from data input.
 * 
 * @return string the message associated to the status of the operation.
 */
function save_flight_record(): string {
  // checking the keys that will be used to save the flight (name => is_required)
  $keys = [
    "departure" => true,
    "departure-counter" => true,
    "arrival" => true,
    "arrival-counter" => true,
    "movements" => true,
    "added-fuel" => false,
    "description" => false
  ];

  // there is a flight to save given the parameters
  $exist_flight_to_save = true;

  // checking the presence of each key
  // if a key is not present and is required, then there is no flight to save
  foreach ($keys as $key => $value) {
    // value is a boolean indicating whether the key is required or not (optional then)
    if ($value) {
      // check if the key is set and not empty
      if (!isset($_GET[$key]) || empty($_GET[$key])) {
        // if the key is not set or empty, no flight to save
        $exist_flight_to_save = false;
      }
    }
  }

  // after checking key, if there is no flight to save
  if (!$exist_flight_to_save) return "";

  // in the other case, there is a flight to save
  // starting by converting time data
  $departure_counter = time_to_float($_GET["departure-counter"]);
  $arrival_counter = time_to_float($_GET["arrival-counter"]);
  $flight_time = $arrival_counter - $departure_counter;
  
  // checking if the counter values are correct
  if ($departure_counter >= $arrival_counter) {
    return "Le compteur d'arrivée ne peut pas être inférieur ou égale au compteur de départ";
  }

  // prepare query to get data about the aircraft
  $query = "SELECT * FROM flights AS f JOIN aircrafts AS a ON a.registration = f.aircraft_reg WHERE f.aircraft_reg = '" . $_GET["registration"] . "' AND f.in_progress=true;";
  
  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);
  
  // get the result of the query
  $result = pg_query($connection, $query);
  
  // fetch an array from the query
  $result_array = pg_fetch_array($result);

  // free the result of the query
  pg_free_result($result);

  if ($result_array["flight_id"] < date("Y-m-d")) {
    pg_close($connection);
    return "Erreur, le vol ne peut être enregistré, la date est dépassée";
  }

  // check that the user hasn't modified the original counter
  if ($result_array["aircraft_counter"] < $departure_counter) {
    pg_close($connection);
    return "Le compteur de départ est incorrect";
  }

  // calculate the price of the flight
  $price = number_format((floatval($result_array["price"]) * $flight_time), 2, '.', '');
  
  // create the insertion query for the flight record depending on the optional elements
  $query = "";

  // if the added-fuel option must be set
  if (isset($_GET["added-fuel"]) && !empty($_GET["added-fuel"])) {

    // if the flight-description must be set
    if (isset($_GET["flight-description"]) && !empty($_GET["flight-description"])) {
      $query = "INSERT INTO 
              flight_records(flight_id, departure, departure_counter, arrival, arrival_counter, movements, flight_time, flight_description, added_fuel) 
              VALUES
              (" . $result_array["flight_id"] . ", '" . $_GET["departure"] . "', " . $departure_counter . ", '" . $_GET["arrival"] . "', " . $arrival_counter . ", " . $_GET["movements"] . ", $flight_time, '" . $_GET["flight-description"] . "', " . $_GET["added-fuel"] . ");";
    }

    // if the record should not contain flight-description
    else {
      $query = "INSERT INTO 
              flight_records(flight_id, departure, departure_counter, arrival, arrival_counter, movements, flight_time, added_fuel) 
              VALUES
              (" . $result_array["flight_id"] . ", '" . $_GET["departure"] . "', " . $departure_counter . ", '" . $_GET["arrival"] . "', " . $arrival_counter . ", " . $_GET["movements"] . ", $flight_time, " . $_GET["added-fuel"] . ");";
    }
  } else {
    // if the record should not contain the added fuel
    if (isset($_GET["flight-description"]) && !empty($_GET["flight-description"])) {
      $query = "INSERT INTO 
              flight_records(flight_id, departure, departure_counter, arrival, arrival_counter, movements, flight_time, flight_description) 
              VALUES
              (" . $result_array["flight_id"] . ", '" . $_GET["departure"] . "', " . $departure_counter . ", '" . $_GET["arrival"] . "', " . $arrival_counter . ", " . $_GET["movements"] . ", $flight_time, '" . $_GET["flight_description"] . "');";
    } else {
      // else, the record does not contain optional elements
      $query = "INSERT INTO 
              flight_records(flight_id, departure, departure_counter, arrival, arrival_counter, movements, flight_time) 
              VALUES
              (" . $result_array["flight_id"] . ", '" . $_GET["departure"] . "', " . $departure_counter . ", '" . $_GET["arrival"] . "', " . $arrival_counter . ", " . $_GET["movements"] . ", $flight_time);";
    }
  }
  pg_query($connection, $query);


  // create the query to update the flight progress
  $query = "UPDATE flights SET in_progress=false WHERE flight_id=" . $result_array["flight_id"] . ";";
  pg_query($connection, $query);

  // create the query to update the aircraft
  $query = "UPDATE aircrafts SET flight_potential=(SELECT flight_potential FROM aircrafts WHERE registration='" . $result_array["registration"] . "')-$flight_time, aircraft_counter=" . $arrival_counter . " WHERE registration='" . $result_array["registration"] . "';";
  pg_query($connection, $query);

  // create the query to update the pilot data
  $query = "UPDATE pilots SET 
      pilot_counter=(SELECT pilot_counter FROM pilots WHERE pilot_id=" . $result_array["pilot_id"] . ")+$flight_time,
      balance=(SELECT balance FROM pilots WHERE pilot_id=" . $result_array["pilot_id"] . ")-$price
      WHERE pilot_id=" . $result_array["pilot_id"] . ";";
  pg_query($connection, $query);

  pg_close($connection);

  return "Le vol a été enregistré. Total : $price euros";
}

/**
 * Procedure that complete an operation.
 * 
 * @return string that inform the user
 */
function complete_operation(): string {
  if (isset($_GET["type"]) && !empty($_GET["type"]) && $_GET["type"] == "complete-operation") {

    $query = "";

    if (isset($_GET["description"]) && !empty($_GET["description"])) {
      $query = "UPDATE operations SET op_date=CURRENT_DATE, op_description='".$_GET["description"]."' WHERE aircraft_reg='".$_GET["registration"]."';";
    }
    else {
      $query = "UPDATE operations SET op_date=CURRENT_DATE WHERE aircraft_reg='".$_GET["registration"]."';";
    }

    $aircraft_update_query = "UPDATE aircrafts SET flight_potential=50 WHERE registration='".$_GET["registration"]."';";

    // connect to the db
    $connection = pg_connect(CONNECTION_STRING);
  
    // execute the query
    pg_query($connection, $query);

    // execute the query
    pg_query($connection, $aircraft_update_query);
  
    // close the connection
    pg_close($connection);
  
    // return the result
    return $_GET["registration"]." a été remis en service";
  }
  else return "";
}

/**
 * Procedure that create an operation.
 * 
 * @param user_id the ID of the mechanic.
 * @return string indicating the status of the request to the user.
 */
function create_operation(string $user_id): string {
  if (isset($_GET["type"]) && !empty($_GET["type"]) && $_GET["type"] == "new-operation") {

    $query = "INSERT INTO operations(mechanic_id, aircraft_reg) VALUES (".$user_id." , '".$_GET["registration"]."');";
    
    // connect to the db
    $connection = pg_connect(CONNECTION_STRING);
  
    // execute the query
    pg_query($connection, $query);
  
    // close the connection
    pg_close($connection);
  
    // return the result
    return $_GET["registration"]." a été retiré du service";
  }
  else return "";
}

/**
 * Get aircrafts that can be book by a pilot given its qualifications.
 * 
 * @param user_id the ID of the pilot.
 * @return the aircrafts a pilot can fly.
 */
function get_allowed_aircrafts(string $user_id): array {
  $query = "SELECT registration,
  CASE
      WHEN ((SELECT p.vpp_qualified FROM pilots AS p 
          WHERE p.pilot_id = $user_id)= TRUE) THEN TRUE
      WHEN ((SELECT p.vpp_qualified FROM pilots AS p 
          WHERE p.pilot_id = $user_id)= FALSE) THEN FALSE
  END AS vpp_qualification,
  CASE
      WHEN ((SELECT p.rg_qualified FROM pilots AS p 
          WHERE p.pilot_id = $user_id)= TRUE) THEN TRUE
      WHEN ((SELECT p.rg_qualified FROM pilots AS p 
          WHERE p.pilot_id = $user_id)= FALSE) THEN FALSE
  END AS rg_qualification
  
  FROM aircrafts AS a
  WHERE (
          ( 
              (((SELECT p.rg_qualified FROM pilots AS p 
                  WHERE p.pilot_id = $user_id) = TRUE)
                  AND(a.has_rg = TRUE OR a.has_rg = FALSE))
          OR
              (((SELECT p.rg_qualified FROM pilots AS p 
                  WHERE p.pilot_id = $user_id)= FALSE)
                  AND(a.has_rg = FALSE)))
  AND
          (
              (((SELECT p.vpp_qualified FROM pilots AS p 
                  WHERE p.pilot_id = $user_id)= TRUE)
                  AND(a.has_vpp = TRUE OR a.has_vpp = FALSE))
          OR
              (((SELECT p.vpp_qualified FROM pilots AS p 
                  WHERE p.pilot_id = $user_id)= FALSE)
                  AND(a.has_vpp = FALSE))
          )
  );";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_all($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // if the result array is null return an empty array
  if (!$result_array) {
    return [];
  }
  // return the resulting array
  return $result_array;
}

/**
 * Function to get available instructors given a certain start and end time.
 * 
 * @param flight_date the date of the flight.
 * @param start_time the beginning time of the flight.
 * @param end_time the end time of the flight.
 * @return array of instructors data.
 */
function get_available_fi(string $flight_date, string $start_time, string $end_time): array {
  $query = "SELECT DISTINCT i.fi_id, i.fi_code
  FROM (SELECT f.flight_id,f.flight_date, f.start_time, f.end_time, fi_id 
      FROM flights AS f 
      JOIN lessons AS l ON f.flight_id = l.flight_id
      UNION 
      SELECT f.flight_id, f.flight_date,f.start_time,f.end_time, pilot_id AS fi_id
      FROM flights AS f 
      JOIN instructors AS i ON f.pilot_id = i.fi_id) AS r
  RIGHT JOIN instructors AS i ON r.fi_id = i.fi_id
  WHERE (r.flight_date = '$flight_date')
  AND((r.start_time < '$start_time')AND(r.end_time < '$start_time')) 
  OR ((r.start_time > '$end_time')AND(r.end_time>'$end_time'))
  OR ((r.start_time IS NULL)AND(r.end_time IS NULL))
  OR NOT EXISTS(SELECT * 
                  FROM (SELECT f.flight_id,f.flight_date, f.start_time, f.end_time, fi_id 
                  FROM flights AS f 
                  JOIN lessons AS l ON f.flight_id = l.flight_id
                  UNION 
                  SELECT f.flight_id, f.flight_date,f.start_time,f.end_time, pilot_id AS fi_id
                  FROM flights AS f 
                  JOIN instructors AS i ON f.pilot_id = i.fi_id) AS r
                  RIGHT JOIN instructors AS i ON r.fi_id = i.fi_id
                  WHERE (r.flight_date = '$flight_date'));";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_all($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // if the result array is null return an empty array
  if (!$result_array) {
    return [];
  }
  // return the resulting array
  return $result_array;
}

/**
 * Function to book a flight.
 * 
 * @param user the user object that will be used to check all informations if the flight can be booked.
 * @return string a message to display to the pilot.
 */
function book_flight(User $user): string {
  // get the data of a pilot
  $pilot_data = $user->get_pilot_data();

  // get the allowed aircrafts that can be book
  $allowed_aircrafts = get_allowed_aircrafts($user->get_user_id());

  // check if the balance of the pilot is not less than 0
  if (intval($pilot_data["balance"]) < 0) {
    return "Erreur, votre solde est inférieur à 0";
  };

  // check if the contribution is up to date
  if ($pilot_data["contribution_date"] < date('Y-m-d', strtotime('-1 year'))) {
    return "Erreur, votre contribution club n'est pas à jours";
  }

  // wether the flight is a lesson or not
  $is_lesson = ($_GET["is-lesson"] == 'on');
  
  // if it is not a lesson and the user is a student, block the flight booking
  if (!$is_lesson && $user->is_student()) {
    return "Erreur, vous devez effectuer une réservation avec instructeur";
  }

  // set that the pilot is allowed to book to false
  $is_allowed = false;

  // for each allowed aircraft check if the registration of the plane match an allowed one
  foreach ($allowed_aircrafts as $key => $aircraft) {
    if ($aircraft["registration"] == $_GET["registration"]) {
      $is_allowed = true;
    }
  }

  // if the pilot is not allowed and it is not a lesson, then block the booking
  if (!$is_allowed && !$is_lesson) {
    return "Erreur, vous n'avez pas les qualifications requises pour réserver cet appareil";
  }

  // check the start and end times
  if ($_GET["start-time"] >= $_GET["end-time"]) {
    return "Erreur, l'heure de début ne peut pas être après l'heure de fin";
  }

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // query that check if the start and end times are not in conflict
  $valid_times_query = "SELECT DISTINCT  NOT((((start_time <= '".$_GET["start-time"]."'::time)AND(end_time <= '".$_GET["start-time"]."'::time)) OR ((start_time >= '".$_GET["end-time"]."'::time)AND(end_time >= '".$_GET["end-time"]."'::time)))
    ) AS vol_invalide
    FROM flights
    WHERE flight_date = '".$_GET["date"]."'
    AND aircraft_reg = '".$_GET["registration"]."' ORDER BY vol_invalide DESC LIMIT 1;";

  // execute the query
  $res = pg_query($connection, $valid_times_query);

  $res_array = pg_fetch_array($res);

  // check if there is a flight in conflict
  if ($res_array && ($res_array[0] != "f")) {
    // close the connection
    pg_close($connection);
    return "Le créneau sélectionné est incorrect";
  }

  // if no, then create the request to insert the flight
  $query = "INSERT INTO flights(aircraft_reg, pilot_id, flight_date, start_time, end_time) VALUES ('".$_GET["registration"]."', ".$user->get_user_id().", '".$_GET["date"]."', '".$_GET["start-time"]."', '".$_GET["end-time"]."');";

  // execute the query
  pg_query($connection, $query);

  // if it is a lesson, insert it to the database
  if ($is_lesson) {
    
    $query = "SELECT flight_id FROM flights WHERE flight_date='".$_GET["date"]."' AND start_time='".$_GET["start-time"]."' AND end_time='".$_GET["end-time"]."';";
    
    // execute and get the result of the query
    $result = pg_query($connection, $query);

    // get the array containing all the data
    $result_array = pg_fetch_array($result);

    // free the result
    pg_free_result($result);

    $query = "INSERT INTO lessons(flight_id, fi_id, objective) VALUES (".$result_array["flight_id"].", ".$_GET["fi-id"].", '".$_GET["objective"]."');";

    // execute the query
    pg_query($connection, $query);
  }

  // close the connection
  pg_close($connection);

  return "Votre réservation a bien été prise en compte";
}

/**
 * Function that update the balance of a pilot.
 * 
 * @param string $user_id the ID of the user
 * @param int $amount the amount to reload on the account
 * @return array containing three values, the first one "success" indicate the success of the operation, 
 * the second one 'message' the possible information to display to the user,
 * the third one 'amount' the amount reloaded on the account
 */
function update_balance(string $user_id, int $amount): array {
  // checking if the amount is correct
  if (intval($amount) < 1) {
    return [
      "success" => false,
      "message" => "La somme doit être supérieur ou égale à 1",
      "amount" => 0
    ];
  }
  // create the query
  $query = "UPDATE pilots SET balance=balance+$amount WHERE pilot_id=$user_id;";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  pg_query($connection, $query);

  // close the connection
  pg_close($connection);

  // return the result message
  return [
    "success" => true,
    "message" => "Compte mis à jours",
    "amount" => $amount
  ];
}

/**
 * Function that cancel a flight.
 * 
 * @param string $flight_id the ID of the flight to cancel.
 * @return string 
 */
function cancel_flight(string $flight_id): string {
  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

   // create the query
   $query = "DELETE FROM lessons WHERE flight_id=$flight_id;";

   // execute the query
   pg_query($connection, $query);

   $query = "DELETE FROM flights WHERE flight_id=$flight_id;";
 
   // execute the query
   pg_query($connection, $query);
 
   // close the connection
   pg_close($connection);

   return "Vol annulé";
}

/**
 * Function that return the number of flights donne for a pilot.
 * 
 * @param string $pilot_id the ID of the pilot.
 * @return array number of flights for a pilot
 */
function get_flights_count(string $pilot_id): array {
  // create the query
  $query = "SELECT COUNT(*)
  FROM flights
  WHERE pilot_id = $pilot_id
  GROUP BY pilot_id;";

  // connect to the db
  $connection = pg_connect(CONNECTION_STRING);

  // execute and get the result of the query
  $result = pg_query($connection, $query);

  // get the array containing all the data
  $result_array = pg_fetch_all($result);

  // free the result
  pg_free_result($result);

  // close the connection
  pg_close($connection);

  // if the result array is null return an empty array
  if (!$result_array) {
    return [['count' => 0]];
  }
  // return the resulting array
  return $result_array;
}

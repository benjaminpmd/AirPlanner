<?php
include_once "./include/utils.inc.php";

function get_aircrafts(): array {
    $query = "SELECT * FROM aircrafts;";

    // connect to the db
    $connection = pg_connect(CONNECTION_STRING);

    // update the password
    $result = pg_query($connection, $query);

    $result_array = pg_fetch_all($result);

    pg_free_result($result);

    return $result_array;
}

function get_instructors(): array {
    $query = "SELECT * FROM instructors JOIN users ON (user_id = fi_id);";

    // connect to the db
    $connection = pg_connect(CONNECTION_STRING);

    // update the password
    $result = pg_query($connection, $query);

    $result_array = pg_fetch_all($result);

    pg_free_result($result);

    return $result_array;
}


function get_operations(string $mechanic_id): array {
    $query = "SELECT * FROM operations WHERE mechanic_id = $mechanic_id;";

    // connect to the db
    $connection = pg_connect(CONNECTION_STRING);

    // update the password
    $result = pg_query($connection, $query);

    $result_array = pg_fetch_all($result);

    pg_free_result($result);

    return $result_array;
}


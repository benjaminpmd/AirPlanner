import psycopg2 as psql
import logging
import config


class Database:
    def __init__(self, db_config: dict[str, str]) -> None:
        """ Constructor of the Database class.

        @param db_config the configuration of the database.
        the configuration for the postgreSQL must be like the following example :

        {
            HOST:       the host (url) of the postgreSQL,
            PORT:       the port of the postgreSQL,
            DBNAME:     the name of the database to use,
            USER:       the username to use for connection,
            PASSWORD:   the password to use to connect
        }
        """
        logging.basicConfig(
            format='%(asctime)s - %(levelname)s - Database: %(message)s',
            datefmt='%m/%d/%Y %H:%M:%S',
            level=config.LOGGING_LEVEL,
            handlers=[
                logging.FileHandler(config.LOGGING_FILE),
                logging.StreamHandler()
            ]
        )
        # create the connection string for the connection to the postgreSQL
        self.connection_string: str = f"host={db_config.get('HOST')} port={db_config.get('PORT')} dbname={db_config.get('DBNAME')} user={db_config.get('USER')} password={db_config.get('PASSWORD')}"

    def check_for_locker_open(self, registration: str, user_id: str) -> tuple:
        """! Get the flight for a specific aircraft and user depending on the date.

        @param registration the aircraft registration.
        @param user_id the ID of the user.
        @return a tuple containing (if the aircraft is in maintenance, if the user is a mechanic, if the user is a pilot with a scheduled flight).
        """
        # init the result
        res: tuple = None
        try:
            # connecting to the database
            connection: psql.connection = psql.connect(self.connection_string)

            # fetching the cursor
            cursor: psql.cursor = connection.cursor()

            # executing the request
            cursor.execute(f"SELECT ((SELECT o.aircraft_reg FROM operations AS o WHERE o.aircraft_reg = '{registration}' AND ((o.op_date > CURRENT_DATE) OR (o.op_date IS NULL)))='{registration}') as unavailable, ((SELECT user_id FROM users AS u JOIN mechanics AS m ON u.user_id = m.mechanic_id WHERE m.mechanic_id = {user_id})={user_id}) AS is_mechanic,((SELECT DISTINCT f.pilot_id FROM flights AS f JOIN (SELECT f.flight_id FROM flights AS f LEFT JOIN lessons AS l ON f.flight_id = l.flight_id WHERE (f.pilot_id = {user_id})) AS m ON f.flight_id = m.flight_id JOIN aircrafts AS a ON f.aircraft_reg = a.registration WHERE ((f.start_time <= CURRENT_TIME)AND(f.end_time >= CURRENT_TIME))AND (a.registration = '{registration}')AND(flight_date = CURRENT_DATE)) ={user_id}) AS is_flight_scheduled FROM users AS u WHERE u.user_id = {user_id};")
            
            # fetch the result
            res = cursor.fetchone()

            # close the cursor
            cursor.close()

            # close the connection
            connection.close()
        except Exception as e:
            # in case we could no connect to the database
            logging.error(f"request on database could not be completed: {e}")

        # return the result
        return res

    def check_for_door_open(self, parking: str, user_id: str):
        connection: psql.connection = psql.connect(self.connection_string)
        cursor: psql.cursor = connection.cursor()
        cursor.execute(f"SELECT ((SELECT a.parking FROM operations AS o JOIN aircrafts AS a ON o.aircraft_reg = a.registration WHERE a.parking = '{parking}' AND ((o.op_date > CURRENT_DATE) OR (o.op_date IS NULL)))='{parking}') as unavailable, ((SELECT user_id FROM users AS u JOIN mechanics AS m ON u.user_id = m.mechanic_id WHERE m.mechanic_id = {user_id})={user_id}) AS is_mechanic, ((SELECT DISTINCT f.pilot_id FROM flights AS f JOIN (SELECT f.flight_id FROM flights AS f LEFT JOIN lessons AS l ON f.flight_id = l.flight_id WHERE (f.pilot_id = {user_id})) AS m ON f.flight_id = m.flight_id JOIN aircrafts AS a ON f.aircraft_reg = a.registration WHERE ((f.start_time <= CURRENT_TIME)AND(f.end_time >= CURRENT_TIME)) AND (a.parking = {parking})AND(flight_date = CURRENT_DATE))={user_id})  AS is_flight_scheduled  FROM users AS u WHERE u.user_id = {user_id};")
        res: tuple = cursor.fetchone()
        cursor.close()
        connection.close()
        return res

    def get_flight(self, registration: str, pilot_id: str) -> tuple:
        """! Get the flight for a specific aircraft and user depending on the date.

        @param registration the aircraft registration.
        @param user_id the ID of the user.
        @return the informations about the flight.
        """
        connection: psql.connection = psql.connect(self.connection_string)
        cursor: psql.cursor = connection.cursor()
        cursor.execute(f"SELECT f.flight_id, f.end_time, a.parking, u.first_name, u.last_name FROM pilots AS p JOIN flights AS f  ON p.pilot_id = f.pilot_id JOIN aircrafts AS a ON f.aircraft_reg = a.registration LEFT JOIN lessons AS l ON f.flight_id = l.flight_id LEFT JOIN users AS u ON l.fi_id = u.user_id WHERE (p.pilot_id = {pilot_id})AND(f.aircraft_reg = '{registration}') AND (f.flight_date = CURRENT_DATE) AND NOT EXISTS(SELECT  o.aircraft_reg FROM operations AS o WHERE o.aircraft_reg = '{registration}' AND ((o.op_date > CURRENT_DATE) OR (o.op_date IS NULL)));")
        res: tuple = cursor.fetchone()
        cursor.close()
        connection.close()
        return res
    
    def set_flight_progress(self, flight_id: int, status: bool = True) -> None:
        connection: psql.connection = psql.connect(self.connection_string)
        cursor: psql.cursor = connection.cursor()
        logging.debug(f"setting the flight {flight_id} progress to {status}")
        if (status):
            cursor.execute(f"UPDATE flights SET in_progress = true WHERE (flight_id={flight_id});")
        else:
            cursor.execute(f"UPDATE flights SET in_progress = false WHERE (flight_id = {flight_id});")
        connection.commit()
        cursor.close()
        connection.close()
import psycopg2 as psql
import logging
import config

class Database:
    def __init__(self, db_config: dict[str, str | None]) -> None:
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
            filename=config.LOGGING_FILE,
            filemode='a',
            format='%(asctime)s - %(levelname)s - Database: %(message)s',
            datefmt='%m/%d/%Y %I:%M:%S %p',
            level=config.LOGGING_LEVEL
        )
        # create the connection string for the connection to the postgreSQL
        self.connection_string: str = f"host={db_config.get('HOST')} port={db_config.get('PORT')} dbname={db_config.get('DBNAME')} user={db_config.get('USER')} password={db_config.get('PASSWORD')}"

    def get_user(self, user_id: str) -> tuple or None:
        """
        @param user_id: test
        """
        connection: psql.connection = psql.connect(self.connection_string)
        cursor: psql.cursor = connection.cursor()
        cursor.execute(f"SELECT * FROM users WHERE (user_id={user_id});")
        res: tuple = cursor.fetchone()
        cursor.close()
        connection.close()
        return res

    def get_flight(self, registration: str, pilot_id: str) -> tuple or None:
        """! Get the flight for a specific aircraft and user depending on the date.
        @param registration the aircraft registration
        @param user_id the ID of the user
        @return the informations about the flight.
        """
        connection: psql.connection = psql.connect(self.connection_string)
        cursor: psql.cursor = connection.cursor()
        cursor.execute(f"SELECT * FROM flights WHERE (pilot_id={pilot_id} AND aircraft_reg='{registration}');")
        res: tuple = cursor.fetchone()
        cursor.close()
        connection.close()
        return res

    def get_flight_from_id(self, flight_id: str) -> tuple or None:
        """! Get the flight for a specific aircraft and user depending on the date.
        @param registration the aircraft registration
        @param user_id the ID of the user
        @return the informations about the flight.
        """
        connection: psql.connection = psql.connect(self.connection_string)
        cursor: psql.cursor = connection.cursor()
        cursor.execute(f"SELECT * FROM flights WHERE (flight_id={flight_id});")
        res: tuple = cursor.fetchone()
        cursor.close()
        connection.close()
        return res

    def get_mechanic(self, user_id: str) -> tuple or None:
        """
        @param user_id the ID of the mechanic
        """
        connection: psql.connection = psql.connect(self.connection_string)
        cursor: psql.cursor = connection.cursor()
        cursor.execute(f"SELECT * FROM users AS u JOIN mechanics AS m ON (u.user_id = m.mechanic_id);")
        res: tuple = cursor.fetchone()
        cursor.close()
        connection.close()
        return res
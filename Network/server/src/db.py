"""! File containing the class for the connections with the database.

@author Benjamin PAUMARD
@version 1.0.0 (28/11/2022)
@since 10/11/2022
"""
# imports
import psycopg2 as psql
import logging
import config


class Database:
    """! Class used to make request on the database.
    """
    def __init__(self, db_config: dict) -> None:
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

        # create the connection string for the connection to the postgreSQL
        self.__connection_string: str = f"host={db_config.get('HOST')} port={db_config.get('PORT')} dbname={db_config.get('DBNAME')} user={db_config.get('USER')} password={db_config.get('PASSWORD')}"

    def __select(self, query: str):
        """! Method that makes a request on the database.
        
        @param query the query as a string.
        @return the result of the query or null.
        """

        # init the result
        res: tuple = None
        try:
            # connecting to the database
            connection: psql.connection = psql.connect(self.__connection_string)

            # fetching the cursor
            cursor: psql.cursor = connection.cursor()

            # executing the request
            cursor.execute(query)
            
            # fetch the result
            res: tuple = cursor.fetchone()

            if (res is None):
                res = ()

            # close the cursor
            cursor.close()

            # close the connection
            connection.close()
        
        except Exception as e:
            # in case we could no connect to the database
            logging.error(f"request on database could not be completed: {e}")

        # return the result
        return res

    def check_for_locker_open(self, registration: str, user_id: str) -> tuple:
        """! Check if a locker can be opened.
        The time used is the current one.

        @param registration the aircraft registration.
        @param user_id the ID of the user.
        @return a tuple containing (if the aircraft is in maintenance, if the user is a mechanic, if the user is a pilot with a scheduled flight).
        """

        # create the query
        query: str = f"SELECT (( SELECT o.aircraft_reg  FROM operations AS o WHERE o.aircraft_reg = '{registration}' AND ((o.op_date > CURRENT_DATE) OR (o.op_date IS NULL)))='{registration}' ) AS is_unavailable,  (( SELECT user_id FROM users AS u JOIN mechanics AS m ON u.user_id = m.mechanic_id WHERE m.mechanic_id = {user_id})={user_id} ) AS is_mechanic, (( SELECT DISTINCT f.pilot_id  FROM flights AS f  JOIN ( SELECT f.flight_id  FROM flights AS f  LEFT JOIN lessons AS l ON f.flight_id = l.flight_id  WHERE (f.pilot_id = {user_id})) AS m ON f.flight_id = m.flight_id  JOIN aircrafts AS a ON f.aircraft_reg = a.registration  WHERE ( (f.start_time <= CURRENT_TIME + interval '1h') AND (f.end_time >= CURRENT_TIME + interval '1h')) AND (a.registration = '{registration}') AND (flight_date = CURRENT_DATE))={user_id} ) AS is_flight_scheduled  FROM users AS u  WHERE u.user_id = {user_id};"
    
        # return the result
        return self.__select(query)

    def check_for_door_open(self, parking: str, user_id: str):
        """! Check if a door can be opened.
        The time used is the current one.

        @param registration the aircraft registration.
        @param user_id the ID of the user.
        @return a tuple containing (if the aircraft is in maintenance, if the user is a mechanic, if the user is a pilot with a scheduled flight).
        """

        # init the query
        query: str = f" SELECT  ((SELECT a.parking  FROM operations AS o JOIN aircrafts AS a ON o.aircraft_reg = a.registration WHERE a.parking = {parking} AND  ((o.op_date > CURRENT_DATE) OR (o.op_date IS NULL)))={parking} ) AS is_unavailable, ((SELECT user_id  FROM users AS u  JOIN mechanics AS m ON u.user_id = m.mechanic_id  WHERE m.mechanic_id = {user_id})={user_id} ) AS is_mechanic, ((SELECT DISTINCT f.pilot_id  FROM flights AS f  JOIN (SELECT f.flight_id  FROM flights AS f  LEFT JOIN lessons AS l ON f.flight_id = l.flight_id WHERE (f.pilot_id = {user_id}))  AS m  ON f.flight_id = m.flight_id  JOIN aircrafts AS a ON f.aircraft_reg = a.registration WHERE ((f.start_time <= CURRENT_TIME + interval '1h') AND(f.end_time >= CURRENT_TIME + interval '1h' )) AND (a.parking = {parking}) AND(flight_date = CURRENT_DATE)) ={user_id} ) AS is_flight_scheduled  FROM users AS u WHERE u.user_id = {user_id};"
        
        # return the result
        return self.__select(query)

    def get_flight_data(self, registration: str, pilot_id: str) -> tuple:
        """! Get the flight data for an aircraft registration and a pilot ID.
        This method base the flight date on current time.

        @param registration the aircraft registration.
        @param user_id the ID of the user.
        @return tuple containing (flight_id, end time, parking, fi first name (may be null), fi last name (may be null)).
        """

        # init the query
        query: str = f"SELECT t.flight_id, t.end_time, t.parking, t.first_name, t.last_name FROM( SELECT f.flight_id, f.start_time, f.end_time, a.parking, u.first_name, u.last_name,  u.user_id,a.registration,f.flight_date FROM pilots AS p  JOIN flights AS f ON p.pilot_id = f.pilot_id  JOIN aircrafts AS a ON f.aircraft_reg = a.registration  JOIN users AS u ON p.pilot_id = u.user_id  UNION  SELECT f.flight_id, f.start_time, f.end_time, a.parking, u.first_name, u.last_name,  u.user_id,a.registration,f.flight_date FROM pilots AS p  JOIN flights AS f ON p.pilot_id = f.pilot_id  JOIN aircrafts AS a ON f.aircraft_reg = a.registration  JOIN lessons AS l ON f.flight_id = l.flight_id  JOIN users AS u ON l.fi_id = u.user_id) AS t WHERE (t.user_id = {pilot_id}) AND (t.registration= '{registration}')  AND  (t.flight_date = CURRENT_DATE)  AND (t.start_time <= CURRENT_TIME + interval '1h') AND (t.end_time > CURRENT_TIME + interval '1h') AND NOT EXISTS (SELECT  o.aircraft_reg FROM operations AS o WHERE o.aircraft_reg = '{registration}' AND (o.op_date IS NULL OR o.op_date > CURRENT_DATE));"
        
        # return the result
        return self.__select(query)
    
    def set_flight_progress(self, flight_id: int, status: bool = True) -> bool:
        """! Set the status of a specific flight.
        
        @param flight_id the ID of the flight.
        @param status a boolean indicating if the flight status should be true or false.
        @return ether the flight has been updated or not.
        """

        # create the query
        query: str = ''

        # setup the query depending on the status
        if (status):
            query = f"UPDATE flights SET in_progress=true WHERE (flight_id={flight_id});"
        else:
            query = f"UPDATE flights SET in_progress=false WHERE (flight_id = {flight_id});"
        
        try: 
            # connecting to the database
            connection: psql.connection = psql.connect(self.__connection_string)

            # fetching the cursor
            cursor: psql.cursor = connection.cursor()

            # executing the request
            cursor.execute(query)

            # commit the changes
            connection.commit()

            # close the connection
            cursor.close()
            connection.close()
            
            logging.debug(f"setting the flight {flight_id} progress to {status}")
            # return that the flight has been updated
            return True

        except Exception as e:
            # in case we could no connect to the database
            logging.error(f"request on database could not be completed: {e}")

            return False

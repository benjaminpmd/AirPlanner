/**
 * File containing all the SELECT requests made in the project.
 * 
 * @author Eva FLEUTRY
 * @version 1.0.0
 * @sincre 02 December 2022
 */

-- select used to check if a door can be opened, we get the availability of the aicraft, 
-- if it is a mechanic, and if the user is a pilot with a scheduled flight

SELECT (
    ( 
        SELECT o.aircraft_reg 
        FROM operations AS o 
        WHERE o.aircraft_reg = 'INSERT registration' AND ((o.op_date > CURRENT_DATE) OR (o.op_date IS NULL)))='INSERT registration' ) AS is_unavailable,  
        (
            ( 
                SELECT user_id 
                FROM users AS u 
                JOIN mechanics AS m ON u.user_id = m.mechanic_id 
                WHERE m.mechanic_id = 'INSERT user_id'
            )='INSERT user_id'
        ) AS is_mechanic,
        (
            ( 
                SELECT DISTINCT f.pilot_id 
                FROM flights AS f  
                JOIN (
                    SELECT f.flight_id  
                    FROM flights AS f  
                    LEFT JOIN lessons AS l ON f.flight_id = l.flight_id  
                    WHERE (f.pilot_id = 'INSERT user_id')
                    ) AS m ON f.flight_id = m.flight_id 
                JOIN aircrafts AS a ON f.aircraft_reg = a.registration 
                WHERE ( (f.start_time <= CURRENT_TIME + interval '1h') AND (f.end_time >= CURRENT_TIME + interval '1h')) AND (a.registration = 'INSERT registration') AND (flight_date = CURRENT_DATE)
            )='INSERT user_id'
        ) AS is_flight_scheduled
                FROM users AS u
                WHERE u.user_id = 'INSERT user_id';

-- select used to check if a door can be opened, we get the availability of the aicraft, 
-- if it is a mechanic, and if the user is a pilot with a scheduled flight
SELECT (
    (
        SELECT a.parking  
        FROM operations AS o 
        JOIN aircrafts AS a ON o.aircraft_reg = a.registration 
        WHERE a.parking = 'INSERT parking' AND  ((o.op_date > CURRENT_DATE) OR (o.op_date IS NULL)))='INSERT parking'
    ) AS is_unavailable, 
    (
        (
            SELECT user_id  
            FROM users AS u  
            JOIN mechanics AS m ON u.user_id = m.mechanic_id  
            WHERE m.mechanic_id = 'INSERT user_id'
        )='INSERT user_id' 
    ) AS is_mechanic, 
    (
        (
            SELECT DISTINCT f.pilot_id  
            FROM flights AS f  
            JOIN (
                SELECT f.flight_id  
                FROM flights AS f  
                LEFT JOIN lessons AS l ON f.flight_id = l.flight_id 
                WHERE (f.pilot_id = 'INSERT user_id'))  AS m  ON f.flight_id = m.flight_id  
                JOIN aircrafts AS a ON f.aircraft_reg = a.registration 
                WHERE ((f.start_time <= CURRENT_TIME + interval '1h') AND (f.end_time >= CURRENT_TIME + interval '1h' )) AND (a.parking = 'INSERT parking') AND (flight_date = CURRENT_DATE)
        ) = 'INSERT user_id' ) AS is_flight_scheduled  
        FROM users AS u WHERE u.user_id = 'INSERT user_id';


-- select informations about a flight, including the ID of the flight, the end time, 
-- the parking number and possibly the first and last name of the instructor if it is a lesson
SELECT t.flight_id, t.end_time, t.parking ,u.last_name, u.first_name 
    FROM ( 
        SELECT f.flight_id, f.start_time, f.end_time, a.parking, u.first_name, u.last_name, u.user_id,a.registration,f.flight_date 
            FROM pilots AS p  
            JOIN flights AS f ON p.pilot_id = f.pilot_id  
            JOIN aircrafts AS a ON f.aircraft_reg = a.registration  
            JOIN users AS u ON p.pilot_id = u.user_id  
        UNION  
        SELECT f.flight_id, f.start_time, f.end_time, a.parking, u.first_name, u.last_name, u.user_id,a.registration,f.flight_date 
            FROM pilots AS p  
            JOIN flights AS f ON p.pilot_id = f.pilot_id  
            JOIN aircrafts AS a ON f.aircraft_reg = a.registration  
            JOIN lessons AS l ON f.flight_id = l.flight_id  
            JOIN users AS u ON l.fi_id = u.user_id) AS t 
            LEFT JOIN lessons AS l ON t.flight_id = l.flight_id 
            LEFT JOIN users AS u ON l.fi_id = u.user_id 
            WHERE (t.user_id = 'INSERT pilot_id') 
            AND (t.registration= 'INSERT registration')  
            AND  (t.flight_date = CURRENT_DATE)  
            AND (t.start_time <= CURRENT_TIME + interval '1h') 
            AND (t.end_time > CURRENT_TIME + interval '1h') 
            AND NOT EXISTS (
                SELECT  o.aircraft_reg  
                    FROM operations AS o  
                    WHERE o.aircraft_reg = 'INSERT registration'  
                    AND (o.op_date IS NULL OR o.op_date > CURRENT_DATE) 
            );

-- request to get the information about a user and if it his a student or maybe a instructor
SELECT email, phone, first_name, last_name, (
        (
            SELECT student_id 
            FROM students 
            WHERE student_id='INSERT user_id'
        )='INSERT user_id') AS is_student, 
    (
        (
            SELECT fi_id 
            FROM instructors 
            WHERE fi_id='INSERT user_id'
        )='INSERT user_id'
    ) AS is_fi 
    FROM users 
    WHERE (user_id='INSERT user_id');


-- request to get all the informations about a pilot
SELECT * FROM pilots WHERE pilot_id="INSERT pilot_id";

-- request to get all the data of a mechanic
SELECT * FROM mechanics WHERE mechanic_id="INSERT user_id";

-- select the primordial informations about a user given an email address
SELECT email, first_name, last_name FROM users WHERE (email='INSERT email');

--request to get all the informations about all the aircrafts--
SELECT * FROM aircrafts;

--request to get all the informations about an instructor--
SELECT * FROM instructors JOIN users ON (user_id = 'INSERT fi_id');

--request to get all the informations about the operations of a mechanic that haven't been done yet--
SELECT * FROM operations WHERE mechanic_id = 'INSERT mechanic_id' AND (op_date IS NULL OR op_date > CURRENT_DATE);

--request to get only the aircraft registration for each operation--
SELECT aircraft_reg FROM operations;

--request to get all the informations about the on going flight of a user and his aircraft--
SELECT * FROM flights AS f JOIN aircrafts AS a ON a.registration = f.aircraft_reg WHERE (f.pilot_id = 'INSERT pilot_id') AND (f.in_progress=true);

--request to get all the informations about the flights scheduled on a day for an aircraft with a given registration, ordered by their start time--
SELECT * FROM flights WHERE flight_date='INSERT date' AND aircraft_reg='INSERT registration' ORDER BY start_time;

--request to get all the informations about the on going flights of the aircraft given by its registration, and the aircraft itself--
SELECT * FROM flights AS f JOIN aircrafts AS a ON a.registration = f.aircraft_reg WHERE f.aircraft_reg = 'INSERT registration' AND f.in_progress=true;

--request to get the registration of the aircrafts a pilot can book--
SELECT registration,
CASE
    WHEN ((SELECT p.vpp_qualified FROM pilots AS p WHERE p.pilot_id = 'INSERT pilot_id')= TRUE) THEN TRUE
    WHEN ((SELECT p.vpp_qualified FROM pilots AS p WHERE p.pilot_id = 'INSERT pilot_id')= FALSE) THEN FALSE
END AS vpp_qualification,
CASE
    WHEN ((SELECT p.rg_qualified FROM pilots AS p WHERE p.pilot_id = 'INSERT pilot_id')= TRUE) THEN TRUE
    WHEN ((SELECT p.rg_qualified FROM pilots AS p WHERE p.pilot_id = 'INSERT pilot_id')= FALSE) THEN FALSE
END AS rg_qualification

FROM aircrafts AS a
WHERE (
        ( 
            (((SELECT p.rg_qualified FROM pilots AS p WHERE p.pilot_id = 'INSERT pilot_id') = TRUE)AND(a.has_rg = TRUE OR a.has_rg = FALSE))
        OR
            (((SELECT p.rg_qualified FROM pilots AS p WHERE p.pilot_id = 'INSERT pilot_id')= FALSE)AND(a.has_rg = FALSE)))
AND
        (
            (((SELECT p.vpp_qualified FROM pilots AS p WHERE p.pilot_id = 'INSERT pilot_id')= TRUE)AND(a.has_vpp = TRUE OR a.has_vpp = FALSE))
        OR
            (((SELECT p.vpp_qualified FROM pilots AS p WHERE p.pilot_id = 'INSERT pilot_id')= FALSE)AND(a.has_vpp = FALSE))
        )
);

--request to get the instructors available given a date and a certain start time and end time--

SELECT DISTINCT i.fi_id, i.fi_code
  FROM (SELECT f.flight_id,f.flight_date, f.start_time, f.end_time, fi_id 
      FROM flights AS f 
      JOIN lessons AS l ON f.flight_id = l.flight_id
      UNION 
      SELECT f.flight_id, f.flight_date,f.start_time,f.end_time, pilot_id AS fi_id
      FROM flights AS f 
      JOIN instructors AS i ON f.pilot_id = i.fi_id) AS r
  RIGHT JOIN instructors AS i ON r.fi_id = i.fi_id
  WHERE (r.flight_date = 'INSERT flight_date')
  AND((r.start_time < 'INSERT start_time')AND(r.end_time < 'INSERT start_time')) 
  OR ((r.start_time > 'INSERT end_time')AND(r.end_time>'INSERT end_time'))
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
                  WHERE (r.flight_date = 'INSERT flight_date'));

--request to get the flight_id of a flight scheduled a given date, start time and end time--
SELECT flight_id FROM flights 
WHERE flight_date='INSERT flight_date' 
AND start_time='INSERT start_time' 
AND end_time='INSERT end_time';

--request to get the count of every flights a pilot did since he registered--
SELECT COUNT(*)
  FROM flights
  WHERE pilot_id = 'INSERT pilot_id'
  GROUP BY pilot_id;


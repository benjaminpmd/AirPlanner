/**
 * Creation of the tables used for the procject
 *
 * @author Eva FLEUTRY
 * @co-author Benjamin PAUMARD
 * @co-author Xuming MA
 * @version 1.0.0
 * @since 22/10/2022
 */

-- removing all data
-- DELETE FROM users CASCADE;
-- DELETE FROM pilots CASCADE;
-- DELETE FROM students CASCADE;
-- DELETE FROM instructors CASCADE;
-- DELETE FROM aircrafts CASCADE;
-- DELETE FROM flight_schedules CASCADE;
-- DELETE FROM flight_records CASCADE;
-- DELETE FROM lessons CASCADE;
-- DELETE FROM mechanics CASCADE;
-- DELETE FROM operations CASCADE;

-- Dropping
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS pilots CASCADE;
DROP TABLE IF EXISTS students CASCADE;
DROP TABLE IF EXISTS instructors CASCADE;
DROP TABLE IF EXISTS aircrafts CASCADE;
DROP TABLE IF EXISTS flight_schedules CASCADE;
DROP TABLE IF EXISTS flight_records CASCADE;
DROP TABLE IF EXISTS lessons CASCADE;
DROP TABLE IF EXISTS mechanics CASCADE;
DROP TABLE IF EXISTS operations CASCADE;

-- Create a new table called 'users'
CREATE TABLE users(
    user_id SERIAL,
    phone CHAR(10) NOT NULL,
    email VARCHAR(100) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    password VARCHAR(60) NOT NULL,
    CONSTRAINT user_pk PRIMARY KEY (user_id),
    CONSTRAINT valid_phone CHECK (phone ~ '^[0-9 ]*$')
);

-- Create a new table called 'pilots'
CREATE TABLE pilots (
    pilot_id SERIAL REFERENCES users(user_id) NOT NULL,
    birth_date DATE NOT NULL,
    pilot_address VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code CHAR(7),
    contribution_date DATE,
    medical_check_date DATE,
    ifr_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    night_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    vpp_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    rg_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    pilot_counter INTEGER NOT NULL DEFAULT(0),
    balance INTEGER NOT NULL DEFAULT(0),
    rib CHAR(27),
    CONSTRAINT pilot_pk PRIMARY KEY (pilot_id),
    CONSTRAINT valid_birth_date CHECK (birth_date <= CURRENT_DATE),
    CONSTRAINT valid_postal_code CHECK (postal_code ~ '^[0-9 ]*$'),
    CONSTRAINT valid_contribution_date CHECK (contribution_date <= CURRENT_DATE),
    CONSTRAINT valid_medical_check_date CHECK (medical_check_date <= CURRENT_DATE)
);

-- Create a new table called 'students'
CREATE table students(
    student_id SERIAL REFERENCES pilots(pilot_id) NOT NULL,
    aircraft_type VARCHAR(10) NOT NULL,
    CONSTRAINT student_pk PRIMARY KEY (student_id),
    CONSTRAINT valid_aircraft_type CHECK (LENGTH(aircraft_type) > 2)
);

-- Create a new table called 'instructors'
CREATE table instructors(
    fi_id SERIAL REFERENCES pilots(pilot_id) NOT NULL,
    fi_code CHAR(3) NOT NULL,
    CONSTRAINT fi_pk PRIMARY KEY (fi_id)
);

-- Create a new table called 'aircrafts'
CREATE table aircrafts(
    registration CHAR(6) NOT NULL,
    aircraft_type VARCHAR(10) NOT NULL,
    max_pax INT NOT NULL,
    aircraft_range INT NOT NULL,
    flight_potential FLOAT NOT NULL,
    nav_certificate_date DATE NOT NULL,
    ifr_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    night_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    aircraft_counter FLOAT NOT NULL DEFAULT(0),
    has_vpp BOOLEAN DEFAULT(FALSE),
    has_rg BOOLEAN DEFAULT(FALSE),
    price FLOAT NOT NULL,
    CONSTRAINT registration_pk PRIMARY KEY(registration),
    CONSTRAINT valid_aircraft_type CHECK (LENGTH(aircraft_type) > 2),
    CONSTRAINT valid_max_pax CHECK (max_pax in(1,2,3)),
    CONSTRAINT valid_range CHECK ((range >= 400)AND(range <= 1700)),
    CONSTRAINT valid_price CHECK ((price >= 50)AND(price <= 350))
);


-- Create a new table called 'flight_schedules'
CREATE table flight_schedules(
    flight_id INT NOT NULL,
    pilot_id SERIAL REFERENCES users(user_id) NOT NULL,
    aircraft_reg CHAR(6) NOT NULL,
    flight_date date NOT NULL,
    start_time time NOT NULL,
    end_time time NOT NULL,
    flight_description VARCHAR(100),
    CONSTRAINT flight_pk PRIMARY KEY(flight_id),
    CONSTRAINT pilot_id_fk FOREIGN KEY (pilot_id) REFERENCES pilots(pilot_id),
    CONSTRAINT registration_fk FOREIGN KEY (aircraft_reg) REFERENCES aircrafts(registration),
    CONSTRAINT valid_flight_date CHECK (flight_date >= CURRENT_DATE)
);

-- Create a new table called 'flight_records'
CREATE table flight_records(
    flight_id INT NOT NULL,
    departure CHAR(4) NOT NULL,
    departure_counter FLOAT NOT NULL,
    arrival CHAR(4) NOT NULL,
    arrival_counter FLOAT NOT NULL,
    movements INT NOT NULL,
    flight_time FLOAT NOT NULL,
    added_fuel INT,
    flight_description VARCHAR(100),
    CONSTRAINT flight_fk FOREIGN KEY (flight_id) REFERENCES flights(flight_id),
    CONSTRAINT valid_counter CHECK (departure_counter < arrival_counter),
    CONSTRAINT valid_movements CHECK (movements >= 2),
    CONSTRAINT flight_rec_pk PRIMARY KEY(flight_id)
);


-- Create a new table called 'lessons'
CREATE table lessons(
    flight_id INT NOT NULL,
    fi_id INT NOT NULL,
    student_id INT NOT NULL,
    objective VARCHAR(200) NOT NULL,
    CONSTRAINT flight_id_fk FOREIGN KEY (flight_id) REFERENCES flight_schedules(flight_id),
    CONSTRAINT fi_id_fk FOREIGN KEY (fi_id) REFERENCES instructors(fi_id),
    CONSTRAINT student_id_pk FOREIGN KEY (student_id) REFERENCES students(student_id),
    CONSTRAINT lessons_pk PRIMARY KEY (flight_id, fi_id, student_id)
);

-- Create a new table called 'mechanics'
CREATE table mechanics(
    mechanic_id SERIAL REFERENCES users(user_id) NOT NULL,
    mechanic_signature INT NOT NULL,
    CONSTRAINT mechanic_pk PRIMARY KEY (mechanic_id)
);

-- Create a new table called 'operations'
CREATE table operations(
    mechanic_id INT NOT NULL,
    aircraft_reg CHAR(6) NOT NULL,
    op_date date,
    flight_description VARCHAR(100),
    CONSTRAINT mechanic_id_fk FOREIGN KEY (mechanic_id) REFERENCES mechanics(mechanic_id),
    CONSTRAINT aircraft_reg_fk FOREIGN KEY (aircraft_reg) REFERENCES aircrafts(registration),
    CONSTRAINT operations_pk PRIMARY KEY (mechanic_id, aircraft_reg)
    
);

/**
 * Creation of the tables used for the procject
 *
 * @author Benjamin PAUMARD
 * @co-author Eva FLEUTRY
 * @co-author Xuming MA
 * @version 1.0.0
 * @since 22/10/2022
 */

-- removing all data
--DELETE FROM site_sessions;
--DELETE FROM pilots;

-- Dropping
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS pilots CASCADE;
DROP TABLE IF EXISTS students CASCADE;
DROP TABLE IF EXISTS instructors CASCADE;
DROP TABLE IF EXISTS aicrafts CASCADE;
DROP TABLE IF EXISTS flight_schedules CASCADE;
DROP TABLE IF EXISTS flight_records CASCADE;
DROP TABLE IF EXISTS lessons CASCADE;
DROP TABLE IF EXISTS mechanics CASCADE;
DROP TABLE IF EXISTS operations CASCADE;
DROP TABLE IF EXISTS site_sessions CASCADE;

-- Create a new table called 'pilots'
CREATE TABLE users (
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
    address VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code CHAR(7),
    contribution_date DATE,
    medical_check_date DATE,
    ifr_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    night_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    vpp_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    rg_qualified BOOLEAN NOT NULL DEFAULT(FALSE),
    counter INTEGER NOT NULL DEFAULT(0),
    balance INTEGER NOT NULL DEFAULT(0),
    rib CHAR(27),
    CONSTRAINT pilot_pk PRIMARY KEY (pilot_id),
    CONSTRAINT valid_birth_date CHECK (birth_date <= CURRENT_DATE),
    CONSTRAINT valid_postal_code CHECK (postal_code ~ '^[0-9 ]*$'),
    CONSTRAINT valid_contribution_date CHECK (contribution_date <= CURRENT_DATE),
    CONSTRAINT valid_medical_check_date CHECK (medical_check_date <= CURRENT_DATE)
);

-- Create a new table called 'site_sessions'
CREATE table site_sessions(
    uid CHAR(13) UNIQUE NOT NULL,
    expiration_time TIMESTAMP NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT user_id_fk FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- DML TEST
INSERT INTO 
    users(email, phone, last_name, first_name, password)
VALUES
    ('dev.benjaminpaumard@gmail.com', '0102030405', 'Pmd', 'Benjamin', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');

/**
 * Insertions of some data for tests purposes
 *
 * @author Benjamin PAUMARD, Eva FLEUTRY, Xuming MA
 * @version 1.0.0
 * @since 22/10/2022
 */

-- Users
INSERT INTO 
    users(email, phone, last_name, first_name, password)
VALUES
    ('dev.benjaminpaumard@gmail.com', '0102030405', 'Pmd', 'Benjamin', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');

INSERT INTO 
    users(email, phone, last_name, first_name, password)
VALUES
    ('contact@benjaminpmd.fr', '0102030405', 'Test', 'Benjamin', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');

-- Pilots
INSERT INTO 
    pilots(pilot_id, birth_date, address, city, postal_code, contribution_date, medical_check_date)
VALUES
    (1, '01-01-2001', '01 rue de la paix', 'Pontoise', '95000', '01-10-2021', '12-09-2021');

INSERT INTO 
    pilots(pilot_id, birth_date, address, city, postal_code, contribution_date, medical_check_date, counter)
VALUES
    (2, '01-01-2000', '05 rue Louis Blériot', 'Pontoise', '95100', '11-12-2021', '05-09-2020', );

-- Student
INSERT INTO 
    students(student_id, aircraft_type)
VALUES
    (1, 'F150');


-- Instructor
INSERT INTO 
    instructors(fi_id, fi_code)
VALUES
    (2, 'PMD');

-- Aircraft
INSERT INTO
    aircrafts(registration, aircraft_type, max_pax, range, flight_potential, night_qualified, nav_certificate_date, counter, price)
VALUES
    ('F-BXNX', 'F150', 1, 563, 50, TRUE, '12-31-2023', 10000.0, 141.50);

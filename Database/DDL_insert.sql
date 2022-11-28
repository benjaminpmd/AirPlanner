--
-- Data for Name: aircrafts; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO aircrafts VALUES ('F-HFPI', 'C182', 3, 1500, 26.32, '2024-01-01', true, true, 5647.2, true, false, 350, 10);
INSERT INTO aircrafts VALUES ('F-GCQA', 'F172RG', 3, 1000, 47, '2022-12-31', false, true, 9500, false, true, 190, 7);
INSERT INTO aircrafts VALUES ('F-BSQD', 'DR300', 3, 800, 50, '2023-08-10', false, false, 8743.18, false, false, 135, 2);
INSERT INTO aircrafts VALUES ('F-BXNX', 'F150', 1, 565, 50, '2023-10-30', false, true, 12846.7, false, false, 141, 1);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO users VALUES (13, '0654872132', 'a.mike@yahoo.com', 'Abih', 'Mike', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (14, '0741892251', 'z.jake@orange.fr', 'Zini', 'Jake', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (15, '0678894152', 's.pierrot@mail.com', 'Scheinman', 'Pierrot', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (16, '063216549 ', 'k.pat@gamil.com', 'Kreul', 'Patrick', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (17, '075894858 ', 's.chris@boitemail.com', 'Sanchez', 'Chris', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (18, '078948152 ', 'a.marianne@yahoo.fr', 'Achim', 'Marianne', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (19, '07845120  ', 'a.sylvie@mail.com', 'Abrego', 'Sylvie', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (20, '0645231548', 'r.armand@gmail.com', 'Armand', 'Rébecca', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (21, '0685416352', 'a.herbert@gmail.com', 'Hebert', 'Aimée', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (22, '0789475236', 'm.ribeiro@orange.fr', 'Ribeiro', 'Marielle', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (23, '078475956 ', 'h.savary@yahoo.fr', 'Savary', 'Hilaire', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (24, '0666225578', 'm.compt@yahoo.fr', 'Michel', 'Compt', '$2y$10$AAIw6fM/dIOk0KJujGIRZOckbWe.Pyqb5zsQQcPyyleUBjHmTLTYm');
INSERT INTO users VALUES (28, '0666666666', 'c.mareshall@mail.fr', 'Mareshall', 'Cédric', '$2y$10$jv7wfDF3Isuf9QDWUKShhemwiB3zanz6vrw3Q9b3GUc4h5QiNcuLG');
INSERT INTO users VALUES (29, '0777777777', 'dimafoippeprou-8771@yopmail.com', 'Mareshall', 'Cédric', '$2y$10$SExoEoCWIV/Ckit7brpQK.Qg330./bcS4qKmwKv3.Ms3mUzcC8lCG');
INSERT INTO users VALUES (27, '0102030405', 'contact.benjaminpaumard@gmail.com', 'PAUMARD', 'Benjamin', '$2y$10$OH/UyuMfQRNqHzLY1AuleOlp0EwrexfgLacDYYmqbpNV/IlVK62ka');
INSERT INTO users VALUES (31, '0651545758', 'eva.fleutry@gmail.com', 'DelaNoche', 'Pedrovitch', '$2y$10$sG1.nuJLR55tbHGJRKSGS.bYy7ioG2pMe2NLQdtZb80UHkjQraa4a');
INSERT INTO users VALUES (37, '0999999999', 'av.volquardsen@gmail.com', 'ARAB', 'Michel', '$2y$10$BywM1WVW9NXEL4/gZJSkpO91NyjA68SRT4HlEi3cDM5Fwb6HsXgyq');
INSERT INTO users VALUES (38, '0123456789', 'av.volquara@gmail.com', 'PADEDIPLOM', 'Jay', '$2y$10$wwDMjfkb9y0hwCpKl0gpjO36s/vcT0Lwmcpdiu/UspfT0nBR5bryC');


--
-- Data for Name: pilots; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO pilots VALUES (14, '1928-09-13', '56 rue des Chamois', 'Pottier', '92905  ', '2015-06-12', '2022-02-02', true, true, true, true, 1950, 20, 'FR7630004000031234567890143');
INSERT INTO pilots VALUES (15, '1994-02-24', '3 place Ferreira', 'Bilzen', '72674  ', '2017-12-12', '2022-02-03', true, true, true, true, 740, 70, 'FR7630004000031234567890175');
INSERT INTO pilots VALUES (16, '2004-02-04', '2 boulevard de Leger', 'Bilzen', '72674  ', '2017-06-06', '2022-03-04', true, false, false, true, 1000, 50, 'FR7630004000031234567890573');
INSERT INTO pilots VALUES (17, '1945-10-17', '93 rue Morvan', 'Chevallier', '95178  ', '2019-06-06', '2021-05-09', true, true, true, true, 2100, 10, 'FR7630004000031234567890314');
INSERT INTO pilots VALUES (18, '1983-10-30', '6 rue de Royer', 'LeRivier', '57270  ', '2018-01-11', '2022-10-10', true, true, false, false, 640, 25, 'FR7630004000031234567890679');
INSERT INTO pilots VALUES (19, '2001-08-10', '53 rue Aubert', 'Alves-sur-Mer', '3512   ', '2017-11-09', '2022-02-03', true, false, false, false, 450, 150, 'FR7630006000011234567890189');
INSERT INTO pilots VALUES (20, '1973-12-15', '13 impasse Edith Clerc', 'Faure-sur-Clerc', '84920  ', '2022-02-11', '2022-06-03', false, false, false, false, 3, 300, 'FR7630006000011234567890456');
INSERT INTO pilots VALUES (21, '1980-06-22', '3 rue Valérie Dias', 'Lemaitre', '57060  ', '2015-03-02', '2021-07-07', true, false, false, true, 1790, 225, 'FR7630006000011234567890123');
INSERT INTO pilots VALUES (22, '1987-12-08', '37 impasse de Loiseau', 'Hubert-la-Forêt', '79520  ', '2011-01-06', '2022-02-10', true, true, true, true, 3010, 150, 'FR7630006000011234567890741');
INSERT INTO pilots VALUES (23, '1949-07-28', '9 rue de Delmas', 'Leroux', '92483  ', '2011-06-07', '2022-06-03', true, true, true, true, 1570, 55, 'FR7630006000011234567890963');
INSERT INTO pilots VALUES (27, '2001-03-04', '1 rue de la paix', 'Pontoise', '95000  ', NULL, NULL, false, false, false, false, 0, 0, NULL);
INSERT INTO pilots VALUES (28, '2000-11-24', '09 Rue Maraichais', 'Seine saint denis', '93500  ', NULL, NULL, false, false, false, false, 0, 0, NULL);
INSERT INTO pilots VALUES (29, '2000-08-02', '09 Rue Maraichais', 'Seine saint denis', '093100 ', NULL, NULL, false, false, false, false, 0, 0, NULL);
INSERT INTO pilots VALUES (13, '1984-06-24', '34, rue de Leroy', 'Hubert-la-Forêt', '79520  ', '2017-12-06', '2020-03-23', true, true, true, true, 2609, -1033, NULL);
INSERT INTO pilots VALUES (31, '2002-03-11', '9 rue des mushrooms', 'Cormeilles', '95119  ', NULL, NULL, true, true, true, true, 0, 0, NULL);
INSERT INTO pilots VALUES (37, '2001-11-09', 'Al Kaida', 'IRAQ', '99999  ', '2022-03-23', '2022-03-23', false, false, false, false, 0, 0, NULL);
INSERT INTO pilots VALUES (38, '2022-11-26', 'dtc lol', 'Moncuq', '23233  ', NULL, NULL, false, false, false, false, 0, 0, NULL);


--
-- Data for Name: flights; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO flights VALUES (2, 13, 'F-HFPI', '2022-11-22', '16:15:00', '18:15:00', NULL);
INSERT INTO flights VALUES (3, 15, 'F-BSQD', '2022-12-02', '15:15:00', '16:15:00', NULL);
INSERT INTO flights VALUES (4, 20, 'F-BXNX', '2022-11-23', '14:20:00', '16:00:00', NULL);
INSERT INTO flights VALUES (5, 21, 'F-BXNX', '2022-12-12', '11:10:00', '13:00:00', NULL);
INSERT INTO flights VALUES (6, 13, 'F-HFPI', '2022-11-14', '12:00:00', '14:20:00', NULL);
INSERT INTO flights VALUES (7, 14, 'F-HFPI', '2022-11-13', '12:00:00', '14:20:00', NULL);
INSERT INTO flights VALUES (8, 13, 'F-BXNX', '2022-11-21', '10:00:00', '15:00:00', NULL);
INSERT INTO flights VALUES (9, 13, 'F-BXNX', '2022-11-27', '11:00:00', '23:50:00', false);
INSERT INTO flights VALUES (18, 37, 'F-HFPI', '2023-09-11', '10:00:00', '11:00:00', NULL);
INSERT INTO flights VALUES (14, 13, 'F-BXNX', '2022-11-28', '10:00:00', '22:10:00', true);


--
-- Data for Name: flight_records; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO flight_records VALUES (9, 'lfpt', 12846.2, 'lfop', 12846.7, 2, 0.5, NULL, NULL);


--
-- Data for Name: instructors; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO instructors VALUES (13, 'ABH');
INSERT INTO instructors VALUES (22, 'REO');
INSERT INTO instructors VALUES (31, 'ABC');


--
-- Data for Name: lessons; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO lessons VALUES (2, 13, 'entrainement atterrissage');
INSERT INTO lessons VALUES (5, 22, 'interruption volontaire du vol');
INSERT INTO lessons VALUES (18, 31, 'test');


--
-- Data for Name: mechanics; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO mechanics VALUES (24, 121290);


--
-- Data for Name: operations; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO operations VALUES (24, 'F-HFPI', '2022-11-26', 'changement moteur', 1);
INSERT INTO operations VALUES (24, 'F-BSQD', '2022-11-27', 'entretien périodique', 2);
INSERT INTO operations VALUES (24, 'F-BSQD', '2022-11-27', 'entretien périodique', 3);


--
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: airplanner_access
--

INSERT INTO students VALUES (14, 'DR400');
INSERT INTO students VALUES (17, 'F150');
INSERT INTO students VALUES (23, 'PA20');

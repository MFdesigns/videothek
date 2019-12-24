USE video_library;

-- Video test data
INSERT INTO TVideos VALUES (1035, 'Star Wars: The Rise of Skywalker', 141, 'Science-Fiction', '2019', 12, 5.00, 25.00, 3);

-- Places test data
INSERT INTO TPlaces VALUES (4805, 'Frauenfeld');
INSERT INTO TPlaces VALUES (4387, 'Zürich');
INSERT INTO TPlaces VALUES (4610, 'Basadingen');
INSERT INTO TPlaces VALUES (5408, 'Stehrenberg');

-- Customer test data
INSERT INTO TCustomers VALUES (234, 'Frau', 'Brigitte ', 'Baum', '1994-12-23', '0785576359', 'Bergrain', '36', 4805);
INSERT INTO TCustomers VALUES (946, 'Herr', 'Jonas', 'Mahler', '1989-07-23', '0794206278', 'Seefeldstrasse', '21', 4805);
INSERT INTO TCustomers VALUES (367, 'Herr', 'Erik', 'Frankfurter', '1995-02-23', '0787903103', 'Quadra', '2', 4387);
INSERT INTO TCustomers VALUES (735, 'Herr', 'Jürgen ', 'Krüger', '2000-06-23', '0524206278', 'Schulstrasse', '67', 4387);
INSERT INTO TCustomers VALUES (564, 'Frau', 'Sabine ', 'Sanger', '1969-01-23', '0795853823', 'Lichtmattstrasse', '58', 4805);
INSERT INTO TCustomers VALUES (935, 'Herr', 'René', 'Klug', '1985-04-23', '0782396376', 'Via Franscini', '128', 4387);
INSERT INTO TCustomers VALUES (958, 'Frau', 'Yvonne ', 'Klug', '1989-03-23', '0787903103', 'Brunnenstrasse', '87', 4805);
INSERT INTO TCustomers VALUES (467, 'Herr', 'Dario ', 'Romandini', '2003-03-21', '0754218724', 'Steig', '4a', 4610);
INSERT INTO TCustomers VALUES (397, 'Herr', 'Michel', 'Fäh', '1989-02-23', '0789669996', 'Zürcherstrasse', '16', 4805);
INSERT INTO TCustomers VALUES (125, 'Herr', 'Julian', 'Vogt', '2002-08-20', '0774036733', 'Niederhof', '13', 5408);
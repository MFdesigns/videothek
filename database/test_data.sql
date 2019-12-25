USE video_library;

-- Video test data
INSERT INTO TVideos VALUES
  (1035, 'Star Wars: The Rise of Skywalker', '02:21:00', 'Science-Fiction', '2019', 12, 5.00, 25.00, 3),
  (750, 'Titanic', '03:15:00', 'Romance', '1997', 12, 3.50, 18.00, 7),
  (1001, 'Godzilla: King of the Monsters', '02:12:00', 'Science-Fiction', '2019', 12, 5.00, 25.00, 5),
  (1037, 'Cats', '01:42:00', 'Musical', '2019', 6, 5.00, 25.00, 2),
  (988, 'Tron: Legacy', '02:05:00', 'Science-Fiction', '2010', 12, 4.00, 20.00, 3),
  (991, 'Invictus', '02:14:00', 'Drama', '2009', 12, 3.50, 18.00, 4),
  (1013, 'Harry Potter and the Goblet of Fire', '02:37:00', 'Fantasy', '2005', 12, 4.00, 20.00, 9),
  (1025, 'Ghostbusters', '01:45:00', 'Science-Fiction', '2016', 12, 5.00, 25.00, 2),
  (1009, 'Cars', '01:56:00', 'Comedy', '2006', 0, 3.50, 18.00, 11),
  (1017, 'John Wick: Chapter 2', '02:02:00', 'Action', '2017', 18, 5.00, 25.00, 6);

-- Places test data
INSERT INTO TPlaces VALUES
  (4805, 'Frauenfeld'),
  (4387, 'Zürich'),
  (4610, 'Basadingen'),
  (5408, 'Stehrenberg');

-- Customer test data
INSERT INTO TCustomers VALUES
  (234, 'Frau', 'Brigitte', 'Baum', '1994-12-23', '0785576359', 'Bergrain', '36', 4805),
  (946, 'Herr', 'Jonas', 'Mahler', '1989-07-23', '0794206278', 'Seefeldstrasse', '21', 4805),
  (367, 'Herr', 'Erik', 'Frankfurter', '1995-02-23', '0787903103', 'Quadra', '2', 4387),
  (735, 'Herr', 'Jürgen', 'Krüger', '2000-06-23', '0524206278', 'Schulstrasse', '67', 4387),
  (564, 'Frau', 'Sabine', 'Sanger', '1969-01-23', '0795853823', 'Lichtmattstrasse', '58', 4805),
  (935, 'Herr', 'René', 'Klug', '1985-04-23', '0782396376', 'Via Franscini', '128', 4387),
  (958, 'Frau', 'Yvonne', 'Klug', '1989-03-23', '0787903103', 'Brunnenstrasse', '87', 4805),
  (467, 'Herr', 'Dario', 'Romandini', '2003-03-21', '0754218724', 'Steig', '4a', 4610),
  (397, 'Herr', 'Michel', 'Fäh', '1989-02-23', '0789669996', 'Zürcherstrasse', '16', 4805),
  (125, 'Herr', 'Julian', 'Vogt', '2002-08-20', '0774036733', 'Niederhof', '13', 5408);

-- Lending test data
INSERT INTO TLendings VALUES
  (112, '2019-12-26', '2019-12-28', 1035, 397),
  (115, '2019-12-13', '2020-01-13', 1013, 234),
  (140, '2019-11-27', '2019-12-26', 1009, 234),
  (127, '2019-12-28', '2019-12-29', 1009, 125),
  (122, '2019-12-20', '2019-12-20', 1017, 935),
  (119, '2019-10-12', '2019-10-17', 988, 564),
  (130, '2019-12-15', '2019-12-17', 991,958),
  (133, '2019-11-28', '2019-12-04', 1001, 467),
  (139, '2019-12-02', '2019-12-12', 750, 735),
  (142, '2019-12-18', '2019-12-19', 1017, 564);

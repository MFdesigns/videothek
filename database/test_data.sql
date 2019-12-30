USE video_library;

-- Video test data
INSERT INTO TVideos (VidTitle, VidDuration, VidCategory, VidYear, VidAgeRating, VidPricePerDay, VidPrice, VidInventory, VidDeleted) VALUES
  ('Star Wars: The Rise of Skywalker', '02:21:00', 'Science-Fiction', '2019', 12, 5.00, 25.00, 3, false),
  ('Titanic', '03:15:00', 'Romance', '1997', 12, 3.50, 18.00, 7, false),
  ('Godzilla: King of the Monsters', '02:12:00', 'Science-Fiction', '2019', 12, 5.00, 25.00, 5, false),
  ('Cats', '01:42:00', 'Musical', '2019', 6, 5.00, 25.00, 2, false),
  ('Tron: Legacy', '02:05:00', 'Science-Fiction', '2010', 12, 4.00, 20.00, 3, false),
  ('Invictus', '02:14:00', 'Drama', '2009', 12, 3.50, 18.00, 4, false),
  ('Harry Potter and the Goblet of Fire', '02:37:00', 'Fantasy', '2005', 12, 4.00, 20.00, 9, false),
  ('Ghostbusters', '01:45:00', 'Science-Fiction', '2016', 12, 5.00, 25.00, 2, false),
  ('Cars', '01:56:00', 'Comedy', '2006', 0, 3.50, 18.00, 11, false),
  ('John Wick: Chapter 2', '02:02:00', 'Action', '2017', 18, 5.00, 25.00, 6, false);

-- Places test data
INSERT INTO TPlaces VALUES
  (4805, 'Frauenfeld', false),
  (4387, 'Zürich', false),
  (4610, 'Basadingen', false),
  (5408, 'Stehrenberg', false);

-- Customer test data
INSERT INTO TCustomers (CustTitle, CustName, CustSurname, CustBirthday, CustPhoneNumber, CustStreet, CustStreetNumber, CustDeleted, PlaceONRP) VALUES
  ('Frau', 'Brigitte', 'Baum', '1994-12-23', '0785576359', 'Bergrain', '36', false, 4805),
  ('Herr', 'Jonas', 'Mahler', '1989-07-23', '0794206278', 'Seefeldstrasse', '21', false, 4805),
  ('Herr', 'Erik', 'Frankfurter', '1995-02-23', '0787903103', 'Quadra', '2', false, 4387),
  ('Herr', 'Jürgen', 'Krüger', '2000-06-23', '0524206278', 'Schulstrasse', '67', false, 4387),
  ('Frau', 'Sabine', 'Sanger', '1969-01-23', '0795853823', 'Lichtmattstrasse', '58', false, 4805),
  ('Herr', 'René', 'Klug', '1985-04-23', '0782396376', 'Via Franscini', '128', false, 4387),
  ('Frau', 'Yvonne', 'Klug', '1989-03-23', '0787903103', 'Brunnenstrasse', '87', false, 4805),
  ('Herr', 'Dario', 'Romandini', '2003-03-21', '0754218724', 'Steig', '4a', false, 4610),
  ('Herr', 'Michel', 'Fäh', '1989-02-23', '0789669996', 'Zürcherstrasse', '16', false, 4805),
  ('Herr', 'Julian', 'Vogt', '2002-08-20', '0774036733', 'Niederhof', '13', false, 5408);

-- Lending test data
INSERT INTO TLendings (LendFrom, LendUntil, LendDeleted, VidNumber, CustId) VALUES
  ('2019-12-26', '2019-12-28', false, 1, 1),
  ('2019-12-13', '2020-01-13', false, 9, 10),
  ('2019-11-27', '2019-12-26', false, 9, 2),
  ('2019-12-28', '2019-12-29', false, 2, 7),
  ('2019-12-20', '2019-12-20', false, 5, 1),
  ('2019-10-12', '2019-10-17', false, 4, 6),
  ('2019-12-15', '2019-12-17', false, 2, 4),
  ('2019-11-28', '2019-12-04', false, 3, 5),
  ('2019-12-02', '2019-12-12', false, 5, 1),
  ('2019-12-18', '2019-12-19', false, 6, 2);

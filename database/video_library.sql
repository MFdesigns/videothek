DROP DATABASE IF EXISTS video_library;
CREATE DATABASE video_library CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE video_library;

CREATE USER IF NOT EXISTS 'VidLibUser' IDENTIFIED BY 'oV1OB%7d';
GRANT SELECT, INSERT, UPDATE ON *.* TO 'VidLibUser';

CREATE TABLE TVideos (
    VidNumber INT UNSIGNED NOT NULL AUTO_INCREMENT,
    VidTitle VARCHAR(64),
    VidDuration TIME,
    VidCategory VARCHAR(64),
    VidYear YEAR,
    VidAgeRating INT,
    VidPricePerDay FLOAT,
    VidPrice FLOAT,
    VidInventory INT,
    VidDeleted BOOL DEFAULT false,
    PRIMARY KEY (VidNumber)
);

CREATE TABLE TPlaces (
    PlaceONRP INT UNSIGNED NOT NULL,
    PlacePLZ VARCHAR(4),
    PlaceCity VARCHAR(64),
    PlaceDeleted BOOL DEFAULT false,
    PRIMARY KEY (PlaceONRP)
);

CREATE TABLE TCustomers (
    CustId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    CustTitle ENUM('Herr', 'Frau'),
    CustName VARCHAR(128),
    CustSurname VARCHAR(128),
    CustBirthday DATE,
    CustPhoneNumber VARCHAR(10),
    CustStreet VARCHAR(128),
    CustStreetNumber VARCHAR(16),
    CustDeleted BOOL DEFAULT false,
    PlaceONRP INT,
    PRIMARY KEY (CustId)
);

CREATE TABLE TLendings (
    LendId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    LendFrom DATE,
    LendUntil DATE,
    LendDeleted BOOL,
    VidNumber INT DEFAULT false,
    CustId INT,
    PRIMARY KEY (LendId)
);

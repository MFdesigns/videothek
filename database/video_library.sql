DROP DATABASE IF EXISTS video_library;
CREATE DATABASE video_library;

USE video_library;

CREATE USER IF NOT EXISTS 'VidLibUser' IDENTIFIED BY 'oV1OB%7d';
GRANT SELECT, INSERT, UPDATE ON *.* TO 'VidLibUser';

CREATE TABLE TVideos (
    VidNumber INT NOT NULL AUTO_INCREMENT,
    VidTitle VARCHAR(64),
    VidDuration TIME,
    VidCategory VARCHAR(64),
    VidYear YEAR,
    VidAgeRating INT,
    VidPricePerDay FLOAT,
    VidPrice FLOAT,
    VidInventory INT,
    VidDeleted BOOL,
    PRIMARY KEY (VidNumber)
);

CREATE TABLE TPlaces (
    PlaceONRP INT NOT NULL,
    PlaceCity VARCHAR(64),
    PlaceDeleted BOOL,
    PRIMARY KEY (PlaceONRP)
);

CREATE TABLE TCustomers (
    CustId INT NOT NULL AUTO_INCREMENT,
    CustTitle ENUM('Herr', 'Frau'),
    CustName VARCHAR(128),
    CustSurname VARCHAR(128),
    CustBirthday DATE,
    CustPhoneNumber INT,
    CustStreet VARCHAR(128),
    CustStreetNumber VARCHAR(16),
    CustDeleted BOOL,
    PlaceONRP INT,
    PRIMARY KEY (CustId)
);

CREATE TABLE TLendings (
    LendId INT NOT NULL AUTO_INCREMENT,
    LendFrom DATE,
    LendUntil DATE,
    LendDeleted BOOL,
    VidNumber INT,
    CustId INT,
    PRIMARY KEY (LendId)
);

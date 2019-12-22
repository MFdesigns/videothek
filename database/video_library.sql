DROP DATABASE IF EXISTS video_library;
CREATE DATABASE video_library;

USE video_library;

CREATE TABLE TVideos (
    VidNumber INT NOT NULL,
    VidTitle VARCHAR(64),
    VidDuration TIME,
    VidCategory VARCHAR(64),
    VidYear DATE,
    VidAgeRating INT,
    VidPricePerDay FLOAT,
    VidPrice FLOAT,
    VidInventory INT,
    PRIMARY KEY (VidNumber)
);

CREATE TABLE TPlaces (
    PlaceONRP INT NOT NULL,
    PlaceStreet INT,
    PlaceNumber INT,
    PlaceCity VARCHAR(64),
    PRIMARY KEY (PlaceONRP)
);

CREATE TABLE TCustomers (
    CustId INT NOT NULL,
    CustTitle ENUM('Herr', 'Frau'),
    CustName VARCHAR(128),
    CustSurname VARCHAR(128),
    CustBirthday DATE,
    CustPhoneNumber INT,
    PlaceONRP INT,
    PRIMARY KEY (CustId)
);

CREATE TABLE TLendings (
    LendId INT NOT NULL,
    LendFrom DATE,
    LendUntill DATE,
    VidNumber INT,
    CustId INT,
    PRIMARY KEY (LendId)
);

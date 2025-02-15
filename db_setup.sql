-- Create the database
CREATE DATABASE webcal;
USE webcal;

-- Create Table USR_DT
CREATE TABLE USR_DT (
    id INT PRIMARY KEY,
    name VARCHAR(30),
    surname VARCHAR(30),
    phone INT,
    email VARCHAR(30),
    birthdate DATE,
    adresse VARCHAR(50),
    lidata INT,
    FOREIGN KEY (lidata) REFERENCES LI_DT(id)
);

-- Create Table LI_DT
CREATE TABLE LI_DT (
    id INT PRIMARY KEY,
    salt CHAR(8),
    pwh CHAR(60),
    sub BOOLEAN DEFAULT FALSE,
    udid INT,
    FOREIGN KEY (udid) REFERENCES USR_DT(id)
);

-- Create Table AR_DT
CREATE TABLE AR_DT (
    id INT PRIMARY KEY,
    src INT,
    dst INT,
    beg_date DATE,
    beg_time TIME,
    end_date DATE,
    end_time TIME,
    used BOOLEAN DEFAULT FALSE,
    info_id INT,
    FOREIGN KEY (src) REFERENCES USR_DT(id),
    FOREIGN KEY (dst) REFERENCES USR_DT(id),
    FOREIGN KEY (info_id) REFERENCES INFO_DT(id)
);

-- Create Table INFO_DT
CREATE TABLE INFO_DT (
    id INT PRIMARY KEY,
    ar_id INT,
    msg VARCHAR(200),
    FOREIGN KEY (ar_id) REFERENCES AR_DT(id)
);

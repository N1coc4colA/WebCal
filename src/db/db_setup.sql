USE webcal;

-- Create Table USR_DT
CREATE TABLE IF NOT EXISTS USR_DT (
    id INT PRIMARY KEY AUTO_INCREMENT,          -- UID
    name VARCHAR(30),                           -- Name (family) of the person
    surname VARCHAR(30),                        -- Surname (usual name) of the person
    phone INT,                                  -- Phone number of the person
    email VARCHAR(30),                          -- Mail of the person
    birthdate DATE,                             -- Birthdate of the person
    address VARCHAR(50),                        -- Address of the person
    pwh CHAR(255),                              -- Hashed password
    sub BOOLEAN DEFAULT FALSE                   -- Submitted, means that the user validated his mail.
);

-- Create Table AR_DT, Available Ranges for a rendez-vous
CREATE TABLE IF NOT EXISTS AR_DT (
    id INT PRIMARY KEY AUTO_INCREMENT,              -- UID
    src INT DEFAULT 0,                              -- UID of the people who owns the range
    beg_date DATE,                                  -- Start date of the range (includes)
    beg_time TIME,                                  -- Start time of the range (includes)
    end_date DATE,                                  -- Start date of the range (includes)
    end_time TIME,                                  -- Start time of the range (includes)
    msg VARCHAR(200),                               -- Message, note, of the people who made the reservation
    FOREIGN KEY (src) REFERENCES USR_DT(id)         --
);

-- Create Table PENDING_DT, holding pending registrations
CREATE TABLE IF NOT EXISTS PENDING_DT (
    id INT PRIMARY KEY AUTO_INCREMENT,          -- UID
    sub_date DATE,                              -- submission date of the mail validation request
    sub_time TIME,                              -- Start time of the mail validation request
    validator VARCHAR(30),                      -- Random string used to validate the account (and avoid others validating others' accounts)
    src INT,                                    -- UID of the person who requested mail validation
    FOREIGN KEY (src) REFERENCES USR_DT(id)     --
);

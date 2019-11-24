use findBand;
drop database if exists findBand;
create database if not exists findBand;
use findBand;

CREATE TABLE IF NOT EXISTS cities (
    city_id INT AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY (city_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT,
    city_id INT NULL,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password CHAR(128) NOT NULL,
    salt CHAR(128) NOT NULL,
    date_of_birth DATETIME NULL,
    info TEXT NULL,
    photo LONGBLOB NULL,
    authentication_link CHAR(128),
    change_password_link CHAR(128) DEFAULT NULL,
    change_password_link_expiration_date DATETIME NULL,
    login_attempts_failed INT DEFAULT 0,
    last_login_failed_date DATETIME NULL,
    options TINYINT(4) DEFAULT 0,
    FOREIGN KEY (city_id)
        REFERENCES cities (city_id),
    PRIMARY KEY (user_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS instruments (
    instrument_id INT AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    options TINYINT(4) DEFAULT 0,
    PRIMARY KEY (instrument_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS users_instruments (
    instrument_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
    FOREIGN KEY (instrument_id)
        REFERENCES instruments (instrument_id),
    PRIMARY KEY (instrument_id , user_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS messages (
    message_id INT AUTO_INCREMENT,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    text TEXT NOT NULL,
    timestamp DATETIME NOT NULL,
    PRIMARY KEY (message_id),
    FOREIGN KEY (sender_id)
        REFERENCES users (user_id),
    FOREIGN KEY (receiver_id)
        REFERENCES users (user_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS bands (
    band_id INT AUTO_INCREMENT,
    title VARCHAR(50) NOT NULL,
    description TEXT NULL,
    created_date DATETIME NOT NULL,
    photo LONGBLOB NULL,
    PRIMARY KEY (band_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS posts (
    post_id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    band_id INT NULL,
    timestamp DATETIME NOT NULL,
    text TEXT NOT NULL,
    photo LONGBLOB NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
    FOREIGN KEY (band_id)
        REFERENCES bands (band_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS users_bands (
    band_id INT NOT NULL,
    user_id INT NOT NULL,
    options TINYINT(4) DEFAULT 0,
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
    FOREIGN KEY (band_id)
        REFERENCES bands (band_id),
    PRIMARY KEY (band_id , user_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS notices (
    notice_id INT NOT NULL,
    band_id INT NULL,
    user_id INT NOT NULL,
    instrument_id INT NULL,
    title VARCHAR(50) NOT NULL,
    details TEXT NOT NULL,
    PRIMARY KEY (notice_id),
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
    FOREIGN KEY (band_id)
        REFERENCES bands (band_id),
    FOREIGN KEY (instrument_id)
        REFERENCES instruments (instrument_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS reports (
    report_id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    post_id INT NULL,
    notice_id INT NULL,
    reason TEXT NOT NULL,
    timestamp DATETIME NOT NULL,
    options TINYINT(4) DEFAULT 0,
    PRIMARY KEY (report_id),
    FOREIGN KEY (notice_id)
        REFERENCES notices (notice_id),
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
    FOREIGN KEY (post_id)
        REFERENCES posts (post_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS add_user_to_band_message (
    add_user_to_band_message_id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    band_id INT NOT NULL,
    reason TEXT NOT NULL,
    options TINYINT(4) NOT NULL DEFAULT 0,
    PRIMARY KEY (add_user_to_band_message_id),
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
    FOREIGN KEY (band_id)
        REFERENCES bands (band_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS music_genres (
    music_genre_id INT AUTO_INCREMENT,
    name varchar (50) NOT NULL,
    PRIMARY KEY (music_genre_id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS users_music_genres (
    music_genre_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
    FOREIGN KEY (music_genre_id)
        REFERENCES music_genres (music_genre_id),
    PRIMARY KEY (music_genre_id , user_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS bands_music_genres (
    music_genre_id INT NOT NULL,
    band_id INT NOT NULL,
    FOREIGN KEY (band_id)
        REFERENCES bands (band_id),
    FOREIGN KEY (music_genre_id)
        REFERENCES music_genres (music_genre_id),
    PRIMARY KEY (music_genre_id , band_id)
)  ENGINE=INNODB;
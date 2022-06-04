DROP DATABASE IF EXISTS hw1;
CREATE DATABASE hw1;

USE hw1;

-- Creazione tabelle --
CREATE TABLE USER (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(255) NOT NULL UNIQUE,
    name        VARCHAR(255) NOT NULL,
    surname     VARCHAR(255) NOT NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    time        TIMESTAMP NOT NULL
) ENGINE=InnoDb;

CREATE TABLE FOLLOW (
    follower    INT,
    following   INT,

    PRIMARY KEY(follower, following),
    FOREIGN KEY(follower) REFERENCES USER(id),
    FOREIGN KEY(following) REFERENCES USER(id)
) ENGINE=InnoDb;

CREATE TABLE POST (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user        INT NOT NULL,
    content     VARCHAR(255) NOT NULL,
    type        VARCHAR(255) NOT NULL,
    type_id     VARCHAR(255) NOT NULL,
    time        TIMESTAMP NOT NULL,

    KEY(user, type, type_id),
    FOREIGN KEY(user) REFERENCES USER(id)
) ENGINE=InnoDb;

CREATE TRIGGER remove_in_watchlist
AFTER INSERT
ON POST FOR EACH ROW
DELETE FROM WANTLIST WHERE user = NEW.user
AND type_id = NEW.type_id;

CREATE TABLE USER_PICS (
    user        INT NOT NULL,
    profile_pic LONGBLOB,
    cover_pic   LONGBLOB,

    PRIMARY KEY(user),
    FOREIGN KEY(user) REFERENCES USER(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE LIKES (
    user        INT,
    post        INT,
    time        TIMESTAMP NOT NULL,

    PRIMARY KEY(user, post),
    FOREIGN KEY(user) REFERENCES USER(id),
    FOREIGN KEY(post) REFERENCES POST(id) ON DELETE CASCADE
) ENGINE=InnoDb;

CREATE TABLE WANTLIST (
    user        INT NOT NULL,
    type        VARCHAR(255) NOT NULL,
    type_id     INT NOT NULL,
    time        TIMESTAMP NOT NULL,

    PRIMARY KEY(user, type, type_id),
    FOREIGN KEY(user) REFERENCES USER(id)
) ENGINE=InnoDb;

CREATE DATABASE dealdone    
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
    
USE dealdone;  

CREATE TABLE categories (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT(11),
    name        CHAR(128)
);
    
CREATE TABLE tasks (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11),
    user_id     INT(11),
    dt_add      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status      TINYINT  NOT NULL DEFAULT 0,
    title       CHAR(128),
    path        CHAR(128),
    dt_complet  DATE DEFAULT NULL    
);

CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    dt_add      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email       CHAR(128) NOT NULL UNIQUE,
    name        CHAR(128),
    password    CHAR(255) NOT NULL    
);
    
    



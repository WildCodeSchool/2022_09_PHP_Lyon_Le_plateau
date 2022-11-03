DROP DATABASE IF EXISTS le_plateau;
CREATE DATABASE le_plateau;
USE le_plateau;

CREATE TABLE user (
id INT PRIMARY KEY AUTO_INCREMENT,
firstname VARCHAR(100) NOT NULL,
lastname VARCHAR (100) NOT NULL,
email VARCHAR(100) NOT NULL,
password VARCHAR (100) NOT NULL
);

CREATE TABLE game (
id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
name VARCHAR(100) NOT NULL,
type VARCHAR(100) NOT NULL,
minimum_players_age INT NOT NULL,
image VARCHAR(255) default 'default.jpg',
id_owner INT NOT NULL,
min_number_players INT NOT NULL,
max_number_players INT NOT NULL,
availability bool NOT NULL default true
);


INSERT INTO user (firstname, lastname, email, password)
VALUES 
('Jerome', 'Dannfald', 'j.dannfald@gmail.com', 'jeje+1234'),
('Charlene', 'DaRugna', 'c.darugna@gmail.com', "chacha+1234"),
('Kevin', 'Albespy', 'k.albespy@gmail.com', "keke+1234"),
('Ibrahim', 'Mohamed', 'i.mohammed@gmail.com', "ibrabra+1234"),
('Matthieu', 'Dufloux', 'm.dufloux@gmail.com', "matmat+1234");

INSERT INTO game (name, type, minimum_players_age, id_owner, image, min_number_players, max_number_players)
VALUES
('Monopoly', 'Stratégie', 10, 1,'monopoly.jpg', 2, 6),
('Uno', 'Jeu d\'ambiance', 6, 3, 'uno.png', 2, 8),
('Cluedo', 'Stratégie', 9, 5, 'cluedo.jpg', 2, 6),
('CodeNames', 'Jeu d\'ambiance', 12, 4, 'codenames.jpg', 2, 8);


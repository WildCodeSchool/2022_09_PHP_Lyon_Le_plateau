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
image VARCHAR(255),
id_owner INT NOT NULL,
min_number_players INT NOT NULL,
max_number_players INT NOT NULL
);


INSERT INTO user (firstname, lastname, email, password)
VALUES 
('Jerome', 'Danfald', 'j.danfald@gmail.com', 'jeje'),
('Charlene', 'DaRugna', 'c.darugna@gmail.com', "chacha"),
('Kevin', 'Albespy', 'k.albespy@gmail.com', "keke"),
('Ibrahim', 'Mohamed', 'i.mohammed@gmail.com', "ibrabra"),
('Matthieu', 'Dufloux', 'm.dufloux@gmail.com', "matmat");

INSERT INTO game (name, type, minimum_players_age, id_owner, image, min_number_players, max_number_players)
VALUES
('Monopoly', 'plateau', 10, 1,'https://img.freepik.com/photos-gratuite/beaucoup-maisons-jouets_144627-1474.jpg?size=626&ext=jpg&ga=GA1.2.839792511.1665667296&semt=sph', 2, 6),
('Uno', 'cartes', 6, 3, 'https://img.freepik.com/photos-gratuite/homme-jouant-aux-cartes-autres-personnes-tenant-jeu_1268-17890.jpg?size=626&ext=jpg&ga=GA1.2.839792511.1665667296&semt=sph', 2, 8),
('Belotte', 'cartes', 15, 5, 'https://img.freepik.com/vecteurs-libre/quatre-as-carte-poker-illustration_1017-3850.jpg?size=338&ext=jpg&ga=GA1.2.839792511.1665667296&semt=sph', 4, 4);

DROP DATABASE IF EXISTS le_plateau;
CREATE DATABASE le_plateau;
USE le_plateau;

CREATE TABLE user (
id INT PRIMARY KEY AUTO_INCREMENT,
firstname VARCHAR(100) NOT NULL,
lastname VARCHAR (100) NOT NULL,
email VARCHAR(100) NOT NULL,
password VARCHAR (100) NOT NULL,
admin BOOL NOT NULL DEFAULT false
);

CREATE TABLE game (
id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
name VARCHAR(100) NOT NULL,
type VARCHAR(100) NOT NULL,
min_number_players INT NOT NULL,
max_number_players INT NOT NULL,
minimum_players_age INT NOT NULL,
image VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
id_owner INT NOT NULL,
availability BOOL NOT NULL DEFAULT true
);


INSERT INTO user (firstname, lastname, email, password, admin)
VALUES 
('Jean Michel', 'Admin', 'jeanmicheladmin@gmail.com', '$2y$10$hGerYQtFreGSdPP/cdpHNuvuU8U5gqF6IgaVLDQL3CAK7JgwxrtEu', true),
('Jerome', 'Dannfald', 'j.dannfald@gmail.com', '$2y$10$jWvlhE5tE5vmXzZ3E0R0a.rXEvajAFnF8Fw6XeVYCaTB6nCqqr7xm', false),
('Charlene', 'Da Rugna', 'c.darugna@gmail.com', '$2y$10$STEv7r3mRow93gvK7hcTkuhlQgwfXeZVRXoVs62bu4LiRUWabMZA6', false),
('Kevin', 'Albespy', 'k.albespy@gmail.com', '$2y$10$BzZjamtUMbI/AJVspRVbheERQx8vPp8vcuaBXvQFHkYjBbbUMSq1u', false),
('Ibrahim', 'Mohamed', 'i.mohammed@gmail.com', '$2y$10$h086skRCxEGu/N5S6JXlo.6GQzsRd5gLrV9LLjLb42ElBROnaXLia', false),
('Matthieu', 'Dufloux', 'm.dufloux@gmail.com', '$2y$10$NzekPyv21wpqy.yBjoBo4e7ra5hzVNBaSAOkeSdJqut4rOVvzbsWu',false);

/* MDP EN CLAIR :
admin+1234
jeje+1234
chacha+1234
keke+1234
ibrabra+1234
matmat+1234
*/

INSERT INTO game (name, type, min_number_players, max_number_players, minimum_players_age, image, id_owner, availability)
VALUES
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 5, 0),
('7 wonders', 'Stratégie', 3, 7, 10, '7wonders.jpg', 6, 1),
('Uno', 'Ambiance', 2, 10, 7, 'uno.png', 3, 0),
('Dice forge', 'Stratégie', 2, 4, 10, 'diceforge.jpg', 4, 1),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 2, 0),
('La bonne paye', 'Ambiance', 2, 6, 8, 'LBP.jpg', 5, 1),
('Codenames', 'Réflexion', 2, 8, 12, 'codenames.jpg', 2, 1),
('Codenames', 'Réflexion', 2, 8, 12, 'codenames.jpg', 5, 0),
('7 wonders', 'Stratégie', 3, 7, 10, '7wonders.jpg', 2, 0),
('La bonne paye', 'Ambiance', 2, 6, 8, 'LBP.jpg', 3, 1),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 4, 0),
('Cluedo', 'Réflexion', 3, 5, 9, 'cluedo.jpg', 5, 0),
('Monopoly', 'Ambiance', 2, 6, 8, 'monopoly.jpg', 4, 1),
('Codenames', 'Réflexion', 2, 8, 12, 'codenames.jpg', 6, 1),
('Cluedo', 'Réflexion', 3, 5, 9, 'cluedo.jpg', 2, 1),
('Cluedo', 'Réflexion', 3, 5, 9, 'cluedo.jpg', 3, 1),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 4, 0),
('7 wonders', 'Stratégie', 3, 7, 10, '7wonders.jpg', 4, 0),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 6, 1),
('Les Loups-Garous de Thiercelieux', 'Ambiance', 8, 18, 10, 'loupgarou.jpg', 5, 1),
('Scrabble', 'Réfléxion', 2, 4, 7, 'scrabble.jpg', 4, 1),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 4, 1);


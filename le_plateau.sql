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
    CONSTRAINT fk_game_user    
        FOREIGN KEY (id_owner)             
        REFERENCES user(id),
availability BOOL NOT NULL DEFAULT true
);

CREATE TABLE contact (
id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
firstname VARCHAR(100) NOT NULL,
lastname VARCHAR (100) NOT NULL,
email VARCHAR(100) NOT NULL,
message VARCHAR(255) NOT NULL,
isRead BOOL NOT NULL DEFAULT true
);

CREATE TABLE status (
id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
borrow_status VARCHAR(100) NOT NULL
);

CREATE TABLE borrow (
id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
request_date DATE,
acceptance_date DATE,
id_status INT DEFAULT 1,
    CONSTRAINT fk_borrow_status     
        FOREIGN KEY (id_status)             
        REFERENCES status(id),
id_game INT NOT NULL,
    CONSTRAINT fk_borrow_game     
        FOREIGN KEY (id_game)             
        REFERENCES game(id)
        ON DELETE CASCADE,
id_user INT NOT NULL,
    CONSTRAINT fk_borrow_user     
        FOREIGN KEY (id_user)             
        REFERENCES user(id)
);

INSERT INTO user (firstname, lastname, email, password, admin)
VALUES 
('Jean Michel', 'Admin', 'jeanmicheladmin@gmail.com', '$2y$10$hGerYQtFreGSdPP/cdpHNuvuU8U5gqF6IgaVLDQL3CAK7JgwxrtEu', true),
('Jerome', 'Dannfald', 'j.dannfald@gmail.com', '$2y$10$jWvlhE5tE5vmXzZ3E0R0a.rXEvajAFnF8Fw6XeVYCaTB6nCqqr7xm', false),
('Charlène', 'Da Rugna', 'c.darugna@gmail.com', '$2y$10$STEv7r3mRow93gvK7hcTkuhlQgwfXeZVRXoVs62bu4LiRUWabMZA6', false),
('Kevin', 'Albespy', 'k.albespy@gmail.com', '$2y$10$BzZjamtUMbI/AJVspRVbheERQx8vPp8vcuaBXvQFHkYjBbbUMSq1u', false),
('Ibrahim', 'Mohamed', 'i.mohamed@gmail.com', '$2y$10$h086skRCxEGu/N5S6JXlo.6GQzsRd5gLrV9LLjLb42ElBROnaXLia', false),
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
('7 wonders', 'Stratégie', 3, 7, 10, '7wonders.jpg', 1, 1),
('Uno', 'Ambiance', 2, 10, 7, 'uno.png', 3, 1),
('Dice forge', 'Stratégie', 2, 4, 10, 'diceforge.jpg', 4, 1),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 2, 0),
('La bonne paye', 'Ambiance', 2, 6, 8, 'LBP.jpg', 5, 1),
('Codenames', 'Réflexion', 2, 8, 12, 'codenames.jpg', 2, 1),
('Codenames', 'Réflexion', 2, 8, 12, 'codenames.jpg', 1, 0),
('7 wonders', 'Stratégie', 3, 7, 10, '7wonders.jpg', 2, 1),
('La bonne paye', 'Ambiance', 2, 6, 8, 'LBP.jpg', 3, 1),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 4, 0),
('Cluedo', 'Réflexion', 3, 5, 9, 'cluedo.jpg', 5, 1),
('Monopoly', 'Ambiance', 2, 6, 8, 'monopoly.jpg', 4, 1),
('Codenames', 'Réflexion', 2, 8, 12, 'codenames.jpg', 6, 1),
('Cluedo', 'Réflexion', 3, 5, 9, 'cluedo.jpg', 2, 1),
('Cluedo', 'Réflexion', 3, 5, 9, 'cluedo.jpg', 3, 1),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 5, 1),
('7 wonders', 'Stratégie', 3, 7, 10, '7wonders.jpg', 1, 1),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 6, 1),
('Les Loups-Garous de Thiercelieux', 'Ambiance', 8, 18, 10, 'loupgarou.jpg', 5, 1),
('Scrabble', 'Réfléxion', 2, 4, 7, 'scrabble.jpg', 4, 0),
('Trivial pursuit', 'Culture Générale', 2, 6, 16, 'trivialpursuit.jpg', 1, 1);

INSERT INTO contact (firstname, lastname, email, message)
VALUES
('Pascaline', 'Lacharité', 'p.lacharité@aol.com', 'J\'adore votre site, du design aux fonctionnalités, tout est parfait, merci le plateau !'),
('Seymour', 'Lanteigne', 's.Lanteigne@libertysurf.fr', 'Bonjour l\'équipe du plateau, je trouve votre site très professionnel et j\'aimerais vous recruter immédiatement. Salaire attractif et ticket resto. Recontactez moi au plus vite!'),
('Sophie', 'Plaisance', 's.plaisance@yahoo.com', 'Personnellement, je préfère les jeux vidéo !'),
('Inco', 'gnito', 'i.gnito@hotmail.com', 'Bonjour, je suis un prince Nigérian et j\'ai besoin de votre aide. Je vous propose de me verser 2.000 € en mandat cash afin de me permettre de récupérer ma fortune. Je vous les rendrais au centuple!'),
('Nord', 'VPN', 'nvpn@outlook.fr', 'Vous connaissez Nord VPN ? Pour seulement 3€ par mois les 3 premiers mois, NordVPN vous permettra de naviguer sur le web de manière sécurisé et invisible. N\'attendez plus et essayez NordVPN dès maintenant ;)');

INSERT INTO status (borrow_status)
VALUES ('En attente'),('Accepté'),('Refusé'),('Terminé');

INSERT INTO borrow (id_game, id_user, id_status, request_date, acceptance_date)
VALUES (6,2,1,'2022-11-15', NULL),(9,4,3,'2022-11-05', '2022-11-07'),(10,6,1,'2022-11-10', NULL),
(20,2,2,'2022-10-25','2022-10-25'),(14,2,3, '2022-09-30','2022-10-10'),(1,1,2, '2022-11-01','2022-11-05'),
(2,4,3, '2022-10-31','2022-11-15'),(3,1,1,'2022-11-15', NULL),(15,1,4,'2022-08-15','2022-08-15'),
(8,2,4,'2022-06-15','2022-06-15'), (17,4,1,'2022-11-16', NULL),(21,5,2,'2022-11-16','2022-11-17'),
(12,3,4,'2022-06-15','2022-06-15');

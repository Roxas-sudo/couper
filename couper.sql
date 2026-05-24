CREATE DATABASE IF NOT EXISTS couper;
USE couper;

CREATE TABLE utilisateurs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role        ENUM('user', 'admin') DEFAULT 'user',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE films (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    titre       VARCHAR(150) NOT NULL,
    description TEXT,
    annee       YEAR,
    genre       VARCHAR(50),
    image       VARCHAR(255),
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notes (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_film        INT NOT NULL,
    note           TINYINT NOT NULL CHECK (note BETWEEN 1 AND 5),
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_note (id_utilisateur, id_film),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_film)        REFERENCES films(id)        ON DELETE CASCADE
);

CREATE TABLE commentaires (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_film        INT NOT NULL,
    contenu        TEXT NOT NULL,
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_film)        REFERENCES films(id)        ON DELETE CASCADE
);

-- Admin
INSERT INTO utilisateurs (username, email, mot_de_passe, role) VALUES
('admin', 'admin@cinemasite.fr', SHA2('Admin123!', 256), 'admin');

-- 10 utilisateurs
INSERT INTO utilisateurs (username, email, mot_de_passe, role) VALUES
('alice',   'alice@mail.fr',   SHA2('Pass123!', 256), 'user'),
('bob',     'bob@mail.fr',     SHA2('Pass123!', 256), 'user'),
('charlie', 'charlie@mail.fr', SHA2('Pass123!', 256), 'user'),
('diana',   'diana@mail.fr',   SHA2('Pass123!', 256), 'user'),
('ethan',   'ethan@mail.fr',   SHA2('Pass123!', 256), 'user'),
('fanny',   'fanny@mail.fr',   SHA2('Pass123!', 256), 'user'),
('gabriel', 'gabriel@mail.fr', SHA2('Pass123!', 256), 'user'),
('hugo',    'hugo@mail.fr',    SHA2('Pass123!', 256), 'user'),
('inès',   'ines@mail.fr',    SHA2('Pass123!', 256), 'user'),
('julian',  'julian@mail.fr',  SHA2('Pass123!', 256), 'user');
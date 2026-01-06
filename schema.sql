-- Schema for StarCin database

CREATE DATABASE IF NOT EXISTS cinema;
USE cinema;

-- Users table
CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Films table
CREATE TABLE film (
    id_film INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    synopsis TEXT,
    duree INT,
    bandeannonce VARCHAR(255),
    datediffusion DATE,
    categorie VARCHAR(100),
    annee INT
);

-- Ratings table
CREATE TABLE note (
    id_note INT AUTO_INCREMENT PRIMARY KEY,
    id_film INT NOT NULL,
    id_utilisateur INT NOT NULL,
    valeur DECIMAL(2,1) NOT NULL,
    FOREIGN KEY (id_film) REFERENCES film(id_film),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    UNIQUE KEY unique_rating (id_film, id_utilisateur)
);

-- Administrators table (linking users to admins)
CREATE TABLE administrateur (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

-- Film propositions
CREATE TABLE proposition_film (
    id_propositionFilm INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    synopsis TEXT,
    statut ENUM('en_attente', 'acceptee', 'refusee') DEFAULT 'en_attente',
    id_admin INT,
    FOREIGN KEY (id_admin) REFERENCES administrateur(id_admin)
);

-- Forums
CREATE TABLE forum (
    id_forum INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL
);

-- Messages in forums
CREATE TABLE message (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    id_utilisateur INT NOT NULL,
    id_forum INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (id_forum) REFERENCES forum(id_forum)
);

-- Insert sample data
INSERT INTO utilisateur (email, mot_de_passe, role) VALUES
('admin@starcin.com', '$2y$10$examplehashedpassword', 'admin'),
('user@example.com', '$2y$10$examplehashedpassword', 'user');

INSERT INTO film (titre, synopsis, categorie, annee) VALUES
('Inception', 'A mind-bending thriller', 'Science-Fiction', 2010),
('Interstellar', 'A space adventure', 'Science-Fiction', 2014);

INSERT INTO forum (titre) VALUES ('General Discussion');
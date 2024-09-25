-- Create table for accounts (compte)
CREATE TABLE compte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Create table for publications (publication)
CREATE TABLE publication (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_compte INT NOT NULL,
    contenu TEXT NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_compte) REFERENCES compte(id) ON DELETE CASCADE
);

-- Create table for comments (comments)
CREATE TABLE comments (
    id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
    id_publication INT NOT NULL,
    id_compte INT NOT NULL,
    contenu TEXT NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_publication) REFERENCES publication(id) ON DELETE CASCADE,
    FOREIGN KEY (id_compte) REFERENCES compte(id) ON DELETE CASCADE
);

-- Create table for publication reactions
CREATE TABLE reaction_publication (
    id_reaction INT AUTO_INCREMENT PRIMARY KEY,
    id_publication INT NOT NULL,
    id_compte INT NOT NULL,
    type ENUM('like', 'love', 'haha', 'angry', 'wow', 'sad') NOT NULL,
    UNIQUE KEY unique_reaction (id_publication, id_compte)
);

-- Create table for comment reactions
CREATE TABLE reaction_commentaire (
    id_reaction INT AUTO_INCREMENT PRIMARY KEY,
    id_commentaire INT NOT NULL,
    id_compte INT NOT NULL,
    type ENUM('like', 'love', 'haha', 'angry', 'wow', 'sad') NOT NULL,
    UNIQUE KEY unique_reaction (id_commentaire, id_compte)
);

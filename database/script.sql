-- Table: utilisateur
CREATE TABLE IF NOT EXISTS utilisateur (
    id_utilisateur INT(11) NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20) DEFAULT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    compte_verifie TINYINT(1) NOT NULL DEFAULT 0,
    verification_token VARCHAR(50) DEFAULT NULL,
    token_expiry DATETIME DEFAULT NULL,
    role ENUM('admin', 'client', 'gerant_terrain') DEFAULT 'client',
    date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id_utilisateur),
    UNIQUE KEY idx_email (email),
    KEY idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: terrain
CREATE TABLE IF NOT EXISTS terrain (
    id_terrain INT(11) NOT NULL AUTO_INCREMENT,
    nom_terrain VARCHAR(100) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    taille ENUM('Mini foot', 'Terrain moyen', 'Grand terrain') NOT NULL,
    type ENUM('Gazon naturel', 'Gazon artificiel', 'Terrain dur') NOT NULL,
    prix_heure FLOAT NOT NULL DEFAULT 0,
    id_utilisateur INT(11) DEFAULT NULL,
    actif TINYINT(1) DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_terrain),
    KEY idx_ville (ville),
    KEY idx_id_utilisateur (id_utilisateur),
    KEY idx_actif (actif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: reservation
CREATE TABLE IF NOT EXISTS reservation (
    id_reservation INT(11) NOT NULL AUTO_INCREMENT,
    date_reservation DATE NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    id_utilisateur INT(11) NOT NULL,
    id_terrain INT(11) NOT NULL,
    commentaires TEXT DEFAULT NULL,
    statut ENUM('En attente', 'Confirmée', 'Annulée', 'Modifiée') DEFAULT 'En attente',
    statut_paiement ENUM('en_attente', 'paye', 'annule') DEFAULT 'en_attente',
    prix_total DECIMAL(10,2) DEFAULT 0.00,
    date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    date_modification TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (id_reservation),
    KEY idx_id_utilisateur (id_utilisateur),
    KEY idx_id_terrain (id_terrain),
    KEY idx_statut (statut),
    KEY idx_statut_paiement (statut_paiement)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: optionsupplementaire
CREATE TABLE IF NOT EXISTS optionsupplementaire (
    id_option INT(11) NOT NULL AUTO_INCREMENT,
    id_terrain INT(11) NOT NULL,
    nom_option VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (id_option),
    KEY idx_id_terrain (id_terrain)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Ajouter les contraintes de clés étrangères (Foreign Keys)
ALTER TABLE reservation
    ADD CONSTRAINT fk_reservation_utilisateur 
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) 
    ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT fk_reservation_terrain 
    FOREIGN KEY (id_terrain) REFERENCES terrain(id_terrain) 
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE terrain
    ADD CONSTRAINT fk_terrain_utilisateur 
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) 
    ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE optionsupplementaire
    ADD CONSTRAINT fk_option_terrain 
    FOREIGN KEY (id_terrain) REFERENCES terrain(id_terrain) 
    ON DELETE CASCADE ON UPDATE CASCADE;





CREATE TABLE Tournoi (
    id_tournoi INT PRIMARY KEY AUTO_INCREMENT,
    nom_tournoi VARCHAR(100) NOT NULL,
    description TEXT,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    id_terrain INT NOT NULL,
    id_gerant INT NOT NULL,
    statut ENUM('En préparation', 'En cours', 'Terminé', 'Annulé') DEFAULT 'En préparation',
    nombre_max_equipes INT DEFAULT 8,
    prix_inscription DECIMAL(10,2) DEFAULT 0.00,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_dates (date_debut, date_fin),
    FOREIGN KEY (id_terrain) REFERENCES Terrain(id_terrain) ON DELETE CASCADE,
    FOREIGN KEY (id_gerant) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- TABLE: Equipe
-- Description: Équipes participant aux tournois
-- ============================================================
CREATE TABLE Equipe (
    id_equipe INT PRIMARY KEY AUTO_INCREMENT,
    nom_equipe VARCHAR(100) NOT NULL,
    id_responsable INT NOT NULL,
    id_tournoi INT NOT NULL,
    nombre_joueurs INT DEFAULT 0,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tournoi (id_tournoi),
    FOREIGN KEY (id_responsable) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_tournoi) REFERENCES Tournoi(id_tournoi) ON DELETE CASCADE,
    UNIQUE KEY unique_equipe_tournoi (nom_equipe, id_tournoi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS newsletter (
  id_newsletter INT(11) NOT NULL AUTO_INCREMENT,
  id_expediteur INT(11) NOT NULL,
  sujet VARCHAR(255) NOT NULL,
  contenu TEXT NOT NULL,
  date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
  nombre_destinataires INT(11) DEFAULT 0,
  piece_jointe VARCHAR(255) DEFAULT NULL,
  statut ENUM('en_cours', 'envoye', 'echoue') DEFAULT 'en_cours',
  PRIMARY KEY (id_newsletter),
  KEY id_expediteur (id_expediteur),
  KEY date_envoi (date_envoi),
  KEY statut (statut),
  CONSTRAINT fk_newsletter_expediteur FOREIGN KEY (id_expediteur) REFERENCES utilisateur (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Dump minimal pour la base "livreor" (structure + contenu d'exemple)
-- Encodage UTF8

DROP DATABASE IF EXISTS `livreor`;
CREATE DATABASE IF NOT EXISTS `livreor` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `livreor`;

-- Table utilisateurs (id, login, password, created_at)
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `login` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table commentaires (id, commentaire, id_utilisateur, date)
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `commentaire` TEXT,
  `id_utilisateur` INT NULL,
  `date` DATETIME,
  CONSTRAINT `fk_commentaire_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Données d'exemple
-- Mot de passe (haché): mot de passe exemple = "password123" (hash généré avec password_hash)
INSERT INTO `utilisateurs` (`login`, `password`) VALUES
('admin', '$2y$10$/vD8hGtkBJsAae2TiSkbV.jg0bnNDAFv8xBewH14.OKvR0PpeVbq6'),
('atikatou', '$2y$10$/vD8hGtkBJsAae2TiSkbV.jg0bnNDAFv8xBewH14.OKvR0PpeVbq6');

INSERT INTO `commentaires` (`commentaire`, `id_utilisateur`, `date`) VALUES
('WAAAH quel joli site !!! j\'espere avoir ton skill un jour pour faire d\'aussi jolis sites internet !', 2, '2021-12-11 10:30:00'),
('c\'est bizarre', 1, '2021-12-03 12:00:00');

-- Fin du dump

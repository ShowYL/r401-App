CREATE TABLE `Joueur` (
    `ID_Joueur` int PRIMARY KEY,
    `Licence` char,
    `Nom` varchar(255),
    `Prénom` varchar(255),
    `Taille` float,
    `Poids` float,
    `Date_Naissance` date,
    `Statut` enum('Actif', 'Inactif'),
    `Commentaire` text
);

CREATE TABLE `Match` (
    `ID_Match` int PRIMARY KEY,
    `Date_Match` date,
    `Heure_Match` time,
    `Equipe_Adverse` varchar(255),
    `Lieu` enum('Domicile', 'Exterieur'),
    `Résultat` enum('Victoire', 'Défaite', 'Nul')
);

CREATE TABLE `Selection` (
    `ID_Selection` int PRIMARY KEY,
    `ID_Joueur` int,
    `ID_Match` int,
    `Titulaire` tinyint,
    `Poste` enum('Gardien', 'Défenseur', 'Milieu', 'Attaquant'),
    `Note` text,
    FOREIGN KEY (`ID_Joueur`) REFERENCES `Joueur`(`ID_Joueur`),
    FOREIGN KEY (`ID_Match`) REFERENCES `Match`(`ID_Match`)
);
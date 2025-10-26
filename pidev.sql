-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 12 mai 2022 à 22:00
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pidev`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `dateCommande` date NOT NULL,
  `idUser` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `confirme` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `dateCommande`, `idUser`, `idProduit`, `confirme`) VALUES
(34, '2022-04-25', 1, 23, 1),
(35, '2022-04-25', 1, 10, 1),
(36, '2022-04-25', 1, 23, 1),
(37, '2022-04-26', 2, 10, 1),
(38, '2022-04-26', 3, 9, 0),
(39, '2022-04-26', 4, 10, 1),
(40, '2022-04-26', 5, 26, 1),
(41, '2022-05-26', 1, 10, 0),
(42, '2022-05-10', 361, 9, 0),
(43, '2022-05-10', 362, 10, 1);

-- --------------------------------------------------------

--
-- Structure de la table `donation`
--

CREATE TABLE `donation` (
  `ID` int(100) NOT NULL,
  `montant` int(100) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idTeam` int(11) NOT NULL,
  `dateDon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `donation`
--

INSERT INTO `donation` (`ID`, `montant`, `idUser`, `idTeam`, `dateDon`) VALUES
(34, 30, 5, 4, '2022-05-27'),
(35, 100, 361, 2, '2022-05-11'),
(36, 200, 362, 7, '2022-06-04'),
(37, 170, 362, 7, '2022-06-04');

--
-- Déclencheurs `donation`
--
DELIMITER $$
CREATE TRIGGER `walletUpdate` AFTER INSERT ON `donation` FOR EACH ROW UPDATE
team
SET
wallet = wallet + new.montant
WHERE
new.idTeam = ID
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `invitation`
--

CREATE TABLE `invitation` (
  `id` int(100) NOT NULL,
  `idcaptain` int(11) NOT NULL,
  `idjoueur` int(11) NOT NULL,
  `dateInvit` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `jeu`
--

CREATE TABLE `jeu` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `nombreJoueursNecessaires` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `jeu`
--

INSERT INTO `jeu` (`id`, `nom`, `type`, `nombreJoueursNecessaires`, `image`) VALUES
(1, 'League Of Legends', 'Moba', 2, 'lol'),
(2, 'Fifa', 'Foot', 88, 'Fifa'),
(4, 'Metin2', 'MMORPG', 8, 'metin2'),
(5, 'Call of duty', 'FPS', 7, 'cod'),
(6, 'Runescape', 'MMORPG', 1, 'rs'),
(7, 'Valorant', 'FPS', 88, 'valorant');

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

CREATE TABLE `joueur` (
  `id` int(50) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `dateNaissance` date NOT NULL,
  `mail` varchar(150) DEFAULT NULL,
  `mdp` varchar(256) NOT NULL,
  `wallet` float NOT NULL,
  `type` enum('admin','joueur','captain','') NOT NULL,
  `photo` varchar(200) NOT NULL,
  `avertissement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `joueur`
--

INSERT INTO `joueur` (`id`, `nom`, `prenom`, `dateNaissance`, `mail`, `mdp`, `wallet`, `type`, `photo`, `avertissement`) VALUES
(1, 'BenArab', 'Hachem', '2021-06-24', 'a', 'a', 0, '', 'no.png', 1),
(2, 'Flija', 'Youssef', '2021-06-24', 'youssef.flija@esprit.tn', 'youssef123', 20, '', 'no.png', 0),
(3, 'Mabrouk', 'Zied', '2012-04-10', 'zied.mabrouk@gmail.com', 'zied123', 0, 'joueur', 'no.png', 0),
(4, 'Fessi', 'Elyes', '2012-04-10', 'elyesfessi@gmail.com', 'elyes123', 0, 'joueur', 'no.png', 1),
(5, 'Chebbi', 'Raed', '2021-06-24', 'hachem.benaraddb@esprit.tn', 'hachem123', 0, '', 'no.png', 0),
(6, 'Ben Salem', 'Yosr', '2021-06-24', 'youssef.flija@esprit.tnzedzed', 'youssef123', 0, '', 'no.png', 0),
(7, 'Maalej', 'Walid', '2012-04-10', 'zied.mabrouk@gmail.com', 'zied123', 0, 'joueur', 'no.png', 0),
(8, 'Essid', 'Cyrine', '2012-04-10', 'dfz', 'elyes123', 0, 'joueur', 'no.png', 3),
(361, 'Mabrouk', 'Zied', '2022-05-21', 'fefe', '$argon2id$v=19$m=65536,t=4,p=1$cnhlZGZ6Nm1wdjZvemdZSA$1V7U3Y7xSMP3uM+TyKPc1ByBULXktH2wHUsxnWBtIRQ', 48996, 'joueur', 'picture', 0),
(362, 'Hachem', 'Ben Arab', '2022-05-29', 'zied.mabrouk@esprit.tn', '$argon2id$v=19$m=65536,t=4,p=1$cnhlZGZ6Nm1wdjZvemdZSA$1V7U3Y7xSMP3uM+TyKPc1ByBULXktH2wHUsxnWBtIRQ', 400, 'captain', 'picture', 0),
(363, 'fzfze', 'ezfzef', '2022-05-05', 'zied@esprit.tn', '$argon2id$v=19$m=65536,t=4,p=1$M0YwcWtzbFVDZE1NYnU5ag$H5TzfzeHktRBW6hL1uhkSCVEhIyMK4hoOT6Xn7rYL7o', 0, 'admin', 'picture', 0);

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `sujet_n` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(256) NOT NULL,
  `date_c` date NOT NULL,
  `date_f` date NOT NULL,
  `idJeu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `news`
--

INSERT INTO `news` (`id`, `sujet_n`, `text`, `image`, `date_c`, `date_f`, `idJeu`) VALUES
(51, 'L\'authentification à deux facteurs est arrivée', 'Cela fait longtemps et nous sommes ravis d\'annoncer que nous avons officiellement lancé l\'authentification à deux facteurs (2FA) pour votre compte Riot. À partir d\'aujourd\'hui, vous pouvez activer 2FA pour minimiser les risques et vous sentir plus en sécurité en sachant que votre compte est protégé', '627a25fc0f12f859494638.jpg', '2022-05-25', '2022-05-31', 1),
(52, '/DEV : LE POINT SUR LA VGU D\'UDYR', 'mise à jour sur la VGU d\'Udyr, qui sortira plus tard cette année. Depuis notre dernier enregistrement, nous sommes entrés en pleine production sur Udyr, donc ce post se concentrera sur son apparence dans le jeu.', '01_Udyr_Banner.jpg', '2022-05-18', '2022-05-26', 1),
(53, 'Riot fait don des ventes de Battle Pass à l\'aide humanitaire en Europe de l\'Est', 'La créativité se développe dans la nuit. Qu\'il s\'agisse de décompresser après le travail, d\'une promenade nocturne ou des pensées fugaces à moitié endormies qui vous poussent à garder un cahier sur votre table de chevet, il y a quelque chose dans le ciel étoilé qui inspire la créativité. Pour Diana, une orpheline troublée qui poursuit sans relâche la connaissance au clair de lune, la nuit est à la maison.', '627a26b336e32419704729.jpg', '2022-05-04', '2022-05-19', 1),
(54, 'QUOI DE NEUF DANS VALORANT EPISODE 4 ACT II', 'Plus tôt cette année, nous avons annoncé que l\'épisode 4 de l\'acte II consisterait à prendre une pause du côté du nouvel agent et de la carte pour jeter un coup d\'œil et renforcer les zones qui avaient besoin d\'un soutien supplémentaire. Cela inclut la refonte tant attendue de Yoru, une définition plus claire de nos agents de contrôleur et vos mises à jour de correctifs habituelles.', '627a26da7f89b968321266.jpg', '2022-05-12', '2022-05-28', 7),
(55, 'Check our new page', 'regregre', '627a658e0a0af598989103.jpg', '2022-05-29', '2022-05-31', 1);

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

CREATE TABLE `offre` (
  `id` int(100) NOT NULL,
  `quantite` int(30) NOT NULL,
  `bonus` int(30) NOT NULL,
  `prix` double NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `offre`
--

INSERT INTO `offre` (`id`, `quantite`, `bonus`, `prix`, `image`) VALUES
(18, 150, 50, 50, 'picture'),
(19, 2, 10, 30, 'picture'),
(20, 200, 10, 60, 'picture');

-- --------------------------------------------------------

--
-- Structure de la table `pari`
--

CREATE TABLE `pari` (
  `idMatch` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `idEquipe` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `pari`
--

INSERT INTO `pari` (`idMatch`, `idUser`, `montant`, `idEquipe`) VALUES
(243, 361, 50, 'A'),
(244, 2, 5, 'A'),
(274, 361, 50, 'A'),
(276, 361, 999, 'A'),
(279, 362, 40, 'B');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `prix` float NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(300) NOT NULL,
  `type` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL,
  `idEquipe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `ref`, `nom`, `prix`, `stock`, `image`, `type`, `description`, `idEquipe`) VALUES
(9, 'ref 1', 'Casque', 25, 9, 'shop_img01.png', 'pc', 'Casque gamer', 5),
(10, 'ref 2', 'Manette', 23, 1, 'shop_img02.png', 'pc', 'Manette gamer', 5),
(23, 'ref3', 'Souris', 20, 3, 'mouse.png', 'pc', 'Souris gamer', 4),
(26, 'azr', 'Pull', 30, 2, 'pull.png', 'habit', 'Beau pull ', 4);

-- --------------------------------------------------------

--
-- Structure de la table `reclamation`
--

CREATE TABLE `reclamation` (
  `id` int(11) NOT NULL,
  `sujet` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `attachement` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `numero_tel` int(11) NOT NULL,
  `status` varchar(256) NOT NULL,
  `object` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reclamation`
--

INSERT INTO `reclamation` (`id`, `sujet`, `description`, `attachement`, `email`, `numero_tel`, `status`, `object`) VALUES
(63, 'Harcelement ', 'Un des adversaires m a insulte tout le match', 'EERdjYjXYAAUwS2.png', 'hachem.benarab@gmail.com', 23743004, 'En attente', 'Report a player'),
(64, 'Violence verbale', 'cette personne n a cessé de m insulter tout au long du jeu', 'EDySEfyW4AAZpV_.png', 'hachem.benarab@gmail.com', 20586787, 'En attente', 'Report a player'),
(66, 'Offre excessivement cher', 'veuilllez reduire encore plus le prix des offres', 'offre.PNG', 'hachem.benarab@gmail.com', 24991110, 'En attente', 'Other'),
(69, 'Perte', 'zefzef', '627a655a4fab6462406362.png', 'zied.mabrouk@esprit.tn', 26225978, 'Traité', 'Report a player');

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reset_password_request`
--

INSERT INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
(45, 361, '4KtcM4pf3BBjVCkYIRYS', '/64PnhOlmc9c2boNXtM6uHyg//9si1UQ5s+r0NSpeSA=', '2022-05-10 14:11:51', '2022-05-10 15:11:51');

-- --------------------------------------------------------

--
-- Structure de la table `team`
--

CREATE TABLE `team` (
  `ID` int(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `wallet` int(100) NOT NULL,
  `captainID` int(11) DEFAULT NULL,
  `pays` varchar(100) NOT NULL,
  `dateCreation` date NOT NULL,
  `joueurs` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `team`
--

INSERT INTO `team` (`ID`, `logo`, `nom`, `wallet`, `captainID`, `pays`, `dateCreation`, `joueurs`) VALUES
(1, 'teamA.jpg', 'Assasins', 200, 1, 'Tunisia', '2022-04-05', '1#2'),
(2, 'teamB.jpg', 'Anubis', 100, 2, 'Tunisia', '2022-04-05', '2#'),
(3, 'teamI.jpg', 'Owls', 0, 3, 'Tunisia', '2022-04-01', '3#'),
(4, 'teamO.jpg', 'Killers', 30, 363, 'Tunisia', '2022-04-01', '4#'),
(5, 'teamM.png', 'Noobs', 0, 4, 'Tunisia', '2022-04-05', '1#'),
(6, 'teamK.jpg', 'Shooter', 300, 6, 'Tunisia', '2022-04-05', '1#'),
(7, 'teamH.png', 'Titans', 370, 7, 'Tunisia', '2022-04-05', '1#'),
(8, 'logoG.jpg', 'Demons', 0, 361, 'Tunisia', '2022-04-05', '2#'),
(101, '59758f96b1290907e0637c17541cfd5f.jpeg', 'NewTeam', 0, 362, 'Algérie', '2022-05-05', '1');

-- --------------------------------------------------------

--
-- Structure de la table `tmatchs`
--

CREATE TABLE `tmatchs` (
  `id` int(11) NOT NULL,
  `idTournoi` int(11) NOT NULL,
  `etat` enum('BetA','betnA') NOT NULL,
  `dateMatch` date NOT NULL,
  `score` enum('A','B','nD') NOT NULL,
  `heureMatch` int(4) NOT NULL,
  `idEquipeA` int(11) NOT NULL,
  `idEquipeB` int(11) NOT NULL,
  `phase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tmatchs`
--

INSERT INTO `tmatchs` (`id`, `idTournoi`, `etat`, `dateMatch`, `score`, `heureMatch`, `idEquipeA`, `idEquipeB`, `phase`) VALUES
(278, 64, 'BetA', '2022-05-10', 'A', 5, 2, 1, 0),
(279, 64, 'BetA', '2022-05-10', 'A', 5, 5, 4, 0),
(280, 64, 'betnA', '2022-05-10', 'A', 5, 2, 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `tournoi`
--

CREATE TABLE `tournoi` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prix` int(11) NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL,
  `details` varchar(500) NOT NULL,
  `equipes` varchar(100) DEFAULT NULL,
  `heure` int(11) NOT NULL,
  `idJeu` int(11) NOT NULL,
  `phase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tournoi`
--

INSERT INTO `tournoi` (`id`, `nom`, `prix`, `dateDebut`, `dateFin`, `details`, `equipes`, `heure`, `idJeu`, `phase`) VALUES
(64, 'Tournoi international', 500, '2022-05-28', '2022-06-04', 'Tournoi international', '2#', 12, 1, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_commande` (`idProduit`),
  ADD KEY `fk_user_commande` (`idUser`);

--
-- Index pour la table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_donation_team` (`idTeam`),
  ADD KEY `fk_donation_user` (`idUser`);

--
-- Index pour la table `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invit_user` (`idjoueur`),
  ADD KEY `fk_invit_captain` (`idcaptain`);

--
-- Index pour la table `jeu`
--
ALTER TABLE `jeu`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `joueur`
--
ALTER TABLE `joueur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idJeu` (`idJeu`);

--
-- Index pour la table `offre`
--
ALTER TABLE `offre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pari`
--
ALTER TABLE `pari`
  ADD PRIMARY KEY (`idMatch`,`idUser`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idEquipe` (`idEquipe`);

--
-- Index pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `captainID` (`captainID`);

--
-- Index pour la table `tmatchs`
--
ALTER TABLE `tmatchs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idEquipeA` (`idEquipeA`),
  ADD KEY `idEquipeB` (`idEquipeB`),
  ADD KEY `idTournoi` (`idTournoi`);

--
-- Index pour la table `tournoi`
--
ALTER TABLE `tournoi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idJeu` (`idJeu`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `donation`
--
ALTER TABLE `donation`
  MODIFY `ID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `invitation`
--
ALTER TABLE `invitation`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `jeu`
--
ALTER TABLE `jeu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `joueur`
--
ALTER TABLE `joueur`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=364;

--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `offre`
--
ALTER TABLE `offre`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `reclamation`
--
ALTER TABLE `reclamation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `team`
--
ALTER TABLE `team`
  MODIFY `ID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT pour la table `tmatchs`
--
ALTER TABLE `tmatchs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT pour la table `tournoi`
--
ALTER TABLE `tournoi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_product_commande` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`id`),
  ADD CONSTRAINT `fk_user_commande` FOREIGN KEY (`idUser`) REFERENCES `joueur` (`id`);

--
-- Contraintes pour la table `donation`
--
ALTER TABLE `donation`
  ADD CONSTRAINT `fk_donation_team` FOREIGN KEY (`idTeam`) REFERENCES `team` (`ID`),
  ADD CONSTRAINT `fk_donation_user` FOREIGN KEY (`idUser`) REFERENCES `joueur` (`id`);

--
-- Contraintes pour la table `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `fk_invit_captain` FOREIGN KEY (`idcaptain`) REFERENCES `joueur` (`id`),
  ADD CONSTRAINT `fk_invit_user` FOREIGN KEY (`idjoueur`) REFERENCES `joueur` (`id`);

--
-- Contraintes pour la table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`idJeu`) REFERENCES `jeu` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`idEquipe`) REFERENCES `team` (`ID`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `joueur` (`id`);

--
-- Contraintes pour la table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`captainID`) REFERENCES `joueur` (`id`);

--
-- Contraintes pour la table `tmatchs`
--
ALTER TABLE `tmatchs`
  ADD CONSTRAINT `tmatchs_ibfk_1` FOREIGN KEY (`idEquipeA`) REFERENCES `team` (`ID`),
  ADD CONSTRAINT `tmatchs_ibfk_2` FOREIGN KEY (`idEquipeB`) REFERENCES `team` (`ID`),
  ADD CONSTRAINT `tmatchs_ibfk_3` FOREIGN KEY (`idTournoi`) REFERENCES `tournoi` (`id`);

--
-- Contraintes pour la table `tournoi`
--
ALTER TABLE `tournoi`
  ADD CONSTRAINT `tournoi_ibfk_1` FOREIGN KEY (`idJeu`) REFERENCES `jeu` (`id`);

DELIMITER $$
--
-- Évènements
--
CREATE DEFINER=`root`@`localhost` EVENT `reset` ON SCHEDULE EVERY 1 MINUTE STARTS '2022-03-05 21:00:00' ON COMPLETION NOT PRESERVE ENABLE DO update `tmatchs` 
set etat = "betnA"
where heureMatch = (SELECT HOUR(LOCALTIME)) and ((extract(day from dateMatch) = (SELECT DAY(LOCALTIME))) and extract(month from dateMatch) = (SELECT month(LOCALTIME)) and extract(year from dateMatch) = (SELECT year(LOCALTIME)))$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

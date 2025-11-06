-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS rdv;
DROP TABLE IF EXISTS Motif;
DROP TABLE IF EXISTS ContratClient;
DROP TABLE IF EXISTS Contrat;
DROP TABLE IF EXISTS Operation;
DROP TABLE IF EXISTS CompteClient;
DROP TABLE IF EXISTS Client;
DROP TABLE IF EXISTS Employe;
DROP TABLE IF EXISTS Compte;
DROP TABLE IF EXISTS Situation;

-- Création des tables
CREATE TABLE Situation (
    idSituation INT PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE Compte (
    idCompte INT PRIMARY KEY AUTO_INCREMENT,
    nomTypeCompte VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Employe (
    numEmploye INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255),
    login VARCHAR(255),
    motDePasse VARCHAR(255),
    categorie VARCHAR(255),
    actif INT
);

CREATE TABLE Client (
    numClient INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    adresse VARCHAR(255),
    mail VARCHAR(255),
    numTel VARCHAR(255),
    dateNaissance DATE,
    idSituation INT,
    numEmploye INT,
    FOREIGN KEY (idSituation) REFERENCES Situation(idSituation),
    FOREIGN KEY (numEmploye) REFERENCES Employe(numEmploye)
);

CREATE TABLE CompteClient (
    idCompteClient INT PRIMARY KEY AUTO_INCREMENT,
    dateOuverture DATE,
    solde DECIMAL(10, 2),
    montantDecouvert DECIMAL(10, 2),
    numClient INT,
    idCompte INT,
    FOREIGN KEY (numClient) REFERENCES Client(numClient),
    FOREIGN KEY (idCompte) REFERENCES Compte(idCompte)
);

CREATE TABLE Operation (
    numOp INT PRIMARY KEY AUTO_INCREMENT,
    montant DECIMAL(10, 2),
    dateOperation DATE,
    typeOp VARCHAR(255),
    idCompteClient INT,
    FOREIGN KEY (idCompteClient) REFERENCES CompteClient(idCompteClient)
);

CREATE TABLE Contrat (
    numContrat INT PRIMARY KEY AUTO_INCREMENT,
    nomTypeContrat VARCHAR(25),
    description VARCHAR(255) 
);

CREATE TABLE ContratClient (
    idContratClient INT PRIMARY KEY AUTO_INCREMENT,
    dateOuvertureContrat DATE,
    tarifMensuel DECIMAL(10, 2),
    numClient INT,
    numContrat INT,
    FOREIGN KEY (numClient) REFERENCES Client(numClient),
    FOREIGN KEY (numContrat) REFERENCES Contrat(numContrat) ON DELETE CASCADE
);

CREATE TABLE Motif (
    idMotif INT PRIMARY KEY AUTO_INCREMENT,
    libelleMotif VARCHAR(255),
    listePieces VARCHAR(255)
);

CREATE TABLE rdv (
    numRdv INT PRIMARY KEY AUTO_INCREMENT,
    dateRdv DATE NOT NULL,
    heureRdv INT,
    numEmploye INT,
    idMotif INT,
    numClient INT,
    FOREIGN KEY (numEmploye) REFERENCES Employe(numEmploye),
    FOREIGN KEY (idMotif) REFERENCES Motif(idMotif),
    FOREIGN KEY (numClient) REFERENCES Client(numClient)
);

-- Insertion des données

-- Insertion des situations
INSERT INTO Situation (description) VALUES
('Marié(e)'),
('Célibataire'),
('Veuf/Veuve'),
('Divorcé(e)'),
('En couple'),
('En concubinage'),
('Séparé(e)'),
('Fiancé(e)'),
('Pacsé(e)'),
('En relation à distance');

-- Insertion des comptes
INSERT INTO Compte (nomTypeCompte, description) VALUES
('Compte Courant', 'Pour les opérations quotidiennes'),
('Livret A', 'Épargne réglementée avec un taux d’intérêt exonéré d’impôts'),
('PEL', 'Plan Épargne Logement pour un projet immobilier'),
('Compte Épargne', 'Compte pour mettre de côté une partie de vos revenus'),
('Compte Joint', 'Compte partagé entre plusieurs personnes pour des dépenses communes'),
('Compte Chèque', 'Compte permettant l’émission de chèques pour paiement');

-- Insertion des employés
INSERT INTO Employe (nom, login, motDePasse, categorie, actif) VALUES
('Benoit', 'Benoit', '$2y$10$4ieMYxLS0BSGqTNQBwI.SOfFUG.VIQPq5cIjDQGg73Bbraw/9Cr1m', 'Conseiller', 1),
('Liudmyla', 'Liudmyla', '$2y$10$4ieMYxLS0BSGqTNQBwI.SOfFUG.VIQPq5cIjDQGg73Bbraw/9Cr1m', 'Directeur', 1),
('Pierre', 'Pierre','$2y$10$4ieMYxLS0BSGqTNQBwI.SOfFUG.VIQPq5cIjDQGg73Bbraw/9Cr1m', 'Agent', 1),
('Oleg', 'COleg', '$2y$10$4ieMYxLS0BSGqTNQBwI.SOfFUG.VIQPq5cIjDQGg73Bbraw/9Cr1m', 'Conseiller', 0),
('Marc', 'Marc','$2y$10$4ieMYxLS0BSGqTNQBwI.SOfFUG.VIQPq5cIjDQGg73Bbraw/9Cr1m', 'Conseiller', 1),
('Max', 'Max', '$2y$10$4ieMYxLS0BSGqTNQBwI.SOfFUG.VIQPq5cIjDQGg73Bbraw/9Cr1m', 'Conseiller', 1),
('Natalie', 'Natalie', '$2y$10$4ieMYxLS0BSGqTNQBwI.SOfFUG.VIQPq5cIjDQGg73Bbraw/9Cr1m', 'Agent', 0),
('Clelia', 'Clelia', '$2y$10$4ieMYxLS0BSGqTNQBwI.SOfFUG.VIQPq5cIjDQGg73Bbraw/9Cr1m', 'Conseiller', 0);


-- Insertion des clients
INSERT INTO Client (nom, prenom, adresse, mail, numTel, dateNaissance, idSituation, numEmploye) VALUES
('Durand', 'Alice', '12 rue des Lilas', 'alice.durand@example.com', '0123456789',  '1985-04-12', 1, 1),
('Moreau', 'Bob', '45 avenue du Général', 'bob.moreau@example.net', '9876543210', '1978-08-23', 2, 5),
('Petit', 'Clara', '78 boulevard de la Liberté', 'clara.petit@example.org', '5678901234', '1990-11-30', 3, 5),
('Dupont', 'Jean', '24 rue des Roses', 'jean.dupont@example.com', '0123456789', '1980-05-15', 1, 5),
('Martin', 'Sophie', '36 avenue Victor Hugo', 'sophie.martin@example.net', '9876543210', '1975-09-28', 2, 1),
('Lefevre', 'Pierre', '50 rue du Commerce', 'pierre.lefevre@example.org', '5678901234', '1992-03-10', 3, 1),
('Roux', 'Émilie', '112 rue de Belleville', 'emilie.roux@example.com', '1234567890', '1993-02-27', 4, 1),
('Blanc', 'Lucas', '88 chemin des Oliviers', 'lucas.blanc@example.net', '2345678901', '1986-07-19', 5, 1),
('Noir', 'Julie', '43 allée des Acacias', 'julie.noir@example.org', '3456789012', '1979-12-15', 6, 5);

-- Insertion des comptes clients
INSERT INTO CompteClient (dateOuverture, solde, montantDecouvert, numClient, idCompte) VALUES
-- (Client 1)
('2020-01-01', 2000.00, 500.00, 1, 1),
('2021-03-15', 1000.00, 0.00, 1, 5),
('2022-06-20', 5000.00, 1000.00, 1, 3),
-- (Client 2)
('2019-07-01', 3000.00, 200.00, 2, 4),
('2020-09-10', 1500.00, 0.00, 2, 1),
-- (Client 3)
('2021-02-01', 4000.00, 800.00, 3, 1),
('2022-01-15', 2000.00, 500.00, 3, 2),
('2023-05-10', 6000.00, 1500.00, 3, 3),
-- (Client 4)
('2022-08-20', 3500.00, 700.00, 4, 4),
('2023-04-05', 1800.00, 400.00, 4, 5),
('2024-11-12', 5000.00, 1200.00, 4, 6),
-- (Client 5)
('2023-09-28', 4200.00, 900.00, 5, 6),
('2022-12-03', 2300.00, 600.00, 5, 2),
('2024-02-18', 6700.00, 1500.00, 5, 3),
-- (Client 6)
('2023-07-15', 3100.00, 800.00, 6, 1),
('2024-05-09', 2800.00, 700.00, 6, 2),
('2022-10-25', 6000.00, 1400.00, 6, 5),
-- (Client 7)
('2023-07-15', 3100.00, 800.00, 7, 4),
('2024-05-09', 2800.00, 700.00, 7, 2),
('2022-10-25', 6000.00, 1400.00, 7, 3),
-- (Client 8)
('2023-08-01', 3500.00, 900.00, 8, 6),
('2022-11-20', 6100.00, 1450.00, 8, 3),
-- (Client 9)
('2023-09-15', 3200.00, 850.00, 9, 5);

 
-- Insertion des opérations
INSERT INTO Operation (montant,  dateOperation, typeOp, idCompteClient) VALUES
-- Compte 1 (Client 1)
(500.00, '2020-01-02', 'Dépôt', 1),
(300.00, '2020-02-15', 'Retrait', 1),
(200.00, '2020-03-20', 'Dépôt', 1),
(100.00, '2020-05-10', 'Retrait', 1),
(700.00, '2020-08-01', 'Dépôt', 1),
(200.00, '2021-03-20', 'Dépôt', 2),
(150.00, '2021-04-05', 'Retrait', 2),
(300.00, '2021-06-10', 'Dépôt', 2),
(100.00, '2021-07-25', 'Retrait', 2),
(400.00, '2021-09-15', 'Dépôt', 2),
(1000.00, '2022-06-25', 'Dépôt', 3),
(500.00, '2022-07-10', 'Retrait', 3),
(2000.00, '2022-08-20', 'Dépôt', 3),
(1500.00, '2022-10-05', 'Retrait', 3),
(3000.00, '2022-12-01', 'Dépôt', 3),
-- Compte 4 (Client 2)
(800.00, '2023-04-15', 'Dépôt', 4),
(400.00, '2023-05-20', 'Retrait', 4),
(1200.00, '2023-07-10', 'Dépôt', 4),
(900.00, '2023-09-05', 'Retrait', 4),
(1500.00, '2023-11-25', 'Dépôt', 4),
-- Compte 5 (Client 2)
(700.00, '2024-02-10', 'Retrait', 5),
(1000.00, '2024-04-15', 'Dépôt', 5),
(600.00, '2024-06-20', 'Retrait', 5),
-- Compte 6 (Client 2)
(1800.00, '2024-08-10', 'Dépôt', 6),
(1300.00, '2024-10-05', 'Retrait', 6);

-- Insertion des contrats
INSERT INTO Contrat (nomTypeContrat, description) VALUES
('ContratBasique', 'C est un contrat basique'),
('ContratPremium','C est un contrat premium'),
('ContratEntreprise','C est un contrat Entreprise'),
('ContratStandard', 'C’est un contrat standard pour les clients réguliers'),
('ContratGold', 'C’est un contrat gold pour les clients premium'),
('ContratSilver', 'C’est un contrat silver pour un niveau intermédiaire de service');

-- Insertion des contrats clients
INSERT INTO ContratClient (dateOuvertureContrat, tarifMensuel, numClient, numContrat) VALUES
-- (Client 1)
('2022-01-01', 9.99, 1, 1),
('2023-02-01', 19.99, 1, 2),
-- (Client 2)
('2021-04-01', 9.99, 2, 1),
-- (Client 3)
('2023-01-01', 29.99, 3, 3),
('2022-01-01', 9.99, 3, 1),
('2023-02-01', 19.99,3, 2),
-- (Client 4)
('2021-04-01', 9.99, 4, 4),
('2023-01-01', 29.99, 4, 5),
-- (Client 5)
('2022-02-01', 14.99, 5, 6),
('2023-03-01', 19.99, 5, 5),
-- (Client 6)
('2021-05-01', 9.99, 6, 6),
('2023-04-01', 24.99, 6, 4),
-- (Client 7)
('2022-03-01', 19.99, 7, 1),
('2023-05-01', 29.99, 7, 5),
-- (Client 8)
('2021-06-01', 14.99, 8, 2),
('2023-06-01', 24.99, 8, 3),
-- (Client 9)
('2022-04-01', 9.99, 9, 6),
('2023-07-01', 19.99, 9, 1),
('2021-07-01', 9.99, 9, 5);

-- Insertion des motifs
INSERT INTO Motif (libelleMotif, listePieces) VALUES
('compte type C', 'Pièce d’identité, Justificatif de domicile'),
('Demande de prêt', 'Avis d’imposition, Bulletins de salaire'),
('Clôture de compte', 'RIB, Lettre de clôture'),
('Ouverture de compte gold', 'Pièce d’identité, Justificatif de scolarité'),
('Renouvellement de carte bancaire', 'Pièce d’identité, Justificatif de domicile, Ancienne carte'),
('Modification de plafond', 'Pièce d’identité, Justificatif de revenus'),
('Formation','Cahier, Stylo');

-- Insertion des rendez-vous
INSERT INTO rdv (dateRdv, heureRdv, numEmploye, idMotif, numClient) VALUES
('2024-07-10', 11, 6, 1, 1),
('2024-08-15', 12, 1, 2, 2),
('2024-09-20', 15, 6, 3, 3),
('2024-02-10', 17, 5, 4, 4),
('2024-03-15', 18, 1, 6, 5),
('2024-04-20', 10, 6, 5, 6),
('2024-05-25', 19, 1, 3, 6),
('2024-05-25', 13, 1, 7, null),
('2024-07-05', 16, 5, 1, 3),
('2024-08-10', 17, 5, 2, 1);

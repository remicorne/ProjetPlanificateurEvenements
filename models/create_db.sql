DROP TABLE IF EXISTS Utilisateurs ;
CREATE TABLE Utilisateurs (
  numUser INTEGER PRIMARY KEY AUTOINCREMENT,
  nom TEXT NOT NULL,
  prenom TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  photo BLOB TEXT,
  thumbnail BLOB TEXT,
  motDePasse TEXT NOT NULL UNIQUE
);


DROP TABLE IF EXISTS Groupes ;
CREATE TABLE Groupes(
	numGroupe INTEGER PRIMARY KEY AUTOINCREMENT,
	nomGroupe TEXT NOT NULL
);


DROP TABLE IF EXISTS Appartenir ;
CREATE TABLE Appartenir(
	numUser INTEGER REFERENCES Utilisateurs(numUser) ON DELETE CASCADE,
	numGroupe INTEGER REFERENCES Groupes(numGroupe) ON DELETE CASCADE,
	proprietaire BOOLEAN,
	PRIMARY KEY (numUser, numGroupe)
);


DROP TABLE IF EXISTS Evenements ;	
CREATE TABLE Evenements(
	numEvent INTEGER PRIMARY KEY AUTOINCREMENT,
	titre TEXT,
	lieu TEXT,
	descri TEXT
);


DROP TABLE IF EXISTS Sondages ;
CREATE TABLE Sondages(
	numSond INTEGER PRIMARY KEY AUTOINCREMENT,
	date_sond DATE,
	heureD TIME,
	heureF TIME,
	numEvent INTEGER REFERENCES Evenements(numEvent) ON DELETE CASCADE
);


ALTER TABLE Evenements
ADD numSond INTEGER REFERENCES Sondages(numSond) ON DELETE CASCADE;


DROP TABLE IF EXISTS Participants ;
CREATE TABLE Participants(
	numPart INTEGER PRIMARY KEY AUTOINCREMENT,
	numEvent INTEGER REFERENCES Evenements(numEvent) ON DELETE CASCADE,
	numUser INTEGER REFERENCES Utilisateurs(numUser) ON DELETE CASCADE,
	statut TEXT CHECK(statut IN ("createur","administrateur","participant")) DEFAULT "participant"
);


DROP TABLE IF EXISTS Repondre;
CREATE TABLE Repondre(
	numSond INTEGER REFERENCES Sondages(numSond) ON DELETE CASCADE,
	numPart INTEGER REFERENCES Participants(numPart) ON DELETE CASCADE,
	reponse TEXT CHECK(reponse IN ("attente","ok","nonOk")) DEFAULT "attente",
	PRIMARY KEY(numPart,numSond)
);


DROP TABLE IF EXISTS Documents ;
CREATE TABLE Documents(
	numDoc INTEGER PRIMARY KEY AUTOINCREMENT,
	nomDoc TEXT,
	com TEXT
);


DROP TABLE IF EXISTS DocsEven ;
CREATE TABLE DocsEven(
	numEvent INTEGER REFERENCES Evenements(numEvent) ON DELETE CASCADE,
	numDoc INTEGER REFERENCES Documents(numDoc) ON DELETE CASCADE,
	PRIMARY KEY(numEvent,numDoc)
);

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
	nom TEXT NOT NULL
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
	numDate INTEGER,
	titre TEXT,
	lieu TEXT,
	descri TEXT
);

DROP TABLE IF EXISTS Documents ;

CREATE TABLE Documents(
	numDoc INTEGER PRIMARY KEY AUTOINCREMENT,
	nomDoc TEXT,
	com TEXT
);

DROP TABLE IF EXISTS Dates ;

CREATE TABLE Dates(
	numDate INTEGER PRIMARY KEY AUTOINCREMENT,
	date_reunion DATE,
	heureD TIME,
	heureF TIME
);

DROP TABLE IF EXISTS DocEven ;

CREATE TABLE DocEven(
	numEvent INTEGER REFERENCES Evenements(numEvent) ON DELETE CASCADE,
	numDoc INTEGER REFERENCES Documents(numDoc) ON DELETE CASCADE,
	PRIMARY KEY(numEvent,numDoc)
);

DROP TABLE IF EXISTS Participants ;

CREATE TABLE Participants(
	numEvent INTEGER REFERENCES Evenements(numEvent) ON DELETE CASCADE,
	numUser INTEGER REFERENCES Utilisateurs(numUser) ON DELETE CASCADE,
	statut TEXT,
	participation TEXT, 
	PRIMARY KEY(numEvent,numUser)
);

DROP TABLE IF EXISTS Sondages ;

CREATE TABLE Sondages(
	numEvent INTEGER REFERENCES Evenements(numEvent) ON DELETE CASCADE,
	numUser INTEGER REFERENCES Utilisateurs(numUser) ON DELETE CASCADE,
	numDate INTEGER REFERENCES Dates(numDate) ON DELETE CASCADE,
	reponse BOOLEAN,
	PRIMARY KEY(numEvent,numUser,numDate)
);

CREATE TABLE uzytkownicy
(
	id SERIAL PRIMARY KEY NOT NULL,
	imie VARCHAR(30),
	nazwisko VARCHAR(30),
	email VARCHAR(40) UNIQUE,
	haslo VARCHAR(100),
	role INT
);


CREATE TABLE filmy
(
	id SERIAL PRIMARY KEY NOT NULL,
	tytul VARCHAR(50),
	opis TEXT,
	gatunek VARCHAR(40),
	rezyser VARCHAR(100),
	od_lat INTEGER NOT NULL,
	dlugosc INTEGER NOT NULL
);

CREATE TABLE bilety
(
	id SERIAL PRIMARY KEY NOT NULL,
	nazwa VARCHAR(50),
	cena INTEGER
);


CREATE TABLE sale
(
	id SERIAL PRIMARY KEY NOT NULL,
	ilosc_miejsc INTEGER
);


CREATE TABLE seanse
(
	id SERIAL PRIMARY KEY NOT NULL,
	id_filmu INTEGER,
    id_sali INTEGER,
    data DATE,
    od VARCHAR,
     FOREIGN KEY (id_filmu) REFERENCES filmy(id) ON DELETE CASCADE,
     FOREIGN KEY (id_sali) REFERENCES sale(id) ON DELETE CASCADE
);


CREATE TABLE kupione
(
	id SERIAL PRIMARY KEY NOT NULL,
	id_seansu INTEGER,
    id_filmu INTEGER,
    miejsce INTEGER,
    cena VARCHAR,
    id_uzytkownika INTEGER,
    data DATE,
    sala INTEGER,
    od VARCHAR,
    tytul VARCHAR,
     FOREIGN KEY (id_seansu) REFERENCES seanse(id) ON DELETE CASCADE,
     FOREIGN KEY (id_uzytkownika) REFERENCES uzytkownicy(id) ON DELETE CASCADE

);

CREATE TABLE podsumowanie
(
	id SERIAL PRIMARY KEY NOT NULL,
    id_filmu INTEGER,
    razem INTEGER,
     FOREIGN KEY (id_filmu) REFERENCES filmy(id) ON DELETE CASCADE

);
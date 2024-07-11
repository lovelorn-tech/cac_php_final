-- CREATE DATABASE --
CREATE DATABASE cac_php;
USE cac_php;

-- CREATE TABLES --
CREATE TABLE Movies(
	id INT NOT NULL auto_increment PRIMARY KEY,
	AuthorID INT NOT null,
	title VARCHAR(255) NOT NULL,
	DESCRIPTION VARCHAR(255),
	thumbnail VARCHAR(255) NOT NULL,
	video VARCHAR(255) NOT NULL,
	duration VARCHAR(255) NOT NULL,
	release_date VARCHAR(255) NOT NULL
);

CREATE TABLE Authors(
	id INT NOT NULL auto_increment PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	lastname VARCHAR(255) NOT NULL,
	dob VARCHAR(255) NOT NULL
);


-- ADD FOREIGN KEYS --
ALTER TABLE Movies
ADD FOREIGN KEY (AuthorID) REFERENCES Authors(id);


-- INSERT AUTHOR QUERY -- 
INSERT INTO authors (NAME, lastname, dob) VALUES ('Dome', 'Karukoski', '29/12/76');
INSERT INTO authors (NAME, lastname, dob) VALUES ('Bennett', 'Miller', '30/12/66');

-- INSERT MOVIE QUERY --
INSERT INTO movies (AuthorID, title, DESCRIPTION, thumbnail, video, duration, release_date) VALUES (1, 'Tolkien', 'Como estudiante, J.R.R. Tolkien encuentra el amor, la amistad y la inspiración artística entre un grupo de compañeros marginados. Estas experiencias inspiran a Tolkien a escribir las novelas "The Hobbit" y "The Lord of the Rings"','','','1h 16m','3/5/19');
INSERT INTO movies (AuthorID, title, DESCRIPTION, thumbnail, video, duration, release_date) VALUES (2, 'Capote', 'El escritor Truman Capote investiga el brutal asesinato de una familia de Kansas para escribir el libro "In Cold Blood" ("A Sangre Fría")','','','1h 38m','2/3/06');
INSERT INTO movies (AuthorID, title, DESCRIPTION, thumbnail, video, duration, release_date) VALUES (2, 'The cruise', 'El guía turístico de Nueva York, Timothy "Speed" Levitch, ofrece un constante bombardeo de comentarios acerca de la ciudad','','','1h 16m','14/10/88');

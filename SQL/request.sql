CREATE SCHEMA `sign`;

CREATE TABLE `sign`.`students` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255),
  `nom` varchar(255),
  `prenom` varchar(255),
  `mail` varchar(255),
  `password` varchar(255),
  `admin` boolean
);

CREATE TABLE `sign`.`logs` (
  `idStudent` int,
  `currentDate` date,
  `enterDate` datetime,
  `exitDate` datetime,
  `timeIn` time
);

CREATE TABLE `sign`.`subjects` {
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `subject_id` int,
  `student_id` int
};

CREATE TABLE `sign`.`eventlogs` {
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `idStudent` int,
  `subject_id` int,
  `currentDate` date,
  `enterDate` datetime,
  `exitDate` datetime,
  `timeIn` time
};
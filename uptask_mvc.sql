CREATE DATABASES uptask_mvc;
USE uptask_mvc;

CREATE TABLE usuarios(
	id INT(11) NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(30),
	email VARCHAR(30),
	password VARCHAR(60),
	token VARCHAR(15),
	confirmado tinyint(1),
	PRIMARY KEY(id)
);

CREATE TABLE proyectos(
	id INT(11) NOT NULL AUTO_INCREMENT,
	proyecto VARCHAR(60),
	url VARCHAR(32),
	propietarioId INT(11),
	PRIMARY KEY(id),
	CONSTRAINT FK_ProyectoUsuario FOREIGN KEY(propietarioId) REFERENCES usuarios(id) 
);

CREATE TABLE tareas(
	id INT(11) NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(60),
	estado tinyint(1),
	proyectoId INT(11),
	PRIMARY KEY(id),
	CONSTRAINT FK_TareaProyecto FOREIGN KEY(proyectoId) REFERENCES proyectos(id) 
);


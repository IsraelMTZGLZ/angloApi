DROP DATABASE IF EXISTS AngloLatinoDB;

CREATE DATABASE AngloLatinoDB;

USE AngloLatinoDB;

CREATE TABLE Tkeys (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `user_id` INT(11) NOT NULL,
   `Ckey` VARCHAR(40) NOT NULL,
   `level` INT(2) NOT NULL,
   `ignore_limits` TINYINT(1) NOT NULL DEFAULT '0',
   `is_private_key` TINYINT(1)  NOT NULL DEFAULT '0',
   `ip_addresses` TEXT NULL DEFAULT NULL,
   `date_created` INT(11) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into Tkeys(user_id,Ckey,level,date_created) values('1','ANGLOKEY','1','123456789');

CREATE TABLE Tb_Imagenes(
	idImagen int not null AUTO_INCREMENT PRIMARY KEY,
	urlImagen text not null,
	typeImagen varchar(15),
	extImagen varchar(10),
	statusImagen enum('Activo','Inactivo') default 'Activo',
	creationDateImagen timestamp default current_timestamp,
	lastUpdateImagen timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_Personas(
    idPersona int not null auto_increment primary key,
    firstNamePersona varchar(80) not null,
    lastNamePersona varchar(80) not null,
    generoPersona enum('Femenino','Masculino'),
    creationDatePersona timestamp default current_timestamp,
    lastUpdatePersona timestamp default current_timestamp on update current_timestamp,
    photoPersona int,
    FOREIGN KEY(photoPersona) REFERENCES Tb_Imagenes(idImagen) on update cascade on delete cascade
);

CREATE TABLE Tb_Usuarios(
    idUsuario int not null auto_increment primary key,
    emailUsuario varchar(120) not null,
    passwordUsuario varchar(120),
    typeOauthUsuario enum('Facebook','Google','Registro') not null,
    tokenPasswordUser text,
    cambiarPasswordUsuario enum('True','False') not null default 'False',
    typeUsuario enum('Agente','Aspirante','Admin') not null,
    statusUsuario enum('Activo','Inactivo') default 'Activo',
    creationDateUsuario timestamp default current_timestamp,
    lastUpdateUsuario timestamp default current_timestamp on update current_timestamp,
    fkPersona int not null,
    FOREIGN KEY(fkPersona) REFERENCES Tb_Personas(idPersona) on update cascade on delete cascade
);

CREATE TABLE Tb_Aspirantes(
    idAspirante int not null auto_increment primary key,
    fechaNacimientoAspirante date,
    telefonoAspirante varchar(15),
    ciudadAspirante varchar(150),
    creationDateAspirante timestamp default current_timestamp,
    lastUpdateAspirante timestamp default current_timestamp on update current_timestamp,
    fkPersona int not null,
    FOREIGN KEY(fkPersona) REFERENCES Tb_Personas(idPersona)
);

CREATE TABLE Tb_Agentes(
    idAgente int not null auto_increment primary key,
    numeroEmpleado varchar(20) not null,
    puestoAgente varchar(50) not null,
    creationDateAgente timestamp default current_timestamp,
    lastUpdateAgente timestamp default current_timestamp on update current_timestamp,
    fkPersona int not null,
    FOREIGN KEY(fkPersona) REFERENCES Tb_Personas(idPersona)
);

CREATE TABLE Tb_Permisos(
    idPermiso int not null auto_increment primary key,
    nombrePermiso varchar(30) not null,
    estatusPermiso varchar(10) not null,
    descPermiso text,
    creationDatePermiso timestamp default current_timestamp,
    lastUpdatePermiso timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_Permisos_Agentes(
    idPermisoAgente int not null auto_increment primary key,
    fkPermiso int not null,
    fkAgente int not null,
    FOREIGN KEY(fkPermiso) REFERENCES Tb_Permisos(idPermiso),
    FOREIGN KEY(fkAgente) REFERENCES Tb_Agentes(idAgente)
);

CREATE TABLE `Tb_config` (
  `email_send` varchar(140) NOT NULL,
  `email_pass` text NOT NULL,
  `email_protocol` varchar(20) NOT NULL,
  `email_port` varchar(5) NOT NULL,
  `email_host` varchar(60) NOT NULL,
  `from_email` varchar(120) NOT NULL,
  PRIMARY KEY (`email_send`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--ASPIRANTE
CREATE OR REPLACE View Vw_Aspirante as
select idPersona as persona,firstNamePersona as names, lastNamePersona as paterns,generoPersona as genero,photoPersona as photo,
if(p.photoPersona is null,'NULL',(select urlImagen from Tb_Imagenes as i,Tb_Personas where i.idImagen=p.photoPersona limit 1)) as photoUrl,
emailUsuario as email, cambiarPasswordUsuario as cambiarP, typeUsuario,statusUsuario as statusU,
idUsuario as usuario,
CASE
    WHEN typeUsuario="Aspirante" THEN
        (select idAspirante from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as aspirante,
CASE
    WHEN typeUsuario="Aspirante" THEN
        (select fechaNacimientoAspirante from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as fechaNacimiento,
CASE
    WHEN typeUsuario="Aspirante" THEN
        (select telefonoAspirante from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as telefono,
CASE
    WHEN typeUsuario="Aspirante" THEN
        (select ciudadAspirante from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as ciudad
from Tb_Personas as p, Tb_Usuarios as u
where p.idPersona = u.fkPersona and typeUsuario="Aspirante";

--vista Agente
CREATE OR REPLACE View Vw_Agente as
select idPersona as persona,firstNamePersona as names, lastNamePersona as paterns,generoPersona as genero,photoPersona as photo,
if(p.photoPersona is null,'NULL',(select urlImagen from Tb_Imagenes as i,Tb_Personas where i.idImagen=p.photoPersona limit 1)) as photoUrl,
emailUsuario as email, cambiarPasswordUsuario as cambiarP, typeUsuario,statusUsuario as statusU,
idUsuario as usuario,
CASE
    WHEN typeUsuario="Agente" THEN
        (select idAgente from Tb_Agentes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as agente,
CASE
    WHEN typeUsuario="Agente" THEN
        (select numeroEmpleado from Tb_Agentes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as numeroEmpleado,
CASE
    WHEN typeUsuario="Agente" THEN
        (select puestoAgente from Tb_Agentes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as puesto
from Tb_Personas as p, Tb_Usuarios as u
where p.idPersona = u.fkPersona and typeUsuario="Agente";

--vista admin
CREATE OR REPLACE View Vw_Admin as
select idPersona as persona,firstNamePersona as names, lastNamePersona as paterns,generoPersona as genero,photoPersona as photo,
if(p.photoPersona is null,'NULL',(select urlImagen from Tb_Imagenes as i,Tb_Personas where i.idImagen=p.photoPersona limit 1)) as photoUrl,
emailUsuario as email, cambiarPasswordUsuario as cambiarP, typeUsuario,statusUsuario as statusU,
idUsuario as usuario
from Tb_Personas as p, Tb_Usuarios as u
where p.idPersona = u.fkPersona and typeUsuario="Admin";

--vista Aspirante

CREATE OR REPLACE View Vw_Admin as
select idPersona as persona,firstNamePersona as names, lastNamePersona as paterns,generoPersona as genero,photoPersona as photo,
if(p.photoPersona is null,'NULL',(select urlImagen from Tb_Imagenes as i,Tb_Personas where i.idImagen=p.photoPersona limit 1)) as photoUrl,
emailUsuario as email, cambiarPasswordUsuario as cambiarP, typeUsuario,statusUsuario as statusU,
idUsuario as usuario
from Tb_Personas as p, Tb_Usuarios as u
where p.idPersona = u.fkPersona and typeUsuario="Admin";

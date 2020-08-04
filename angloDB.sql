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
    statusUsuario enum('Activo','Inactivo','Pendiente') default 'Activo',
    tipoRegistro enum('WEB','ASF','BC','CON','EM','EUR','FB','FPP','OTH','REC','RS','TEC','TEL','UNAM','UNI','UP') default 'WEB' not null,
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
    programaDeInteres enum('Universidad','Preparatoria','CursoIngles','CursoVerano'),
    statusAspirante enum('0','1','2') default '0',
    statusAspiranteControl enum('Activo','Inactivo') default 'Activo',
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

CREATE OR REPLACE View Vw_Aspirante as
select idPersona as persona,firstNamePersona as names, lastNamePersona as paterns,concat(firstNamePersona,' ',lastNamePersona) as fullname,generoPersona as genero,photoPersona as photo,
if(p.photoPersona is null,'NULL',(select urlImagen from Tb_Imagenes as i,Tb_Personas where i.idImagen=p.photoPersona limit 1)) as photoUrl,
emailUsuario as email, cambiarPasswordUsuario as cambiarP, typeUsuario,statusUsuario as statusU,tipoRegistro,
idUsuario as usuario,typeOauthUsuario,MONTH(creationDateUsuario) as mesCreation,YEAR(creationDateUsuario)  as yearCreation,concat(MONTH(creationDateUsuario),'-',YEAR(creationDateUsuario)) as completeFecha,
CASE
    WHEN typeUsuario="Aspirante" THEN
        (select idAspirante from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as aspirante,
CASE
    WHEN typeUsuario="Aspirante" THEN
        (select concat(p.firstNamePersona,' ',p.lastNamePersona,' ',a.idAspirante) from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as aspiranteIdCarpeta,
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
END as ciudad,
CASE
    WHEN typeUsuario="Aspirante" THEN
        (select statusAspirante from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as statusAspirante,
CASE
WHEN typeUsuario="Aspirante" THEN
   (select programaDeInteres from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as programaDeInteres,
CASE
WHEN typeUsuario="Aspirante" THEN
   (select statusAspiranteControl from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as statusAspiranteControl
from Tb_Personas as p, Tb_Usuarios as u
where p.idPersona = u.fkPersona and typeUsuario="Aspirante";


CREATE OR REPLACE View Vw_Agente as
select idPersona as persona,firstNamePersona as names, lastNamePersona as paterns,concat(firstNamePersona,' ',lastNamePersona) as fullname,generoPersona as genero,photoPersona as photo,
if(p.photoPersona is null,'NULL',(select urlImagen from Tb_Imagenes as i,Tb_Personas where i.idImagen=p.photoPersona limit 1)) as photoUrl,
emailUsuario as email, cambiarPasswordUsuario as cambiarP, typeUsuario,statusUsuario as statusU,
idUsuario as usuario,typeOauthUsuario,
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


CREATE OR REPLACE View Vw_Admin as
select idPersona as persona,firstNamePersona as names, lastNamePersona as paterns,generoPersona as genero,photoPersona as photo,
if(p.photoPersona is null,'NULL',(select urlImagen from Tb_Imagenes as i,Tb_Personas where i.idImagen=p.photoPersona limit 1)) as photoUrl,
emailUsuario as email, cambiarPasswordUsuario as cambiarP, typeUsuario,statusUsuario as statusU,
idUsuario as usuario
from Tb_Personas as p, Tb_Usuarios as u
where p.idPersona = u.fkPersona and typeUsuario="Admin";

CREATE OR REPLACE View Vw_Permiso as
select idPermisoAgente, fkPermiso as permiso, fkAgente as agente,
nombrePermiso,estatusPermiso
from Tb_Agentes as a, Tb_Permisos as p, Tb_Permisos_Agentes as pa
where pa.fkAgente = a.idAgente and pa.fkPermiso = p.idPermiso;

CREATE TABLE Tb_Institucion(
    idInstitucion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombreInstitucion VARCHAR(120) NOT NULL,
    logoInstitucion text,
    ubicacionInstitucion text,
    statusInstitucion enum('Activo','Inactivo') default 'Activo',
    creationDateInstitucion timestamp default current_timestamp,
    lastUpdateInstitucion timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_Facultad(
    idFacultad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombreFacultad VARCHAR(120) NOT NULL,
    abreviacionFacultad VARCHAR(10) NOT NULL,
    statusFacultad enum('Activo','Inactivo') default 'Activo',
    creationDateFacultad timestamp default current_timestamp,
    lastUpdateFacultad timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_InstitucionFacultad(
    idInstitucionFacultad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkFacultad INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkFacultad) REFERENCES Tb_Facultad(idFacultad) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    statusInstitucionFacultad enum('Activo','Inactivo') default 'Activo',
    creationInstitucionFacultad timestamp default current_timestamp,
    lastUpdateInstitucionFacultad timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_TipoEstudio(
    idTipoEstudio INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombreTipoEstudio VARCHAR(120) NOT NULL,
    abreviacionTipoEstudio VARCHAR(10) NOT NULL,
    statusInstitucionTipoEstudio enum('Activo','Inactivo') default 'Activo',
    creationInstitucionTipoEstudio timestamp default current_timestamp,
    lastUpdateInstitucionTipoEstudio timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_TipoEstudioInstituccion(
    idTipoEstudioInstituccion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkTipoEstudio INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkTipoEstudio) REFERENCES Tb_TipoEstudio(idTipoEstudio) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    statusTipoEstudioInstituccion enum('Activo','Inactivo') default 'Activo',
    creationTipoEstudioInstituccion timestamp default current_timestamp,
    lastUpdateTipoEstudioInstituccion timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW Vw_tipoEstudio as
select nombreTipoEstudio,abreviacionTipoEstudio,nombreInstitucion,logoInstitucion,
ubicacionInstitucion,idInstitucion,idTipoEstudio,idTipoEstudioInstituccion
from Tb_Institucion as i, Tb_TipoEstudio as te, Tb_TipoEstudioInstituccion as tei
where tei.fkTipoEstudio=te.idTipoEstudio and tei.fkInstitucion=i.idInstitucion;

CREATE TABLE Tb_TipoAlojamiento(
    idTipoAlojamiento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombreTipoAlojamiento VARCHAR(120) NOT NULL,
    abreviacionTipoAlojamiento VARCHAR(10) NOT NULL,
    statusTipoTipoAlojamiento enum('Activo','Inactivo') default 'Activo',
    creationTipoAlojamiento timestamp default current_timestamp,
    lastUpdateTipoAlojamiento timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_TipoAlojamientoInstitucion(
    idTipoAlojamientoInstitucion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkTipoAlojamiento INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkTipoAlojamiento) REFERENCES Tb_TipoAlojamiento(idTipoAlojamiento) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade
);

CREATE OR REPLACE VIEW Vw_tipoAlojamiento as
select nombreTipoAlojamiento,abreviacionTipoAlojamiento,nombreInstitucion,logoInstitucion,
ubicacionInstitucion,idInstitucion,idTipoAlojamiento,idTipoAlojamientoInstitucion
from Tb_Institucion as i, Tb_TipoAlojamiento as ta, Tb_TipoAlojamientoInstitucion as tai
where tai.fkTipoAlojamiento=ta.idTipoAlojamiento and tai.fkInstitucion=i.idInstitucion;

INSERT INTO Tb_Institucion(nombreInstitucion,logoInstitucion,ubicacionInstitucion) VALUES('University of Bath','https://upload.wikimedia.org/wikipedia/en/thumb/c/ca/University_of_Bath_logo.svg/1200px-University_of_Bath_logo.svg.png','test');
INSERT INTO Tb_Institucion(nombreInstitucion,logoInstitucion) VALUES('University of Bristol',1);
INSERT INTO Tb_Institucion(nombreInstitucion,logoInstitucion) VALUES('Cambridge School of Visual & Performing Arts',1);
INSERT INTO Tb_Institucion(nombreInstitucion,logoInstitucion) VALUES('Concord College',1);

INSERT INTO Tb_Facultad(nombreFacultad,abreviacionFacultad) VALUES('Arquitectura','AR');
INSERT INTO Tb_Facultad(nombreFacultad,abreviacionFacultad) VALUES('Artes y Humanidades','AH');
INSERT INTO Tb_Facultad(nombreFacultad,abreviacionFacultad) VALUES('Ciencias','C');
INSERT INTO Tb_Facultad(nombreFacultad,abreviacionFacultad) VALUES('Ciencias De La Salud','CS');
INSERT INTO Tb_Facultad(nombreFacultad,abreviacionFacultad) VALUES('Ciencias Sociales','SOC');
INSERT INTO Tb_Facultad(nombreFacultad,abreviacionFacultad) VALUES('Ingenieria','I');
INSERT INTO Tb_Facultad(nombreFacultad,abreviacionFacultad) VALUES('Medicina','M');
INSERT INTO Tb_Facultad(nombreFacultad,abreviacionFacultad) VALUES('Negocios','N');

INSERT INTO Tb_TipoEstudio (nombreTipoEstudio,abreviacionTipoEstudio) VALUES('Año Academico','A');
INSERT INTO Tb_TipoEstudio (nombreTipoEstudio,abreviacionTipoEstudio) VALUES('Prepa Completa','P');
INSERT INTO Tb_TipoEstudio (nombreTipoEstudio,abreviacionTipoEstudio) VALUES('Semestre Academico','S');

INSERT INTO Tb_TipoAlojamiento (nombreTipoAlojamiento,abreviacionTipoAlojamiento) VALUES('Con familia','F');
INSERT INTO Tb_TipoAlojamiento (nombreTipoAlojamiento,abreviacionTipoAlojamiento) VALUES('Internado','I');
INSERT INTO Tb_TipoAlojamiento (nombreTipoAlojamiento,abreviacionTipoAlojamiento) VALUES('Residencial','R');

INSERT INTO Tb_InstitucionFacultad (fkFacultad,fkInstitucion) VALUES(1,1);
INSERT INTO Tb_InstitucionFacultad (fkFacultad,fkInstitucion) VALUES(3,1);
INSERT INTO Tb_InstitucionFacultad (fkFacultad,fkInstitucion) VALUES(4,1);
INSERT INTO Tb_InstitucionFacultad (fkFacultad,fkInstitucion) VALUES(5,1);
INSERT INTO Tb_InstitucionFacultad (fkFacultad,fkInstitucion) VALUES(6,1);
INSERT INTO Tb_InstitucionFacultad (fkFacultad,fkInstitucion) VALUES(8,1);
INSERT INTO Tb_InstitucionFacultad (fkFacultad,fkInstitucion) VALUES(2,3);

INSERT INTO Tb_TipoEstudioInstituccion (fkTipoEstudio,fkInstitucion) VALUES(1,3);
INSERT INTO Tb_TipoEstudioInstituccion (fkTipoEstudio,fkInstitucion) VALUES(2,3);
INSERT INTO Tb_TipoEstudioInstituccion (fkTipoEstudio,fkInstitucion) VALUES(3,3);
INSERT INTO Tb_TipoEstudioInstituccion (fkTipoEstudio,fkInstitucion) VALUES(1,4);
INSERT INTO Tb_TipoEstudioInstituccion (fkTipoEstudio,fkInstitucion) VALUES(2,4);

INSERT INTO Tb_TipoAlojamientoInstitucion (fkTipoAlojamiento,fkInstitucion) VALUES(3,3);
INSERT INTO Tb_TipoAlojamientoInstitucion (fkTipoAlojamiento,fkInstitucion) VALUES(2,4);

CREATE OR REPLACE VIEW Vw_Uni as
select idInstitucion,nombreInstitucion,ubicacionInstitucion,logoInstitucion,idFacultad,nombreFacultad,abreviacionFacultad,idInstitucionFacultad
from Tb_Facultad as f, Tb_Institucion as i, Tb_InstitucionFacultad as insfa
where insfa.fkFacultad = f.idFacultad and insfa.fkInstitucion=i.idInstitucion;

CREATE OR REPLACE VIEW Vw_Prep as
select idInstitucion,nombreInstitucion,logoInstitucion,ubicacionInstitucion,
idTipoEstudio,nombreTipoEstudio,abreviacionTipoEstudio,
idTipoAlojamiento,nombreTipoAlojamiento,abreviacionTipoAlojamiento,
idTipoEstudioInstituccion,idTipoAlojamientoInstitucion
FROM Tb_Institucion as i, Tb_TipoEstudio as te, Tb_TipoEstudioInstituccion as tei,
Tb_TipoAlojamiento as ta, Tb_TipoAlojamientoInstitucion as tai
where tei.fkInstitucion = i.idInstitucion and tei.fkTipoEstudio = te.idTipoEstudio
and tai.fkTipoAlojamiento = ta.idTipoAlojamiento and tai.fkInstitucion=i.idInstitucion;

CREATE TABLE Tb_Edades(
    idEdad INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombreEdad VARCHAR(50) NOT NULL,
    abreviacionEdad VARCHAR(10) NOT NULL,
    edadEdad VARCHAR(30) NOT NULL,
    CDEdad timestamp default current_timestamp,
    LUEdad timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_EdadesInstituciones(
    idEdadInstitucion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkEdad INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkEdad) REFERENCES Tb_Edades(idEdad) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    CDEdadInstitucion timestamp default current_timestamp,
    LUEdadInstitucion timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW Vw_EdadesInst as select idEdadInstitucion as id, nombreEdad as nombre, abreviacionEdad as abreviacion, edadEdad as edad,
nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
from Tb_EdadesInstituciones as ei, Tb_Edades as e, Tb_Institucion as i
where ei.fkEdad = e.idEdad and ei.fkInstitucion= i.idInstitucion;



INSERT INTO Tb_Edades (nombreEdad,abreviacionEdad,edadEdad) VALUES('Junior','J','7-13');
INSERT INTO Tb_EdadesInstituciones (fkEdad,fkInstitucion) VALUES(1,1);

CREATE TABLE Tb_Campamentos(
    idCampamento INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombreCampamento VARCHAR(50) NOT NULL,
    abreviacionCampamento VARCHAR(10) NOT NULL,
    CDCampamento timestamp default current_timestamp,
    LUCampamento timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_CampamentosInstituciones(
    idCampamentoInstitucion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkCampamento INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkCampamento) REFERENCES Tb_Campamentos(idCampamento) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    CDCampInst timestamp default current_timestamp,
    LUCampInst timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW Vw_CampInst as select idCampamentoInstitucion as id, nombreCampamento as nombre, abreviacionCampamento as abreviacion,
nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
from Tb_CampamentosInstituciones as ei, Tb_Campamentos as e, Tb_Institucion as i
where ei.fkCampamento = e.idCampamento and ei.fkInstitucion= i.idInstitucion;

INSERT INTO Tb_Campamentos (nombreCampamento,abreviacionCampamento) VALUES('Ingles','In');
INSERT INTO Tb_CampamentosInstituciones (fkCampamento,fkInstitucion) VALUES(1,1);

CREATE TABLE Tb_AlojamientoCampamento(
    idAlojamientoCampamento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkTipoAlojamiento INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkTipoAlojamiento) REFERENCES Tb_TipoAlojamiento(idTipoAlojamiento) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    CDAlojCamp timestamp default current_timestamp,
    LUAlojCamp timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW Vw_AlojCamp as select idAlojamientoCampamento as id, nombreTipoAlojamiento as nombre, abreviacionTipoAlojamiento as abreviacion,statusTipoTipoAlojamiento as status,
nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
from Tb_AlojamientoCampamento as ei, Tb_TipoAlojamiento as e, Tb_Institucion as i
where ei.fkTipoAlojamiento = e.idTipoAlojamiento and ei.fkInstitucion= i.idInstitucion;


INSERT INTO Tb_AlojamientoCampamento (fkTipoAlojamiento,fkInstitucion) VALUES(1,1);

CREATE OR REPLACE VIEW Vw_Verano as
select nombreInstitucion,abreviacionEdad,abreviacionCampamento,abreviacionTipoAlojamiento
FROM Tb_Institucion as i, Tb_Edades as e, Tb_Campamentos as c,
Tb_TipoAlojamiento as t,
Tb_EdadesInstituciones as ei, Tb_CampamentosInstituciones as ci,
Tb_AlojamientoCampamento as ac
where ei.fkEdad = e.idEdad and ei.fkInstitucion=i.idInstitucion and
ci.fkCampamento = c.idCampamento and ci.fkInstitucion = i.idInstitucion and
ac.fkTipoAlojamiento = t.idTipoAlojamiento and ac.fkInstitucion= i.idInstitucion;

CREATE TABLE Tb_TipoCursos(
    idTipoCurso INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombreTipoCurso VARCHAR(50) NOT NULL,
    abreviacionTipoCurso VARCHAR(10) NOT NULL,
    CDTipoCurso timestamp default current_timestamp,
    LUTipoCurso timestamp default current_timestamp on update current_timestamp
);

INSERT INTO Tb_TipoCursos (nombreTipoCurso,abreviacionTipoCurso) VALUES('Ingles General','G');

CREATE TABLE Tb_TipoCursosInstituciones(
    idTipoCursosInstituciones INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkTipoCurso  INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkTipoCurso) REFERENCES Tb_TipoCursos(idTipoCurso) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    CDCurInst timestamp default current_timestamp,
    LUCurInst timestamp default current_timestamp on update current_timestamp
);



INSERT INTO Tb_TipoCursosInstituciones (fkTipoCurso,fkInstitucion) VALUES(1,1);


CREATE TABLE Tb_AlojamientoCurso(
    idAlojamientoCampamento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkTipoAlojamiento INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkTipoAlojamiento) REFERENCES Tb_TipoAlojamiento(idTipoAlojamiento) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    CDAlojCur timestamp default current_timestamp,
    LUAlojCur timestamp default current_timestamp on update current_timestamp
);

INSERT INTO Tb_AlojamientoCurso (fkTipoAlojamiento,fkInstitucion) VALUES(1,1);

CREATE OR REPLACE VIEW Vw_Ingles as
select nombreInstitucion,abreviacionTipoCurso,abreviacionTipoAlojamiento
from Tb_Institucion as i, Tb_TipoCursos as tc, Tb_TipoAlojamiento as ta,
Tb_TipoCursosInstituciones as tci,Tb_AlojamientoCurso as ac
where tci.fkTipoCurso = tc.idTipoCurso and tci.fkInstitucion =i.idInstitucion and
ac.fkTipoAlojamiento = ta.idTipoAlojamiento and ac.fkInstitucion=i.idInstitucion;

CREATE TABLE Tb_AspiranteUniversidades(
    idAspiranteUniversidad INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fkAspirante int,
    fkFacultad int,
    estudiosAspiranteUniversidad enum('Carrera','Masters','PhD'),
    anioMesIngreso date,
    carrera text,
    statusAU enum('Activo','Inactivo') default 'Activo',
    FOREIGN KEY(fkFacultad) REFERENCES Tb_Facultad(idFacultad) on update cascade on delete cascade,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    CAU timestamp default current_timestamp,
    LUAU timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_InstitucionAspiranteUniversidades(
    idInstitucionAspiranteUniversidades INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fkInstitucion int,
    fkAspiranteUniversidad int,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    FOREIGN KEY(fkAspiranteUniversidad) REFERENCES Tb_AspiranteUniversidades(idAspiranteUniversidad) on update cascade on delete cascade,
    CIAU timestamp default current_timestamp,
    LUIAU timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW Vw_AspiranteUniversidad as
select nombreFacultad,abreviacionFacultad,idFacultad,
fkAspirante,estudiosAspiranteUniversidad,anioMesIngreso,idAspiranteUniversidad,statusAU,
YEAR(anioMesIngreso) AS anio,MONTHNAME(anioMesIngreso) AS mes,carrera
from Tb_Facultad as f, Tb_Aspirantes as a, Tb_AspiranteUniversidades as au
where au.fkAspirante = a.idAspirante and au.fkFacultad = f.idFacultad;

CREATE OR REPLACE VIEW Vw_AspiranteInstituciones as
select nombreInstitucion,logoInstitucion,ubicacionInstitucion,
idAspiranteUniversidad,idInstitucion
from Tb_Institucion as i , Tb_AspiranteUniversidades as au,
Tb_InstitucionAspiranteUniversidades as iau
where iau.fkInstitucion = i.idInstitucion and iau.fkAspiranteUniversidad = au.idAspiranteUniversidad;

CREATE TABLE Tb_AspirantePreparatorias(
    idAspirantePreparatoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fkTipoEstudio int,
    fkTipoAlojamiento int,
    anioMesIngreso date,
    carrera text,
    fkAspirante int,
    statusAP enum('Activo','Inactivo') default 'Activo',
    FOREIGN KEY(fkTipoEstudio) REFERENCES Tb_TipoEstudio(idTipoEstudio) on update cascade on delete cascade,
    FOREIGN KEY(fkTipoAlojamiento) REFERENCES Tb_TipoAlojamiento(idTipoAlojamiento) on update cascade on delete cascade,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    CIAP timestamp default current_timestamp,
    LUIAP timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_InstitucionAspirantePreparatorias(
    idInstitucionAspirantePreparatorias INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fkInstitucion int,
    fkAspirantePreparatoria int,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    FOREIGN KEY(fkAspirantePreparatoria) REFERENCES Tb_AspirantePreparatorias(idAspirantePreparatoria) on update cascade on delete cascade,
    CIAU timestamp default current_timestamp,
    LUIAU timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW Vw_AspirantePreparatoria as
select
nombreTipoEstudio,abreviacionTipoEstudio,idTipoEstudio,
nombreTipoAlojamiento,abreviacionTipoAlojamiento,idTipoAlojamiento,
anioMesIngreso,fkAspirante,idAspirantePreparatoria,YEAR(anioMesIngreso) AS anio,MONTHNAME(anioMesIngreso) AS mes,carrera
from Tb_TipoEstudio as te,Tb_TipoAlojamiento as ta, Tb_AspirantePreparatorias as ap,
Tb_Aspirantes as a
where ap.fkAspirante = a.idAspirante and ap.fkTipoEstudio = te.idTipoEstudio and
ap.fkTipoAlojamiento = ta.idTipoAlojamiento;

CREATE TABLE Tb_EventosImages(
    idEventoI int not null AUTO_INCREMENT PRIMARY KEY,
	urlEventoI text not null,
	typeEventoI varchar(15),
	extEventoI varchar(10),
    nombreEventoI text,
	statusEventoI enum('Activo','Inactivo') default 'Activo',
	creationDateEventoI timestamp default current_timestamp,
	lastUpdateEventoI timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_Eventos(
    idEvento int not null AUTO_INCREMENT PRIMARY KEY,
    tituloEvento text not null,
    fechaEvento varchar(30) not null,
    horarioEvento VARCHAR(20) not null,
    descEvento text,
    urlEvento text not null,
    fkEventoI int,
    statusEvento enum('Activo','Inactivo') default 'Activo',
	creationDateEvento timestamp default current_timestamp,
	lastUpdateEvento timestamp default current_timestamp on update current_timestamp,
    FOREIGN KEY(fkEventoI) REFERENCES Tb_EventosImages(idEventoI) on update cascade on delete cascade
);

CREATE OR REPLACE View Vw_Eventos as
select
tituloEvento as titulo,fechaEvento as fecha,
horarioEvento as horario,descEvento as descE,
statusEvento,urlEvento as urlE,idEvento,
if(e.fkEventoI is null,'NULL',(select idEventoI from Tb_EventosImages as ei,Tb_Eventos where ei.idEventoI=e.fkEventoI limit 1)) as idEventoI,
if(e.fkEventoI is null,'NULL',(select urlEventoI from Tb_EventosImages as ei,Tb_Eventos where ei.idEventoI=e.fkEventoI limit 1)) as urlImagen
from Tb_Eventos as e
order by idEvento desc;

CREATE TABLE Tb_Documentos(
    idReal int not null AUTO_INCREMENT primary key,
    idDocumento varchar(100) not null unique,
    nameDocumento text,
    sizeDocumento int,
    pathDisplayDocumento text,
    pathLowerDocumento text,
    contentHashDocumento text,
    clientModifiedDocumento text,
    verUrlDocumento text,
    revDocumento text,
    nameCarpeta text,
    idCarpeta text,
    pathDisplayCarpeta text,
    pathLowerCarpeta text,
    fkAspirante int,
    tipoDocumento enum('Carrera','Masters','PhD','Preparatoria') not null,
    tipo enum('Transcripcion','CartaMotivo','CartaRecomendacion','Boleta','Pasaporte','Propuesta','CV','CartaAutorizacion','FormatoSolicitud') not null,
	statusDocumento enum('Pendiente','Rechazado','Aceptado') default 'Pendiente',
    descDocumento text,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW View_Aspirantes_Uni_Prepa as
select * from Vw_Aspirante where programaDeInteres="Universidad" or programaDeInteres="Preparatoria" or programaDeInteres is null;

CREATE TABLE Tb_DocumentosAgente(
	idDA int not null AUTO_INCREMENT PRIMARY KEY,
	urlDA text not null,
    nameDocumento text,
	typeDA varchar(15),
	extDA varchar(10),
	statusDA enum('Activo','Inactivo') default 'Activo',
    fkAspirante int,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
	creationDateDA timestamp default current_timestamp,
	lastUpdateDA timestamp default current_timestamp on update current_timestamp
);

ALTER TABLE Tb_InstitucionAspiranteUniversidades ADD numeroAceptacion varchar(50);
ALTER TABLE Tb_InstitucionAspiranteUniversidades ADD fechaAceptacion text;

CREATE OR REPLACE VIEW Vw_AspiranteInstituciones as
select nombreInstitucion,logoInstitucion,ubicacionInstitucion,
idAspiranteUniversidad,idInstitucion,numeroAceptacion,idInstitucionAspiranteUniversidades,fechaAceptacion
from Tb_Institucion as i , Tb_AspiranteUniversidades as au,
Tb_InstitucionAspiranteUniversidades as iau
where iau.fkInstitucion = i.idInstitucion and iau.fkAspiranteUniversidad = au.idAspiranteUniversidad;

CREATE OR REPLACE VIEW View_Aspirantes_By_Status as
select * from View_Aspirantes_Uni_Prepa
where statusAspirante='2' OR statusAspirante='2R' OR statusAspirante='3' OR statusAspirante='4U' OR statusAspirante='4C' OR statusAspirante='D';

ALTER TABLE  Tb_Documentos change tipo tipo enum('Transcripcion','CartaMotivo','CartaRecomendacion','Boleta','Pasaporte','Propuesta','CV','CartaAutorizacion','FormatoSolicitud','examenIngles','calificacionFinal','visa','TranscripcionFinal','ATAS','Titulo') not null;

ALTER TABLE Tb_InstitucionAspirantePreparatorias ADD numeroAceptacion varchar(50);
ALTER TABLE Tb_InstitucionAspirantePreparatorias ADD fechaAceptacion text;

CREATE OR REPLACE VIEW Vw_AspiranteInstitucionesPrepas as
select nombreInstitucion,logoInstitucion,ubicacionInstitucion,
idAspirantePreparatoria,idInstitucion,idInstitucionAspirantePreparatorias,numeroAceptacion,fechaAceptacion
from Tb_Institucion as i , Tb_AspirantePreparatorias as ap,
Tb_InstitucionAspirantePreparatorias as iap
where iap.fkInstitucion = i.idInstitucion and iap.fkAspirantePreparatoria = ap.idAspirantePreparatoria;

ALTER TABLE Tb_Aspirantes change statusAspirante statusAspirante enum('0','1','2','2R','3','4C','4U','5','D') default '0';

CREATE TABLE Tb_DocumentosOfertaCU(
    idReal int not null AUTO_INCREMENT primary key,
    idDocumento varchar(100) not null unique,
    nameDocumento text,
    sizeDocumento int,
    pathDisplayDocumento text,
    pathLowerDocumento text,
    contentHashDocumento text,
    clientModifiedDocumento text,
    verUrlDocumento text,
    revDocumento text,
    nameCarpeta text,
    fkAspirante int,
    fkInstitucionAspiranteUniversidades int,
    statusDocumento enum('Pendiente','Aceptado','Rechazado') default 'Pendiente',
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucionAspiranteUniversidades) REFERENCES Tb_InstitucionAspiranteUniversidades(idInstitucionAspiranteUniversidades) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

ALTER TABLE Tb_DocumentosOfertaCU ADD statusAspirante enum('U','C');

CREATE OR REPLACE VIEW Vw_UnisOfertaAspirantes as
select ai.*,do.* from Vw_AspiranteInstituciones as ai
left join Tb_DocumentosOfertaCU as do on ai.idInstitucionAspiranteUniversidades = do.fkInstitucionAspiranteUniversidades
order by idInstitucionAspiranteUniversidades;

CREATE TABLE Tb_DocumentosOfertaCUPrepa(
    idReal int not null AUTO_INCREMENT primary key,
    idDocumento varchar(100) not null unique,
    nameDocumento text,
    sizeDocumento int,
    pathDisplayDocumento text,
    pathLowerDocumento text,
    contentHashDocumento text,
    clientModifiedDocumento text,
    verUrlDocumento text,
    revDocumento text,
    nameCarpeta text,
    fkAspirante int,
    fkInstitucionAspirantePreparatorias int,
    statusDocumento enum('Pendiente','Aceptado','Rechazado') default 'Pendiente',
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucionAspirantePreparatorias) REFERENCES Tb_InstitucionAspirantePreparatorias(idInstitucionAspirantePreparatorias) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

ALTER TABLE Tb_DocumentosOfertaCUPrepa ADD statusAspirante enum('U','C');


CREATE OR REPLACE VIEW Vw_UnisOfertaAspirantesPrepa as
select ai.*,do.* from Vw_AspiranteInstitucionesPrepas as ai
left join Tb_DocumentosOfertaCUPrepa as do on ai.idInstitucionAspirantePreparatorias = do.fkInstitucionAspirantePreparatorias
order by idInstitucionAspirantePreparatorias;


CREATE TABLE Tb_DocumentosVisa(
    idReal int not null AUTO_INCREMENT primary key,
    idDocumento varchar(100) not null unique,
    nameDocumento text,
    sizeDocumento int,
    pathDisplayDocumento text,
    pathLowerDocumento text,
    contentHashDocumento text,
    clientModifiedDocumento text,
    verUrlDocumento text,
    revDocumento text,
    nameCarpeta text,
    fkAspirante int,
    statusDocumento enum('Pendiente','Aceptado') default 'Pendiente',
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

ALTER TABLE Tb_Documentos add column BoletaTipo enum('Final','Incompleta');

CREATE TABLE Tb_NecesidadesBecas(
    idNB int not null AUTO_INCREMENT primary key,
    descNB text
);

INSERT INTO Tb_NecesidadesBecas(descNB) VALUES('CAS - documento digital emitido por la Universidad que especifica los documentos a presentar para la solicitud para la visa');
INSERT INTO Tb_NecesidadesBecas(descNB) VALUES('Evidencia de fondos - beca, fondos personales, pueden ser mas de un documento');

CREATE TABLE Tb_NBAspirante(
    idNBAAspirante int NOT null AUTO_INCREMENT PRIMARY key,
    fkAspirante INT,
    fkNB INT,
    statusNB enum('Pendiente','Rechazado','Aceptado') DEFAULT 'Pendiente',
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    FOREIGN KEY(fkNB) REFERENCES Tb_NecesidadesBecas(idNB) on update cascade on delete cascade
);

CREATE OR REPLACE VIEW Vw_NBA as
select nb.*,nba.*
from Tb_NBAspirante as nba , Tb_NecesidadesBecas as nb
where nba.fkNB = nb.idNB;

CREATE TABLE Tb_DocVisa(
    idReal int not null AUTO_INCREMENT primary key,
    idDocumento varchar(100) not null unique,
    nameDocumento text,
    sizeDocumento int,
    pathDisplayDocumento text,
    pathLowerDocumento text,
    contentHashDocumento text,
    clientModifiedDocumento text,
    verUrlDocumento text,
    revDocumento text,
    nameCarpeta text,
    fkAspirante int,
    descDocumento text,
    statusDocumento enum('Pendiente','Aceptado','Rechazado') default 'Pendiente',
    tipoDocumento enum('Visa','ATAS'),
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE AplicaATASAspirante(
    idAAA INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    aplicaATAS enum('Si','No'),
    fkAspirante int,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_DocDeferral(
    idReal int not null AUTO_INCREMENT primary key,
    idDocumento varchar(100) not null unique,
    nameDocumento text,
    sizeDocumento int,
    pathDisplayDocumento text,
    pathLowerDocumento text,
    contentHashDocumento text,
    clientModifiedDocumento text,
    verUrlDocumento text,
    revDocumento text,
    nameCarpeta text,
    pathDisplayCarpeta text,
    pathLowerCarpeta text,
    fkAspirante int,
    fkInstitucion int,
    logo text,
    statusDocumento enum('Pendiente','Aceptado','Rechazado') default 'Pendiente',
    keyDocumentoEliminar text,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW View_Aspirantes_By_Status5 as
select * from View_Aspirantes_Uni_Prepa
where statusAspirante='5';

CREATE OR REPLACE VIEW View_DocDefereal as
select * from Tb_DocDeferral as df, Tb_Institucion as i
where df.fkInstitucion = i.idInstitucion;

CREATE TABLE AplicaBecasAspirante(
    idABA INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    aplicaBecas enum('Si','No'),
    fkAspirante int,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_DocBecasUP(
    idReal int not null AUTO_INCREMENT primary key,
    idDocumento varchar(100) not null unique,
    nameDocumento text,
    sizeDocumento int,
    pathDisplayDocumento text,
    pathLowerDocumento text,
    contentHashDocumento text,
    clientModifiedDocumento text,
    verUrlDocumento text,
    revDocumento text,
    nameCarpeta text,
    pathDisplayCarpeta text,
    pathLowerCarpeta text,
    fkAspirante int,
    descDocumento text,
    statusDocumento enum('Aceptado','Rechazado','Pendiente') default 'Pendiente',
    motivo text,
    FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
    creationDate timestamp default current_timestamp(),
	lastUpdate timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_Becas (
    idBeca int not null AUTO_INCREMENT  primary key,
    paisBeca text not null,
    nombreBeca text not null,
    linkBeca text not null,
    aperturaFechaBeca text,
    cierreFcehaBeca text,
    periodoEvaluacionBeca text,
    resultadosBecas text,
    procesoAsignacionBeca text,
    montoBeca text,
    programaBeca text,
    descripcionBeca text,
    convenioBeca text
);
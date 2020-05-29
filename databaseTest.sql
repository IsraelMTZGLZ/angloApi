DROP TABLE Tb_TipoAlojamientoInstitucion;
DROP TABLE Tb_TipoEstudioInstituccion;
DROP TABLE Tb_TipoEstudio;
DROP TABLE Tb_InstitucionFacultad;
DROP TABLE Tb_Facultad;
DROP TABLE Tb_EdadesInstituciones;
DROP TABLE Tb_Edades;
DROP TABLE Tb_CampamentosInstituciones;
DROP TABLE Tb_Campamentos;
DROP TABLE Tb_TipoCursosInstituciones;
DROP TABLE Tb_AlojamientoCurso;
DROP TABLE Tb_TipoCursos;
DROP TABLE Tb_AlojamientoCampamento;
DROP TABLE Tb_Institucion;
DROP TABLE Tb_TipoAlojamiento;

CREATE TABLE Tb_Institucion(
    idInstitucion INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombreInstitucion VARCHAR(120) NOT NULL,
    logoInstitucion int,
    ubicacionInstitucion text,
    statusInstitucion enum('Activo','Inactivo') default 'Activo',
    creationDateInstitucion timestamp default current_timestamp,
    lastUpdateInstitucion timestamp default current_timestamp on update current_timestamp,
    FOREIGN KEY(logoInstitucion) REFERENCES Tb_Imagenes(idImagen) on update cascade on delete cascade
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

--preparatoria
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

INSERT INTO Tb_Institucion(nombreInstitucion,logoInstitucion) VALUES('University of Bath',1);
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
select idInstitucion,nombreInstitucion,idFacultad,nombreFacultad,abreviacionFacultad,idInstitucionFacultad
from Tb_Facultad as f, Tb_Institucion as i, Tb_InstitucionFacultad as insfa
where insfa.fkFacultad = f.idFacultad and insfa.fkInstitucion=i.idInstitucion;

CREATE OR REPLACE VIEW Vw_Prep as
select nombreInstitucion,abreviacionTipoEstudio,abreviacionTipoAlojamiento
FROM Tb_Institucion as i, Tb_TipoEstudio as te, Tb_TipoEstudioInstituccion as tei,
Tb_TipoAlojamiento as ta, Tb_TipoAlojamientoInstitucion as tai
where tei.fkInstitucion = i.idInstitucion and tei.fkTipoEstudio = te.idTipoEstudio
and tai.fkTipoAlojamiento = ta.idTipoAlojamiento and tai.fkInstitucion=i.idInstitucion;


CREATE TABLE Tb_TipoCurso_Verano(
  int idTcv int primary key auto_increment,
  tipocursoVerano enum ('Cursos académico de verano','Curso de verano de Inglés'),
  CDEdad timestamp default current_timestamp,
  LUEdad timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_TipoInstitucion(
  int idTipoInstitucion int primary key auto_increment,
  tipoInstitucion enum('Académico','Inglés','Ninguno') default 'Ninguno',
  CDEdad timestamp default current_timestamp,
  LUEdad timestamp default current_timestamp on update current_timestamp
);

--Campamento de verano
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

CREATE OR REPLACE VIEW Vw_EdadesInst as select idEdadInstitucion as idEdadInstitucion, idEdad as idEdad ,nombreEdad as nombre, abreviacionEdad as abreviacion, edadEdad as edad,
idInstitucion as idInstitucion,nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
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

CREATE OR REPLACE VIEW Vw_CampInst as select idCampamentoInstitucion as idCampamentoInstitucion,idCampamento as idCampamento, nombreCampamento as nombre, abreviacionCampamento as abreviacion,
idInstitucion as idInstitucion,nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
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

CREATE OR REPLACE VIEW Vw_AlojInst as select idAlojamientoCampamento,idTipoAlojamiento, nombreTipoAlojamiento as nombre, abreviacionTipoAlojamiento as abreviacion,statusTipoTipoAlojamiento as status,
idInstitucion,nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
from Tb_AlojamientoCampamento as ei, Tb_TipoAlojamiento as e, Tb_Institucion as i
where ei.fkTipoAlojamiento = e.idTipoAlojamiento and ei.fkInstitucion= i.idInstitucion;


INSERT INTO Tb_AlojamientoCampamento (fkTipoAlojamiento,fkInstitucion) VALUES(1,1);

CREATE OR REPLACE VIEW Vw_Verano as
select idInstitucion,nombreInstitucion,idEdad,abreviacionEdad,nombreEdad, edadEdad,idCampamento,abreviacionCampamento,nombreCampamento,idTipoAlojamiento,nombreTipoAlojamiento,abreviacionTipoAlojamiento
FROM Tb_Institucion as i, Tb_Edades as e, Tb_Campamentos as c,
Tb_TipoAlojamiento as t,
Tb_EdadesInstituciones as ei, Tb_CampamentosInstituciones as ci,
Tb_AlojamientoCampamento as ac
where ei.fkEdad = e.idEdad and ei.fkInstitucion=i.idInstitucion and
ci.fkCampamento = c.idCampamento and ci.fkInstitucion = i.idInstitucion and
ac.fkTipoAlojamiento = t.idTipoAlojamiento and ac.fkInstitucion= i.idInstitucion;

--curso de ingles
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

CREATE OR REPLACE VIEW Vw_CursoInst as select idTipoCursosInstituciones,idTipoCurso, nombreTipoCurso as nombre, abreviacionTipoCurso as abreviacion,
idInstitucion,nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
from Tb_TipoCursosInstituciones as ei, Tb_TipoCursos as e, Tb_Institucion as i
where ei.fkTipoCurso = e.idTipoCurso and ei.fkInstitucion= i.idInstitucion;


INSERT INTO Tb_TipoCursosInstituciones (fkTipoCurso,fkInstitucion) VALUES(1,1);

CREATE TABLE Tb_TipoAlojamientoCurso(
    idTipoAlojamiento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombreTipoAlojamiento VARCHAR(120) NOT NULL,
    abreviacionTipoAlojamiento VARCHAR(10) NOT NULL,
    statusTipoTipoAlojamiento enum('Activo','Inactivo') default 'Activo',
    creationTipoAlojamiento timestamp default current_timestamp,
    lastUpdateTipoAlojamiento timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_AlojamientoCurso(
    idAlojamientoCampamento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fkTipoAlojamiento INT NOT NULL,
    fkInstitucion INT NOT NULL,
    FOREIGN KEY(fkTipoAlojamiento) REFERENCES Tb_TipoAlojamientoCurso(idTipoAlojamiento) on update cascade on delete cascade,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    CDAlojCur timestamp default current_timestamp,
    LUAlojCur timestamp default current_timestamp on update current_timestamp
);

INSERT INTO Tb_AlojamientoCurso (fkTipoAlojamiento,fkInstitucion) VALUES(1,1);

CREATE OR REPLACE VIEW Vw_AlojInstIngles as select idAlojamientoCampamento,idTipoAlojamiento, nombreTipoAlojamiento as nombre, abreviacionTipoAlojamiento as abreviacion,statusTipoTipoAlojamiento as status,
idInstitucion,nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
from Tb_AlojamientoCurso as ei, Tb_TipoAlojamientoCurso as e, Tb_Institucion as i
where ei.fkTipoAlojamiento = e.idTipoAlojamiento and ei.fkInstitucion= i.idInstitucion;


CREATE OR REPLACE VIEW Vw_Ingles as
select nombreInstitucion,abreviacionTipoCurso,abreviacionTipoAlojamiento
from Tb_Institucion as i, Tb_TipoCursos as tc, Tb_TipoAlojamiento as ta,
Tb_TipoCursosInstituciones as tci,Tb_AlojamientoCurso as ac
where tci.fkTipoCurso = tc.idTipoCurso and tci.fkInstitucion =i.idInstitucion and
ac.fkTipoAlojamiento = ta.idTipoAlojamiento and ac.fkInstitucion=i.idInstitucion;

CREATE TABLE Tb_Aspirante_E_C_A_I(
  idACAI int primary key auto_increment,
  fkAspirante int,
  fkEdad int,
  fkCampamento int,
  fkAlojamiento int,
  fkInstitutoOne int,
  fkInstitutoTwo int,
  fkInstitutoThree int,
  anioMesIngreso date,
  FOREIGN KEY(fkAlojamiento) REFERENCES Tb_TipoAlojamiento(idTipoAlojamiento) on update cascade on delete cascade,
  FOREIGN KEY(fkEdad) REFERENCES Tb_Edades(idEdad) on update cascade on delete cascade,
  FOREIGN KEY(fkInstitutoOne) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkInstitutoTwo) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkInstitutoThree) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkCampamento) REFERENCES Tb_Campamentos(idCampamento) on update cascade on delete cascade,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp
);

CREATE OR REPLACE VIEW Vw_AspiranteVerano AS SELECT
from idPersona as persona,firstNamePersona as names, lastNamePersona as paterns,concat(firstNamePersona,' ',lastNamePersona) as fullname,generoPersona as genero,photoPersona as photo,
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
END as ciudad,
CASE
WHEN typeUsuario="Aspirante" THEN
   (select programaDeInteres from Tb_Aspirantes as a,Tb_Personas where a.fkPersona = p.idPersona limit 1)
END as programaDeInteres
from Tb_Personas as p, Tb_Usuarios as u
where p.idPersona = u.fkPersona and typeUsuario="Aspirante";

CREATE OR REPLACE VIEW Vw_InfoVeranoInsSelect AS SELECT idACAI, idAspirante, idEdad, idCampamento, idTipoAlojamiento, fkInstitutoOne, fkInstitutoTwo, fkInstitutoThree
from Tb_Aspirante_E_C_A_I as ae, Tb_TipoAlojamiento as ta, Tb_Edades as e, Tb_Institucion as i, Tb_Campamentos as c, Tb_Aspirantes as a
where ae.fkAspirante = a.idAspirante and ae.fkEdad = e.idEdad and ae.fkCampamento = c.idCampamento and ae.fkAlojamiento = ta.idTipoAlojamiento and ae.fkInstitutoOne = i.idInstitucion ;

CREATE OR REPLACE VIEW Vw_InfoVeranoApirante AS SELECT idACAI, idAspirante, idEdad, nombreEdad, abreviacionEdad, edadEdad, idCampamento, nombreCampamento, abreviacionCampamento, idTipoAlojamiento, nombreTipoAlojamiento, abreviacionTipoAlojamiento
from Tb_Aspirante_E_C_A_I as ae, Tb_TipoAlojamiento as ta, Tb_Edades as e, Tb_Institucion as i, Tb_Campamentos as c, Tb_Aspirantes as a
where ae.fkAspirante = a.idAspirante and ae.fkEdad = e.idEdad and ae.fkCampamento = c.idCampamento and ae.fkAlojamiento = ta.idTipoAlojamiento and ae.fkInstitutoOne = i.idInstitucion ;


CREATE OR REPLACE VIEW Vw_InfoVeranoFoto AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante,urlImagen
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u, Tb_Imagenes as i
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and p.photoPersona= i.idImagen;

CREATE OR REPLACE VIEW Vw_InfoVerano AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona ;


CREATE OR REPLACE VIEW Vw_VeranoStatusCero AS SELECT  firstNamePersona, lastNamePersona, generoPersona,emailUsuario, statusUsuario
from Tb_Personas as p, Tb_Usuarios as u
where   u.fkPersona = p.idPersona and u.statusUsuario = 'Activo';


CREATE OR REPLACE VIEW Vw_VeranoStatusUno AS SELECT  idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from  Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where  a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVerano' ;

CREATE OR REPLACE VIEW Vw_VeranoStatusDos AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVerano' ;

CREATE TABLE Tb_DocumentosVerano(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo enum('Boleta','CartaMotivo','Pasaporte') not null,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);



CREATE TABLE Tb_Aspirante_Ingles_C_A_I(
  idACAI int primary key auto_increment,
  fkAspirante int,
  fkCurso int NOT NULL,
  fkAlojamiento int,
  fkInstitutoOne int,
  fkInstitutoTwo int,
  fkInstitutoThree int,
  anioMesIngreso date,
  FOREIGN KEY(fkAlojamiento) REFERENCES Tb_TipoAlojamientoCurso(idTipoAlojamiento) on update cascade on delete cascade,
  FOREIGN KEY(fkInstitutoOne) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkInstitutoTwo) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkInstitutoThree) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkCurso) REFERENCES Tb_TipoCursos(idTipoCurso) on update cascade on delete cascade,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_RecomendacionAspirante(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);


CREATE OR REPLACE VIEW Vw_InglesInst as
select idInstitucion,nombreInstitucion,idTipoCurso,abreviacionTipoCurso,nombreTipoCurso,idTipoAlojamiento,nombreTipoAlojamiento,abreviacionTipoAlojamiento
FROM Tb_Institucion as i, Tb_TipoCursos as c,
Tb_TipoAlojamientoCurso as t,
Tb_TipoCursosInstituciones ci,
Tb_AlojamientoCurso as ac
where
ci.fkTipoCurso = c.idTipoCurso and ci.fkInstitucion = i.idInstitucion and
ac.fkTipoAlojamiento = t.idTipoAlojamiento and ac.fkInstitucion= i.idInstitucion;


CREATE OR REPLACE VIEW Vw_InfoEnglish AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario
from Tb_Aspirante_Ingles_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona ;

CREATE OR REPLACE VIEW Vw_InfoStatusCero AS SELECT  firstNamePersona, lastNamePersona, generoPersona,emailUsuario, statusUsuario
from Tb_Personas as p, Tb_Usuarios as u
where   u.fkPersona = p.idPersona and u.statusUsuario = 'Activo';


CREATE OR REPLACE VIEW Vw_InfoStatusUno AS SELECT  idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from  Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where  a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoIngles' ;

CREATE OR REPLACE VIEW Vw_InfoStatusDos AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_Ingles_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoIngles' ;


CREATE OR REPLACE VIEW Vw_InfoEnglishInsSelect AS SELECT idACAI, idAspirante, idTipoCurso, idTipoAlojamiento, fkInstitutoOne, fkInstitutoTwo, fkInstitutoThree
from Tb_Aspirante_Ingles_C_A_I as ae, Tb_TipoAlojamientoCurso as ta, Tb_Institucion as i, Tb_TipoCursos as c, Tb_Aspirantes as a
where ae.fkAspirante = a.idAspirante  and ae.fkCurso = c.idTipoCurso and ae.fkAlojamiento = ta.idTipoAlojamiento and ae.fkInstitutoOne = i.idInstitucion ;

CREATE OR REPLACE VIEW Vw_InfoEnglishApirante AS SELECT idACAI, idAspirante, idTipoCurso, nombreTipoCurso, abreviacionTipoCurso, idTipoAlojamiento, nombreTipoAlojamiento, abreviacionTipoAlojamiento
from Tb_Aspirante_Ingles_C_A_I as ae, Tb_TipoAlojamientoCurso as ta, Tb_Institucion as i, Tb_TipoCursos as c, Tb_Aspirantes as a
where ae.fkAspirante = a.idAspirante  and ae.fkCurso = c.idTipoCurso and ae.fkAlojamiento = ta.idTipoAlojamiento and ae.fkInstitutoOne = i.idInstitucion ;

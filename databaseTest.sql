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

CREATE TABLE Tb_TipoCampamento(
  idTipoCampamento int PRIMARY key AUTO_INCREMENT,
  nombreTipoCampamento varchar(50),
  abreviacionTipoCampamento varchar(50),
  CDCampamento timestamp default current_timestamp,
  LUCampamento timestamp default current_timestamp on update current_timestamp
);


CREATE TABLE Tb_TipoCampamentoInstitucion(
  idTipoCampInst int PRIMARY key AUTO_INCREMENT,
  fkInstitucion int,
  fkTipoCampamento int,
  FOREIGN KEY(fkTipoCampamento) REFERENCES Tb_TipoCampamento(idTipoCampamento) on update cascade on delete cascade,
  FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  CDCampInst timestamp default current_timestamp,
  LUCampInst timestamp default current_timestamp on update current_timestamp
);
CREATE OR REPLACE VIEW Vw_TipoCursoInst as select idTipoCampInst,idTipoCampamento, nombreTipoCampamento as nombre, abreviacionTipoCampamento as abreviacion,
idInstitucion,nombreInstitucion as institucion, statusInstitucion as statusInstitucion, ubicacionInstitucion as ubicacion
from Tb_TipoCampamentoInstitucion as ei, Tb_TipoCampamento as e, Tb_Institucion as i
where ei.fkTipoCampamento = e.idTipoCampamento and ei.fkInstitucion= i.idInstitucion;


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
select idInstitucion,nombreInstitucion,idEdad,abreviacionEdad,nombreEdad, edadEdad,idCampamento,abreviacionCampamento,nombreCampamento,idTipoAlojamiento,nombreTipoAlojamiento,abreviacionTipoAlojamiento,idTipoCampamento, nombreTipoCampamento,abreviacionTipoCampamento
FROM Tb_Institucion as i, Tb_Edades as e, Tb_Campamentos as c,
Tb_TipoAlojamiento as t,
Tb_EdadesInstituciones as ei, Tb_CampamentosInstituciones as ci,
Tb_AlojamientoCampamento as ac, Tb_TipoCampamentoInstitucion as tci, Tb_TipoCampamento as tc
where ei.fkEdad = e.idEdad and ei.fkInstitucion=i.idInstitucion and
ci.fkCampamento = c.idCampamento and ci.fkInstitucion = i.idInstitucion and
ac.fkTipoAlojamiento = t.idTipoAlojamiento and ac.fkInstitucion= i.idInstitucion and tci.fkTipoCampamento = tc.idTipoCampamento and tci.fkInstitucion = i.idInstitucion;

CREATE OR REPLACE VIEW Vw_VeranoIngles as
select idInstitucion,nombreInstitucion,idEdad,abreviacionEdad,nombreEdad, edadEdad,idCampamento,abreviacionCampamento,nombreCampamento,idTipoAlojamiento,nombreTipoAlojamiento,abreviacionTipoAlojamiento,idTipoCampamento, nombreTipoCampamento,abreviacionTipoCampamento
FROM Tb_Institucion as i, Tb_Edades as e, Tb_Campamentos as c,
Tb_TipoAlojamiento as t,
Tb_EdadesInstituciones as ei, Tb_CampamentosInstituciones as ci,
Tb_AlojamientoCampamento as ac, Tb_TipoCampamentoInstitucion as tci, Tb_TipoCampamento as tc
where ei.fkEdad = e.idEdad and ei.fkInstitucion=i.idInstitucion and
ci.fkCampamento = c.idCampamento and ci.fkInstitucion = i.idInstitucion and
ac.fkTipoAlojamiento = t.idTipoAlojamiento and ac.fkInstitucion= i.idInstitucion and tci.fkTipoCampamento = tc.idTipoCampamento and tci.fkInstitucion = i.idInstitucion and tc.nombreTipoCampamento = 'Verano Inglés';

CREATE OR REPLACE VIEW Vw_VeranoInglesInst as
select idInstitucion,nombreInstitucion,nombreTipoCampamento,abreviacionTipoCampamento, ubicacionInstitucion
FROM Tb_Institucion as i,  Tb_TipoCampamento as tc, Tb_TipoCampamentoInstitucion as tci
where  tci.fkInstitucion = i.idInstitucion and tci.fkTipoCampamento = tc.idTipoCampamento and tc.nombreTipoCampamento = 'Verano Inglés';
CREATE OR REPLACE VIEW Vw_VeranoAcademicoInst as
select idInstitucion,nombreInstitucion,nombreTipoCampamento,abreviacionTipoCampamento, ubicacionInstitucion
FROM Tb_Institucion as i,  Tb_TipoCampamento as tc, Tb_TipoCampamentoInstitucion as tci
where  tci.fkInstitucion = i.idInstitucion and tci.fkTipoCampamento = tc.idTipoCampamento and tc.nombreTipoCampamento = 'Verano Académico';


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


CREATE TABLE Tb_Aspirante_E_C_A_I(
  idACAI int primary key auto_increment,
  fkAspirante int,
  fkEdad int,
  fkTipoCampamento int,
  fkCampamento int,
  fkAlojamiento int,
  FOREIGN KEY(fkTipoCampamento) REFERENCES Tb_TipoCampamento(idTipoCampamento) on update cascade on delete cascade,
  FOREIGN KEY(fkAlojamiento) REFERENCES Tb_TipoAlojamiento(idTipoAlojamiento) on update cascade on delete cascade,
  FOREIGN KEY(fkEdad) REFERENCES Tb_Edades(idEdad) on update cascade on delete cascade,
  FOREIGN KEY(fkCampamento) REFERENCES Tb_Campamentos(idCampamento) on update cascade on delete cascade,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp
);

alter table  Tb_Aspirante_E_C_A_I drop column fkInstitutoOne;

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
from Tb_Aspirante_E_C_A_I as ae, Tb_TipoAlojamiento as ta, Tb_Edades as e, Tb_Institucion as i, Tb_Campamentos as c, Tb_Aspirantes as a , Tb_TipoCampamento as tc
where ae.fkAspirante = a.idAspirante and ae.fkEdad = e.idEdad and ae.fkCampamento = c.idCampamento and ae.fkAlojamiento = ta.idTipoAlojamiento and ae.fkTipoCampamento = tc.idTipoCampamento and ae.fkInstitutoOne = i.idInstitucion ;



CREATE OR REPLACE VIEW Vw_InfoVeranoFoto AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante,urlImagen
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u, Tb_Imagenes as i
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and p.photoPersona= i.idImagen;

CREATE OR REPLACE VIEW Vw_InfoVerano AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,statusAspirante
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona ;


CREATE OR REPLACE VIEW Vw_VeranoStatusCero AS SELECT  firstNamePersona, lastNamePersona, generoPersona,emailUsuario, statusUsuario
from Tb_Personas as p, Tb_Usuarios as u
where   u.fkPersona = p.idPersona and u.statusUsuario = 'Activo';



CREATE OR REPLACE VIEW Vw_VeranoStatusUno AS SELECT  idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from  Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where  a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoIngles' and statusAspirante = '1' ;

CREATE OR REPLACE VIEW Vw_VeranoStatusDos AS SELECT  idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from  Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where  a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoIngles' and a.statusAspirante = '2';

-- CREATE OR REPLACE VIEW Vw_VeranoStatusDos AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
-- from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
-- where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVerano' ;



CREATE OR REPLACE VIEW Vw_VeranoStatusDosR AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoIngles' and a.statusAspirante = '2R';

CREATE OR REPLACE VIEW Vw_VeranoStatusTres AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoIngles' and a.statusAspirante = '3';

CREATE OR REPLACE VIEW Vw_VeranoStatusCuatroC AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoIngles' and a.statusAspirante = '4C';

CREATE OR REPLACE VIEW Vw_VeranoStatusCuatroU AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoIngles' and a.statusAspirante = '4U';

CREATE OR REPLACE VIEW Vw_VeranoStatus AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante,statusAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoIngles';



CREATE TABLE Tb_Aspirante_Eleccion(
  idACAI int primary key auto_increment,
  fkAspirante int,
  fkInstituto int,
  FOREIGN KEY(fkInstituto) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_Aspirante_MesAnio(
  idMesAnio int primary key auto_increment,
  fkAspirante int,
  anioMesIngreso date,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp
);

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
ALTER TABLE Tb_DocumentosVerano change tipo tipo enum('Boleta','CartaMotivo','Pasaporte','Pago','Visa','Examen');

CREATE TABLE Tb_DocumentosVeranoIngles(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_DocumentosAspirante(
  idDocumentosAspirante int primary key auto_increment,
  fkAspirante int,
  fkDocumento int,
  tipoDoc text,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  FOREIGN KEY(fkDocumento) REFERENCES Tb_DocumentosVeranoIngles(idDocumento) on update cascade on delete cascade
);

-- CREATE TABLE Tb_DocumentosAspirante(
--   idDocumentosAspirante int primary key auto_increment,
--   fkAspirante int,
--   fkDocumento int,
--   tipoDoc text,
--   FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
--   FOREIGN KEY(fkDocumento) REFERENCES Tb_DocumentosVeranoIngles(idDocumento) on update cascade on delete cascade
-- );

CREATE TABLE Tb_Recomendation(
  idRecomendation int primary key auto_increment,
  description text,
  fkDocumento int,
  FOREIGN KEY(fkDocumento) REFERENCES Tb_DocumentosVeranoIngles(idDocumento) on update cascade on delete cascade,
  CD timestamp default current_timestamp,
  CU timestamp default current_timestamp on update current_timestamp
);


CREATE OR REPLACE VIEW Vw_DocumentosVeranoIngles as select idDocumentosAspirante, idDocumento,fkAspirante as idAspirante,urlDocumento, typeDocumento,extDocumento, nombreDocumento,tipo,statusDocumento,creationDateDocumento,lastUpdateDocumento
from Tb_DocumentosAspirante as td, Tb_Aspirantes as ta, Tb_DocumentosVeranoIngles as tdv
where td.fkAspirante = ta.idAspirante and td.fkDocumento = tdv.idDocumento;

CREATE TABLE Tb_DocVerIngVisa(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo enum('Visa') not null,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_RecomendacionAspiranteForm(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  fkDocumento int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  FOREIGN KEY(fkDocumento) REFERENCES Tb_formdesolicitudVeranoIngAspirante(idDocumento) on update cascade on delete cascade
);

CREATE TABLE Tb_DocformatodesolicitudVerano(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkInstitucion int,
  FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade
);
CREATE TABLE Tb_formdesolicitudVeranoIngAspirante (
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);
CREATE TABLE Tb_OfertaUOIAspirante(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE OR REPLACE VIEW Vw_InfoVeranoApirante AS SELECT idACAI, idAspirante,statusAspirante,programaDeInteres, idEdad, nombreEdad, abreviacionEdad, edadEdad, idCampamento, nombreCampamento, abreviacionCampamento, idTipoAlojamiento, nombreTipoAlojamiento, abreviacionTipoAlojamiento, anioMesIngreso
from Tb_Aspirante_E_C_A_I as ae, Tb_TipoAlojamiento as ta, Tb_Edades as e, Tb_Campamentos as c, Tb_Aspirantes as a, Tb_Aspirante_MesAnio as ma
where ae.fkAspirante = a.idAspirante and ae.fkEdad = e.idEdad and ae.fkCampamento = c.idCampamento and ae.fkAlojamiento = ta.idTipoAlojamiento and ma.fkAspirante = a.idAspirante ;

CREATE OR REPLACE VIEW Vw_InstitutoInformation AS SELECT idInstitucion, nombreInstitucion, ubicacionInstitucion,statusInstitucion,urlImagen
from Tb_Institucion as ti , Tb_Imagenes as t
where ti.logoInstitucion = t.idImagen;



CREATE OR REPLACE VIEW vw_DocumentosVI AS SELECT idAspirante, statusDocumento
from Tb_formdesolicitudVeranoIngAspirante as f, Tb_Aspirantes as a
where f.fkAspirante = a.idAspirante and f.statusDocumento = 'Aceptado';
--//Inicia Verano Academico
CREATE TABLE Tb_DocumentosVeranoAcademico(
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

ALTER TABLE Tb_DocumentosVeranoAcademico change tipo tipo enum('Boleta','CartaMotivo','Pasaporte','Examen','SinTipo')default 'SinTipo';

CREATE TABLE Tb_PasaporteAspiranteAcademico(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_DocVerAcademicoVisa(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo enum('Visa') not null,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);



CREATE TABLE Tb_formsolicitudVeranoAcademico(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkInstitucion int,
  FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade
);



CREATE TABLE Tb_formsolicitudVAcademicoAspirante(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);
CREATE TABLE Tb_RecomenAspiranteAcademicForm(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  fkDocumento int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  FOREIGN KEY(fkDocumento) REFERENCES Tb_formsolicitudVAcademicoAspirante(idDocumento) on update cascade on delete cascade
);

CREATE TABLE Tb_TranscripcionesAspirante(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_RecomendacionTranscripcion(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);
CREATE TABLE Tb_TraduccionInglesAspirante(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_RecAcademicTraduccion(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_ExamenInglesAspirante(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);
CREATE TABLE Tb_RecomendacionExamendeIngles(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);




CREATE TABLE Tb_DocsApiranteVIA(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  type enum('OfertaCondicional','OfertaIncondicional','Visa','SinCalificar','Ticket_De_Pago','formatoSolicitud'),
  typeUser enum('Aspirante','Agente','Admin','Ninguno')default 'Ninguno',
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  fkInstitucion int,
  FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);
ALTER TABLE Tb_Aspirantes change statusAspirante statusAspirante enum('0','1','2','2R','3','4U','4C','4','5') default '0';
ALTER TABLE Tb_DocsApiranteVIA change type type enum('OfertaCondicional','OfertaIncondicional','Visa','SinCalificar','Ticket_De_Pago','formatoSolicitud');
CREATE TABLE Tb_RecomendationVIA(
  idRecomendation int primary key auto_increment,
  description text,
  fkDocumento int,
  FOREIGN KEY(fkDocumento) REFERENCES Tb_DocsApiranteVIA(idDocumento) on update cascade on delete cascade,
  CD timestamp default current_timestamp,
  CU timestamp default current_timestamp on update current_timestamp
);

CREATE TABLE Tb_RecomendationDocsApiranteVIA(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  type enum('OfertaCondicional','OfertaIncondicional','Visa','Carta'),
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  fkDocumento int,
  FOREIGN KEY(fkDocumento) REFERENCES Tb_DocsApiranteVIA(idDocumento) on update cascade on delete cascade,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);


CREATE TABLE Tb_RecomendacionAspiranteAcademicForm(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  fkDocumento int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  FOREIGN KEY(fkDocumento) REFERENCES Tb_formsolicitudVAcademicoAspirante(idDocumento) on update cascade on delete cascade
);
CREATE TABLE Tb_DocVerAcademicVisa(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo enum('Visa') not null,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_RecAcademicVisaAspirante(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_NumAplicanteAcademico(
  idNumAplicante int primary key auto_increment,
  numeroAplicante text,
  statusNumAplicante enum('Activo','Inactivo') default 'Activo',
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  fkInstitucion int,
  FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE OR REPLACE VIEW Vw_VeranoStatusCero AS SELECT  firstNamePersona, lastNamePersona, generoPersona,emailUsuario, statusUsuario
from Tb_Personas as p, Tb_Usuarios as u
where   u.fkPersona = p.idPersona and u.statusUsuario = 'Activo';



CREATE OR REPLACE VIEW Vw_VAcademicoStatusUno AS SELECT  idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from  Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where  a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoAcademico' and statusAspirante = '1' ;

CREATE OR REPLACE VIEW Vw_VAcademicoStatusDos AS SELECT  idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from  Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where  a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoAcademico' and a.statusAspirante = '2';

-- CREATE OR REPLACE VIEW Vw_VeranoStatusDos AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
-- from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
-- where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVerano' ;


CREATE OR REPLACE VIEW Vw_VAcademicoStatusDosR AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoAcademico' and a.statusAspirante = '2R';

CREATE OR REPLACE VIEW Vw_VAcademicoStatusTres AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoAcademico' and a.statusAspirante = '3';

CREATE OR REPLACE VIEW Vw_VeranoAcademicoStatus AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante,statusAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_E_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoVeranoAcademico';



CREATE OR REPLACE VIEW Vw_Documentos AS SELECT idAspirante, statusDocumento
from Tb_DocsApiranteVIA as f, Tb_Aspirantes as a
where f.fkAspirante = a.idAspirante and f.statusDocumento = 'Aceptado' and f.type = "formatoSolicitud";

CREATE OR REPLACE VIEW Vw_DocumentosVIA AS SELECT idAspirante, statusNumAplicante
from Tb_NumAplicanteAcademico as f, Tb_Aspirantes as a
where f.fkAspirante = a.idAspirante and f.statusNumAplicante = 'Activo';



-- //INICIA INGLES
CREATE TABLE Tb_DocumentosIngles(
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
  FOREIGN KEY(fkAlojamiento) REFERENCES Tb_TipoAlojamientoCurso(idTipoAlojamiento) on update cascade on delete cascade,
  FOREIGN KEY(fkCurso) REFERENCES Tb_TipoCursos(idTipoCurso) on update cascade on delete cascade,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp
);


CREATE TABLE Tb_Aspirante_Ingles_Eleccion(
  idACAI int primary key auto_increment,
  fkAspirante int,
  fkInstituto int,
  FOREIGN KEY(fkInstituto) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
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

ALTER TABLE Tb_RecomendacionAspirante change tipo tipo enum('Boleta','CartaMotivo','Pasaporte','Pago','Visa','Examen');



CREATE TABLE Tb_RecomenVisaAspirante(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_DocformatodesolicitudIngles(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkInstitucion int,
  FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade
);

CREATE TABLE Tb_formdesolicitudIngAspirante(
	idDocumento int not null AUTO_INCREMENT PRIMARY KEY,
	urlDocumento text not null,
	typeDocumento varchar(15),
	extDocumento varchar(10),
  nombreDocumento text,
  tipo text,
	statusDocumento enum('Activo','Inactivo','Revision','Rechazado','Aceptado') default 'Activo',
	creationDateDocumento timestamp default current_timestamp,
	lastUpdateDocumento timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_RecomendacionAspiranteInglesForm(
  idRecomendacion int primary key auto_increment,
  descripcion text,
  CD timestamp default current_timestamp,
  LU timestamp default current_timestamp on update current_timestamp,
  fkAspirante int,
  fkDocumento int,
  FOREIGN KEY(fkAspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade,
  FOREIGN KEY(fkDocumento) REFERENCES Tb_formdesolicitudIngAspirante(idDocumento) on update cascade on delete cascade
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


CREATE OR REPLACE VIEW Vw_InfoEnglish AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,statusAspirante
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

CREATE OR REPLACE VIEW Vw_InglesStatus AS SELECT idACAI, idAspirante, firstNamePersona, lastNamePersona,statusAspirante, generoPersona,emailUsuario,telefonoAspirante, ciudadAspirante, statusUsuario,programaDeInteres
from Tb_Aspirante_Ingles_C_A_I as ae,Tb_Aspirantes as a,Tb_Personas as p, Tb_Usuarios as u
where ae.fkAspirante = a.idAspirante and a.fkPersona = p.idPersona and u.fkPersona = p.idPersona and a.programaDeInteres = 'CursoIngles' ;


CREATE OR REPLACE VIEW Vw_InfoEnglishInsSelect AS SELECT idACAI, idAspirante, idTipoCurso, idTipoAlojamiento, fkInstitutoOne, fkInstitutoTwo, fkInstitutoThree
from Tb_Aspirante_Ingles_C_A_I as ae, Tb_TipoAlojamientoCurso as ta, Tb_Institucion as i, Tb_TipoCursos as c, Tb_Aspirantes as a
where ae.fkAspirante = a.idAspirante  and ae.fkCurso = c.idTipoCurso and ae.fkAlojamiento = ta.idTipoAlojamiento and ae.fkInstitutoOne = i.idInstitucion ;

CREATE OR REPLACE VIEW Vw_InfoEnglishApirante AS SELECT idACAI, idAspirante, idTipoCurso, nombreTipoCurso, abreviacionTipoCurso, idTipoAlojamiento, nombreTipoAlojamiento, abreviacionTipoAlojamiento,anioMesIngreso,statusAspirante
from Tb_Aspirante_Ingles_C_A_I as ae, Tb_TipoAlojamientoCurso as ta, Tb_TipoCursos as c, Tb_Aspirantes as a, Tb_Aspirante_MesAnio as ma
where ae.fkAspirante = a.idAspirante  and ae.fkCurso = c.idTipoCurso and ae.fkAlojamiento = ta.idTipoAlojamiento and ma.fkAspirante = a.idAspirante ;

DROP DATABASE IF EXISTS DBtest;

CREATE DATABASE DBtest;

USE DBtest;

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

insert into Tb_Imagenes(urlImagen,typeImagen,extImagen)values('https://youtube.com','img','jpg');

CREATE TABLE Tb_Logotipos(
	idLogotipo int not null AUTO_INCREMENT PRIMARY KEY,
	urlLogotipo text not null,
	typeLogotipo varchar(15),
	extLogotipo varchar(10),
	statusLogotipo enum('Activo','Inactivo') default 'Activo',
	creationDateLogotipo timestamp default current_timestamp,
	lastUpdateLogotipo timestamp default current_timestamp on update current_timestamp);
  insert into Tb_Logotipos(urlLogotipo,typeLogotipo,extLogotipo)values('https://youtube.com','img','jpg');



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
insert into Tb_Personas(firstNamePersona,lastNamePersona,generoPersona,photoPersona)values('David','Patherson','Masculino',1);

CREATE TABLE Tb_Usuarios(
    idUsuario int not null auto_increment primary key,
    emailUsuario varchar(120) not null,
    passwordUsuario varchar(120),
    typeOauthUsuario enum('Facebook','Google','Registro') not null,
    tokenPasswordUser text,
    cambiarPasswordUsuario enum('True','False') not null default 'False',
    typeUsuario enum('Agente','Aspirante','Admin') not null,
    statusUsuario enum('Activo','Inactivo','Pendiente') default 'Activo',
    creationDateUsuario timestamp default current_timestamp,
    lastUpdateUsuario timestamp default current_timestamp on update current_timestamp,
    fkPersona int not null,
    FOREIGN KEY(fkPersona) REFERENCES Tb_Personas(idPersona) on update cascade on delete cascade
);

insert into Tb_Usuarios(emailUsuario,passwordUsuario,typeOauthUsuario,tokenPasswordUser,typeUsuario,fkPersona)values('isra@gmail.com','123456789','Registro','12345','Aspirante',1);

CREATE TABLE Tb_Aspirantes(
    idAspirante int not null auto_increment primary key,
    fechaNacimientoAspirante text,
    telefonoAspirante varchar(15),
    ciudadAspirante varchar(150),
    programaDeInteres enum('Universidad','Preparatoria','CursoIngles','CursoVerano'),
    creationDateAspirante timestamp default current_timestamp,
    lastUpdateAspirante timestamp default current_timestamp on update current_timestamp,
    fkPersona int not null,
    FOREIGN KEY(fkPersona) REFERENCES Tb_Personas(idPersona)
);

Insert into Tb_Aspirantes(fechaNacimientoAspirante,telefonoAspirante,ciudadAspirante,programaDeInteres,fkPersona) values('12-12-2010','4432342342','Qro','Universidad',1);

CREATE TABLE Tb_Universidades(
    idUniversidad int primary key auto_increment,
    nombre_Universidad varchar(80) not null,
    estudios_Universidad varchar(80) not null,
    facultades_Universidad varchar(80) not null,
    campus_Universidad text,
    anioIngreso_Universidad varchar(80) not null,
    status_Universidad enum('Activo','Inactivo','Pendiente') default 'Pendiente',
    creationDate_Universidad timestamp default current_timestamp,
    lastUpdate_Universidad timestamp default current_timestamp on update current_timestamp,
    photo_Universidad int,
    logotipo_Universidad int,
    FOREIGN KEY(photo_Universidad) REFERENCES Tb_Imagenes(idImagen) on update cascade on delete cascade,
    FOREIGN KEY(logotipo_Universidad) REFERENCES Tb_Logotipos(idLogotipo) on update cascade on delete cascade
);

Insert into Tb_Universidades(nombre_Universidad,estudios_Universidad,facultades_Universidad,campus_Universidad,anioIngreso_Universidad,photo_Universidad,logotipo_Universidad) values('Cats','Maestria, Doctorado','Medicina, Artes','Uk','2019',1,1);

Insert into Tb_Universidades(nombre_Universidad,estudios_Universidad,facultades_Universidad,campus_Universidad,anioIngreso_Universidad,photo_Universidad,logotipo_Universidad) values('Bath','Maestria, Doctorado','Medicina, Artes','Uk','2019',1,1);

Insert into Tb_Universidades(nombre_Universidad,estudios_Universidad,facultades_Universidad,campus_Universidad,anioIngreso_Universidad,photo_Universidad,logotipo_Universidad) values('Bristol','Maestria, Doctorado','Medicina, Artes','Uk','2019',1,1);


CREATE TABLE Tb_Tipo(
    idTipo int not null auto_increment primary key,
    tipo enum('Maestria','Doctorado'),
    creationDateTipo timestamp default current_timestamp,
  	lastUpdateTipo  timestamp default current_timestamp on update current_timestamp,
    fk_Aspirante int,
    FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

Insert Into Tb_Tipo(tipo,fk_Aspirante)values('Maestria',1);

CREATE TABLE Tb_Facultades(
    idFacultad int not null auto_increment primary key,
    nombreFacultad varchar(80) not null,
    creationDateFacultad timestamp default current_timestamp,
    lastUpdateFacultad  timestamp default current_timestamp on update current_timestamp,
    fk_Aspirante int,
    FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

Insert Into Tb_Facultades(nombreFacultad,fk_Aspirante)values('Ciencias, Ingenieria',1);

create table Tb_Universidad_Aspirante(
  idInstitucion_Aspirante int primary key auto_increment,
  fk_InstitucionOpcionUno int,
  fk_InstitucionOpcionDos int,
  fk_InstitucionOpcionTres int,
  creationDateInstitucion timestamp default current_timestamp,
  lastUpdateInstitucion  timestamp default current_timestamp on update current_timestamp,
  fk_Aspirante int,
  FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

  insert into Tb_Universidad_Aspirante(fk_InstitucionOpcionUno,fk_InstitucionOpcionDos,fk_InstitucionOpcionTres,fk_Aspirante)values(1,2,3,1);

CREATE TABLE Tb_Preparatorias(
  idPreparatoria int primary key auto_increment,
  nombre_Preparatoria varchar(80) not null,
  alojamiento_Preparatoria varchar(80) not null,
  estudios_Preparatoria varchar(80) not null,
  anioIngreso_Preparatoria varchar(80) not null,
  campus_Preparatoria text,
  status_Preparatoria enum('Activo','Inactivo','Pendiente') default 'Pendiente',
  creationDate_Preparatoria timestamp default current_timestamp,
  lastUpdate_Preparatoria timestamp default current_timestamp on update current_timestamp,
  photo_Preparatoria int,
  logotipo_Preparatoria int,
  FOREIGN KEY(photo_Preparatoria) REFERENCES Tb_Imagenes(idImagen) on update cascade on delete cascade,
  FOREIGN KEY(logotipo_Preparatoria) REFERENCES Tb_Logotipos(idLogotipo) on update cascade on delete cascade
);

CREATE TABLE Tb_TipoPreparatoria(
    idTipo int not null auto_increment primary key,
    abreviacionTipo varchar(20) not null,
    descTipo text,
    tipo enum('Año Académico','Preparatoria Completa','Semestre Completo'),
    creationDateTipo timestamp default current_timestamp,
  	lastUpdateTipo  timestamp default current_timestamp on update current_timestamp,
    fk_Aspirante int,
    FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

CREATE TABLE Tb_Alojamiento(
    idTipo int not null auto_increment primary key,
    abreviacion_Alojamiento varchar(20) not null,
    descAlojamiento text,
    Alojamiento enum('Familia','Internado','Residencial'),
    creationDateAlojamiento timestamp default current_timestamp,
  	lastUpdateAlojamiento  timestamp default current_timestamp on update current_timestamp,
    fk_Aspirante int,
    FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);


create table Tb_Preparatoria_Aspirante(
  id_PreparatoriaAspirante int primary key auto_increment,
  fk_InstitucionOpcionUno int,
  fk_InstitucionOpcionDos int,
  fk_InstitucionOpcionTres int,
  creationDateInstitucion timestamp default current_timestamp,
  lastUpdateInstitucion  timestamp default current_timestamp on update current_timestamp,
  fk_Aspirante int,
  FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
);

 CREATE TABLE Tb_Ingles(
   idIngles int primary key auto_increment,
   nombre_Ciudad varchar(80) not null,
   edades_Ingles varchar(80) default '16 +',
   tipoCurso_Ingles varchar(80) not null,
   campus_Ingles text,
   alojamiento_Ingles varchar(80) not null,
   anioIngreso_Ingles varchar(80) not null,
   status_Ingles enum('Activo','Inactivo','Pendiente') default 'Pendiente',
   creationDate_Ingles timestamp default current_timestamp,
   lastUpdate_Ingles timestamp default current_timestamp on update current_timestamp,
   photo_Ingles int,
   logotipo_Ingles int,
   FOREIGN KEY(photo_Ingles) REFERENCES Tb_Imagenes(idImagen) on update cascade on delete cascade,
   FOREIGN KEY(logotipo_Ingles) REFERENCES Tb_Logotipos(idLogotipo) on update cascade on delete cascade
  );

  CREATE TABLE Tb_TipoIngles(
      idTipo int not null auto_increment primary key,
      abreviacionTipo varchar(20) not null,
      descTipo text,
      tipo enum('Inglés general','Inglés profesional','Preparación para exámenes'),
      creationDateTipo timestamp default current_timestamp,
    	lastUpdateTipo  timestamp default current_timestamp on update current_timestamp,
      fk_Aspirante int,
      FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
  );

  CREATE TABLE Tb_AlojamientoIngles(
      idTipo int not null auto_increment primary key,
      abreviacion_Alojamiento varchar(20) not null,
      descAlojamiento text,
      Alojamiento enum('Familia','Residencial'),
      creationDateAlojamiento timestamp default current_timestamp,
    	lastUpdateAlojamiento  timestamp default current_timestamp on update current_timestamp,
      fk_Aspirante int,
      FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
  );

  create table Tb_Ingles_Aspirante(
    id_InglesAspirante int primary key auto_increment,
    fk_InstitucionOpcionUno int,
    fk_InstitucionOpcionDos int,
    fk_InstitucionOpcionTres int,
    creationDateInstitucion timestamp default current_timestamp,
    lastUpdateInstitucion  timestamp default current_timestamp on update current_timestamp,
    fk_Aspirante int,
    FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
  );

  CREATE TABLE Tb_Verano(
    idVerano int primary key auto_increment,
    nombre_Institucion varchar(80) not null,
    edades_Verano varchar(80) not null,
    tipoCampamento_Verano varchar(80) not null,
    alojamiento_Verano varchar(80) not null,
    anioIngreso_Verano varchar(80) not null,
    campus_Verano text,
    status_Verano enum('Activo','Inactivo','Pendiente') default 'Pendiente',
    creationDate_Verano timestamp default current_timestamp,
    lastUpdate_Verano timestamp default current_timestamp on update current_timestamp,
    photo_Verano int,
    logotipo_Verano int,
    FOREIGN KEY(photo_Verano) REFERENCES Tb_Imagenes(idImagen) on update cascade on delete cascade,
    FOREIGN KEY(logotipo_Verano) REFERENCES Tb_Logotipos(idLogotipo) on update cascade on delete cascade
   );

   CREATE TABLE Tb_TipoVerano(
       idTipo int not null auto_increment primary key,
       abreviacionTipo varchar(20) not null,
       descTipo text,
       tipo enum('Académico','Inglés','Combinado','Inglés más Deporte'),
       creationDateTipo timestamp default current_timestamp,
     	lastUpdateTipo  timestamp default current_timestamp on update current_timestamp,
       fk_Aspirante int,
       FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
   );

   CREATE TABLE Tb_Edad(
       idEdad int not null auto_increment primary key,
       abreviacionEdad varchar(20) not null,
       descEdad text,
       tipo enum('Junior 7-13','Senior 14 - 17','Universitario 18 - 25'),
       creationDateEdad timestamp default current_timestamp,
     	lastUpdateEdad  timestamp default current_timestamp on update current_timestamp,
       fk_Aspirante int,
       FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
   );
   CREATE TABLE Tb_AlojamientoVerano(
       idTipo int not null auto_increment primary key,
       abreviacion_Alojamiento varchar(20) not null,
       descAlojamiento text,
       Alojamiento enum('Familia','Internado','Residencial'),
       creationDateAlojamiento timestamp default current_timestamp,
       lastUpdateAlojamiento  timestamp default current_timestamp on update current_timestamp,
       fk_Aspirante int,
       FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
   );

   create table Tb_Verano_Aspirante(
     id_VeranoAspirante int primary key auto_increment,
     fk_InstitucionOpcionUno int,
     fk_InstitucionOpcionDos int,
     fk_InstitucionOpcionTres int,
     creationDateInstitucion timestamp default current_timestamp,
     lastUpdateInstitucion  timestamp default current_timestamp on update current_timestamp,
     fk_Aspirante int,
     FOREIGN KEY(fk_Aspirante) REFERENCES Tb_Aspirantes(idAspirante) on update cascade on delete cascade
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


 -- cambios nuevos

  CREATE TABLE Tb_CarpetaBecas(
    idCarpetaBeca int primary key auto_increment,
    nombre varchar(60),
    CD timestamp default current_timestamp,
    LU timestamp default current_timestamp on update current_timestamp
  );

   CREATE TABLE Tb_DocumentoBeca(
   	 idDocumentoBeca int not null AUTO_INCREMENT PRIMARY KEY,
   	 urlDocumentoBeca text not null,
   	 typeDocumentoBeca varchar(15),
   	 extDocumentoBeca varchar(10),
     nombreDocumentoBeca text,
     tipoBeca enum('Beca') default 'Beca',
   	 statusDocumentoBeca enum('Activo','Inactivo','Revision') default 'Activo',
   	 creationDateBeca timestamp default current_timestamp,
   	 lastUpdateBeca timestamp default current_timestamp on update current_timestamp
   );
   insert into Tb_DocumentoBeca(urlDocumentoBeca,typeDocumentoBeca,extDocumentoBeca,nombreDocumentoBeca)values('http://localhost/angloApi/files/VeranoAcademico/45/Pasaporte.pdf','application/pdf','.pdf','Pasaporte.pdf');

   CREATE TABLE Tb_DocumentoBecaExtras(
   	 idDocumentoBecaExtra int not null AUTO_INCREMENT PRIMARY KEY,
   	 urlDocumentoBecaExtra text not null,
   	 typeDocumentoBecaExtra varchar(15),
   	 extDocumentoBecaExtra varchar(10),
     nombreDocumentoBecaExtra text,
     tipoBecaExtra enum('ExtraBeca') default 'ExtraBeca',
   	 statusDocumentoBecaExtra enum('Activo','Inactivo','Revision') default 'Activo',
   	 creationBecaExtra timestamp default current_timestamp,
   	 lastUpdateBecaExtra timestamp default current_timestamp on update current_timestamp
   );
   insert into Tb_DocumentoBecaExtras(urlDocumentoBecaExtra,typeDocumentoBecaExtra,extDocumentoBecaExtra,nombreDocumentoBecaExtra)values('http://localhost/angloApi/files/VeranoAcademico/48/Pasaporte.pdf','application/pdf','.pdf','Extra.pdf');
  CREATE TABLE Tb_DescBeca(
    idDescBeca int primary key auto_increment,
    descBeca text,
    estados text,
    CD timestamp default current_timestamp,
    LU timestamp default current_timestamp on update current_timestamp
  );

  insert into Tb_DescBeca(descBeca, estados)values('Es una beca para estudiantes de Uk','Mex, Qro, Mtr');

  CREATE TABLE Tb_DocBeca_Extra_Desc(
    idTbBecaExtraDesc int primary key auto_increment,
    fkDocumentoBeca int,
    fkDocumentoBecaExtras int,
    fkDescBeca int,
    FOREIGN KEY(fkDocumentoBeca) REFERENCES Tb_DocumentoBeca(idDocumentoBeca) on update cascade on delete cascade,
    FOREIGN KEY(fkDocumentoBecaExtras) REFERENCES Tb_DocumentoBecaExtras(idDocumentoBecaExtra) on update cascade on delete cascade,
    FOREIGN KEY(fkDescBeca) REFERENCES Tb_DescBeca(idDescBeca) on update cascade on delete cascade
  );
  insert into Tb_DocBeca_Extra_Desc(fkDocumentoBeca,fkDocumentoBecaExtras,fkDescBeca)values(1,1,1);

  CREATE TABLE Tb_DocsExtra(
    idDocsExtra int primary key auto_increment,
    decDocsExtra text,
    tipoInstitucionExtra enum('Universidad','Preparatoria','CursoIngles','CursoVerano','CursoVeranoIngles','CursoVeranoAcademico'),
    fkDocBeca_Extra_Desc int,
    FOREIGN KEY(fkDocBeca_Extra_Desc) REFERENCES Tb_DocBeca_Extra_Desc(idTbBecaExtraDesc) on update cascade on delete cascade
  );
  insert into Tb_DocsExtra(decDocsExtra,tipoInstitucionExtra,fkDocBeca_Extra_Desc)values('Una carta','CursoVeranoIngles',1);
    insert into Tb_DocsExtra(decDocsExtra,tipoInstitucionExtra,fkDocBeca_Extra_Desc)values('Una Pasaporte','CursoVeranoIngles',1);
      insert into Tb_DocsExtra(decDocsExtra,tipoInstitucionExtra,fkDocBeca_Extra_Desc)values('Una Visa','CursoVeranoIngles',1);
  CREATE TABLE Tb_InstDocBeca_Extra_Desc(
    idInstDocBeca_Extra_Desc int primary key auto_increment,
    fkInstitucion int,
    fkCarpetaBeca int,
    statusBeca enum('Activo','Inactivo','Revision') default 'Activo',
    tipoInstitucion enum('Universidad','Preparatoria','CursoIngles','CursoVerano','CursoVeranoIngles','CursoVeranoAcademico'),
    fkDocBeca_Extra_Desc int,
    FOREIGN KEY(fkInstitucion) REFERENCES Tb_Institucion(idInstitucion) on update cascade on delete cascade,
    FOREIGN KEY(fkDocBeca_Extra_Desc) REFERENCES Tb_DocBeca_Extra_Desc(idTbBecaExtraDesc) on update cascade on delete cascade,
    FOREIGN KEY(fkCarpetaBeca) REFERENCES Tb_CarpetaBecas(idCarpetaBeca) on update cascade on delete cascade
  );

  insert into Tb_InstDocBeca_Extra_Desc(fkInstitucion,tipoInstitucion,fkDocBeca_Extra_Desc) values(17,'CursoVeranoIngles',1);
    insert into Tb_InstDocBeca_Extra_Desc(fkInstitucion,tipoInstitucion,fkDocBeca_Extra_Desc) values(19,'CursoVeranoIngles',1);

  CREATE OR REPLACE VIEW Vw_BecasInst  AS SELECT idInstitucion,nombreInstitucion,nombreDocumentoBeca,urlDocumentoBeca,nombreDocumentoBecaExtra,urlDocumentoBecaExtra,descBeca,estados,statusBeca
  FROM Tb_InstDocBeca_Extra_Desc as t,Tb_DocBeca_Extra_Desc as tb, Tb_DocumentoBeca as tdb, Tb_DocumentoBecaExtras as tdbe, Tb_DescBeca as tbdsc, Tb_Institucion as i
  where t.fkDocBeca_Extra_Desc = tb.idTbBecaExtraDesc and t.fkInstitucion = i.idInstitucion and tb.fkDocumentoBeca = tdb.idDocumentoBeca and tb.fkDocumentoBecaExtras = tdbe.idDocumentoBecaExtra and tb.fkDescBeca =  tbdsc.idDescBeca and tipoInstitucion = 'CursoVeranoIngles';

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

CREATE user 'anglo'@'localhost' identified by 'anglo2020';
grant all privileges on *.* to 'anglo'@'localhost';

Drop database if exists AngloDB;

CREATE DATABASE AngloDB;
USE AngloDB;


CREATE TABLE Tb_Universidades(
    idUniversidad int not null auto_increment primary key,
    nombreUniversidad varchar(120) not null,
    fundacionUniversidad date not null,
    fkLogoImagenUniversidad int not null,
    linkUniversidad text not null,
    linkVideoUniversidad text not null,
    fkPrincipalImagenUniversidad int not null,
    creationDateUniversidad  timestamp default current_timestamp,
	lastUpdateUniversidad  timestamp default current_timestamp on update current_timestamp,
    FOREIGN KEY(fkLogoImagenUniversidad) REFERENCES Tb_Imagenes(idImagen),
    FOREIGN KEY(fkPrincipalImagenUniversidad) REFERENCES Tb_Imagenes(idImagen)
);

CREATE TABLE Tb_Rankings(
    idRanking int not null auto_increment primary key,
    descRanking text not null,
    fkUniversidad int not null,
    creationDateRanking  timestamp default current_timestamp,
	lastUpdateRanking   timestamp default current_timestamp on update current_timestamp,
    FOREIGN KEY(fkUniversidad) REFERENCES Tb_Universidades(idUniversidad)
);

CREATE TABLE Tb_SabiasQues(
    idSabiasQue int not null auto_increment primary key,
    descSabiasque text not null,
    fkUniversidad int not null,
    creationDateSabiasque timestamp default current_timestamp,
	lastUpdateSabiasque  timestamp default current_timestamp on update current_timestamp,
    FOREIGN KEY(fkUniversidad) REFERENCES Tb_Universidades(idUniversidad)
);

CREATE TABLE Tb_Campus(
    idCampus int not null auto_increment primary key,
    direccionCampus text not null,
    iframeCampus text not null,
    descCampus text,
    creationDateCampus timestamp default current_timestamp,
	lastUpdateCampus  timestamp default current_timestamp on update current_timestamp,
    fkUniversidad int not null,
    FOREIGN KEY(fkUniversidad) REFERENCES Tb_Universidades(idUniversidad)
);

CREATE TABLE Tb_Tipo(
    idTipo int not null auto_increment primary key,
    abreviacionTipo varchar(20) not null,
    descTipo text,
    tipo enum('Maestria','Doctorado','Preparatoria','Curso_Ingles'),
    creationDateTipo timestamp default current_timestamp,
	lastUpdateTipo  timestamp default current_timestamp on update current_timestamp 
);

CREATE TABLE Tb_Facultades(
    idFacultad int not null auto_increment primary key,
    nombreFacultad varchar(80) not null,
    descFacultad text,
    colorFacultad varchar(10),
    creationDateCampus timestamp default current_timestamp,
	lastUpdateCampus  timestamp default current_timestamp on update current_timestamp,
    fkCampus int not null,
    FOREIGN KEY(fkCampus) REFERENCES Tb_Campus(idCampus)
);

CREATE TABLE Tb_Carreras(
    idCarrera int not null auto_increment primary key,
    nombreCarrera varchar(50) not null,
    descCarrera text,
    fkTipoCarrera int not null,
    fkFacultad int not null,
    creationDateCarrera timestamp default current_timestamp,
	lastUpdateCarrera  timestamp default current_timestamp on update current_timestamp,
    FOREIGN KEY(fkTipoCarrera) REFERENCES Tb_Tipo(idTipo),
    FOREIGN KEY(fkFacultad) REFERENCES Tb_Facultades(idFacultad)
);
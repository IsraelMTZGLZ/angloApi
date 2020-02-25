create user 'anglo'@'localhost' identified by 'anglo2020';
grant all privileges on *.* to 'anglo'@'localhost';

Drop database if exists AngloDB;

CREATE DATABASE AngloDB;
USE AngloDB;
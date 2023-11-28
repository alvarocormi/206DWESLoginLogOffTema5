/*
* @author Alvaro Cordero
* @version 1.0
* @since 218/11/2023
*/

/*Creacion de la base de datos*/
create database if not exists DB206DWESLoginLogOff;

/*Se pone en uso la base de datos*/
use DB206DWESLoginLogOff;

/*Creacion de la tabla Usuario*/
create table if not exists T01_Usuario(
T01_CodUsuario varchar(8) primary key,
T01_Password varchar(255),
T01_DescUsuario varchar (255),
T01_NumConexiones int default 1,
T01_FechaHoraUltimaConexion datetime default CURRENT_TIMESTAMP,
T01_Perfil enum('usuario','administrador') default 'usuario',
T01_ImagenUsuario blob)engine=innodb; 
/*Blob es un tipo de dato que almacena un objeto binario grande que puede contener una cantidad variable de datos (mediumblob, blob, tinyblob)*/

/*Creacion de la tabla Departamento*/
create table if not exists T02_Departamento(
T02_CodDepartamento varchar(3) primary key,
T02_DescDepartamento varchar(255),
T02_FechaCreacionDepartamento datetime,
T02_VolumenDeNegocio float,
T02_FechaBajaDepartamento datetime default null)engine=innodb;

/*Creacion del usuario*/
create user 'user206DWESLoginLogOff'@'%' identified by 'P@ssw0rd';
grant all privileges on DB206DWESLoginLogOff.* to 'user206DWESLoginLogOff'@'%';


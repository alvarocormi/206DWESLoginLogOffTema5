<?php
/**
 * @author: Alvaro Cordero Miñambres
 * @since: 23/11/2022
 * @copyright: Copyright (c) 2023, Alvaro Cordero Miñambres
 * Script creadcion de la base de datos
 */
//Incluyo las variables de la conexion
require_once '../conf/confDBPDO.php';

try {
    //Hago la conexion con la base de datos
    $miDB = new PDO(DSN, USERNAME, PASSWORD);

    // Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION
    $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Consulta para eliminar las tablas
    $consulta = <<<CONSULTA
                
            use dbs12302430;

            create table if not exists T01_Usuario(
            T01_CodUsuario varchar(8) primary key,
            T01_Password varchar(255),
            T01_DescUsuario varchar (255),
            T01_NumConexiones INT,
            T01_FechaHoraUltimaConexion datetime default 0,
            T01_Perfil enum('usuario','administrador') default 'usuario',
            T01_ImagenUsuario blob)engine=innodb; 
          

            create table if not exists T02_Departamento(
            T02_CodDepartamento varchar(3) primary key,
            T02_DescDepartamento varchar(255),
            T02_FechaCreacionDepartamento datetime,
            T02_VolumenDeNegocio float,
            T02_FechaBajaDepartamento datetime default null)engine=innodb;

            
            create user 'dbu1636093'@'%' identified by 'P@ssw0rd';
            grant all privileges on dbs12302430.* to 'dbu1636093'@'%';


    CONSULTA;

    $miDB->exec($consulta); //Ejecuto la consulta

    echo 'Tablas creadas con exito';
} catch (PDOException $excepcion) {//Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode(); //Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage(); //Obtengo el mensaje del error y lo almaceno en la variable mensajeException
    echo "<span style='color: red'>Codigo del error: </span>" . $errorExcepcion; //Muestro el codigo del error
    echo "<span style='color: red'>Mensaje del error: </span>" . $mensajeException; //Muestro el mensaje del error
} finally {
    //Cierro la conexion
    unset($miDB);
}
?>
<?php

/**
 * @author Alvaro Cordero <https://github.com/alvarocormi>
 * @author Ismael Ferreras <https://github.com/IsmaelFG>
 * @version 1.0
 * @since 05-12-2023
 * 
 * @Annotation Proyecto LoginLogOFF Alvaro Cordero
 */

/**
 * @link https://www.php.net/manual/function.session-start.php
 * 
 * Iniciamos la sesion mediante session_start()
 * La funcion session_start() -> Iniciar una nueva sesión o reanudar la existente
 */
session_start();

/**
 * Inlucimos la libreria de validacion de formularios
 * Incluimos la configuracion de la base de datos
 */
require_once('../core/231018libreriaValidacion.php');
require_once('../config/confDBPDO.php');


//Esta variable booleana la usaremos para indicar si las respuestas son correctas
$entradaOK = true;

/**
 * Almacenamos los errores del usuario en un array asociativo
 * Estos errores van a controlar la auteticacion del usuario
 * Guardaremos un mensaje en cada uno de ellos cuando ocurra un error en el mismo
 */
$aErrores = [
    'usuario' => '',
    'contrasena' => ''
];

/**
 * @link https://www.php.net/manual/function.isset.php
 * 
 * Comprobamos mediante la funcion isset si el usuario le ha dado a enviar 
 * La funcion isset() Determina si una variable está definida y no es null .
 */
if (isset($_REQUEST['enviar'])) {

    /**
     * Validamos el usuario 
     * Validamos la contrseña
     * 
     * Todas estas validaciones estan realizdas usando la libreria de validacion
     * comprobarAlfanumerico() -> Funcion que compueba si el parametro recibido esta compuesto por caracteres alfabeticos y numericos conjuntamente.
     * validarPassword() -> Funcion que compueba si el parametro recibido es una contraseña valida.
     */
    $aErrores['usuario'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['usuario'], 32, 4, 1);
    $aErrores['contrasena'] = validacionFormularios::validarPassword($_REQUEST['contrasena'], 32, 4, 2, 1);

    //Abrimos un bloque try catch para tener un mejor control de los errores
    try {
        /**
         * @link https://www.php.net/manual/pdo.construct.php
         * 
         * Iniciamos la conexion con la base de datos mediante la clase PDO
         * DNS -> Host de la base de datos
         * USERNAME -> Usuario de la base de datos
         * PASSWORD -> Contraseña de la abse de datos
         */
        $miDB = new PDO(DSN, USERNAME, PASSWORD);


        /**
         * @link https://www.php.net/manual/function.hash.php
         * 
         * Creamos una nueva contraseña mediante la funcion hash()
         * Le pasamos como parametro el usuario y la contraseña
         * 
         * hash() -> Generar un valor hash (resumen de mensaje)
         * Preparamos la consulta de seleccion
         * Para comprobar que el usuario y la contraseña son correctos
         */
        $stmt = $miDB->prepare('SELECT * FROM T01_Usuario WHERE T01_CodUsuario="' . $_REQUEST['usuario'] . '" and T01_Password="' . hash("sha256", ($_REQUEST['usuario'] . $_REQUEST['contrasena'])) . '";');

        //Ejecutamos la consulta
        $stmt->execute();

        /**
         * @link https://www.php.net/manual/pdostatement.fetchobject.php
         * 
         * Almacenamos el resultado de la query como objetos mediante la funcion fecthObject()
         * fetchObject() -> Obtiene la siguiente fila y la devuelve como un objeto
         */
        $oUsuarioActivo = $stmt->fetchObject();

        //Si no es resultado o no devuelve nada
        if (!$oUsuarioActivo) {

            //Guardamos un mensaje de error
            $aErrores['usuario'] = "Error de autenticacion";

            //Ponemos la entradaOk a false
            $entradaOK = false;
        }

        // Recorre aErrores para ver si hay algun error
        foreach ($aErrores as $campo => $valor) {

            //Si el valor es distinto de null
            if ($valor != null) {

                //Ponemos la entradaOK a false
                $entradaOK = false;

                // Limpiamos el campo
                $_REQUEST[$campo] = '';
            }
        }

        /**
         * @link https://www.php.net/manual/class.pdoexception.php
         * 
         * Controlamos los errores mediante la clase PDOException
         * PDOException() -> Representa un error generado por PDO.
         */
    } catch (PDOException $exception) {
        // Si aparecen errores, se muestra por pantalla el error
        echo ('<div><p>Ha fallado la conexion: ' . $exception->getMessage() . '</p></div>');
    }


    //Si el usuario no ha pulsado el boton enviar 
} else {

    //Ponemos la entradaOK a false
    $entradaOK = false;
}

//En caso de que '$entradaOK' sea true, cargamos las respuestas en el array '$aRespuestas' 
if ($entradaOK) {

    //Abrimos un bloque try catch para tener un mejor control de los errores
    try {
        //Incrementamos el número de conexiones
        $numConexionActual = $oUsuarioActivo->T01_NumConexiones + 1;

        /**
         * Actualizamos la fecha y la hora de la ultima conexion
         * Lo guardamos en una variable y no lo hacemos directamente en la consulta de actualizacion
         * Con el objetivo de guardar la fecha y hora de ulima conexion anterioir
         */
        $fechaHoraUltimaConexionAnterior = $oUsuarioActivo->T01_FechaHoraUltimaConexion;

        /**
         * Realizamos la consulta de aztualizacion
         * Mediante esta consulta vamos a actualizar el numero de conexiones de la base de datos del usuario
         * Lo realizamos mediante Current_TimeStamp para que nos coga la fecha y la hora actual
         */
        $sqlActualizacion = 'UPDATE T01_Usuario SET T01_NumConexiones =' . $numConexionActual . ', T01_FechaHoraUltimaConexion=now() WHERE T01_CodUsuario="' . $_REQUEST['usuario'] . '";';

        //Preparamos la consulta
        $consultaActualizacion = $miDB->prepare($sqlActualizacion);

        //Se ejecuta la consulta de actualizacion
        $consultaActualizacion->execute();

        /**
         * Configuramos las sesiones para almacenar los datos del usuario
         * Lo realizamos mediante la variable $_SESSION
         */
        $_SESSION['usuario'] = $oUsuarioActivo->T01_CodUsuario;
        $_SESSION['numConexiones'] = $numConexionActual;
        $_SESSION['ultimaConexion'] = $fechaHoraUltimaConexionAnterior;

        /**
         * Redirigimos al usuario a la pagina Progam.php
         * Para echo usamos la etiqueta <meta/> y le pasamos como parametro la url del fichero
         */
        echo '<meta http-equiv="refresh" content="0;url=Programa.php">';

        /**
         * @link https://www.php.net/manual/function.exit.php
         * 
         * Cerramos la ejecucion del progama
         * Mediante exit podremos cerrar la ejecucion del progama
         */
        exit();

        /**
         * @link https://www.php.net/manual/class.pdoexception.php
         * 
         * Controlamos los errores mediante la clase PDOException
         * PDOException() -> Representa un error generado por PDO.
         */
    } catch (PDOException $exception) {

        // Si aparecen errores, se muestra por pantalla el error
        echo ('<div><p>Ha fallado la conexion: ' . $exception->getMessage() . '</p></div>');

       //Pase lo que pase 
    } finally {

        // Se cierra la conexion con la base de datos
        unset($miDB);
    }

    //Si el fromulario a sido enviado pero el usuario o contraseña no ha sido valdiado 
} else {
    // Mostramos un mensaje de error y el formulario nuevamente
    require_once("../codigoPHP/form.php");

    //Importamos el footer
    require_once("../codigoPHP/footer.php");
}

<?php
// Iniciar la sesión
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Iconos-->
    <link rel="shortcut icon" href="../webroot/img/palma.png" type="image/x-icon" />
    <!--CSS-->
    <link rel="stylesheet" href="../webroot/css/styleForm.css" />

    <title>Login- Alvaro Cordero Miñambres</title>
</head>

<body>
    <div class="center">
        <h1>Login</h1>
        <?php
        /**
         * Inlucimos la libreria de validacion de formularios
         * Incluimos la configuracion de la base de datos
         */
        require_once('../core/231018libreriaValidacion.php');
        require_once('../config/confDBPDO.php');

        //Esta variable booleana la usaremos para indicar si las respuestas son correctas
        $entradaOK = true;

        // Inicializamos la fecha actual ya que es un campo deshabilitado
        $_REQUEST['fecha_deshabilitada'] = date('Y-m-d - H:i:s');

        // Almacena las respuestas
        $aRespuestas = [
            'usuario' => '',
            'contrasena' => ''
        ];

        // Almacena los errores
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

            /**
             * @link https://www.php.net/manual/pdo.construct.php
             * 
             * Iniciamos la conexion con la base de datos mediante la clase PDO
             * DNS -> Host de la base de datos
             * USERNAME -> Usuario de la base de datos
             * PASSWORD -> Contraseña de la abse de datos
             */
            $miDB = new PDO(DSN, USERNAME, PASSWORD);

            //Recogemos el nombre del usuario
            $usuario = $_REQUEST['usuario'];

            //Recogemos la contraseña del usuario
            $contrasena = $_REQUEST['contrasena'];

            /**
             * @link https://www.php.net/manual/function.hash.php
             * 
             * Creamos una nueva contraseña mediante la funcion hash()
             * Le pasamos como parametro el usuario y la contraseña
             * 
             * hash() -> Generar un valor hash (resumen de mensaje)
             */
            $hashContrasena = hash('sha256', $usuario . $contrasena);

            /**
             * Preparamos la consulta de seleccion
             * Para comprobar que el usuario y la contraseña son correctos
             */
            $stmt = $miDB->prepare("SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :usuario AND T01_Password = :hashContrasena");

            //Ejecutamos la consulta
            $stmt->execute(['usuario' => $usuario, 'hashContrasena' => $hashContrasena]);

            /**
             * @link https://www.php.net/manual/pdostatement.fetchobject.php
             * 
             * Almacenamos el resultado de la query como objetos mediante la funcion fecthObject()
             * fetchObject() -> Obtiene la siguiente fila y la devuelve como un objeto
             */
            $oUsuarioActivo = $stmt->fetchObject();

            //Si no es resultado o no devuelve nada
            if (!$oUsuarioActivo) {

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

            //Si el usuario no ha pulsado el boton enviar 
        } else {

            //Ponemos la entradaOK a false
            $entradaOK = false;
        }

        //En caso de que '$entradaOK' sea true, cargamos las respuestas en el array '$aRespuestas' 
        if ($entradaOK) {

            //Almacenamos las respuestas del usuario
            $aRespuestas['usuario'] = $_REQUEST['usuario'];
            $aRespuestas['contrasena'] = $_REQUEST['contrasena'];

            //Incrementamos el número de conexiones
            $numConexiones = $oUsuarioActivo->T01_NumConexiones + 1;

            //Actualizamos la fecha y hora de la última conexión
            $fechaHoraUltimaConexion = $oUsuarioActivo->T01_FechaHoraUltimaConexion;

            /**
             * Realizamos la consulta de aztualizacion
             * Mediante esta consulta vamos a actualizar el numero de conexiones de la base de datos del usuario
             */
            $miDB->query("UPDATE T01_Usuario SET T01_NumConexiones = $numConexiones, T01_FechaHoraUltimaConexion = CURRENT_TIMESTAMP WHERE T01_CodUsuario = '$usuario'");

            /**
             * Configuramos las sesiones para almacenar los datos del usuario
             * Lo realizamos mediante la variable $_SESSION
             */
            $_SESSION['usuario'] = $oUsuarioActivo->T01_DescUsuario;
            $_SESSION['numConexiones'] = $numConexiones;
            $_SESSION['ultimaConexion'] = $fechaHoraUltimaConexion;

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

            //Si el fromulario a sido enviado pero el usuario o contrasena no ha sido valdiado 
        } else {

            /**
             * Mediante la funcion isset() comprobamos si el usuario le ha dado a enviar y si el resultado es true
             * la funcion isset() -> Determina si una variable está definida y no es null .
             */
            if (isset($_REQUEST['enviar']) && !$oUsuarioActivo) {
                // Mostramos un mensaje de error y el formulario nuevamente
        ?>

                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="txt_field">
                        <input type="text" id="usuario" name="usuario" value="<?php echo (isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : ''); ?>">
                        <span class="raya"></span>
                        <label>Usuario: </label>

                    </div>
                    <div class=" txt_field">
                        <input type="password" id="contrasena" name="contrasena" value="<?php echo (isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : ''); ?>">
                        <span class="raya"></span>
                        <label>Password: </label>

                    </div>

                    <p class='error' style="color: red; font-size: 15px; margin-bottom: 15px; ">Usuario o contrasena incorrectos. Inténtalo de nuevo.</p>
                    <input type="submit" value="Iniciar Sesion" name="enviar">
                </form>
            <?php
            } else {
                // Formulario que se le muestra al cliente para que lo rellene
            ?>
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="txt_field">
                        <input type="text" id="usuario" name="usuario" value="<?php echo (isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : ''); ?>">
                        <span class="raya"></span>
                        <label>Usuario: </label>

                    </div>
                    <div class=" txt_field">
                        <input type="password" id="contrasena" name="contrasena" value="<?php echo (isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : ''); ?>">
                        <span class="raya"></span>
                        <label>Password: </label>


                    </div>

                    <input type="submit" value="Iniciar Sesion" name="enviar">
                </form>
    </div>
<?php
            }
        }

?>

</body>

</html>
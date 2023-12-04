<?php
session_start(); // Iniciar la sesión
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <!--Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.1.3/darkly/bootstrap.min.css" integrity="sha512-ZdxIsDOtKj2Xmr/av3D/uo1g15yxNFjkhrcfLooZV5fW0TT7aF7Z3wY1LOA16h0VgFLwteg14lWqlYUQK3to/w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Iconos-->
    <link rel="shortcut icon" href="../webroot/img/palma.png" type="image/x-icon" />
    <!--CSS-->
    <link rel="stylesheet" href="../webroot/css/main.css" />
    <link rel="stylesheet" href="../webroot/css/loginLogOff.css" />

    <title>Login- Alvaro Cordero Miñambres</title>
</head>

<body>
    <header>
        <div class="daw">
            <span>DWES.</span>
        </div>
    </header>
    <main>
        <div class="contenido">
            <h2>Login</h2>
            <p>IES LOS SAUCES - BENAVENTE</p>

        </div>
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
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-check-inline" style="width: 100%; position: fixed; top: 250px; left: 42%">
                    <div>
                        <label for="usuario" style="color: black;">Usuario: </label>
                        <input type="text" id="usuario" name="usuario" value="<?php echo (isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : ''); ?>">
                        <?php echo ($aErrores['usuario'] != null ? "<span style='color:red; padding: 0; margin: 0;'>" . $aErrores['usuario'] . "</span>" : ''); ?>
                        <br><br>

                        <label for="contrasena" style="margin-top: 5px; color: black;">Password: </label>
                        <input type="contrasena" id="contrasena" name="contrasena" value="<?php echo (isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : ''); ?>">
                        <?php echo ($aErrores['contrasena'] != null ? "<span style='color:red'>" . $aErrores['contrasena'] . "</span>" : null); ?>
                        <br><br>

                        <p class='error' style="color: red; ">Usuario o contrasena incorrectos. Inténtalo de nuevo.</p>
                        <input type="submit" value="Iniciar Sesion" name="enviar">
                    </div>
                </form>
            <?php
            } else {
                // Formulario que se le muestra al cliente para que lo rellene
            ?>
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-check-inline" style="width: 100%; position: fixed; top: 250px; left: 42%">
                    <div>
                        <label for="usuario" style="color: black;">Usuario: </label>
                        <input type="text" id="usuario" name="usuario" value="<?php echo (isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : ''); ?>">
                        <?php echo ($aErrores['usuario'] != null ? "<span style='color:red; padding: 0; margin: 0;'>" . $aErrores['usuario'] . "</span>" : ''); ?>
                        <br><br>

                        <label for="contrasena" style="margin-top: 5px; color: black;">Password: </label>
                        <input type="password" id="contrasena" name="contrasena" value="<?php echo (isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : ''); ?>">
                        <?php echo ($aErrores['contrasena'] != null ? "<span style='color:red'>" . $aErrores['contrasena'] . "</span>" : null); ?>
                        <br><br>


                        <input type="submit" value="Iniciar Sesion" name="enviar">
                    </div>
                </form>
        <?php
            }
        }
        //Importamos el footer
        require_once("../codigoPHP/footer.php");
        ?>

</body>

</html>
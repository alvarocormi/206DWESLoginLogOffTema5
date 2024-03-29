<?php

/**
 * Iniciamos la sesion mediante session_start()
 * La funcion session_start() -> Iniciar una nueva sesión o reanudar la existente
 */
session_start();

/**
 * @link https://www.php.net/manual/function.empty.php
 * 
 * Comprobamos que el usuario, el numero de conexiones y la ultima conexion de la sesion no esten vacios
 * empty() -> Determina si una variable es considerada vacía
 */
if (empty($_SESSION['usuario']) || empty($_SESSION['numConexiones']) || empty($_SESSION['ultimaConexion'])) {

    /**
     * Redirige al usuario al index Login.php mediante la etiqueta <meta/>
     * Pasandole como parametro url el fichero de configuracion
     */
    header("Location: Login.php");

    /**
     * @link https://www.php.net/manual/function.exit.php
     * 
     * Cerramos la ejecucion del progama mediante la funcion exit()
     * exit() -> Finaliza la ejecución del script
     */
    exit();
}


/**
 * Almacenamos los datos de la sesion mediante la variable GLOBAL $_SESSION
 * Almacenamos el usuario, el numero de conexiones y la ultima conexion
 */
$usuario = $_SESSION['usuario'];
$numConexiones = $_SESSION['numConexiones'];
$ultimaConexion = $_SESSION['ultimaConexion'];



/**
 * Comprobamos que le ha dado al boton de cerrar sesion
 * Mediante isset() -> Determina si una variable está definida y no es null .
 */
if (isset($_POST['cerrar_sesion'])) {

    /**
     * @link https://www.php.net/manual/function.session-unset.php
     * 
     * Liberamos todas las variables de sesion
     * Mediante session_unset() -> podremos liberar todas las variables de sesion actualmente registrtadas
     */
    session_unset();

    /**
     * @link https://www.php.net/manual/function.session-destroy.php
     * 
     * Destruimos la sesion
     * Mediante session_destroy() -> podremos destruir toda la informacion asociada con la sesion actual
     */
    session_destroy();

    /**
     * Redirigimos al usuario al Login
     * Mediante la funcion header pasandole el parametro Location
     */
    header("Location: ../indexLoginLogOff.php");

    /**
     * @link https://www.php.net/manual/function.exit.php
     * 
     * Finzalizamos la ejecucion del script
     * Mediante exit() -> podremos finalizar la ejecucion del script
     */
    exit();
}

$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'es';

// Define los mensajes según el idioma
//Si el idioma es espñaol
if ($idioma == 'es') {
    
    //Guaradamos en una variable el mensaje de bienvenida
    $bienvenida = "Bienvenido, {$usuario}";

    //Guardamos en una variable el numero de conexiones
    $numConexiones = "Esta es tu <strong>{$numConexiones}</strong> vez conectándote";

    //Si el numero de exiones es 1
    if ($_SESSION['numConexiones'] == 1) {

        //Guardamos un mensaje indicandole que es la primera vez que se conecta el usuario
        $ultimaConexion = "Esta es la primera vez que te conectas";
    } else {

        //Si se ha conectado mas veces le mostramos otro mensaje
        $ultimaConexion = "Te conectaste por última vez <strong>{$ultimaConexion}.</strong>";
    }

    //Si el idioma es ingles repetimos el mismo proceso
} elseif ($idioma == 'en') {
    $bienvenida = "Welcome, {$usuario}";
    $numConexiones = "This is your <strong>{$numConexiones}</strong> time logging in";
    if ($_SESSION['numConexiones'] == 1) {
        $ultimaConexion = "This is the first time you connect";
    } else {
        $ultimaConexion = "You last logged in on <strong>{$ultimaConexion}.</strong>";
    }
}
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.1.3/darkly/bootstrap.min.css" integrity="sha512-ZdxIsDOtKj2Xmr/av3D/uo1g15yxNFjkhrcfLooZV5fW0TT7aF7Z3wY1LOA16h0VgFLwteg14lWqlYUQK3to/w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Iconos-->
    <link rel="shortcut icon" href="../webroot/img/palma.png" type="image/x-icon" />
    <!--CSS-->
    <link rel="stylesheet" href="../webroot/css/progama.css" />

    <title>Alvaro Cordero Miñambres - Progama</title>
</head>

<body>
    <main>
        <div class="center">

            <div class="card text-center" style="width: 370px; height: 320px; background-color: white;">
                <div class="card-body" style="color: black; padding: 15px;">
                    <h5 class="card-title" style="font-size: 1.7rem; font-weight: bold;"><?php echo ("$bienvenida") ?></h5>
                    <hr>
                    <p class="card-text" style="font-size: 18px;"><?php echo ("$numConexiones") ?><?php echo ("<br>$ultimaConexion") ?></p>
                    <a href=" ./Detalle.php" class="btn btn-primary" style="position: absolute; bottom: 10px; right: 10px; width: 150px; height: 50px; line-height: 35px; background: #2691d9; font-weight: bold;">Detalle</a>
                    <?php
                    echo '<form method="post" action="">';
                    echo ('<input style="position: absolute; bottom: 10px; left: 10px; width: 150px; height: 50px; background-color: #bf1515; font-weight: bold;" type="submit" name="cerrar_sesion" class="btn btn-primary" value="Cerrar Sesión">');
                    echo '<form method="post" action="">';
                    ?>
                </div>
            </div>
        </div>

    </main>
</body>
<?php require_once("../codigoPHP/footer.php") ?>


</html>
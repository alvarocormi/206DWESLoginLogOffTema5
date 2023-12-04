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
if(empty($_SESSION['usuario']) || empty($_SESSION['numConexiones']) || empty($_SESSION['ultimaConexion'])){

    /**
     * Redirige al usuario al index Login.php mediante la etiqueta <meta/>
     * Pasandole como parametro url el fichero de configuracion
     */
    echo '<meta http-equiv="refresh" content="0;url=Login.php">'; 

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
     * Mediante la etiqueta html <meta> y el atributo url
     */
    echo '<meta http-equiv="refresh" content="0;url=Login.php">';

    /**
     * @link https://www.php.net/manual/function.exit.php
     * 
     * Finzalizamos la ejecucion del script
     * Mediante exit() -> podremos finalizar la ejecucion del script
     */
    exit();
}

// Mostrar la información
echo "Bienvenido, $usuario.<br>";
echo "Esta es tu $numConexiones vez conectándote.<br>";
echo "Te conectaste por última vez el $ultimaConexion.<br>";

// Formulario de cierre de sesión
echo '<form method="post" action="">';
echo '<input type="submit" name="cerrar_sesion" value="Cerrar Sesión">';
echo '</form>';

echo '<a href="Detalle.php">Detalle</a>';
?>
<?php
session_start(); // Iniciar la sesión

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

if (isset($_REQUEST['atras'])) {
    // Se redirige al usuario al login
    header('Location: Programa.php'); // Llevo al usuario a la pagina 'programa.php'
    // Termina el programa
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Iconos-->
    <link rel="shortcut icon" href="../../webroot/img/palma.png" type="image/x-icon" />
    <!--CSS-->
    <link rel="stylesheet" href="../webroot/css/loginLogOff.css" />
    <!-- TITULO -->
    <title>Login- Alvaro Cordero Miñambres</title>
</head>

<body>
    <div>
        <form name="Programa" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <button class="btn btn-secondary" aria-disabled="true" type="submit" name="atras">Volver</button>
        </form>
        <?php
        /**
         * @author Ismael Ferreras García, Alvaro Cordero Miñambres
         * @version 1.0
         * @since 06/12/2023
         * 
         * En este apartado pas a poder comprobar el contenido de las variables de sesion.
         */
        echo '<br><br><h3>Variable <b>$_SERVER</b></h3>';
        foreach ($_SERVER as $key => $value) {
            echo "<b>$key</b>: $value<br>";
        }

        if (isset($_SESSION)) {
            echo '<br><br><h3>Variable <b>$_SESSION</b></h3>';
            foreach ($_SESSION as $key => $value) {
                echo "<b>$key</b>: $value<br>";
            }
        } else {
            echo '<h3>La variable <b>$_SESSION</b> no está definida</h3>';
        }

        echo '<br><br><h3>Variable <b>$_COOKIE</b></h3>';
        foreach ($_COOKIE as $key => $value) {
            echo "<b>$key</b>: $value<br>";
        }

        echo '<br><br><h3>Variable <b>$_GET</b></h3>';
        foreach ($_GET as $key => $value) {
            echo "<b>$key</b>: $value<br>";
        }

        echo '<br><br><h3>Variable <b>$_POST</b></h3>';
        foreach ($_POST as $key => $value) {
            echo "$key: $value<br>";
        }

        echo '<br><br><h3>Variable <b>$_FILES</b></h3>';
        foreach ($_FILES as $key => $value) {
            echo "<b>$key</b>: $value<br>";
        }

        echo '<br><br><h3>Variable <b>$_REQUEST</b></h3>';
        foreach ($_REQUEST as $key => $value) {
            echo "<b>$key</b>: $value<br>";
        }

        echo '<br><br><h3>Variable <b>$_ENV</b></h3>';
        foreach ($_ENV as $key => $value) {
            echo "<b>$key</b>: $value<br>";
        }

        echo '<br><br><h3>Variable <b>$GLOBALS</b></h3>';
        echo '<pre>';
        print_r($GLOBALS);
        echo '</pre>';
        phpinfo();
        ?>

    </div>
</body>
<footer style="position: fixed;
                bottom: 45px;
                left: 80%;
                width: 100%;">
    <div class="enlaces-footer">
        <a href="../../index.html" style="color: black; text-decoration: none; font-size: 20px;">
            © Alvaro Cordero</a>
        <a href="../../206DWESLoginLogOffTema5/indexLoginLogOff.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="black" class="bi bi-house-fill" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"></path>
                <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"></path>
            </svg>
        </a>
        <a href="https://github.com/alvarocormi/206DWESLoginLogOffTema5" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="black" class="bi bi-github" viewBox="0 0 16 16">
                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path>
            </svg></a>
        <a href="https://es.linkedin.com/in/%C3%A1lvaro-cordero-mi%C3%B1ambres-2a1893233" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="black" class="bi bi-linkedin" viewBox="0 0 16 16">
                <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z" />
            </svg>
        </a>
    </div>
</footer>

</html>
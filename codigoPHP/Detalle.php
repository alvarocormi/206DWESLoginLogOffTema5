<?php
session_start(); // Iniciar la sesión
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Iconos-->
    <link rel="shortcut icon" href="../../webroot/img/palma.png" type="image/x-icon" />
    <!--CSS-->
    <link rel="stylesheet" href="../webroot/css/main.css" />
    <link rel="stylesheet" href="../webroot/css/loginLogOff.css" />
    <!-- TITULO -->
    <title>Login- Alvaro Cordero Miñambres</title>
</head>

<body>
    <div class="contenido">
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
<?php require_once("../codigoPHP/footer.php"); ?>
</html>
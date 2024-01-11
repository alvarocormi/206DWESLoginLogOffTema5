<?php

/**
 * @author Alvaro Cordero Miñambres, Ismael Ferreras García
 * @version 1.0
 * @since 21/11/2023
 */

//Comprobamos si la cooki es nula o esta vacia
if (!isset($_COOKIE['idioma'])) {

	// En caso negativo la creamos y ponemos el valor por defecto
	setcookie("idioma", "es", time() + (30 * 24 * 60 * 60), "/");
}

//Si se ha enviado algo mediante el metodo POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Redirige a la página del programa
	header("Location: codigoPHP/Login.php");

	//Finaliza la ejecuion del script
	exit();
}

//Si el idioma enviado por metodo GET esta vacio o es null
if (isset($_REQUEST['idioma'])) {
	/**
	 * @link https://www.php.net/manual/function.setcookie.php
	 * 
	 * Creamos una cookie y le pasamos el idioma y el tiempo que queremos que dure la cookie
	 * setcookie -> define una cookie para ser enviada junto con el resto de cabeceras HTTP
	 */
	setcookie("idioma", $_REQUEST['idioma'], time() + (30 * 24 * 60 * 60), "/");

	//Te redidirge a la pagina en la que estas actualmente
	header('Location: ' . $_SERVER['PHP_SELF']);

	//Finalizamos la ejecucion del script
	exit;
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
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.1.3/darkly/bootstrap.min.css" integrity="sha512-ZdxIsDOtKj2Xmr/av3D/uo1g15yxNFjkhrcfLooZV5fW0TT7aF7Z3wY1LOA16h0VgFLwteg14lWqlYUQK3to/w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- Iconos-->
	<link rel="shortcut icon" href="../webroot/img/palma.png" type="image/x-icon" />
	<!--CSS-->
	<link rel="stylesheet" href="./webroot/css/main.css" />
	<link rel="stylesheet" href="./webroot/css/loginLogOff.css" />

	<title>Alvaro Cordero Miñambres - LoginLogoff</title>
</head>

<body>
	<header>
		<div class="daw">
			<span>DWES.</span>
		</div>
	</header>
	<main>
		<div class="contenido">
			<h2>Login Logoff</h2>
			<p>IES LOS SAUCES - BENAVENTE</p>
			<button type="button" class="btn btn-secondary btn-lg" style="background-color: #6b7280;">
				<a href="./codigoPHP/Login.php" class="text-white text-decoration-none">LOGIN</a>
			</button>
			<br>
			<div>
				<a class="boton" href="?idioma=es">
					<img src="./webroot/img/spain.jpg" alt="es" width="40" height="30">
				</a>
				<a class="boton" href="?idioma=en">
					<img src="./webroot/img/english.png" alt="en" width="40" height="30">
				</a>
			</div>
			<img src="./webroot/img/fondo.png" alt="" width="650px" style="margin-top: 30px;">
		</div>

	</main>
</body>
<?php require_once("./codigoPHP/footer.php") ?>

</html>
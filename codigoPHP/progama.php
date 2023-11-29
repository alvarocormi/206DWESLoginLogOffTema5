<?php

/**
 * @author Alvaro Cordero Miñambres <https://github.com/alvarocormi>
 * @version 1.0
 * @since 29/11/2023
 * @Annotation Control de acceso mediante un fornularo y la funcion header en php
 */

/**
 * @link  https://php.net/manual/en/function.session-start.php
 * 
 * Podremos iniciar la sesion gracias a la funcion sesion_start()
 * session_start() -> Devuelve tru si la session se ha iniciado
 */
session_start();

/* Con la superglobal $_SESSION compruebo si tiene un valor nulo, es decir,
 * el usuario no se la logado o lo ha hecho incorréctamente, en tal caso, 
 * lo redirijo a login.php
 */
if (is_null($_SESSION['user206DWESLoginLogOffTema5'])) {

	//Mediante la funcion header y el parametro location reridigo al usuairo a la pagina de login
	header('Location: login.php');

	//Finalizamos la ejecucion del script
	exit;
}

/* El usuario tiene un botón de salir, en caso de pulsarlo, borro los datos de la superglobal
 * $_SESSION, destruyo la session previamente iniciada y lo redirijo a login.php  */
if (isset($_REQUEST['salir'])) {

	//Ponemos los valores de 
	$_SESSION['user206DWESLoginLogOffTema5'] = null;
	$_SESSION['FechaHoraUltimaConexionAnterior'] = null;

	/**
	 * @link https://php.net/manual/en/function.session-destroy.php
	 * 
	 * Destruimos o cerramos la sesion mediante la funcion session_setroy()
	 * session_destroy() -> Devuelve true o false
	 */
	session_destroy();

	//Mediante la funcion header y el parametro location reridigo al usuairo a la pagina de login
	header('Location: login.php');

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
	<link rel="stylesheet" href="../webroot/css/main.css" />
	<link rel="stylesheet" href="../webroot/css/loginLogOff.css" />

	<title>Progama</title>
</head>

<body>
	<header>
		<div class="daw">
			<span>DWES.</span>
		</div>
	</header>
	<main>
		<div class="contenido">
			<h2>Login correcto, bienvenido <?php $_SESSION['user206DWESLoginLogOffTema5']->T01_DescUsuario ?></h2>
			<p>IES LOS SAUCES - BENAVENTE</p>
			<?php
			/**
			 * Si el numero de coonexiones del usuario es mayor a 1
			 * Toda esta informacion esta guardada en la base de datos
			 */
			if ($_SESSION['user206DWESLoginLogOffTema5']->T01_NumConexiones > 1) {

				/**
				 * Mostramos por pantalla el mensaje de bienvenida
				 * Bienvenido "descripción del usuario" esta es la "n" vez que te conectas; usted se conectó por última vez el "fecha-hora de la última conexión * anterior"
				 */
				echo "<p>Bienvenido " .$_SESSION['user206DWESLoginLogOffTema5']->T01_DescUsuario ." esta es la ".$_SESSION['user206DWESLoginLogOffTema5']->T01_NumConexiones ." que te conectas". "usted se conecto por ultima vez: " . $_SESSION['FechaHoraUltimaConexionAnterior'] . "</p>";


				//Si el numero de conexiones es menor a 1
			} else {
				echo "<p>Bienvenido " .$_SESSION['user206DWESLoginLogOffTema5']->T01_DescUsuario ." esta es la primera vez que te conectas". "usted se conecto por ultima vez: " . $_SESSION['FechaHoraUltimaConexionAnterior'] . "</p>";
			}

			?>
		</div>
	</main>
</body>
<?php require_once("../codigoPHP/footer.php") ?>

</html>
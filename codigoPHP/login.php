<?php

/**
 * @author Alvaro Cordero Miñambres <https://github.com/alvarocormi>
 * @version 1.0
 * @since 29/11/2023
 * @Annotation Control de acceso mediante un fornularo y la funcion header en php
 */

/**
 * Incluimos la libreria de validacion de formularios
 * Inluimos el fichero de configuracion de la base de datos
 */
require_once("../core/231018libreriaValidacion.php");
require_once("../config/confDBPDODesarrollo.php");

//Inicialiamos la variable booleana entradaOK para comprobar si los datos son correctos
$entradaOK = true;

/**
 * Array de errores para evaluar si son las campos introducidos por el usuario son correctos
 * usuario -> Comprobamos si el nombre del usuario cumple la sintaxis correcta
 * password -> Comprobamos si la contraseña cumple la sintaxis correcta
 */
$aErrores = [
	'usuario' => null,
	'password' => null
];

/**
 * Escribimos la consulta para comprobar si el usuario existe o no en la base de datos
 * Lo haremos mediante una variable HEREDOC para que la lectura del codigo sea mas agradable
 */
$consultaSeleccion = <<< sql1
	SELECT * FROM T01_Usuario WHERE T01_CodUsuario=:codUsuario
sql1;

/**
 * Escribimos la consulta para actualizar el numero de conexiones del usuario y la fecha de la ultima conexion
 * Lo haremos mediante una variable HEREDOC para que la lectura del codigo sea mas agradable
 */
$consultaActualizacion = <<< sql2
	UPDATE T01_Usuario SET T01_NumConexiones=T01_NumConexiones+1,
	T01_FechaHoraUltimaConexion= unix_timestamp()
	WHERE T01_CodUsuario=:codUsuario;
sql2;

//Abrimos un bloque try catch para controlar los errores
try {
	/**
	 * Comprobamos si se ha pulsado el boton de iniciar sesion mediante la funcion isset()
	 * isset() -> Valida si el campo esta null o vacio
	 */
	if (isset($_REQUEST['iniciarSesion'])) {
		/*Establecimiento de la conexion
		/*
			Instanciamos un objeto PDO y establecemos la conexión
			Construccion de la cadena PDO: (ej. 'mysql:host=localhost; dbname=midb')
			host – nombre o dirección IP del servidor
			dbname – nombre de la base de datos
        */
		$miDB = new PDO(DSN, USER, PASSWORD);

		/**
		 * Comprobamos mediante los metodos de la libreria de validacion
		 * Si los campos usuario y contraseña esta correctamente escritos
		 */
		$aErrores['usuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['usuario'], 8, 4, 1);
		$aErrores['password'] = validacionFormularios::validarPassword($_REQUEST['password'], 8, 4, 1);

		//Recorremos los mensajes de error mediante un bucle for each
		foreach ($aErrores as $campo => $error) {
			if ($error == !null) {
				//Limpiamos el campos
				$entradaOK = false;
				$_REQUEST[$campo] = '';
			}
		}

		//Si la entrada es OK
		if ($entradaOK) {

			//Preparamos la consulta de seleccion
			$queryConsultaPorCodigo = $miDB->prepare($consultaSeleccion);

			//Preparamos los parametros
			$queryConsultaPorCodigo->bindParam(':codUsuario', $_REQUEST['usuario']);

			//Ejecutamos la consulta
			$queryConsultaPorCodigo->execute();

			//objeto donde guardar lo devuelto por el select.
			$oUsuario = $queryConsultaPorCodigo->fetchObject();

			/** 
			 * @link https://www.php.net/manual/function.hash.php
			 * 
			 * Comprobamos si la contraseña y el usuario coinciden con los de la base de datos	  
			 * Para comprobar la contraseña usamos la funcion hash la cual convierte una cadena en un algoritmo sha256
			 */
			if (!$oUsuario || $oUsuario->T01_Password != hash('sha256', ($_REQUEST['usuario'] . $_REQUEST['password']))) {

				//Si no coinciden pasamos la entradaOK a false
				$entradaOK = false;
			}
		}

		//Si no e ha pulsado iniciar sesion, mostramos el formulario
	} else {

		//Ponemos la variable entradaOK a false
		$entradaOK = false;
	}

	//Mediante PDOException mostramos un mensaje de error cuando salte la exception
} catch (PDOException $excepcion) {
	/**
	 * Mostramos los mensajes de error
	 * getMessage() -> Devuelve mensaje de error
	 * getCode() -> Devuelve el codigo del error
	 */
	echo 'Error: ' . $excepcion->getMessage() . "<br>";
	echo 'Código de error: ' . $excepcion->getCode() . "<br>";

	//Pase lo que pase la sesion de la base de datos se cerrara
} finally {

	//Mediante unset cerramos la sesion de la base de datos
	unset($miDB);
}

//Si la entrada es OK
if ($entradaOK) {
	//Iniciamos la sesión mediante la funcion session start
	session_start();

	/** 
	 * @link https://www.php.net/manual/es/reserved.variables.session.php
	 * 
	 * Introducimos el usuario en la sesion dándole valor a la superglobal $_SESSION. Esta superglobal
	 * será utilizada en los siguientes archivos PHP para comprobar si el usuario tenía sesión abierta, es decir,
	 * se ha logado corréctamente. 
	 */
	$_SESSION['user206DWESLoginLogOffTema5'] = $oUsuario;

	/**
	 * Al tener la base de datos el campo FechaHoraUltimaConexion como timestamp, tengo que comprobar que,
	 * si no es null, construya un objeto datetime con la fecha actual. 
	 */
	if (!is_null($oUsuario->T01_FechaHoraUltimaConexion)) {

		//Creamos un objeto DateTime con la hora y la fecha actual
		$oFechaTimesTamp = new DateTime();

		/**
		 * Le establezco la fecha con el valor devuelto al objeto $oUsuario al ejecutar la consulta por codigo
		 * y hacer fetchObject sobre el.          
		 */
		$oFechaTimesTamp->setTimestamp($oUsuario->T01_FechaHoraUltimaConexion);

		//Y se guarda en el $_SESSION el valor de la fecha de su última conexion ya formateada
		$_SESSION['FechaHoraUltimaConexionAnterior'] = $oFechaTimesTamp->format('d/m/Y H:i:s T');
	}
	// Si no ha habido conexiones anteriores
	else {

		//Podremos la fecha de ultima conexion a null
		$_SESSION['FechaHoraUltimaConexionAnterior'] = null;
	}

	//Abrimos un bloque try catch para tener un mayor control de los errores
	try {
		/*Establecimiento de la conexion
		/*
			Instanciamos un objeto PDO y establecemos la conexión
			Construccion de la cadena PDO: (ej. 'mysql:host=localhost; dbname=midb')
			host – nombre o dirección IP del servidor
			dbname – nombre de la base de datos
        */
		$miDB = new PDO(DSN, USER, PASSWORD);

		/**
		 * Actualizacion ultimo usuario
		 * Preparamos la consulta de actualizacion
		 */
		$queryActualizacion = $miDB->prepare($sQueryActualizacion);

		//Preparamos los parametros de la consulta
		$queryActualizacion->bindParam(":codUsuario", $oUsuario->T01_CodUsuario);

		//Ejecutamos la consulta
		$queryActualizacion->execute();

		//Volvemos a buscar el usuario para actualizar el objeto usuario
		$queryConsultaPorCodigo = $miDB->prepare($sQuerySeleccion);

		//Preparamos los parametros de la consulta
		$queryConsultaPorCodigo->bindParam(':codUsuario', $_REQUEST['usuario']);

		//Ejecutamos la consulta
		$queryConsultaPorCodigo->execute();

		//Guardamos el valor de la consulta en un objeto 
		$oUsuario = $queryConsultaPorCodigo->fetchObject();

		//Mediante PDOException mostramos un mensaje de error cuando salte la exception
	} catch (PDOException $excepcion) {
		/**
		 * Mostramos los mensajes de error
		 * getMessage() -> Devuelve mensaje de error
		 * getCode() -> Devuelve el codigo del error
		 */
		echo 'Error: ' . $excepcion->getMessage() . "<br>";
		echo 'Código de error: ' . $excepcion->getCode() . "<br>";

		//Pase lo que pase la sesion de la base de datos se cerrara
	} finally {

		//Mediante unset cerramos la sesion de la base de datos
		unset($miDB);
	}

	//Fecha actual
	$oFechaActual = new DateTime('now');

	//A la que le añado 60 minutos
	$oFechaDentroDeUnaHora = $oFechaActual->add(new DateInterval("PT60M"));

	//Y de esta última obtengo el timestamp
	$enteroFechaDentroDeUnaHora = $oFechaDentroDeUnaHora->getTimestamp();

	/**
	 * @link https://www.php.net/manual/function.header.php
	 * Seguimos dentro del supuesto en que la entrada ha sido válida, por lo tanto, redirigimos al usuario a programa.php
	 */
	header('Location: programa.php');

	//Finalizamos la ejecución del script por seguridad.
	exit();

	//Si la entrada no ha sido correcta	
} else {

	//Imprimo el formulario por pantalla	
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

		<title>Login</title>
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
			<form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-check-inline" style="width: 100%; position: fixed; top: 250px; left: 42%">
					<div>
						<label for="usuario" style="color: black;">Usuario: </label>
						<input type="text" id="usuario" name="usuario" value="<?php echo (isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : ''); ?>">
						<?php echo ($aErrores['usuario'] != null ? "<span style='color:red; padding: 0; margin: 0;'>" . $aErrores['usuario'] . "</span>" : ''); ?>
						<br><br>
				
						<label for="password" style="margin-top: 5px; color: black;">Password: </label>
						<input type="password" id="password" name="password" value="<?php echo (isset($_REQUEST['password']) ? $_REQUEST['password'] : ''); ?>">
						<?php echo ($aErrores['password'] != null ? "<span style='color:red'>" . $aErrores['password'] . "</span>" : null); ?>
						<br><br>


						<input type="submit" value="Iniciar Sesion" name="iniciarSesion">
					</div>
				</form>

		</main>
	<?php } ?>
	</body>
	<?php require_once("../codigoPHP/footer.php") ?>

	</html>
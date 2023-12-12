<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<!-- Iconos-->
	<link rel="shortcut icon" href="../../webroot/img/palma.png" type="image/x-icon" />
	<!--CSS-->
	<link rel="stylesheet" href="../webroot/css/styleForm.css" />
	<!-- TITULO -->
	<title>Login- Alvaro Cordero Miñambres</title>
</head>

<body>
	<div class="center">
		<h1>Login</h1>
		<form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="txt_field">
				<input type="text" id="usuario" name="usuario" value="<?php echo (isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : ''); ?>">
				<span class="raya"></span>
				<label>Usuario: </label>

			</div>
			<div class="txt_field">
				<input type="password" id="contrasena" name="contrasena" value="<?php echo (isset($_REQUEST['contrasena']) ? $_REQUEST['contrasena'] : ''); ?>">
				<span class="raya"></span>
				<label>Password: </label>

			</div>

			<p style="color: red; position: absolute; bottom: 80px; left: 28%"><?php echo (!empty($aErrores["usuario"]) ? $aErrores["usuario"] : ''); ?></p>
			<div class="botons" style="display: flex;">
				<input style="width: 50%; margin-right: 10px;" type="submit" value="Iniciar Sesion" name="enviar">
				<input style="width: 50%; margin-left: 10px; background-color: #bf1515;" type="submit" value="Cancelar" name="cancelar">
			</div>
		</form>
	</div>
</body>

</html>
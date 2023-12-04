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
				<a href="./codigoPHP/login.php" class="text-white text-decoration-none">LOGIN</a>
			</button>
		</div>

	</main>
</body>
<?php require_once("./codigoPHP/footer.php") ?>

</html>
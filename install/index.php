<?php
	define("INSTALLING",true);
	
	include("../core.php");
	include("scripts/install_lib.php");
	
	if($DFW_DB == null){
		header("Location: /{$DFW_PATH}/install/install_database.php");
		exit();
	}
	
	if(!$DFW_IS_SESSION){
		header("Location: /{$DFW_PATH}/install/login.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>
		DFW Zephyra | Administración
	</title>
</head>
	<?php
		DFW_BOOTSTRAP();
	?>
	<link rel="stylesheet" href="../css/theme.css">
<body>
	<?php
			echo("Bienvenido <strong>{$DFW_SESSION->getName()}</strong>, al panel de administración de Delta Framwork<br><br>");
			echo("opciones: <br>");
			
			echo("<li><a href='test/write.php'>Test de funciones PHP</a></li>");
			echo("<li><a href='functions/user_mode.php'>Cambiar a modo de usuario</a></li>");
			echo("<li><a href='functions/close_session.php'>Cerrar sesión</a></li>");

			DFW_CLOSE();
	?>
</body>
</html>

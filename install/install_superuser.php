<?php
	define("INSTALLING",true);
	
	include("../core.php");
	include("scripts/install_lib.php");
	
	if(DFW_INSTALL_IS_BD_NEED() == true){
		header("Location: /{$DFW_PATH}/install/installer.php");	// No puedes realizar la instalacion de base de datos (solo se puede hacer una vez)
		exit();
	}
	
	if(DFW_INSTALL_IS_USER_NEED() == false){
		header("Location: /{$DFW_PATH}/install/installer.php");	// No puedes realizar la instalacion de base de datos (solo se puede hacer una vez)
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title> 
		DFWlib - installer Administrador
	</title>
	<?php
		DFW_BOOTSTRAP("css");
	?>
	<link rel="stylesheet" href="../css/theme.css">
</head>
<body>
	<center>
	<p><img src="img/dfw_logo.png" style="margin:15px;"><br>Crear administrador</p>
	</center>
	
	<form action="scripts/install_superuser.php" method="POST" style="position:relative; margin:auto; width:480px; background-color:#464646; border-radius:4px; overflow:hidden;">
		<input type="hidden" name="action" value="create">
		<h3 style="padding:10px;">Registrar usuario Super Administrador</h3>
		<div style="background-color:#FFFFFF; padding:15px; color:#232323;">
			<input type="hidden" name="action" value="create">
			Nombre de usuario:<br>
			<input type="text" name="user" class="textfield"  placeholder="Admin"><br><br>
			Email:<br>
			<input type="text" name="email" class="textfield"  placeholder="nombre@servidor.com"><br><br>
			Contraseña:<br>
			<input type="password" name="pass1"  class="textfield" placeholder="Contraseña"><br><br>
			<input type="password" name="pass2"  class="textfield" placeholder="Confirmación">
			<br><br>
			<p align="right"><input type="submit" value="Finalizar instalación" class="btn btn-default"></p>
		</div>
	</form>
	
	<?php
		DFW_CLOSE();
	?>
</body>
</html>

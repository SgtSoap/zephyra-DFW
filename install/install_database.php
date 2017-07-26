<?php
	define("INSTALLING",true);
	
	include("../core.php");
	include("scripts/install_lib.php");
	
	if(!DFW_INSTALL_IS_BD_NEED()){
		header("Location: /{$DFW_PATH}/install/");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title> 
		DFWlib - installer Base de datos
	</title>
	<?php
		DFW_LIB_BOOTSTRAP("css");
	?>
	<link rel="stylesheet" href="../css/theme.css">
</head>

<body>
	<center>
	<p><img src="img/dfw_logo.png" style="margin:15px;"><br>Delta Framework <?php echo("V".DFW_VERSION." R".DFW_REV); ?></p>
	</center>
	
	<form action="scripts/install_database.php" method="POST" style="position:relative; margin:auto; width:480px; background-color:#464646; border-radius:4px; overflow:hidden;">
		
		<input type="hidden" name="action" value="create">
		<h3 style="padding:10px;">Formulario del servidor</h3>
		<div style="background-color:#FFFFFF; padding:15px; color:#232323;">
			Servidor:<br>
			<input type="text" name="server_name" class="textfield"  placeholder="Nombre del servidor"><br><br>
			Nombre de usuario:<br>
			<input type="text" name="server_user"  class="textfield" placeholder="Usuario"><br><br>
			Contraseña:<br>
			<input type="password" name="server_pass"  class="textfield" placeholder="Contraseña">
			<hr>
			Nombre de base de datos:<br>
			<input type="text" name="db_name"  class="textfield" placeholder="Nombre de la base de datos"><br><br>
			<p align="right"><input type="submit" value="Conectar/Crear base de datos MYSQL" class="btn btn-default"></p>
		</div>
	</form>
</body>
</html>

<?php
	define("INSTALLING",true);
	
	include("../core.php");
	include("scripts/install_lib.php");
	
	if($DFW_IS_SESSION == true || $DFW_DB == null || DFW_INSTALL_IS_USER_NEED() == true){
		header("Location: /{$DFW_PATH}/install/installer.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title> 
		DFWlib by Zephyra Corp. | Administración
	</title>
	<?php
		DFW_BOOTSTRAP();
	?>
	<link rel="stylesheet" href="../css/theme.css">
</head>
<body>
	<div id="login-field">
		<div id="login-header">
			<center>Login</center>
		</div>
		<form method="POST" action="functions/open_session.php" align="right">
			<input type="textfield" class="textfield" name="usr" placeholder="Nombre de usuario"><br><br>
			<input type="password" class="textfield" name="key" placeholder="Contraseña"><br><br>
			<input type="submit" value="Iniciar sesión" class="btn btn-default">
		</form>
		<?php
			if(isset($_GET["err"])){
				echo('
					<div style="padding:5px; font-size:15px; color:#870000;" align="center">
						Nombre de usuario o contraseña incorrectos
					</div>
						
				');
			}
		?>
	</div>
	<div style="position:relative; top:150px;" align="center">
		<img src="img/dfw_logo.png"><br>Delta Framwork @ Zephyra
	</div>
	<?php
		DFW_CLOSE();
	?>
</body>
</html>

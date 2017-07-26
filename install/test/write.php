<?php
	define("INSTALLING",true);
	include("../../core.php");
	
	if(!$DFW_SESSION->check_access(1)){
		header("Location: /{$DFW_DIR}/install/");
		exit();
	}
?>
<html>
<head>
	<title>
		Test de codigos PHP
	</title>
	<link rel="stylesheet" href="../css/theme.css">
<head>

<body>
	Escribe el codigo PHP que quieres ejecutar: <a href="../index.php"><button>Ir al menú principal</button></a><br>
	(Variable global $ECHO_TEST)<br><br>
	<form action="install_code.php" METHOD="POST">
		<textarea name="code" rows="10" cols="100"><?php echo(file_get_contents("test_php_code.php")); ?></textarea><br>
		<input type="submit" value="Ejecutar" rows="6">
	</form>
</body>
</html>
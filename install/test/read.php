<?php
	define("INSTALLING",true);
	
	include("../../core.php");
	include("../scripts/install_lib.php");
	
	if(!$DFW_SESSION->check_access(1)){
		header("Location: /{$DFW_DIR}/install/");
		exit();
	}
?>

<html>
<head>
	<title>
		Ejecucion de codigo
	</title>
	<?php
	DFW_BOOTSTRAP();
?>
	<link rel="stylesheet" href="../css/theme.css">
	
</head>
<body>
	<?php
		$ECHO_TEST = "";
		echo("Codigo:<br><quote>");
		highlight_file("test_php_code.php");
		echo("</quote><br><br>Ejecutando codigo...");
		
		include("test_php_code.php");
		echo("<br><p><strong>IMPRESION DE \$ECHO_TEST:</strong><br>{$ECHO_TEST}</p>");
	?>
	<br>
	<p>
		<a href="write.php"><button>Regresar</button></a>
	</p>
</body>
</html>
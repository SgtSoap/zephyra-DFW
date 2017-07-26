<?php
	define("INSTALLING",true);
	include("../../core.php");
	
	if(!$DFW_SESSION->check_access(1)){
		header("Location: /{$DFW_DIR}/install/");
		exit();
	}
	
	$f = fopen("test_php_code.php","w");
	fwrite($f,"" . $_POST["code"] . "");
	fclose($f);
	
	header("Location: read.php");
?>
<?php
	define("INSTALLING",true);
	
	include("../core.php");
	include("scripts/install_lib.php");
	
	if(DFW_INSTALL_IS_BD_NEED()){
		header("Location: /{$DFW_PATH}/install/install_database.php");
		exit();
	}
	
	if(DFW_INSTALL_IS_USER_NEED()){
		header("Location: /{$DFW_PATH}/install/install_superuser.php");
		exit();
	}
	
	header("Location: /{$DFW_PATH}/install/");
	exit();
?>
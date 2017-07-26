<?php
	define("INSTALLING",true);
	
	include("../../core.php");
	include("../scripts/install_lib.php");
	
	if (!defined('DFD8334D3Y')){
		exit();
	}

	DFW_CLOSE_SESSION();
	
	header("Location: /{$DFW_PATH}/install/index.php");
?>
<?php
	define("INSTALLING",true);
	
	include("../../core.php");
	include("../scripts/install_lib.php");
	
	if (!defined('DFD8334D3Y')){
		exit();
	}
	
	if($DFW_IS_SESSION && $DFW_SESSION->checkAccess(1) | $DFW_SESSION->checkAccess(2)){
		$_SESSION['DEV_MODE'] = true;
	}
		
	header("Location: /");
	exit();
?>
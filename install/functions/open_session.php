<?php
	define("INSTALLING",true);
	
	include("../../core.php");
	include("../scripts/install_lib.php");
	
	if (!defined('DFD8334D3Y')){
		exit();
	}
	
	if(!isset($_POST['usr']) || !isset($_POST['key'])){	
		header("Location: /{$DFW_PATH}/install/index.php");
		exit();
	}
	
	$sst = DFW_OPEN_SESSION($_POST['usr'],$_POST['key'],false);
	if($sst == 1){
		header("Location: /{$DFW_PATH}/install/index.php");
		exit();
	}else{
		header("Location: /{$DFW_PATH}/install/login.php?err=".$sst);
		exit();
	}
?>
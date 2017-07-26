<?php
	include("core.php");

	if(file_exists($DFW_ROOT . "/install/install") && !defined('INSTALLING')){
		if(isset($_SESSION['DEV_MODE']) && $_SESSION['DEV_MODE'] == true && $DFW_IS_SESSION == true){
			header('Location: /' . $DFW_PATH .'/install');
			exit();
		}else if(!defined('INSTALLING')){
			header('Location: /' . $DFW_PATH .'/install');
			exit();
		}
	}
	
	header("Location: /");
	exit();
?>
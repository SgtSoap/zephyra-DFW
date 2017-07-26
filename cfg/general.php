<?php
	/**
		Delta Framework DFW @ Delta Advanced Systems 
		Libreria para desarrollo de sistemas basados en PHP
	**/
	
	if (!defined('DFD8334D3Y')){
		exit();
	}
		
	$DFW_CONFIG = array();
	$DFW_DBCONFIG = array();
	$DFW_DBCONFIG["ENCRYTED_KEY"] = "Z74RC09GO1";	// Es recomendble modificar la llave de cifrado
	
	///////////////////////////////////////////////
	/// Configuracion de base de datos DeltaFW ////
	///////////////////////////////////////////////
	
	if(!file_exists($DFW_ROOT . "/cfg/database.php") && !defined('INSTALLING')){
		header("Location: /{$DFW_PATH}/install/installer.php");
		exit();
	}else if(file_exists($DFW_ROOT . "/cfg/database.php")){
		require_once($DFW_ROOT . "/cfg/database.php");		// Configuraciónes de la base de datos
	}

	// Esta función es llamada el nucleo para recargar las configuraciones
	function DFW_GET_CONFIG(){
		global $DFW_DB,$DFW_CONFIG;
		
		$a = $DFW_DB->get_full_array($DFW_DB->QUERY("SELECT * FROM dfw_config"));
		$res = array();
		
		for($i = 0;$i<count($a);$i++){
			$res["{$a[$i]['keyword']}"] = $a[$i]['value'];
		}
		
		return $res;
	}
	
?>
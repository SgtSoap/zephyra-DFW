<?php
/*
	Delta Framework DFW @ Zephyra 
	Libreria para desarrollo de sistemas basados en PHP

	VARIABLES GLOBALES:
		$DFW_CONFIG[] 											// Vector de configuraciones globales
		$DFW_SESSION											// Objeto de sesion actual, ya sea un usuario o como invitado
		
	OBJETOS:
		$DFW_database											// Clase de base de datos (contiene objetos db_layers para los tipos de base de datos)
		$DFW_user												// Clase de usaurio
		$DFW_session											// Clase de sesion una sola instancia en todo el sistema	

	PENDIENTE --> [enviar un aviso de error mas sofisticado]
*/

	define("DFD8334D3Y",true);									// Llave definida para cargar scripts de DFWlib	

	/// DFW Datos de la libreria
	define("DFW_VERSION", "1.0.0");
	define("DFW_REV", 1);

	$DFW_DIR 	= 	basename(dirname(__FILE__));											// dfw
	$DFW_HOST 	= 	$_SERVER["HTTP_HOST"];													// pacupa
	$DFW_ROOT 	= 	dirname(__FILE__);														// C:\PACUPA\dfw
	$DFW_PATH 	= 	substr($DFW_ROOT,strlen($_SERVER["DOCUMENT_ROOT"]),strlen($DFW_ROOT));	// 
	
	
	$DFW_DIR 	= strtolower(str_replace("\\","/",$DFW_DIR));
	$DFW_HOST 	= strtolower(str_replace("\\","/",$DFW_HOST));
	$DFW_ROOT 	= strtolower(str_replace("\\","/",$DFW_ROOT));
	$DFW_PATH 	= strtolower(str_replace("\\","/",$DFW_PATH));
	
	if(substr($DFW_PATH,0,1) == "/"){ $DFW_PATH = substr($DFW_PATH,1); }
	if($DFW_PATH == ""){ $DFW_PATH = "."; }
	
	////////////////////////
	/// Carga de scripts ///
	////////////////////////

	require_once($DFW_ROOT . "/cfg/general.php");				// Carga de configuracion general del sistema
	
	require_once($DFW_ROOT . "/modules/utils.php");				// Utilidades para el sistema
	require_once($DFW_ROOT . "/modules/security.php");			// Utilidades para seguridad
	require_once($DFW_ROOT . "/modules/session.php");			// Modulo gestor de sessiones de usuario
	require_once($DFW_ROOT . "/modules/user_accounts.php");		// Utilidades para el sistema
	require_once($DFW_ROOT . "/modules/database.php");			// Modulo que contiene el codigo de conexion a base de datos
	require_once($DFW_ROOT . "/modules/access.php");			// Sistema de accesos DFW
	require_once($DFW_ROOT . "/modules/system.php");			// Sistema general de DFW
	
	if(!file_exists($DFW_ROOT . "/cfg/database.php")){
		if(defined('INSTALLING')){
			return;
		}else{
			header('Location: /'. $DFW_PATH .'/install');	
			exit();
		}
	}
	
	/// Inicializacion de componentes
	if($DFW_DBCONFIG['DB_PASSWORD'] == ""){
		$DFW_DBCONFIG['DB_PASSWORD'] = "";
	}else{
		$DFW_DBCONFIG['DB_PASSWORD'] = DFW_UTILS_DECRYPT($DFW_DBCONFIG['DB_PASSWORD'],$DFW_DBCONFIG["ENCRYTED_KEY"]);
	}
	
	// Conectando base de datos principal de DFWlib
	$DFW_DB = new DFW_database(
		null,
		$DFW_DBCONFIG["DB_SERVER"],
		$DFW_DBCONFIG["DB_USER"],
		$DFW_DBCONFIG["DB_NAME"],
		$DFW_DBCONFIG["DB_PASSWORD"]);
		
		$DFW_DBCONFIG['DB_PASSWORD'] = null;
		
	if ($DFW_DB->connect() == 0){// No se pudo establecer conexion con la base de datos de DFWlib
		if(file_exists($DFW_ROOT . "/install/install") && !defined('INSTALLING')){
			header('Location: /'. $DFW_PATH .'/install/installer.php');
			exit();
		}else{
			exit("[DFWLIB][DATABASE] No se pudo establecer conexión con la base de datos del sistema DFW");
			// PENDIENTE --> [enviar un aviso de error mas sofisticado]
		}
	}
	
	$DFW_CONFIG = DFW_GET_CONFIG();	// Cargamos las configuraciónes
	
	$DFW_SESSION 		= DFW_MAKE_SESSION();				// Objeto de sesion de usuario (como usuario o como invitado)
	$DFW_IS_SESSION 	= $DFW_SESSION->isOpen();			// Comprobando sesion
		
	if($DFW_CONFIG["ONLINE_STATISTICS"] == true){				// Comprobando disponibilidad del modulo
		DFW_COUNT_USERS_ONLINE();								// Contabiliza los usuarios online
	}

	if(file_exists($DFW_ROOT . "/install/install") && !defined('INSTALLING')){
		if(isset($_SESSION['DEV_MODE']) && $_SESSION['DEV_MODE'] == true && $DFW_IS_SESSION == true){
			return;
		}else if(!defined('INSTALLING')){
			header('Location: /' . $DFW_PATH .'/install');
			exit();
		}
	}
?>
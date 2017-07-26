<?php
	/*
		SCRIPT DE INSTALACION DE BASE DE DATOS 
		DATOS PASADOS POR METODO POST
		
		INDICE DE ERRORES 100 101-10X
		
		server_name				// nombre del servidor
		server_user				// nombre de usuario
		server_pass				// Contraseña del servidor de base de datos
		db_name					// Nombre de la base de datos
		action					// Accion, si se desea crear o conectar con la base de datos [create,connect]
		
		Permisos requeridos NINGUNO
		Condiciones:
		Tener el archivo install/install, no haber conexion con base de datos en DFW_DB, no existir el archivo de configuración 
		
		PENDIENTE-> Por ahora es solo compatible con MYSQLi
		
	*/
	
	define("INSTALLING",true);
	
	include("../../core.php");
	include("install_lib.php");
		
	if(!isset($_POST['action']) || !isset($_POST['server_name']) || !isset($_POST['server_user'])  || !isset($_POST['server_pass']) || !isset($_POST['db_name'])){
		header("Location: /{$DFW_PATH}/install/install_database.php?err=102");
		exit();
	}
	
	if(DFW_INSTALL_IS_BD_NEED() == false){
		header("Location: /{$DFW_PATH}/install/install_database.php?err=107");	// No puedes realizar la instalacion de base de datos (solo se puede hacer una vez)
		exit();
	}
	
	$server 	=	$_POST['server_name'];
	$user   	=	$_POST['server_user'];
	$name   	=	$_POST['db_name'];
	$password 	=	$_POST['server_pass'];
	
	if($_POST['action'] == "create"){
		if(!DFW_CREATE_DATABASE($server,$user,$password,$name,file_get_contents("{$DFW_ROOT}/install/db/dfw_db.sql"))){
			header("Location: /{$DFW_PATH}/install/install_database.php?err=106");		// No se pudo crear la base de datos
			exit();
		}
		
		if(!DFW_SET_DATABASE_CONFIG("mysqli",$server,$user,$name,$password)){
			header("Location: /{$DFW_PATH}/install/install_database.php?err=108");		// No se pudo crear la base de datos
			exit();
		}
	}else if($_POST['action'] == "connect"){
		$DFW_DB = new DFW_database(null,$server,$user,$name,$password);
		$DFW_DB->connect();
		
		if($DFW_DB->is_connected()){
			if(!DFW_SET_DATABASE_CONFIG("mysqli",$server,$user,$name,$password)){
				header("Location: /{$DFW_PATH}/install/install_database.php?err=104");	// No se pudo guardar la configuración
				exit();
			}	
		}else{
			header("Location: /{$DFW_PATH}/install/install_database.php?err=105");		// No se pudo establecer conexión
			exit();
		}
	}else{
		header("Location: /{$DFW_PATH}/install/install_database.php?err=103");  			//  Accion no corresponde a ninguna funcion valida
		exit();
	}
	
	header("Location: /{$DFW_PATH}/install/installer.php");  							//  Accion no corresponde a ninguna funcion valida
	exit();	
?>
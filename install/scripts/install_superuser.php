<?php
	/*
		SCRIPT DE INSTALACION DE BASE DE DATOS 
		DATOS PASADOS POR METODO POST
		
		INDICE DE ERRORES 200 201-20X
		
		user				// nombre del servidor
		email				// correo electronico con el que se asociará la cuenta
		pass1				// Contraseña de usuario
		pass2				// Confirmación contraseña de usuario
		
		PENDIENTE-> Por ahora es solo compatible con MYSQLi
	*/
	
	define("INSTALLING",true);
	
	include("../../core.php");
	include("install_lib.php");
		
	if(!isset($_POST['user']) || !isset($_POST['email']) || !isset($_POST['pass1']) || !isset($_POST['pass2'])){
		header("Location: /{$DFW_PATH}/install/install_superuser.php?err=202");
		exit();
	}
	
	if(DFW_INSTALL_IS_USER_NEED() == false){
		header("Location: /{$DFW_PATH}/install/install_superuser.php?err=203");	// No puedes realizar la instalacion de base de datos (solo se puede hacer una vez)
		exit();
	}
	
	$name 		=	$_POST['user'];
	$pass   	=	$_POST['pass1'];
	$pass2  	=	$_POST['pass2'];
	$email  	=	$_POST['email'];
	
	if($pass != $pass2){
		header("Location: /{$DFW_PATH}/install/install_superuser.php?err=204");
		exit();
	}
	
	// Creamos superusuario con la ayuda de DFWlib
	if(DFW_INSTALL_SUPERADMIN($name,$email,$pass)){
		header("Location: /{$DFW_PATH}/install");
		exit();	
	}else{
		header("Location: /{$DFW_PATH}/install/install_superuser.php?err=205");
		exit();
	}
?>
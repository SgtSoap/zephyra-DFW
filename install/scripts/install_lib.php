<?php	
	/**
		DFWlib @ zephyra Corp.
		@Authors: Aldo Cesar Gutierrez [zephyra.zero.zerozelta]
		
		Libreria con funciones para unstalación
		Unicamente es servible si se encuentra el archivo install/install de la carpeta de DFWlib
	**/
	
	if (!defined('INSTALLING') || !defined('DFD8334D3Y')){				// tiene que estar definido installing y el codigo de DFWlib
		exit("install_lib.php -> Variables de entorno no definidas");
	}
	
	if(!file_exists($DFW_ROOT . "/install")){
		return;
	}
	
	function DFW_SET_DATABASE_CONFIG($layer = "mysqli",$server,$user,$name,$password){
		global $DFW_ROOT;
		
		$DFW_DB = new DFW_database(
		null,
		$server,
		$user,
		$name,
		$password);
		
		$DFW_DB->connect(); 
		
		if($DFW_DB == null || $DFW_DB->is_connected() == false){
			return false;
		}
		
		$text_pattern = "áéíóú_@ -";
				
		$n = $DFW_DB->get_num($DFW_DB->query("SELECT id FROM DFW_users"));
		
		if($n != 0){
			return false;
		}
		
		if(!file_exists($DFW_ROOT . "/install")){
			return false;
		}
		
		if($password == "" || $password == null){
			$password = "";
		}else{
			$password = DFW_UTILS_ENCRYPT($password,"Z74RC09GO1");
		}
		
		if($layer == null){
			$layer = "mysql";
		}
		
		if(!DFW_UTILS_IS_BASIC_TEXT($layer,30,$text_pattern)   ||
			!DFW_UTILS_IS_BASIC_TEXT($server,30,$text_pattern) ||
			!DFW_UTILS_IS_BASIC_TEXT($user,30,$text_pattern) ||
			!DFW_UTILS_IS_BASIC_TEXT($name,30,$text_pattern)){
			return false;
		}
		
		$layer = strip_tags($layer);
		$server =  strip_tags($server);
		$user =  strip_tags($user);
		$name =  strip_tags($name);
		
		$res = "<?php
			if(!defined('DFD8334D3Y')){	exit(); }
			\$DFW_DBCONFIG['DB_LAYER'] 	= \"{$layer}\";
	
			\$DFW_DBCONFIG['DB_SERVER'] 	= \"{$server}\";
			\$DFW_DBCONFIG['DB_USER'] 		= \"{$user}\";
			\$DFW_DBCONFIG['DB_NAME'] 		= \"{$name}\";
			\$DFW_DBCONFIG['DB_PASSWORD'] 	= \"{$password}\";
			?>";

		return DFW_UTILS_WRITE_FILE($DFW_ROOT . "/cfg/database.php",$res);
	}
	
	// Crea una cuenta de super usuario con las validaciones necesarias
	function DFW_INSTALL_SUPERADMIN($name,$email,$password){
		global $DFW_DB,$DFW_ROOT;

		$num = $DFW_DB->get_num($DFW_DB->query("SELECT * FROM DFW_users"));
		
		if($num > 0){
			return false;	// Solo se hará un usuario administrador cuando no haya ningún usuario en la tabla
		}
		
		if(!file_exists($DFW_ROOT . "/install") && !defined('INSTALLING')){
			return false;	// Es necesario estar en modo instalacion para poder instalar un animistrador por este medio
		}	
		
		if(!DFW_INSTALL_IS_USER_NEED()){
			return false;
		}
		
		
		if(!DFW_USR_CREATE($name,$email,$password,0)){
			return false;
		}
		
		$u = new DFW_user($name);
		
		if(!$u->isExists()){
			return false;
		}

		DFW_ACCESS_ADD_CREDENTIAL($u->get_ID(),1); /// Otorgamos permisos de administrador
		
		return true;
	}
	
	// Crea la base de datos y ejecuta el sql pasado como argumento
	function DFW_CREATE_DATABASE($db_server,$db_user,$db_pass,$db_name,$sql = ""){
		global $DFW_SESSION,$DFW_ROOT;
		
		$link = mysqli_connect($db_server,$db_user,$db_pass);

		if(!$link){
			return false;
		}
		
		$dbn = mysqli_real_escape_string ($link , $db_name );
		
		$sql1 = "CREATE DATABASE IF NOT EXISTS {$dbn} DEFAULT CHARACTER SET latin1;";
		
		$res = mysqli_query($link,$sql1);
		
		mysqli_select_db($link,$db_name);
		
		$sqla = explode(";",$sql);
		
		for($i = 0; $i < count($sqla); $i++){
			if($sqla[$i] != null && $sqla[$i] != ""){
				mysqli_query($link,$sqla[$i]) or die(exit("error: " . mysqli_error($link)));
			}
		}
		
		mysqli_close($link);

		return true;
	}
	
	// Carga un archivo SQL de la carpeta DFWlib/install/db con el nombre pasado como argumento
	// Devuelve 1 enc aso de exito y -1 en caso de error
	function DFW_LOAD_SQL_SCRIPT($db,$name){
		global $DFW_SESSION,$DFW_ROOT;
		
		if(!file_exists($DFW_ROOT . "/install")){
			return -1;
		}
		
		if(!$DFW_SESSION->IS_VALID() || !$DFW_SESSION->check_Access(1)){
			return -2;
		}
		
		$sql = file_get_contents($DFW_ROOT . "/install/sql/".$name);
		
		$sqla = explode(";",$sql);
		
		for($i = 0; $i < count($sqla); $i++){
			if($sqla[$i] != null && $sqla[$i] != ""){
				$db->query($sqla[$i]);
			}
		}
		
		return 1;
	}
	
	// Comprueba si se está en modo instalación y requiere instalacion de base de datos
	function DFW_INSTALL_IS_BD_NEED(){
		global $DFW_DB,$DFW_SESSION,$DFW_ROOT;
		
		if($DFW_DB != null && $DFW_DB->is_connected() == true){
			return false;
		}
		
		if(!file_exists($DFW_ROOT . "/install")){
			return false;
		}
		
		if($DFW_SESSION != null && $DFW_SESSION->IS_VALID() == true){	// No debe haber nadie logeado
			return false;
		}
		
		return true;
	}
	
	function DFW_INSTALL_IS_USER_NEED(){
		global $DFW_DB,$DFW_SESSION,$DFW_ROOT;
		
		if($DFW_DB == null || $DFW_DB->is_connected() != true){
			return false;
		}

		if(!file_exists($DFW_ROOT . "/install")){
			return false;
		}
		
		if($DFW_SESSION->IS_VALID() == true){
			return false;
		}

		$n = $DFW_DB->get_num($DFW_DB->query("SELECT * FROM dfw_users"));
		
		if($n != 0){
			return false;
		}	

		return true;
	}
?>
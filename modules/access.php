<?php
/**
	DFW Delta Framework @  zephyra
	@Authors: Aldo Cesar Gutierrez [zerozelta]
	
	Licencia: GPL puede distribuir, utilizar y modificar el codigo abiertamente
	
	Libreria de funciones DFW_USERS 1.0
	Fecha de creacion: 05/02/2016
	Ultima fecha de modificacion: 10/02/2016
	
	Documentación
		Indices 
	1		Super Admin
	2		Administrador
	3		Banned
	4		Moderador
	5		Editor
	6		VIP						-- 
	7		session_user_lock		-- Bloquea los inicios de sesión de este usuario
*/

if(!defined('DFD8334D3Y')){
	exit;
}

define("_DFW_ACCESS_SUPERADMIN",1);
define("_DFW_ACCESS_ADMIN", 2);
define("_DFW_ACCESS_BANED", 3);
define("_DFW_ACCESS_MODERATOR", 4);
define("_DFW_ACCESS_EDITOR", 5);
define("_DFW_ACCESS_VIP", 6);

define("_DFW_ACCESS_EST_SESIONLOCK", 7);

// Funcion que instala una nueva credencial en la base de datos
function DFW_ACCESS_CREATE_CREDENTIAL($code,$name,$root = 0){
	global $DFW_DB,$DFW_SESSION;
	
	if($name == null || $name == ""){
		return -1;
	}
	
	if(!is_numeric($root)){
		return -2;
	}

	if(!DFW_SECURITY_IS_CLEAR_TEXT($code,5) || !DFW_SECURITY_IS_CLEAR_TEXT($code,30)){
		return -3;
	}
	
	if($DFW_SESSION->check_access(1) == false && $DFW_SESSION->check_access(2) == false){	// PERMISOS DE ADMINISTRADOR
		return -4;
	}
	
	$code = $DFW_DB->escape_string($code);
	$name = $DFW_DB->escape_string($name);
	
	return $DFW_DB->query("INSERT INTO dfw_access (code,name,root) VALUES ('{$code}','{$name}','{$root}')");
}

// Devuelve un array bidimencional asociativo con todas las credenciales disponibles en la base de datos
function DFW_ACCESS_GET_CREDENTIALS_TABLE(){
	global $DFW_DB;
	
	$res = $DFW_DB->query("SELECT * FROM dfw_access");
	return $DFW_DB->get_full_array($res);
}

// Añade una nueva credencial de usuario con el ID pasado como argumento (Tiene que haber un administrador logeado para otorgar credenciales)
// La unica validacion que hace es la limitacion de administradores, las demas validaciones tiene que hacerce manualmente
function DFW_ACCESS_ADD_CREDENTIAL($userid,$idcred){
	global $DFW_SESSION,$DFW_DB;
	
	if(is_numeric($idcred) == false || is_numeric($userid) == false){
		return 0;
	}
	
	$n = $DFW_DB->get_num($DFW_DB->query("SELECT user FROM dfw_users_access WHERE (user='{$userid}' AND access='{$idcred}')"));
		
	if($idcred == 1 && n == 0){
		// Damos acceso directo
	}else{
		if($idcred < 6 && $DFW_SESSION->IS_VALID() == false){
			return -1;	// No puedes añadir credenciales importantes si no estas logeado
		}
		
		if($idcred == 1 && !$DFW_SESSION->check_access(1)){
			return -2;	// Solo super-admin puede dar permisos de super-admin
		}
		
		if($idcred == 2 && !($DFW_SESSION->check_access(1) || $DFW_SESSION->check_access(2))){
			return -3;	// Solo un super-admin o un administrador pueden dar permisos de administrador
		}
	}

	$time = time();
	$idcred = $DFW_DB->escape_string($idcred);
	
	if(!$DFW_DB->query("INSERT INTO dfw_users_access (user,access,time_r,sponsor) VALUES('{$userid}','{$idcred}',NOW(),'{$DFW_SESSION->get_ID()}')")){
		return -4;
	}
	
	return 1;
}

// Elimina la credencial del usuario
function DFW_ACCESS_REMOVE_CREDENTIAL_FROM($user,$idcredential){
	global $DFW_DB,$DFW_SESSION;
	
	if($idcredential <= 6 && !$DFW_SESSION->IS_VALID() && !$DFW_SESSION->check_access(_DFW_ACCESS_SUPERADMIN) | !$DFW_SESSION->check_access(_DFW_ACCESS_ADMIN)){
		return false;	// no se puede modificar estas credenciales sin tener un acceso de administrador en el sistema
	}
	
	if(!is_numeric($user) || !is_numeric($idcredential)){
		return false;
	}
	
	$DFW_DB->query("DELETE FROM dfw_users_access WHERE user={$user} AND access={$idcredential}");
}

// Comprueba si el usuario logeado tiene acceso o accesos solicitados
function DFW_ACCESS_CHECK($access){
	global $DFW_SESSION;
	
	if($DFW_SESSION == null){
		return false;
	}
	
	return $DFW_SESSION->check_access($access);
}

/// Obtiene todas las credenciales del usuario con el ID pasado como argumento
function DFW_ACCESS_GET_CREDENTIALS_FROM($user){
	global $DFW_DB;
	
	if($user == null || is_numeric($user)== false){
		return null;
	}
	
	return $DFW_DB->get_full_array($DFW_DB->query("SELECT * FROM dfw_users_access WHERE (user='{$user}')"));
}

// Busca una credencial especifica ($idcredential) en el usuario con el id pasado como argumentos ($user)
function DFW_ACCESS_FIND_CREDENTIAL_FROM($user,$idcredential){
	$a = DFW_ACCESS_GET_CREDENTIALS_FROM($user);
	
	if($a == null || $a == false){
		return null;
	}
	
	$res = null;
	
	for($i = 0;$i < count($a) ; $i++){
		if($a[$i]["credential"] == $idcredential){
			$res = $a[$i];
		}
	}
	return $res;
}

/// Checa en la lista de credenciales del tipo $list[0]['id'] por la credecial pasada como argumento
/// $list -> lista con las credenciales        $credential -> id de la credencial con la que se va a comparar
function DFW_ACCESS_CHECK_ARRAY($list,$credential){
	if($list == null || count($list) == 0){
		return false;
	}
	
	if(is_numeric($credential) == false || is_array($list) == false){
		return false;
	}
	
	$res = false;
	
	for($i = 0; $i < count($list) ; $i++){
		if($list[$i]['access'] == $credential){
			$res = true;
		}
	}
	
	return $res;
}

// Comprueba si un usuario tiene acceso solicitado y devuelve un array con el contnido de la credencial 
// @args: $user(Int) - ID del usuario     $idcredential(Int)  - ID de la credencial a buscar
function DFW_ACCESS_CHECK_FROM_USER($user,$idcredential){
	global $DFW_DB,$DFW_IS_SESSION,$DFW_SESSION;
	
	if(is_numeric($idcredential) == false || is_numeric($user) == false){
		return false;
	}
	
	if($DFW_IS_SESSION == null || $DFW_IS_SESSION->get_ID == 0){
		return false;
	}
	
	$list = DFW_ACCESS_GET_CREDENTIALS_FROM($user);
	
	return  DFW_ACCESS_CHECK_ARRAY($list,$idcredential);
}


// Busca que el array de credenciales cumpla 1 o mas permisos del array de acceso
function DFW_FIND_ACCESS($a_credentials,$a_access){
	if(!is_array($a_credentials) || !is_array($a_credentials)){
		if(is_array($a_credentials) && is_numeric($a_access)){
			return DFW_ACCESS_CHECK_ARRAY($a_credentials,$a_access);
		}else{
			return false;
		}
	}
	
	if($a_credentials == null || count($a_credentials) == 0){
		return false;	// No hay ninguna credencial, por lo tanto no hay acceso a nada
	}
	
	$result = false;
	
	for($i=0; $i < count($a_credentials); $i++){
		for($j = 0; $j< count($a_access) ; $j++){
			//echo("Comparando: " . $a_credentials[$i]['access'] . " -- " . $a_access[$j] . "<br>");
			if(is_numeric($a_access[$j]) && is_numeric($a_credentials[$i]['access']) ){
				if($a_access[$j] == $a_credentials[$i]['access']){
					$result = true;
					break;
				}
			}
		}
	}
	return $result;
}

class DFW_credential{
	public $ID = null;
	public $CODE = null;
	public $ROOT = null;
	
	function __construct($id){
		if(is_numeric($id)){
			$this->$ID = $id;
		}elseif(DFW_SECURITY_IS_CLEAR_TEXT($id,4)){
			
		}
	}

	function compare($acs){
		if($this->ID == null){
			return false;
		}else if($acs == null || $acs == $this->ID){
			return true;
		}else{
			return false;
		}
	}
}
?>
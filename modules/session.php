<?php
/**
	DFW @ Delta Advanced Systems
	@Authors: Aldo Cesar Gutierrez [zerozelta]
	
	Licencia: GPL puede distribuir, utilizar y modificar el codigo abiertamente
	
	Control de sesiones
	
	Documentación
	Cookies contenidas
		- usr -> id de usuario
		- key -> clave de acceso encriptada
	
	variables de sesion
		- usr -> id de usuario
		- key -> clave de acceso encriptada
*/

if (!defined('DFD8334D3Y')){
	exit();
}

SESSION_START();			// Iniciando sesion del el cliente
session_regenerate_id();	// Regeneramos ID de sesion

$DFW_USERS_STATUS = array();
$DFW_USERS_STATUS['ONLINE'] = 0;
$DFW_USERS_STATUS['ONLINE_REGISTERED'] = 0;
$DFW_USERS_STATUS['ONLINE_GUESTS'] = 0;
$DFW_USERS_STATUS['REGISTERED'] = 0;

$DFW_SESSION = null;

// Elimina las cookies de SESSION en el explorador
function DFW_SESSION_DELETE_COOKIES(){
	setcookie("usr","",time(),'/',$_SERVER["SERVER_NAME"]);
	setcookie("key","",time(),'/',$_SERVER["SERVER_NAME"]);
	setcookie("sid","",time(),'/',$_SERVER["SERVER_NAME"]);
}

// Elimina datos basura de usuario en los registros de la base de datos
function DFW_DELETE_USER_GARBAGE_DATA(){
	global $DFW_DB,$DFW_CONFIG,$DFW_CONFIG;
	
	$_online_time = $DFW_DB->escape_string($DFW_CONFIG["MAX_SESION_TIME"]);
	$_sesion_time = $DFW_DB->escape_string($DFW_CONFIG["EXPIRE_SESION_TIME"]);
	
	$DFW_DB->query("DELETE FROM dfw_users_online WHERE (UNIX_TIMESTAMP(last_time) + {$_online_time} <= UNIX_TIMESTAMP(NOW()))");
	$DFW_DB->query("DELETE FROM dfw_sessions WHERE (UNIX_TIMESTAMP(last_time) + {$_sesion_time} <= UNIX_TIMESTAMP(NOW()))");
}

function DFW_COUNT_USERS_ONLINE(){
	global 
	$DFW_DB,$DFW_IS_SESSION,
	$DFW_CONFIG,
	$DFW_USERS_STATUS;
	
	$total 		= 0;
	$registered = 0;
	$guests 	= 0;
	
	$ip 		= 	$DFW_DB->escape_string(DFW_UTILS_GET_IP());
	$browser 	=	$DFW_DB->escape_string(DFW_UTILS_GET_BROWSER());
	
	$max_session_time =  $DFW_DB->escape_string($DFW_CONFIG["MAX_SESION_TIME"]);
	
	//exit($DFW_DB->get_full_array($DFW_DB->QUERY("SELECT SECOND(TIMEDIFF(NOW(),last_time)) tiempo FROM dfw_sessions"))[0]['tiempo']);
	
	DFW_DELETE_USER_GARBAGE_DATA();
	
	// contamos los diferentes usuarios registrados (diferente id)
	$result = $DFW_DB->query("SELECT DISTINCT user_id FROM dfw_sessions WHERE (UNIX_TIMESTAMP(last_time) >= UNIX_TIMESTAMP(NOW()) - {$max_session_time})");
	
	if (!$result){
		$registered = 0;
	}else{
		$registered = $DFW_DB->get_num($result);
	}
		
	if ($DFW_IS_SESSION == false && $DFW_DB->get_num($DFW_DB->query("SELECT * FROM dfw_users_online WHERE (ip = '{$ip}' AND  browser = '{$browser}' )")) == 0){
		$DFW_DB->query("INSERT INTO dfw_users_online (ip,last_time,browser) VALUES ('{$ip}',NOW(),'{$browser}')");
	}
	
	$guests = $DFW_DB->get_num($DFW_DB->query("SELECT DISTINCT ip,browser FROM dfw_users_online WHERE (UNIX_TIMESTAMP(last_time) >= UNIX_TIMESTAMP(NOW()) - {$max_session_time})"));	
	
	$total = $guests + $registered;
	
	$DFW_USERS_STATUS['REGISTERED']			= $DFW_DB->get_num($DFW_DB->query("SELECT id FROM dfw_users"));
	$DFW_USERS_STATUS['ONLINE'] 			= $total;
	$DFW_USERS_STATUS['ONLINE_REGISTERED'] 	= $registered;
	$DFW_USERS_STATUS['ONLINE_GUESTS'] 		= $guests;
}

// Comprueba si la sesion ya esta iniciada basados en cookies o variables de sesión, establece $DFW_session como la secion comprobada y devuelve
// verdadero o falso si hay una sesion abierta o no
function DFW_MAKE_SESSION(){
	global $DFW_DB,$DFW_SESSION,$_DFW_ACCESS_EST_SESIONLOCK,$DFW_CONFIG;
	
	$usr_id = 0;
	$usr_key = "";
	 
	$DFW_SESSION = new DFW_session(null,null);

	if(!isset($_SESSION["usr"]) || !isset($_SESSION["key"])){
		if(!isset($_COOKIE['usr']) || !isset($_COOKIE['usr'])){
			return $DFW_SESSION ;
		}else{
			if($DFW_CONFIG["SESSION_COOKIES"] == false){
				return $DFW_SESSION ;
			}else{
				// Se validan los valores de entrada (sesiones y cookies)
				if(!is_numeric($_SESSION["usr"])){
					return $DFW_SESSION ;
				}

				if(!DFW_UTILS_IS_BASIC_TEXT($_SESSION["sid"],15,"")){
					return $DFW_SESSION ;
				}
				
				$usr_id = $DFW_DB->escape_string($_COOKIE["usr"]);
				$usr_key = DFW_UTILS_DECRYPT($_COOKIE["key"],"vxlc");
				
				// Añadimos una semana mas para expirar las cookies
				setcookie("usr",$_COOKIE["usr"], time() + $DFW_CONFIG["EXPIRE_SESION_TIME"] ,'/',$_SERVER["SERVER_NAME"],false,true);
				setcookie("key",$_COOKIE["key"],time() + $DFW_CONFIG["EXPIRE_SESION_TIME"] ,'/',$_SERVER["SERVER_NAME"],false,true);
			}
		}
	}else{
		// Se validan los valores de entrada (sesiones y cookies)
		if(!is_numeric($_SESSION["usr"])){
			return $DFW_SESSION ;
		}
		
		if(!DFW_UTILS_IS_BASIC_TEXT($_SESSION["sid"],15,"")){
			return $DFW_SESSION ;
		}

		/////////////////////////////////////////////////////////
	
		$usr_id = $DFW_DB->escape_string($_SESSION["usr"]);
		$usr_key = DFW_UTILS_DECRYPT($_SESSION["key"],"vxlc");
	}
	
	$sesion = new DFW_session($usr_id,$usr_key);
	$DFW_SESSION = $sesion;
	
	return $DFW_SESSION;
}

// Checa y crea la session
function DFW_CHECK_SESSION(){
	global $DFW_SESSION;
	
	DFW_MAKE_SESSION();
	
	return $DFW_SESSION->IS_VALID();
}

// Acceso directo a DFW_CHECK_SESSION
function DFW_SESSION_CHECK(){
	return DFW_CHECK_SESSION();
}

// Función que abre sesión con el usuario y contraseña pasados como argumeto (remember es un booleano para el uso de cookies que permite recordar las sesiones)
function DFW_OPEN_SESSION($USER,$PASSWORD,$REMEMBER = false){
	global $DFW_SESSION,$DFW_IS_SESSION,$DFW_CONFIG,$DFW_CONFIG,$DFW_DB;

	if($DFW_IS_SESSION){	// La sesión ya está abierta
		return -1;
	}
	
	$PASSWORD = DFW_UTILS_CIFRATE($PASSWORD);
	
	$sesion = new DFW_user($USER);
	
	if($sesion->isExists() == false){
		return -2;
	}
	
	if(DFW_SESSION_GET_TRYS_FOR($sesion->get_ID()) > $DFW_CONFIG["SESSION_MAX_TRY_LOGIN"]){
		if($sesion->check_access(_DFW_ACCESS_EST_SESIONLOCK)){
			$cred = DFW_ACCESS_FIND_CREDENTIAL_FROM($sesion->get_ID(),_DFW_ACCESS_EST_SESIONLOCK);
			$t = time() - $cred['time'];
			if(($DFW_CONFIG["SESSION_LOCK_TIME"] - $t) <= 0){
				DFW_ACCESS_REMOVE_CREDENTIAL_FROM($sesion->get_ID(),_DFW_ACCESS_EST_SESIONLOCK); // Se borra el bloqueo de usuario
			}else{
				return -3;	// todavia no se cumple la penitencia
			}
		}else{
			DFW_ACCESS_ADD_CREDENTIAL_TO_USER($sesion->get_ID(),_DFW_ACCESS_EST_SESIONLOCK);	// Añadimos credencial de usuario bloqueado
			return -4;
		}
	}
		
	$charset = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");
	$code = "";
	
	foreach(array_rand($charset,8) as $c){
		$code = $code.$charset[$c];
	}
	
	if($sesion->getPassword() != $PASSWORD){
		return -5;
	}
		
	DFW_SESSION_REMOVE_TRY_TRACE_FROM($sesion->get_ID());

	$_SESSION["usr"] = $sesion->get_ID();
	$_SESSION["key"] = DFW_UTILS_ENCRYPT($PASSWORD,"vxlc");
	$_SESSION["sid"] = $code;
	
	if($DFW_CONFIG["SESSION_COOKIES"] == true && $REMEMBER == true){
		setcookie("usr",$sesion->get_ID(), time() + $DFW_CONFIG["EXPIRE_SESION_TIME"] ,'/',$_SERVER["SERVER_NAME"],false,true);
		setcookie("key",DFW_UTILS_ENCRYPT($PASSWORD,"vxlc"),time() + $DFW_CONFIG["EXPIRE_SESION_TIME"] ,'/',$_SERVER["SERVER_NAME"],false,true);
		setcookie("sid",$code, time() + $DFW_CONFIG["EXPIRE_SESION_TIME"] ,'/',$_SERVER["SERVER_NAME"],false,true);
	}
		
	$ip = 				$DFW_DB->escape_string(DFW_UTILS_GET_IP());
	$browser = 			$DFW_DB->escape_string(DFW_UTILS_GET_BROWSER());
	
	$DFW_DB->query("DELETE FROM dfw_sessions WHERE (user_id='{$DFW_SESSION->get_ID()}' AND ip='{$ip}' AND browser='{$browser}')");
	$DFW_DB->query("DELETE FROM dfw_users_online WHERE (ip='{$ip}' AND browser='{$browser}')");
	
	$DFW_DB->query("INSERT INTO dfw_sessions (user_id,ip,browser,code,last_time) VALUES ('{$sesion->get_ID()}','{$ip}','{$browser}','{$code}',NOW())");
	
	$DFW_SESSION = new DFW_session($DFW_SESSION->get_ID(),$PASSWORD);
	
	if($DFW_SESSION->isOpen() == false){
		return -6;
	}
	
	return 1;
}

// Acceso directo a DFW_OPEN_SESSION
function DFW_SESSION_OPEN($USER,$PASSWORD,$REMEMBER = false){
	return DFW_OPEN_SESSION($USER,$PASSWORD,$REMEMBER);
}


function DFW_CLOSE_SESSION(){
	global $DFW_DB,$DFW_SESSION;
	
	if($DFW_SESSION == null || $DFW_SESSION->isOpen() == false){	// La sesion ya esta cerrada, o no es válida 
		return;
	}
	
	DFW_SESSION_DELETE_COOKIES();
	
	$_SESSION["usr"] = null;
	$_SESSION["key"] = null;
	$_SESSION["sid"] = null;
		
	$DFW_DB->query("DELETE FROM dfw_sessions WHERE (user_id='{$DFW_SESSION->get_ID()}' AND ip='{$DFW_SESSION->getIP()}' AND browser='{$DFW_SESSION->getBrowser()}')");
}

// Acceso directo a DFW_CLOSE_SESSION
function DFW_SESSION_CLOSE(){
	return DFW_CLOSE_SESSION();
}

// 
function DFW_SESSION_ADD_LOGIN_TRY($usr_id){
	global $DFW_DB;
	
	if(!is_numeric($usr_id)){
		return false;
	}

	$ip = $DFW_DB->escape_string(DFW_UTILS_GET_IP());
	
	$browser = 	$DFW_DB->escape_string(DFW_UTILS_GET_BROWSER());
	
	$DFW_DB->query("INSERT INTO dfw_session_try (user,ip,browser,time) VALUES({$usr_id},'{$ip}',NOW(),'{$browser}')");
	
	return true;
}

// Devuelve la cantidad de intentos de session para el usuario seleccionado
function DFW_SESSION_GET_TRYS_FOR($usr_id){
	global $DFW_DB;
	
	if(!is_numeric($usr_id)){
		return false;
	}
	
	return $DFW_DB->get_num($DFW_DB->query("SELECT * FROM dfw_session_try WHERE user={$usr_id}"));
}

function DFW_SESSION_REMOVE_TRY_TRACE_FROM($usr_id){
	global $DFW_DB;
	
	if(!is_numeric($usr_id)){
		return false;
	}
	
	return $DFW_DB->get_num($DFW_DB->query("DELETE FROM dfw_session_try WHERE user={$usr_id}"));
}

// Carga y devuelve un objeto de usuario con el nombre o id pasado como argumento (SI existe)
// Return: DFW_user Object || null
function DFW_GET_USER($usr){
	$u = new DFW_user($usr);
	if($u->isExists()){
		return $u;
	}else{
		return null;
	}
}

class DFW_user{
	
	private $IS_VALID = false;
	
	private $ID = 0;
	private $NAME = null;
	private $EMAIL = null;
	private $PASSWORD = null;
	
	private $CREDENTIALS = array();
	
	function __construct($user){
		global $DFW_DB;
		
		if($user == null){ return; }
		
		
		$a = null;
		
		if(is_numeric($user)){
			// un id
			$a = $DFW_DB->get_array($DFW_DB->query("SELECT * FROM dfw_users WHERE id={$user}"));
			if(!$a || count($a) == 0){
				return;
			}
		}else{
			$user = $DFW_DB->escape_string($user);
			
			// nombre de usuario
			if(!DFW_SECURITY_IS_CLEAR_TEXT($user,50)){
				return;
			}
			
			$a = $DFW_DB->get_array($DFW_DB->query("SELECT * FROM dfw_users WHERE name='{$user}'"));
			
			if(!$a || count($a) == 0){
				return;
			}
		}
		
		if($a==null){ return; }
		
		$this->ID = $a["id"];
		$this->NAME = $a["name"];
		$this->EMAIL = $a["mail"];
		$this->PASSWORD = $a['password'];
		
		$this->IS_VALID = true;
	}
	
	public function reload_access(){
		$this->CREDENTIALS = DFW_ACCESS_GET_CREDENTIALS_FROM($this->get_ID());
	}
	
	public function check_access($access){
		if($this->isExists() == false){
			return false;
		}
		
		if(is_array($access)){
			return DFW_FIND_ACCESS($this->CREDENTIALS,$access);
		}else if(is_numeric($access)){
			return DFW_ACCESS_CHECK_ARRAY($this->CREDENTIALS,$access);
		}else{
			return false;
		}
	}
		function checkAccess($access){
			return $this->check_access($access);
		}
	
	function is_exists(){
		return $this->IS_VALID;
	}
		function isExists(){
			return $this->is_exists();
		}
	
	function get_ID(){
		return $this->ID;
	}
		function getID(){
			return $this->get_ID();
		}
	
	function get_NAME(){
		return $this->NAME;
	}
		function getName(){
			return $this->get_NAME();
		}
		
	function get_EMAIL(){
		return $this->EMAIL;
	}
		function getEmail(){
			return $this->get_EMAIL();
		}
		
	function get_PASSWORD(){
		return $this->PASSWORD;
	}
		function getPassword(){
			return $this->get_PASSWORD();
		}
}

class DFW_session extends DFW_user{
	
	private $IP = null;
	private $BROWSER = null;
	private $SESSION_TIME = null;
	
	private $VALID = false;

	function __construct($USER,$PASS){
		parent::__construct($USER);
		
		global $DFW_DB,$DFW_SESSION,$_DFW_ACCESS_EST_SESIONLOCK,$DFW_CONFIG;
		
		if($USER == null & $PASS == null || !$this->isExists()){
			return;
		}
		
		$id =				$this->getID();
		$ip = 				$this->getIP();
		$browser = 			$this->getBrowser();

		$s_consult = $DFW_DB->query("SELECT * FROM dfw_sessions WHERE (browser='{$browser}' AND ip='{$ip}' AND user_id='{$id}')");
	
		if(!$s_consult || $DFW_DB->get_num($s_consult) == 0){ return; }

		if($this->getPassword() != $PASS){ return; }	// Comprobamos la contraseña
		//$this->CREDENTIALS = DFW_ACCESS_GET_CREDENTIALS_FROM($this->ID);
		
		$DFW_DB->query("UPDATE dfw_sessions SET last_time=NOW() WHERE (browser='{$browser}' AND ip='{$ip}' AND user_id='{$id}')");

		$this->reload_access();
		
		$this->VALID = true;
	}
	
	function IS_VALID(){
		return $this->VALID;
	}
		function isValid(){
			return $this->IS_VALID();
		}
		function isOpen(){
			return $this->IS_VALID();
		}
		
	function getIP(){
		global $DFW_DB;
		return $DFW_DB->escape_string(DFW_UTILS_GET_IP());
	}
	
	function getBrowser(){
		global $DFW_DB;
		return $DFW_DB->escape_string(DFW_UTILS_GET_BROWSER());
	}
} 
?>
<?php
	/*
	DFW Delta Framework @  zephyra
	@Authors:
	Aldo Cesar Gutierrez [zerozelta]
	Mauricio Daniel Tiscareño Uribarrien [SgtSoap]
	Eric Luis Vega Silva [iRiotPro]
	José Salvador Batres Romo [Batresrock]
	
	Licencia: GPL puede distribuir, utilizar y modificar el codigo abiertamente
	
	Modulo de utilidades
	Fecha de creacion: 20/07/2016
	Ultima fecha de modificacion: 14/10/2017
	*/
	
	class DFW_utils{
		/**
		* Devuelve un color en formato Hexadecimal aleatoriamente.
		* @return devuelve un color en Hexadecimal.
		*/
		function randomFlatColor(){
			$r=mt_rand(50,100);
			$g=mt_rand(50,100);
			$b=mt_rand(50,100);
			$color="#";
			$color.=str_pad(dechex($r),2,"0",STR_PAD_LEFT);
			$color.=str_pad(dechex($g),2,"0",STR_PAD_LEFT);
			$color.=str_pad(dechex($b),2,"0",STR_PAD_LEFT);
			return $color;
		}
		
	}
	/*
		Convierte un formato dd/mm/aaaa a un formato de dia en unix
	*/
	function DFW_UTILS_DAY_TO_UNIX($str){
		$dex = explode("/",$str);
		$date = new DateTime("{$dex[2]}-{$dex[1]}-{$dex[0]} 00:00");
		return $date->getTimestamp();
	}
		
	/*
		importa las librerias basicas de DFW Lib (bootstrap y jquery) dentro del documento HTML
	*/
	function DFW_UTILS_ALL_LIBS(){
		DFW_UTILS_JQUERY();
		DFW_UTILS_BOOTSTRAP();
	}
	
	/*
		importa JQuery dentro del documento HTML
	*/
	function DFW_UTILS_JQUERY(){
		global $DFW_PATH;
		echo ('<script type="text/javascript" src="/'. $DFW_PATH . '/lib/jquery/jquery.js"></script>');	
	}
	
	function DFW_JQUERY(){
		DFW_UTILS_JQUERY();
	}
	
	function DFW_LIB_JQUERY(){
		DFW_UTILS_JQUERY();
	}
	
	/*
		importa las librerias basicas de DFW Lib (bootstrap y jquery) dentro del documento HTML
	*/
	function DFW_UTILS_BOOTSTRAP(){
		global $DFW_PATH;
		
		echo ('<link rel="stylesheet" href="/'. $DFW_PATH . '/lib/bootstrap/css/bootstrap.min.css">');
		echo ('<script src="/'. $DFW_PATH . '/lib/bootstrap/js/bootstrap.min.js"></script>');
	}
	
	function DFW_BOOTSTRAP(){
		DFW_UTILS_BOOTSTRAP();
	}
	
	function DFW_LIB_BOOTSTRAP(){
		DFW_UTILS_BOOTSTRAP();
	}
	
	/*
		Establece el charset en el HTML
	*/
	function DFW_UTILS_CHARSET($type = "UTF-8"){
		echo ("<meta charset='{$type}'>");
	}
	
	/*
	
	*/
	function DFW_UTILS_CIFRATE($password){
	$salt = '$bgr$/';
	$password = sha1(md5($salt . $password));	
	return $password;
	}
	
	/*
	
	*/
	function DFW_UTILS_ENCRYPT($string, $key) {
  		 $result = '';
  		 for($i=0; $i<strlen($string); $i++) {
     		$char = substr($string, $i, 1);
      		$keychar = substr($key, ($i % strlen($key))-1, 1);
      		$char = chr(ord($char)+ord($keychar));
     		 $result.=$char;
		}
   		return base64_encode($result);
	}
	
	/*
	
	*/
	function DFW_UTILS_DECRYPT($string, $key) {
   		$result = '';
   		$string = base64_decode($string);
   		for($i=0; $i<strlen($string); $i++) {
      		$char = substr($string, $i, 1);
      		$keychar = substr($key, ($i % strlen($key))-1, 1);
      		$char = chr(ord($char)-ord($keychar));
      		$result.=$char;
		   }
  		return $result;
	}	
	
		
	// Comprueba si es un texto basico con el limite y los simbolos y caracteres admitidos pasados como argumento
	// $valid_simbols son simbolos y caracteres válidos para complementar el patron, si se desea validar e "-" debe ser el ultimo caracter del arreglo
	function DFW_UTILS_IS_BASIC_TEXT($text,$char_limit,$valid_simbols = "_ -"){
		return ereg("^[a-zA-Z0-9\\".$valid_simbols."]{0,".$char_limit."}$", $text);
	}

	// comprueba que sea un formato de email válido
	function DFW_UTILS_CHECK_EMAIL($email){
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			return false;
		}else{
			return true;
		}
	}

	// true | false si se esta navegando desde un dispositivo móvil
	function DFW_UTILS_IS_MOVILE(){
		$movile_data = "/ipod|iphone|ipad|android|opera mini|blackberry|palm os|windows ce|bada|windows phone|symbian|psp/i";
		if( preg_match($movile_data,strtolower($_SERVER['HTTP_USER_AGENT'])) > 0){
			return true;
		}else{
			return false;
		}
	}

	// Devuelve la direccion IP del cliente
	function DFW_UTILS_GET_IP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			return $_SERVER['HTTP_CLIENT_IP'];
		   
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
	   
		return $_SERVER['REMOTE_ADDR'];
	}


	// Devuelve el identificador del web browser del cliente
	function DFW_UTILS_GET_BROWSER(){
		return $_SERVER['HTTP_USER_AGENT']; 	
	}

	// Escribe en el documento con la ruta file_path el contenido establecido como argumento
	function DFW_UTILS_WRITE_FILE($file_path,$content){
			if (!$gestor = fopen($file_path, 'w')) {
				 return false;
			}

			// Escribir contenido a nuestro archivo abierto.
			if (fwrite($gestor, $content) === false){
				return false;
			}

			fclose($gestor);
			return true;
	}

	// Detecta si se esta navegando con internet explorer 
	function IS_MSIE(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$msie = strrpos($user_agent, "MSIE");
		if ($msie === false) { return false; } else { return true; }
	}
	
	/*
		Codifica un vector a UTF8
	*/
	function UTF8_ARRAY($ar){
		return array_map("utf8_encode", $ar);
	}

	function UTF8($var){
		return utf8_encode($var);
	}
?>
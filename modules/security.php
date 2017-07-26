<?php
/**
	DFW @ Delta Advanced Systems
	@Authors: Aldo Cesar Gutierrez [zerozelta]
	
	Licencia: GPL puede distribuir, utilizar y modificar el codigo abiertamente
	
	Control de sesiones
*/

	if (!defined('DFD8334D3Y')){
		exit();
	}
	
	$filename_bad_chars =	array(
		'../', '<!--', '-->', '<', '>',
		"'", '"', '&', '$', '#',
		'{', '}', '[', ']', '=',
		';', '?', '%20', '%22',
		'%3c',		// <
		'%253c',	// <
		'%3e',		// >
		'%0e',		// >
		'%28',		// (
		'%29',		// )
		'%2528',	// (
		'%26',		// &
		'%24',		// $
		'%3f',		// ?
		'%3b',		// ;
		'%3d'		// =
	);
	
	function DFW_SECURITY_IS_CLEAR_TEXT($text,$charnum,$charset = null){
		return DFW_UTILS_IS_BASIC_TEXT($text,$charnum,$charset);
	}
	
	function DFW_SECURITY_ENCRIPT($text){
		
	}
	
	function DFW_SECURITY_CIFRATE($tex,$key){
		
	}
	
	function DFW_SECURITY_DESCIFRATE($tex,$key){
		
		
	}
	
?>
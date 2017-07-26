<?php
	/**
	Vexilla @ zephyra Corp.
	layer mysqli para el modulo de base de datos
	
	@Authors: zerozelta
	**/
	
	class mysqli_layer{
	
		var $is_connected = false;
		var $link;
		
		// Establece conexion con la base de datos
		function connect($db_server,$db_user,$db_name,$db_pass){
	
			$link = @mysqli_connect($db_server,$db_user,$db_pass);
			
			if (!$link){
				return 0;
			}
			
			if (!mysqli_select_db($link,$db_name)){
				return 0;
			}
			
			$this->link = $link;
			$this->is_connected = true;
			
			return 1;
		}
		
		// Realiza un comando a la base de datos, generalmente es para hacer una modificacion a la misma
		function query($sql){
			return mysqli_query($this->link,$sql);
		}
		
		// Obtiene un array sociativo de la consulta pasada como argumento ($result) ejemplo: array['columna'] = dato
		function get_array($result){
			return $result->fetch_array(MYSQLI_ASSOC);
		}
		
		// Obtiene una fila de resultados como un array enumerado
		function get_row($result){
			return $result->fetch_row();
		}
		
		// Obtiene el numero de registros encontrados de la consulta realizada
		function get_num($result){
			return @mysqli_num_rows($result);
		}
				
		// Valida el contenido del string para evitar inyecciones sql
		function escape_string($txt){
			if($txt == null || $this->link == null){
				return null;
			}
			
			return mysqli_real_escape_string($this->link,$txt);
		}
	
		// Devuelve el ultimo error ocurrido
		function get_error(){
			return mysqli_error($this->link);
		}
		
		function get_auto_increment_from($table){
			return $this->get_array($this->query("SHOW TABLE STATUS LIKE '{$table}'"))["Auto_increment"];
		}
		
		// Cierra la base de datos
		function close(){
			mysqli_close($this->link);
			$this->is_connected = false;
		}
		
		// Devuelve el link de la conexion
		function get_connection(){
			return $this->link;
		}
	}
?>
<?php
	/**
	DFW @ Delta Advanced Systems
	@Authors: Aldo Cesar Gutierrez [zerozelta]
	
	Licencia: GPL puede distribuir, utilizar y modificar el codigo abiertamente
	
	Clase de manejo de base de datos
*/
	
	if (!defined('DFD8334D3Y')){
		exit();
	}
	
	class DFW_database{
		private $dblayer;	// layer de la base de datos a utilizar 
		
		private $db_server,$db_user,$db_name,$db_pass;
		
		function __construct($layer,$server,$user,$name,$pass){
			global $DFW_ROOT;
			
			if($layer == null){
				$layer = "mysqli";	// Valor por default
			}
			
			$this->db_server = $server;
			$this->db_user = $user;
			$this->db_name = $name;
			$this->db_pass = $pass;
			
			if($layer == "mysqli"){
				include($DFW_ROOT."/modules/db_layers/mysqli.php");
				$this->dblayer = new mysqli_layer();
			}elseif ($layer == "mysql"){
				include($DFW_ROOT."/modules/db_layers/mysql.php");
				$this->dblayer = new mysql_layer();
			}else{
		
			}
		}
		
		function is_connected(){
			if(empty($this->dblayer)){
				return false;				
			}
			
			return $this->dblayer->is_connected; 
		}
		
		function connect(){
			if ($this->is_connected() == true){
				return 1;
			}
			
			return $this->dblayer->connect($this->db_server,$this->db_user,$this->db_name,$this->db_pass);
		}
		
		// Devuelve el link de la conexion
		function get_link(){
			if ($this->is_connected() == false){
				return null;
			}
			
			return $this->dblayer->link;
		}
		
		// Devuelve el link de la conexion
		function get_connection(){
			if ($this->is_connected() == false){
				return null;
			}
			
			return $this->dblayer->get_connection();
		}
		
		function query($sql = null){
			if($sql == null){ return null; }
			if ($this->is_connected() == false){
				return null;
			}
			
			return $this->dblayer->query($sql);
		}
		
		function get_array($result){
			if ($this->is_connected() == false){
				return null;
			}
			
			if (!$result){ return null; }
			
			return $this->dblayer->get_array($result);
		}
		
		// Devuelve un array completo con todos los datos del result ejemplo de uso: result[2]["columna"]
		function get_full_array($result){
			$full = Array();
			while($item = $this->get_array($result)){
				$full[] = $item;
			}
			return $full;
		}
		
		function get_auto_increment_from($table){
			if ($this->is_connected() == false){
				return null;
			}

			return $this->dblayer->get_auto_increment_from($table);
		}
		
		function fetch_array($result){
			if ($this->is_connected() == false){
				return null;
			}
			
			if(!$result){ return null; }
			
			return mysqli_fetch_array($result);
		}
		
		function get_row($result){
			if ($this->is_connected() == false){
				return null;
			}
			
			if (!$result){ return null; }
			
			return $this->dblayer->get_row($result);
		}
		
		function get_num($result){
			if ($this->is_connected() == false){
				return null;
			}
			
			if (!$result){ return null; }
			
			return $this->dblayer->get_num($result);
		}
		
		function escape_string($txt){
			return $this->dblayer->escape_string($txt);
		}

		function get_error(){
				return $this->dblayer->get_error();
		}
		
		function close(){
			if ($this->is_connected() == false){
				return 1;
			}
			return $this->dblayer->close();
		}
	}
?>
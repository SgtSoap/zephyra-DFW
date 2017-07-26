<?php

	if (!defined('DFD8334D3Y')){
		exit();
	}
	
	//Libreria de funciones DFW_USERS 1.0
	//Fecha de creacion: 05/02/2016
	//Ultima fecha de modificacion: 10/02/2016
	// Author: Salvador B. Romo
	
	function DFW_USR_CREATE($name,$mail,$password,$date_birth){
		global $DFW_DB;
		//Funcion para crear usuarios en la base de datos.
		//Validamos que el nombre, correo o contrasena no supere los valores
		//maximos permitidos en la base de datos.
		//INDICE DE RETORNOS
		//return  1 -> Si funciono.
		//return  0 -> No funciono.
		//return -1 -> El nombre supero el tamano maximo permitido.
		//return -2 -> El correo supero el tamano maximo permitido.
		//return -3 -> La contrasena supero el tamano maximo permitido.
		//return -4 -> La fecha de nacimiento es incorrecta.
		//return -5 -> El nombre ya fue registrado con anterioridad.
		//return -6 -> El correo ya fue registrado con anterioridad.
		if(strlen($name)>"50"){
			return -1;
		}else if(strlen($mail)>"120"){
			return -2;
		}else if(strlen($password)>"70"){
			return -3;
		}else if(!is_numeric($date_birth)){
			return -4;
		}else if(DFW_USR_EXIST_BY_NAME($name)){
			return -5;
		}else if(DFW_USR_EXIST_BY_MAIL($mail)){
			return -6;
		}else{
			//Ciframos la contrasena para almacenarla.
			//$confirm_password = DFW_UTILS_CIFRAR($confirm_password);
			$password = DFW_UTILS_CIFRATE($password);
			//Guardamos la fecha de registro en una variable.
			$date_register = time();
			//Incrementamos la seguridad para evitar ataques por inyeccion SQL
			//con $DFW_DB->escape_string (msqli_real_escape_string).
			$name = $DFW_DB->escape_string($name);		//$name = mysqli_real_escape_string($LINK,$name);
			$mail = $DFW_DB->escape_string($mail);      //$mail = mysqli_real_escape_string($LINK,$mail);
			//Ejecutamos el mysqli_query(); para registrar al usuario.
			$insert = $DFW_DB->query("INSERT INTO dfw_users (name,mail,time_r,password,d_birth) 
			VALUES ('$name','$mail',$date_register,'$password',$date_birth)");
			if (!$insert) {
				return 0;
			}else{
				return 1;
			}
		}
	}

	function DFW_USR_EDIT_NAME($id_user,$name){
		//Funcion para editar el nombre de un usuario.
		//INDICE DE RETORNOS
		//return  1 -> Si funciono.
		//return  0 -> No funciono.
		//return -1 -> El nombre supero el tamano maximo permitido.
		//return -2 -> EL nombre ya fue regisrado con anterioridad.
		global $DFW_DB;
		//Validamos que no exceda tamano maximo permitido.
		if(strlen($name)>"50"){
			return -1;
		}else if(DFW_USR_EXIST_BY_NAME($name)){
			return -2;
		}else{
			//Incrementamos la seguridad para evitar ataques por inyeccion SQL
			//con mysqli_real_escape_string.
			$name = $DFW_DB->escape_string($name); //$name = mysqli_real_escape_string($LINK,$name);
			//Se realiza el cambio de nombre.
			$edit = "UPDATE dfw_users SET name = '$name' WHERE id = '$id_user'";
			$result = $DFW_DB->query($edit);
			if(!$result){
				return 0;
			}else{
				return 1;
			}
		}
	}

	function DFW_USR_EDIT_MAIL($id_user,$mail){
		//Funcion para editar el correo de un usuario.
		//INDICE DE RETORNOS
		//return  1 -> Si funciono.
		//return  0 -> No funciono.
		//return -1 -> El correo supero el tamano maximo permitido.
		//return -2 -> El correo ya fue registrado con anterioridad.
		global $DFW_DB;
		//Validamos que no exceda el tamano maximo permitido.
		if(strlen($mail)>"120"){
			return -1;
		}else if(DFW_USR_EXIST_BY_MAIL($mail)){
			return -2;
		}else{
			//Incrementamos la seguridad para evitar ataques por inyeccion SQL
			//con mysqli_real_escape_string.
			$mail = $DFW_DB->escape_string($mail); //$mail = mysqli_real_escape_string($LINK,$mail);
			//Se realiza el cambio de correo.
			$edit = "UPDATE dfw_users SET mail = '$mail' WHERE id = '$id_user'";
			$result = $DFW_DB->query($edit);
			if(!$result){
				return 0;
			}else{
				return 1;
			}
		}
	}

	function DFW_USR_EDIT_PASSWORD($id_user,$new_password,$old_password){
		//Funcion para editar la contrasena de un usuario.
		//INDICE DE RETORNOS
		//return  1 -> Si funciono.
		//return  0 -> No funciono.
		//return -1 -> La contrasena antigua no coincide.
		//return -2 -> La contrasena nueva no puede ser la misma a la anterior.
		global $DFW_DB;
		//Traemos la contrasena almacenada en la base de datos.
		$result = $DFW_DB->query("SELECT password FROM dfw_users WHERE id ='$id_user'");
		$db_password = $DFW_DB->get_array($result)['password'];
		//Ciframos la nueva contrasena.
		$new_password = DFW_UTILS_CIFRATE($new_password);
		//Ciframos la contrasena vieja.
		$old_password = DFW_UTILS_CIFRATE($old_password);
		if(!($old_password==$db_password)){
			return -1;
		}else if($new_password==$old_password){
			return -2;
		}else{
			//Se realiza el cambio.
			$edit = $DFW_DB->query("UPDATE dfw_users SET password = '$new_password' WHERE id = '$id_user'");
			if(!$edit){
				return 0;
			}else{
				return 1;
			}
		}
	}

	function DFW_USR_EXIST_BY_NAME($name){
		//Funcion para verificar la existencia del usuario
		//por nombre en la base de datos.
		//INDICE DE RETORNOS
		//return true  -> Si cumple.
		//return flase -> No cumple
		global $DFW_DB;
		$search = "SELECT COUNT(*) AS total FROM dfw_users WHERE name = '$name'";
		$result = $DFW_DB->query($search);
		$data = $DFW_DB->get_array($result);
		if($data['total']>=1){
			return true;
		}else{
			return false;
		}
	}

	function DFW_USR_EXIST_BY_MAIL($mail){
		//Funcion para verificar la existencia del usuario
		//por correo electronico en la base de datos.
		//INDICE DE RETORNOS
		//return true  -> Si cumple.
		//return false -> No cumple.
		global $DFW_DB;
		$search = "SELECT COUNT(*) AS total FROM dfw_users WHERE mail = '$mail'";
		$result = $DFW_DB->query($search);
		$data = $DFW_DB->get_array($result);
		if($data['total']>=1){
			return true;
		}else{
			return false;
		}
	}

	function DFW_USR_ADMIN_DELETE($id_user){
		//Funcion de super usuario o administrador para
		//eliminar usuario.
		//INDICE DE RETORNOS
		//return 1 -> Si funciono.
		//return 0 -> No funciono.
		global $DFW_DB;
		//Sentencia SQL para eliminar usuario de la base de datos.
		$delete = "DELETE FROM dfw_users WHERE id = '$id_user'";
		$result = $DFW_DB->query($delete);
		if($result){
			return 1;
		}else{
			return 0;
		}
	}

	function DFW_USR_DELETE($id_user,$password){
		//Funcion de usuario para borrar su cuenta.
		//INDICE DE RETORNOS
		//return 1 -> Si funciono.
		//return 0 -> No funciono. Contrasena incorrecta.
		global  $DFW_DB;
		//Traemos la contrasena almacenada en la base de datos.
		$result = $DFW_DB->query("SELECT password FROM dfw_users WHERE id ='$id_user'");
		$db_password = $DFW_DB->get_array($result)['password'];
		//Ciframos la contrasena de usuario.
		$password = DFW_UTILS_CIFRATE($password);
		//Comparamos ambas contrasenas para permitir
		//o denegar el permiso de borrar su usuario.
		if($password==$db_password){
			//Sentencia SQL para eliminar usuario de la base de datos.
			$delete = "DELETE FROM dfw_users WHERE id = '$id_user'";
			$result = $DFW_DB->query($delete);
			return 1;
		}else{
			return 0;
		}
	}
?>
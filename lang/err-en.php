<?php

function getError($errorNumber)
{
    
	$errores = array(
			//Execution errors
			1 => "Invalid class name",
			2 => "Can´t find the requested class in the lib folder ",
			3 => "Can´t instantiate the class ",
			4 => "Don´t have privileges to see the requested class ", 
			5 => "There is no information about the requested module",
			6 => "Can´t find the requested class",
			7 => "there is no label in the language file",
			8 => "Success file upload",
			9 => "There is an error uploading the files",
			
			//Errors in the variables
			10 => "Cannot find the variable in GET ",
			11 => "The filter for GET variable must be an Array",
			12 => "Cannot find the variable in POST ",
			13 => "The filter for POST variable must be an Array",
			14 => "The Array that contains the values for the select can not be null",
			15 => "There are not valid keys for session",
			
			//Login errors
			20 => "Autentication error ",
			21 => "No username and password",
			22 => "No username",
			23 => "Password missing",			
			24 => "Didn´t select captcha x",
			25 => "Captcha mismatch",
			26 => "Incorrect username",
			27 => "There is an active session with this user", 
			28 => "User disabled", 
			29 => "Wrong password", 
			
			
			//Database connection errors
			30 => "The connection to the database failed",
			31 => "The connection with the javaBridge failed",
			32 => "Can´t establish connection with the database",			
			33 => "Invalid database",
			34 => "Invalid database connection",
			

			//Query execution errors			
			40 => "The table wasn´t specified for the query ",
			41 => "Error while execute the query",
			42 => "The query does´t return any results", 
			43 => "Error creating prepareStatement",
			44 => "Error updating",
			45 => "Error while execute the query with javaBridge",
			46 => "The table wasn´t specified for the query",
			47 => "Error while creating the query",
			48 => "Error inserting",
			49 => "Error deleting", 
			
			//Errores de sistema
			
			50 => "Library not loaded",
			51 => "No hay datos del perfil al crear la sesión",
			52 => "Ya existe una llave primaria con los datos enviados",			
			53 => "The requested language file doesn´t exist ",
			54 => "No existe el controlador solicitado",	
			55 => "No se tiene permisos de visualización para el controlador solicitado",
			56 => "Maybe there is no information in the system fot today( cartera,  matrices)",
			57 => "The value must be an array",
			

			//Errores de conexión LDAP
			70 => "Falló al hacer conexion con el servidor LDAP",
			71 => "Falló al establecer la versión de protocolo",
			72 => "Falló al hacer el bind()",
			
			
			//Mensajes para los dialogos
			93 => "Los registros se confirmaron con éxito",
			94 => "Existe un error de comunicación con el servidor",
			95 => "Existe un error al ejecutar el proceso",
			96 => "Proceso ejecutado con éxito", 
			97 => "No existen datos para generar el excel",
			98 => "Error al generar excel",
			99 => "Consulta existosa",
			100 => "The query does´t return any results",
			
			
			//Errores para diferentes clases
			110 => "No es posibe cargar los fondos asignados para el usuario. Revisar los permisos",
			111 => "No existe la tabla para generar el select",
			112 => "No se pudo recuperar la información del select",
			
			//Errores para archivos
			130 => "No se selecciono ningun archivo para procesar",			
			131 => "Error al crear la ruta para el archivo (Verificar permisos)",
			132 => "La ruta o el archivo a cargar son incorrectos",
			133 => "No se puede crear o no se tiene acceso a la carpeta ",
			134 => "Existe un problema al recuperar el archivo generado",
			135 => "The date in the file must be the same as the date selected",
			136 => "The file you try to download does not exist",
			137 => "No existen parámetros para la descarga",
			138 => "Existió un error al procesar el(los) archivo(s)",
			139 => "No existen archivos cargados para la fecha seleccionada",

			//Para los errores de archivos
			140 => "Can´t access to the specified path", 
			141 => "Existe un error al generar el archivo",
			
	  154 => "Usuario bloqueado por intentos fallidos, contacte al administrador",
	  157 => "Usuario bloqueado por inactividad en el sistema de más de 45 dias",
	  158 => "Usuario eliminado por el administrador",
	  159 => "Usuario eliminado por el usuario padre",
	  
			
			//Errores para Correo y Cambio de contraseña
			301 => "Formato de correo electrónico inválido",
			302 => "Existe un error al enviar correo",
			303 => "No existe código para cambiar contraseña",
			304 => "La contraseñas no coinciden",
			305 => "La contraseña tiene menos de 8 caracteres",
			306 => "La contraseña debe tener al menos una letra",
			307 => "La contraseña debe tener al menos una mayúscula",
			308 => "La contraseña debe tener al menos un número",
			309 => "La contraseña debe tener letra, número y caracter especial de la lista",
			310 => "El correo ingresado no se encuentra registrado",
			311 => "Existe un error al generar código de recuperación",
			312 => "Existe un error al cambiar la contraseña",
			313 => "El correo se encuentra asignado a más de una cuenta",
			314 => "La contraseña debe tener al menos una minúscula",
			317 => "La contraseña ha caducado, favor de cambiarla",
			318 => "Se envió un correo con las instrucciones para recuperar su contraseña",
		);
		
		if(isset($errores[$errorNumber]))
		{
			$errorMsg = $errores[$errorNumber];	
		}
		else
		{
			$errorMsg = "Error no definido"; 
		}
		
		return $errorMsg;
}

?>
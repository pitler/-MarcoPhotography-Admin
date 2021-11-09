<?php

function getError($errorNumber)
{

	$errores = array(
			//Errores para la clase Funciones
			1 =>  'No existe el nombre de la clase o esta vacio',
			2 =>  'No existe la clase solicitada en la carpeta de librerias',
	        3 =>  'Error al instanciar la clase indicada',	
	        4 => "No se tiene permisos de visualización para la clase solicitada",
			5 => "No existe información sobre el módulo ",
			6 => "No existe la clase solicitada",		
	        7 =>  'No existe el diccionario de errores',
	        8 => "Archivos cargados con éxito",
			9 => "Existe un error al cargar el (los) archivo(s)",
	        10 => 'No se encuentra la variable en $_GET',
	        11 => 'El filtro para la variable GET debe de ser un Array',
	        12 => 'No se encuentra la variable en $_POST' ,
	        13 => 'El filtro para la variable POST debe de ser un Array',
	       	14 => "El Array con los valores para el select está vacio",
			15 => "No existen llaves válidas para la sesión",
		
	    


	        	//Errores al hacer el login
			20 => "Error de autenticación ",
			21 => "No tiene login ni password",
			22 => "No tiene login",
			23 => "No tiene password",			
			24 => "No se seleccionó el captcha ",
			25 => "No coincide el captcha",
			26 => "Login incorrecto",
			27 => "Sesión activa con el mismo usuario", 
			28 => "Usuario inhabilitado", 
	        29 => "Password incorrecto ", 	

	        //Errores de conexión a la base
			30 => "Existe un error al hacer la conexión a la base",
			31 => "SD",
			32 => "No se pudo establecer la conexión a la base de datos",			
			33 => "Base de datos inválida",
			34 => "La conexión a la base de datos es inválida",			

			//Errores de ejecución de querys			
			40 => "No se especificó la tabla para el query",
			41 => "Error al ejecutar el query",
			42 => "El query no regreso ningún resultado", 
			43 => "Error al crear el prepareStatement",
			44 => "Error al hacer update",
			45 => "SD",
			46 => "No se definió la tabla para el query",
			47 => "Error al generar la consulta ",
			48 => "Error al insertar",
			49 => "Error al borrar ", 
	    
	    
	       // Para la clase JqController
			50 => "La librería no está cargada",
			51 => "No hay datos del perfil al crear la sesión",
			52 => "Ya existe una llave primaria con los datos enviados",
			53 => "No se encontro el archivo de idioma especificado ",
	        54 => "No existe el controlador solicitado",	
	    
			
			//Errores de sistema
			
			55 => "No se tiene permisos de visualización para el controlador solicitado",
			56 => "Posiblemente no hay información en cartera o matrices para este día",
			57 => "El valor esperado debe de ser un array",
			58 => "El password que intenta cambiar ya ha sido usado",
			
			
			//
			60 => "Existe un error al recuperar la lista de imágenes",
			61 => "Error al leer permisos del controlador ",
			62 => "No existen permisos en la base para la clase",

			//Errores de conexión LDAP
			
			//Mensajes para los dialogos
			93 => "Los registros se confirmaron con éxito",
			94 => "Existe un error de comunicación con el servidor",
			95 => "Existe un error al ejecutar el proceso",
			96 => "Proceso ejecutado con éxito", 
			97 => "No existen datos para generar el excel",
			98 => "Error al generar excel",
			99 => "Consulta existosa",
			100 => "La consulta no generó ningún resultado",
			
			
			//Errores para diferentes clases
			//110 => "No es posibe cargar los fondos asignados para el usuario. Revisar los permisos",
			111 => "No existe la tabla para generar el select",
			112 => "No se pudo recuperar la información del select",
			113 => "No se pudo recuperar la información para los <options>",
			
			
			
			//Errores para archivos
						
			131 => "Error al crear la ruta para el archivo (Verificar permisos)",
			132 => "La ruta o el archivo a cargar son incorrectos",
			133 => "No se puede crear o no se tiene acceso a la carpeta ", 
			134 => "Existe un problema al recuperar el archivo generado",
			135 => "La fecha del archivo debe de ser la misma que se seleccionó",			
			136 => "El archivo que intenta descargar no existe",
			137 => "No existen parámetros para la descarga",
			138 => "Existió un error al procesar el(los) archivo(s)",
			139 => "No existen archivos cargados para la fecha seleccionada",
			132 => "La ruta o el archivo a cargar son incorrectos",
			
			
			//Para los errores de archivos
			140 => "No se puede acceder a la ruta especificada",
			141 => "Existe un error al generar el archivo",
			142 => "Archivos cargados con éxito",
			143 => "Existe un problema al eliminar la imagen",
			144 => "No se pudo eliminar el directorio",
			145 => "Existe un error al consultar los grados escolares",	
	  
	    // Mensaje para correo
	    153 => "Error al crear la sesión",
	    154 => "Usuario bloqueado por intentos fallidos, contacte al administrador",
	    157 => "Usuario bloqueado por inactividad en el sistema de más de 45 dias",
	    158 => "Usuario eliminado por el administrador",
	    159 => "Usuario eliminado por el usuario padre",
	    160 => "Sesión terminada por inactividad del usuario, favor de intentar nuevamente",
	  	

			//Errores para CRUD
	    	200 => "No se encuentra el ID para el campo del modelo ",
	    	201 => "No se puede generar el objeto de la tabla (DbClassGenerator)",
	    	202 => "Existe un error al cargar la información", 
	    	203 => "No se le pueda dar formato a la fecha",

			
			//Errores para Correo y Cambio de contraseña
			301 => "Formato de correo electrónico inválido",
			302 => "Existe un error al enviar correo",
			303 => "No existe código para restaurar contraseña o es inválido",
			304 => "La contraseñas no coinciden",
			305 => "La contraseña tiene menos de 8 caracteres",
			306 => "La contraseña debe tener al menos una letra",
			307 => "La contraseña debe tener al menos una mayúscula",
			308 => "La contraseña debe tener al menos un número",
			309 => "Los siguientes cacteres no son válidos : ",
			310 => "El correo ingresado no se encuentra registrado",
			311 => "Existe un error al generar código de recuperación",
			312 => "Existe un error al cambiar la contraseña",
			313 => "El correo se encuentra asignado a más de una cuenta",
			314 => "La contraseña debe tener al menos una minúscula",
			317 => "La contraseña ha caducado, favor de cambiarla",
			318 => "Se envió un correo con las instrucciones para recuperar su contraseña",
			319 => "La contraseña se cambio con éxito, favor de iniciar sesión",
			
		);
		
		if(isset($errores[$errorNumber]))
		{
			$errorMsg = $errores[$errorNumber];	
		}
		else
		{
			$errorMsg = "Numero de error no definido ($errorNumber)"; 
		}		
		return $errorMsg;
}
?>

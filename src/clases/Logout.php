<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;

use Pitweb\Connection as PwConnection;
use Pitweb\Sql as PwSql;



/**
 * 
 * Clase que cierra la sesión del sistema
 * Regenera la sesion y borra la anterior
 * Borra los valores
 * Destruye la sesion
 * @author pcalzada
 *
 */

class Logout 
{	
	
	public function getData()
	{
		
		$connection = PwConnection::getInstance()->connection;        
		//Actualiza la base de datos
		//Si el usuario se encuentra bloqueado por exceso de intentos no se cambia estatus
		$cveUsuario = PwSecurity::decryptVariable ( 2, "cveUsuario" );
		$condition = array ("CVE_USUARIO" => $cveUsuario);
		$fields = array ("STATUS");
		$tabla = "FC_SYS_USUARIOS";
		$sqlResult = PwSql::executeQuery ( $connection, $tabla, $fields, $condition );

		
		
		$status = 0;
		if ($sqlResult)
		{
			$sqlItem = $sqlResult[0];
			$status = $sqlItem["STATUS"];
		}
		if($status == 1 || $status == 3)
		{
			$datos = array("STATUS" => 1);
			$keyFields = array("CVE_USUARIO" => $cveUsuario);
			$consulta =  PwSql::updateData($connection, $tabla, $datos, $keyFields);
			//$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUsuario, "login", "", "Inicio de sesión", "Logout", "PII");		
		}
		
	//	$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUsuario, "logout", "Término de sesión", $msg);
		
		$_SESSION = null;
		$_COOKIE = null;
		setcookie(session_name(), "", time()-42000, dirname($_SERVER["PHP_SELF"]));
		setcookie("sessionKey", "", time()-42000, dirname($_SERVER["PHP_SELF"]));
		session_unset();
		session_destroy();				
		PwFunciones::reloadPage();
	}


}
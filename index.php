<?php
//namespace Pitweb;
/** 
 * @name Pitweb
 * @version 3.0
 * @author pitler
 * Date  Febrero 2018
 * --------------------------------------------------------------------
 * Clase inicial del sitio publico, por aqui es donde entra todo
 * Revisa la sesión y la cambia si no se autentica
 * Instancia y ejecuta la clase principal 
 * --------------------------------------------------------------------
 */
/*
 session_start();
 $_SESSION = null;
 $_COOKIE = null;
 setcookie(session_name(), "", time()-42000, dirname($_SERVER["PHP_SELF"]));
 setcookie("sessionKey", "", time()-42000, dirname($_SERVER["PHP_SELF"]));
 session_unset();
 session_destroy();				
 die();*/
//Estoy en test2 cambio
 /*echo "Pagina";
 die();*/
 //^\s*\n
namespace Pitweb;
use Pitweb\Funciones as PwFunciones;
ob_start();
error_reporting(E_ERROR);
ini_set('error_log','logs/sysLog.log');
//TODO -  Debe de ir en el config
date_default_timezone_set('America/Mexico_City');
//Configuración inicial
include_once ("config.php");
/**
* Auloader para las clases del PitWeb
* Deben de llamarse : 
* use Pitweb\Funciones\Funciones as Pwunciones;
*/
spl_autoload_register(
	function($nombre)
	{
		//error_log("Nombre Main ::: $nombre ::". PITWEB);
		if(strpos($nombre, "Pitweb") !== false)
		{
			$nombre = explode("\\", $nombre);
    		$nombre = $nombre[1];
			include_once PITWEB."lib/$nombre.php";
		}
		else
		{
			//error_log("Entro a $nombre");
			include_once SITECLASESPATH.$nombre.".php";
		}
	}
);
$debug = PwFunciones::getGVariable("debug");
$tiempoInicio =  microtime(true);
//Iniciamos la sesión
session_start();
/*
$_SESSION["autentified"] = null;
session_unset();
session_destroy();	
die();
*/
// Cambia el id de sesión cada que se recarga la página
// Esto lo hace evitar el robo de sesion por el id.
// Una vez autentificado deja el  id estático
if(!isset($_SESSION["autentified"]))
{
	//TODO
	//Validar desde el config si se regenera la sesión
	session_regenerate_id(true);
}
/**
 * Instanciamos el objeto main, llamamos
 * la función principal y nos regresa el contenido a pintar 
 */
include_once 'Main.php';
//use Pitweb\Main as Pw;
$pw = new Main;
$mainData = $pw->getPageData();
$tiempoFin = microtime(true);
$tEjecucion = $tiempoFin - $tiempoInicio;
   if(EXECUTETIME == true)
   {
       error_log("Ejecucion :: $tEjecucion");
   }
$mainData = preg_replace("/__EJECUCION__/", $tEjecucion, $mainData);
ob_end_flush();
echo $mainData;
?>
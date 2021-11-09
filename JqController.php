<?php
namespace Pitweb;

use Pitweb\Funciones as PwFunciones;
use Pitweb\Login as PwLogin;
use Pitweb\Recover as PwRecover;
use Pitweb\Security as PwSecurity;
//use Pitweb\Security as PwConnection;

//Configuración inicial
include_once ("config.php");

date_default_timezone_set('America/Mexico_City');
//Mandamos los errores al log
error_reporting(E_ALL);
ini_set('error_log','logs/sysLog.log');



//error_log("JqController listo");
/**
* Auloader para las clases del PitWeb
* Deben de llamarse : 
* use Pitweb\Funciones\Funciones as PwFunciones;
*/
/*spl_autoload_register(
    function($nombre)
    {

        $nombre = explode("\\", $nombre);
        $nombre = $nombre[1];
        include_once PITWEB."lib/$nombre.php";
    }
);*/


/**
* Auloader para las clases del PitWeb
* Deben de llamarse : 
* use Pitweb\Funciones\Funciones as Pwunciones;
*/
spl_autoload_register(
    function($nombre)
    {
        if(strpos($nombre, "Pitweb") !== false)
        {
           // error_log("Del pitweb busco :: $nombre");
            $nombre = explode("\\", $nombre);
            $nombre = $nombre[1];
            $path = "";
            
            switch ($nombre)
            {
                default :              
                    $path = PITWEB."lib/$nombre.php";
                break;
            }                                     
            include_once $path;            
        }
        else
        {
            //error_log("Controlador :: ". SITECONTROLLERPATH.$nombre.".php");            
            include_once SITECONTROLLERPATH.$nombre.".php";
        }
    }
);



session_start();


$debug = PwFunciones::getGVariable("debug");


$tiempoInicio =  microtime(true);


$mc = new JqController;
$mainData = $mc->getData();

$tiempoFin = microtime(true);
$tEjecucion = $tiempoFin - $tiempoInicio;



    error_log("Ejecucion controller:: $tEjecucion");


echo $mainData;


/**
 *
 * Clase encargada de administrar las llamadas a clases por medio de jquery
 * Se cargan todos los objetos nuevamente
 * @author pitler
 *
 */

class JqController
{
    
    /**
     * Objeto para las funciones generales
     * @var ObjFunciones Objeto para las funciones generales
     */
    //private $mainObj;
    
    /**
     * Nombre de la clase
     * @var String  - Nombre de la clase
     */
    private $className;

    //private $cvePerfil;
    
    
    //Constructor de la clase
    function __construct ()
    {
        //$this->mainObj = new mainVars(DBASE, "");
        $this->className = "JqController";
       
    }
    
    public static function getData()
    {

        $encrypt = PwFunciones::getPVariable("encrypt");
        
        //La clase a la que nos dirigimos
        $clase = rawurldecode(PwFunciones::getPVariable("class"));
        
      

       //$connection1 = PwConnection::getInstance()->connection;    

        
        
        /**
         * Para personalizar el comportamiento de clases externas como login o recover
         */
        switch ($clase)
        {
            case "Login": 
            $mode = PwFunciones::getPVariable("mode");
            if (! isset($mode))
            {
                $mode = 1;
            }
            
            $mainLogin = PwLogin::getData($mode);
            
            //PwFunciones::getVardumpLog($mainLogin);
            
            echo $mainLogin;
            die();
            break;

            case "Recover": 
            
            $mode = PwFunciones::getPVariable("mode");
            if (! isset($mode))
            {
                $mode = 1;
            }
            
            $recover = PwRecover::getData($mode);
            
            echo $recover;
            die();
            break;
        }
         //Clave Perfil
         $cvePerfil = PwSecurity::decryptVariable ( 2, "cvePerfil" );  
        
        //Si necesitamos estar logueados, por lo general es para traer variables encriptadas
        if($encrypt == 2)
        {            
            $clase = PwSecurity::decryptVariable(1, $clase);           
        }
        
        //Verificamos si existe físicamente, sino mandamos error al log
        if(file_exists(SITECONTROLLERPATH.$clase.".php"))
        {
            
            //Checamos si tiene permiso, si no mandamos error en el log
            $permiso = PwSecurity::validateAccess( $clase, $cvePerfil);
            
            if($permiso["VISUALIZAR"] == 1)
            {
                include_once(SITECONTROLLERPATH.$clase.".php");
                $moduleData = new $clase;
                $data = $moduleData->getData();
                //por alguna razon concatena un 1
                echo $data;
            }
            else
            {                
                
                PwFunciones::setLogError(55,__CLASS__. ":: $clase");
                return  json_encode(array("status" => "false",  "message" => "No se tiene permisos de visualización para el modulo $clase", "type" => "error"));		
                
            }
        }
        //Si no existe fisicamente, mandamos error pero solo por medio del log
        else
        {
            
            PwFunciones::setLogError(54,__CLASS__. ":: $clase");
            return  json_encode(array("status" => "false",  "message" => "No existe la clase solicitada $clase", "type" => "error"));		

           //echo "serverFalse";
            //return false;
        }
    }
}
?>